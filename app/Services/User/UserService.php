<?php


namespace App\Services\User;


use App\Models\User;
use App\Services\DingTalk\DingTalkService;
use App\Services\Service;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserService extends Service
{
    /**
     * @param $dingUserId
     * @return \Illuminate\Database\Eloquent\Model|Authenticatable
     */
    public function getUserByDingUserId($dingUserId)
    {
        if (!$user = User::query()->where('ding_user_id', $dingUserId)->first()) {
            $dingUser = DingTalkService::instance()->getUserByDingUserId($dingUserId);
            if ($dingUser['errcode'] != 0) {
                $this->throwAppException('用户不存在');
            }
            $user = $this->createUser($dingUser);
        }
        return $user;
    }

    public function getUser($id)
    {
        return User::query()->find($id);
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function createUser($data)
    {
        $user = [
            'ding_user_id' => $data['userid'],
            'username' => $data['name'],
            'avatar' => $data['avatar'],
            'mobile' => $data['mobile'],
        ];
        return User::query()->create($user);
    }

    /**
     * @param array $dingUserIds
     * @return array
     */
    public function getUserIdsByDingIds(array $dingUserIds): array
    {
        return User::query()->whereIn('ding_user_id', $dingUserIds)->pluck('ding_user_id', 'id')->toArray();
    }

    /**
     * @param string $name
     * @return array
     */
    public function getUserIdsByName(string $name): array
    {
        return User::query()->where('username', 'like', '%'.$name.'%')->pluck('id')->toArray();
    }

    /**
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUsersByIds(array $ids)
    {
        return User::query()->whereIn('id', $ids)->get();
    }

    /**
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\AppException
     */
    public function getUserByName(string $name)
    {
        if (!$user = User::query()->where('username', $name)->first()) {
            $this->throwAppException('用户不存在');
        }
        return $user;
    }

    /**
     * @param string $mobile
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \App\Exceptions\AppException
     */
    public function getUserByMobile(string $mobile)
    {
        if (!$user = User::query()->where('mobile', $mobile)->first()) {
            $this->throwAppException('用户不存在');
        }
        return $user;
    }
}
