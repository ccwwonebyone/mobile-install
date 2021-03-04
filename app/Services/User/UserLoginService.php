<?php


namespace App\Services\User;


use App\Models\User;
use App\Services\DingTalk\DingTalkService;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;

class UserLoginService extends Service
{
    /**
     * @param $code
     * @return string|void
     * @throws \App\Exceptions\AppException
     */
    public function login($code)
    {
//        $user = UserService::instance()->getUserByDingUserId($code);
//        return Auth::login($user);

        $userInfo = DingTalkService::instance()->getUserByCode($code);
        if ($userInfo['errcode'] != 0) {
            $this->throwAppException('异常登录');
        }
        $user = UserService::instance()->getUserByDingUserId($userInfo['userid']);
        return Auth::login($user);
    }

    /**
     * @param $mobile
     * @param $password
     * @return string
     * @throws \App\Exceptions\AppException
     */
    public function passwordLogin($mobile, $password)
    {
        $user = UserService::instance()->getUserByMobile($mobile);
        if (!password_verify($password, $user->password)) {
            $this->throwAppException('密码错误');
        }
        return Auth::login($user);
    }

    /**
     * @param $mobile
     * @param $password
     * @param $username
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \App\Exceptions\AppException
     */
    public function logon($mobile, $password, $username)
    {
        UserService::instance()->getUserByMobile($mobile);
        return UserService::instance()->createUser([
            'mobile' => $mobile,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'username' => $username
        ]);
    }
}
