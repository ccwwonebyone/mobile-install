<?php

namespace App\Jobs;

use App\Services\System\SystemLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SystemLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    protected $data;

    protected $method;

    protected $userId;

    protected $username;

    /**
     * SystemLogJob constructor.
     * @param $url
     * @param $data
     * @param $method
     * @param $userId
     * @param $username
     */
    public function __construct($url, $data, $method, $userId = 0, $username = '')
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = $method;
        $this->userId = $userId;
        $this->username = $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        SystemLogService::instance()->create($this->url, $this->data, $this->method, $this->userId, $this->username);
    }
}
