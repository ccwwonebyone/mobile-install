<?php


namespace App\Services\Qiniu;


use App\Services\Service;
use Qiniu\Auth;

class QiniuService extends Service
{
    protected $config;

    protected $auth;

    public function __construct($config = null)
    {
        $this->config = $config ?? config('qiniu');
        $this->auth = new Auth($this->config['access_key'], $this->config['secret_key']);
    }

    public function getToken()
    {
        return $this->auth->uploadToken($this->config['bucket']);
    }

    /**
     * 获取上传文件路径+文件名
     * @return string
     */
    public function createImageFileKey()
    {
        return 'upload/'.date('Ymd').'/'.date('His').uniqid();
    }
}
