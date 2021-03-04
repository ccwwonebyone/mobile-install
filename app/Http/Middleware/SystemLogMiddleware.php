<?php

namespace App\Http\Middleware;

use App\Jobs\SystemLogJob;
use App\Services\System\SystemLogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($request->method() != 'GET') {
            $userId = $user ? $user->id : 0;
            $username = $user ? $user->username : 0;
            dispatch(new SystemLogJob($request->url(), $request->all(), $request->method(), $userId, $username));
        }
        return $next($request);
    }
}
