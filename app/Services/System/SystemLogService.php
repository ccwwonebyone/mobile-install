<?php


namespace App\Services\System;


use App\Models\System\SystemLog;
use App\Services\Service;

class SystemLogService extends Service
{
    /**
     * @param $url
     * @param $data
     * @param $method
     * @param  int  $userId
     * @param  string  $username
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function create($url, $data, $method, int $userId = 0, string $username = '')
    {
        return SystemLog::query()->create([
            'url' => $url,
            'data' => $data,
            'method' => $method,
            'user_id' => $userId,
            'username' => $username
        ]);
    }
}
