<?php

namespace App\Http\Controllers\DingTalk;

use App\Http\Controllers\Controller;
use App\Services\DingTalk\DingTalkService;
use Illuminate\Http\Request;

class DingTalkController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sign(Request $request)
    {
        return $this->success(
            DingTalkService::instance()->getSign($request->input('url'))
        );
    }
}
