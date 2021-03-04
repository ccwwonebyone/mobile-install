<?php


namespace App\Services\DingTalk;


use App\Services\Service;
use EasyDingTalk\Application;

class DingTalkService extends Service
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct($config = null)
    {
        $this->app = new Application($config ?: config('ding_talk'));
    }

    /**
     * 电脑端打开
     *
     * @return string
     */
    public function openAppPcUrl()
    {
        return 'dingtalk://dingtalkclient/action/openapp?corpid='
        .config('ding_talk.corp_id').'&container_type=work_platform&app_id=0_'
        .config('ding_talk.agent_id').'&redirect_type=jump&redirect_url=';
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getUserByCode($code)
    {
        return $this->app->user->getUserByCode($code);
    }

    /**
     * @param $dingUserId
     * @return mixed
     */
    public function getUserByDingUserId($dingUserId)
    {
        return $this->app->user->get($dingUserId);
    }

    /**
     * @param string $userId
     * @param string $cid
     * @param array $msg
     * @return mixed
     */
    public function sendCidMessage(string $userId, string $cid, array $msg)
    {
        if (isset($msg['link'])) {
            $msg['link']['picUrl'] = empty($msg['link']['picUrl']) ? '@lALOACZwe2Rk' : $msg['link']['picUrl'];
            $msg['link']['messageUrl'] = $this->openAppPcUrl().urlencode($msg['link']['messageUrl']);
        }
        return $this->app->conversation->sendGeneralMessage($userId, $cid, json_encode($msg));
    }

    public function getUserByMobile($mobile)
    {
        return $this->app->user->getUserIdByPhone($mobile);
    }

    /**
     * @param $dingUserIds
     * @param $msg
     * @return mixed
     */
    public function sendNoticeMessage($dingUserIds, $msg)
    {
        if (isset($msg['link'])) {
            $msg['link']['picUrl'] = empty($msg['link']['picUrl']) ? '@lALOACZwe2Rk' : $msg['link']['picUrl'];
            $msg['link']['messageUrl'] = $this->openAppPcUrl().urlencode($msg['link']['messageUrl']);
        }
        $data = [
            'agent_id' => config('ding_talk.agent_id'),
            'userid_list' => is_array($dingUserIds) ? implode(',', $dingUserIds) : $dingUserIds,
            'msg' => json_encode($msg)
        ];
        return $this->app->conversation->sendCorporationMessage($data);
    }

    /**
     * 获取鉴权参数
     * @param string $url
     * @return mixed
     */
    public function getSign(string $url)
    {
        return $this->app->h5app->getSignature($url);
    }
}
