<?php

namespace App\Http\Controllers\Qiniu;

use App\Http\Controllers\Controller;
use App\Services\Qiniu\QiniuService;
use Illuminate\Http\Request;

class QiniuController extends Controller
{
    public function index()
    {
        return $this->success([
            'token' => QiniuService::instance()->getToken(),
            'key' => QiniuService::instance()->createImageFileKey()
        ]);
    }
}
