<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\User\UserLoginService;
use App\Traits\ResponseTrait;
use App\Traits\UserTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class CheckAuthMiddleware
{
    use ResponseTrait;
    use UserTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            //尝试刷新token
            try {
                $token = Auth::refresh();
                header('token:'.$token);
            } catch (TokenExpiredException $e) {
                return $this->error('token完全过期，需要重新登录', 429);
            } catch (TokenBlacklistedException $e) {
                return $this->error('使用的是过期的token', 429);
            } catch (JWTException $e) {
                return $this->error('非法请求', 429);
            }
        }
        return $next($request);
    }
}
