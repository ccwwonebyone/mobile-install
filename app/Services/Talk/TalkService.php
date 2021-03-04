<?php


namespace App\Services\Talk;


use App\Enums\Enum;
use App\Exceptions\AppException;
use App\Filters\QueryFilter;
use App\Models\Talk\Talk;
use App\Models\Talk\TalkComment;
use App\Models\Talk\TalkUser;
use App\Models\User;
use App\Services\DingTalk\DingTalkService;
use App\Services\Service;
use App\Services\User\UserService;
use Illuminate\Database\Eloquent\Builder;

class TalkService extends Service
{
    /**
     * @param QueryFilter $filter
     * @param User $user
     * @param int $num
     * @return array
     */
    public function talkList(QueryFilter $filter, User $user, $num = 10)
    {
        return Talk::filter($filter)
            ->select('talks.*')
            ->with('user:id,username,avatar')
            ->withCount(['comment' => function (Builder $query) use ($user) {
                $query->where('user_id', $user->id)->where('is_read', Enum::NO);
            }])
            ->join('talk_users', 'talk_users.talk_id', '=', 'talks.id')
            ->where('talk_users.user_id', $user->id)
            ->whereNull('talk_users.deleted_at')
            ->orderBy('id', 'desc')
            ->paginate($num)
            ->toArray();
    }

    /**
     * @param $data
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($data, User $user)
    {
        $data['user_id'] = $user->id;
        if ($talk = Talk::query()->create($data)) {
            //新增创建者
            $this->addTalkUser($talk->id, [$user->ding_user_id], 0);
            //新增成员
            $this->addTalkUser($talk->id, $data['ding_user_ids']);
            DingTalkService::instance()->sendNoticeMessage($data['ding_user_ids'], [
                'msgtype' => 'link',
                'link' => [
                    'messageUrl' => config('ding_talk.ding_url').'/theme.html?t='.time().'#/detail?id='.$talk->id.'&single=1',
                    'picUrl' => '',
                    'title' => '讨论组通知',
                    'text' => $user->username.'邀请你加入讨论组'
                ]
            ]);
            return $talk;
        }
    }

    /**
     * @param $id
     * @param User $user
     * @return array
     * @throws AppException
     */
    public function get($id, User $user): array
    {
        $talk = Talk::query()->with('user:id,username,avatar,ding_user_id')->find($id);
        if (!$talk) {
            $this->throwAppException('未找到话题，或已被删除');
        }
        $this->checkUserAuth($talk, $user);
        $talk = $talk->toArray();
        $members = TalkUser::query()->where('talk_id', $id)->where('source', TalkUser::SOURCE_MEMBER)->pluck('user_id')->toArray();
        $talk['members'] = UserService::instance()->getUsersByIds($members)->toArray();
        $this->setCommentRead($id, $user);
        return $talk;
    }

    /**
     * @param $talkId
     * @param User $user
     * @return int
     */
    public function setCommentRead($talkId, User $user)
    {
        return TalkComment::query()->where('talk_id', $talkId)->where('user_id', $user->id)->update([
            'is_read' => Enum::IS
        ]);
    }

    /**
     * @param $id
     * @param $data
     * @param User $user
     * @return bool
     * @throws AppException
     */
    public function update($id, $data, User $user)
    {
        $talk = $this->findTalkById($id);
        if ($talk->user_id != $user->id) {
            $this->throwAppException('权限不足');
        }
        if ($talk->update($data)) {
            $this->removeUserByTalkAndType($id, TalkUser::SOURCE_MEMBER);
            $this->addTalkUser($id, $data['ding_user_ids'], TalkUser::SOURCE_MEMBER);
            DingTalkService::instance()->sendNoticeMessage($data['ding_user_ids'], [
                'msgtype' => 'link',
                'link' => [
                    'messageUrl' => config('ding_talk.ding_url').'/theme.html?t='.time().'#/detail?id='.$id.'&single=1',
                    'picUrl' => '',
                    'title' => '讨论组通知',
                    'text' => $user->username.'邀请你加入讨论组'
                ]
            ]);
        }
        return true;
    }

    /**
     * @param $id
     * @param $user
     * @return bool
     */
    public function hasUser($id, $user): bool
    {
        return (bool) TalkUser::query()->where('talk_id', $id)->where('user_id', $user->id)->count();
    }

    /**
     * @param $id
     * @param User $user
     * @return mixed
     * @throws AppException
     */
    public function delTalk($id, User $user)
    {
        $talk = $this->findTalkById($id);
        if ($talk->user_id == $user->id || $user->is_admin == Enum::IS) {
            return Talk::query()->where('id', $id)->delete();
        }
        $this->throwAppException('权限不足', 409);
    }

    /**
     * @param $id
     * @param bool $over
     * @return bool
     * @throws AppException
     */
    public function setOver($id, $over = true)
    {
        $talk = $this->findTalkById($id);
        return $talk->update([
            'status' => $over ? Enum::NO : Enum::IS
        ]);
    }

    /**
     * @param $id
     * @param bool $except
     * @return \Illuminate\Database\Eloquent\Model
     * @throws AppException
     */
    public function findTalkById($id, $except = true)
    {
        $talk = Talk::query()->find($id);
        if (!$talk && $except) {
            $this->throwAppException('未找到讨论话题');
        }
        return $talk;
    }

    /**
     * @param int $talkId
     * @param array $data
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws AppException
     */
    public function comment(int $talkId, array $data, User $user)
    {
        $this->findTalkById($talkId);
        $notices = $data['notice'] ?? [];
//        $userIds = array_column($notices, 'ding_user_id');
//        $this->addTalkUser($talkId, $userIds, Enum::NO);
        $data['user_id'] = $user->id;
        foreach ($notices as $notice) {
            DingTalkService::instance()->sendCidMessage($user->ding_user_id, $notice['cid'], [
                'msgtype' => 'link',
                'link' => [
                    'messageUrl' => $notice['url'],
                    'title' => '华铁讨论组',
                    'text' => '我在讨论组中@了你',
                    'picUrl' => ''
                ]
            ]);
        }
        if (!empty($data['pid'])) {
            $pTalkComment = $this->findTalkCommentById($data['pid']);
            $pTalkComment->update([
                'is_read' => Enum::NO
            ]);
            $pUser = UserService::instance()->getUser($pTalkComment->user_id);
            if ($pUser->id != $user->id) {
                DingTalkService::instance()->sendNoticeMessage($pUser->ding_user_id, [
                    'msgtype' => 'link',
                    'link' => [
                        'messageUrl' => config('ding_talk.ding_url').'/theme.html?t='.time().'#/detail?id='.$talkId.'&single=1',
                        'picUrl' => '',
                        'title' => '讨论组通知',
                        'text' => $user->username.'回复了你的评论'
                    ]
                ]);
            }

        }
        return TalkComment::query()->create($data);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function findTalkCommentById($id)
    {
        if (!$pTalkComment = TalkComment::query()->find($id)) {
            $this->throwAppException('评论丢失');
        }
        return $pTalkComment;
    }

    /**
     * @param int $talkId
     * @param array $dingUserIds
     * @param int $isMember
     */
    public function addTalkUser(int $talkId, array $dingUserIds, $isMember = Enum::IS)
    {
        $exDingUserIds = UserService::instance()->getUserIdsByDingIds($dingUserIds);
        $noDingUserIds = array_diff($dingUserIds, $exDingUserIds);
        foreach ($noDingUserIds as $dingUserId) {
            $user = UserService::instance()->getUserByDingUserId($dingUserId);
            $exDingUserIds[$user->id] = $dingUserId;
        }
        $userIds = TalkUser::query()->where('talk_id', $talkId)
            ->whereIn('user_id', array_keys($dingUserIds))->pluck('user_id')->toArray();
        $userIds = array_diff(array_keys($exDingUserIds), $userIds);
        $userIds && TalkUser::query()->insert(
            array_map(function ($userId) use ($talkId, $isMember) {
                return [
                    'user_id' => $userId,
                    'source' => $isMember,
                    'talk_id' => $talkId
                ];
            }, $userIds)
        );
        //更新评论中@,更新为成员
        if ($isMember == TalkUser::SOURCE_MEMBER) {
            TalkUser::query()->where('talk_id', $talkId)
                ->whereIn('user_id', array_keys($dingUserIds))
                ->where('source', TalkUser::SOURCE_COMMENT)->update([
                    'source' => $isMember
                ]);
        }

    }

    public function getCommentDingUsers($talkId)
    {
        $this->findTalkById($talkId);
        $notices = TalkComment::query()->where('talk_id', $talkId)->pluck('notice')->toArray();
        $dingUserIds = [];
        foreach ($notices as $notice) {
            $dingUserIds = array_merge(array_column($notice, 'ding_user_id'), $dingUserIds);
        }
        return array_unique($dingUserIds);
    }

    public function removeUserByTalkAndType($talkId, $type)
    {
        return TalkUser::query()->where('talk_id', $talkId)->where('source', $type)->delete();
    }

    /**
     * @param $id
     * @param User $user
     * @return bool|mixed|null
     * @throws AppException
     */
    public function delComment($id, User $user)
    {
        $talkComment = TalkComment::query()->find($id);
        if (!$talkComment) {
            $this->throwAppException('资源丢失');
        }
        if ($talkComment->user_id != $user->id) {
            $this->throwAppException('权限不足');
        }
        if (time() - strtotime($talkComment->created_at) > 120) {
            $this->throwAppException('发表超过两分钟，无法删除');
        }
        return $talkComment->delete();
//        $talkId = $talkComment->talk_id;
//        if ($talkComment->delete()) {
//            $this->removeUserByTalkAndType($talkId, TalkUser::SOURCE_COMMENT);
//            $this->addTalkUser($talkId, $this->getCommentDingUsers($talkId), TalkUser::SOURCE_COMMENT);
//        }
    }

    /**
     * @param User $user
     * @return int|mixed
     */
    public function delCommentAll(User $user)
    {
        if ($user->is_admin) {
            return Talk::query()->delete();
        }
        return 1;
    }

    /**
     * @param $talkId
     * @param User $user
     * @param int $num
     * @return array
     * @throws AppException
     */
    public function getComment($talkId, User $user, $num = 10)
    {
        $talk = $this->findTalkById($talkId);
        $this->checkUserAuth($talk, $user);
        $data = TalkComment::query()
            ->where('talk_id', $talkId)
            ->with('user:id,username,avatar,ding_user_id')
            ->orderBy('id', 'asc')->paginate($num)->toArray();
        $comments = $data['data'];
        $comments = array_combine(array_column($comments, 'id'), $comments);
        foreach ($comments as $id => &$comment) {
            if ($comment['pid'] != 0) {
                $comment['reply'] = $comments[$comment['pid']]['user'] ?? null;
            } else {
                $comment['reply'] = null;
            }
        }
        $data['data'] = array_values($comments);
        return $data;
    }

    /**
     * @param Talk $talk
     * @param User $user
     * @throws AppException
     */
    public function checkUserAuth($talk, User $user)
    {
        if (!$this->hasUser($talk->id, $user)) {
            $this->throwAppException('非法请求', 409);
        }
    }
}
