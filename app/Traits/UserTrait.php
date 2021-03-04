<?php


namespace App\Traits;


use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait UserTrait
{
    use ExceptionTrait;

    /**
     * @param null $guard
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|User
     */
    public function getUser()
    {
//        return User::query()->find(1);
        return Auth::user();
    }
}
