<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AppException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @param  Request  $request
     * @param  \Throwable  $throwable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, \Throwable $throwable)
    {
        if (!config('app.debug')) {
            if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
                return $this->error('系统正在开小差，请稍后重试。');
            }
        }
        return parent::render($request, $throwable);
    }
}
