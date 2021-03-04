<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\UserLoginService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function login(Request $request)
    {
        $this->valid($request, [
            'mobile' => 'required',
            'password' => 'required'
        ]);
        return $this->success([
            'token' => UserLoginService::instance()->passwordLogin($request->mobile, $request->password)
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\AppException
     */
    public function logon(Request $request)
    {
        $this->valid($request, [
            'mobile' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password',
            'username' => 'required'
        ]);
        return $this->success(
            UserLoginService::instance()->logon($request->mobile, $request->password, $request->username)
        );
    }

    public function loginView()
    {
        return view('login');
    }

    public function logonView()
    {
        return view('logon');
    }
}
