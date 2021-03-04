<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrent()
    {
        return $this->success(
            $this->getUser()
        );
    }

    public function test($id)
    {
        dump($id);
    }
}
