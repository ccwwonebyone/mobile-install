<?php

namespace App\Console\Commands;

use App\Services\DingTalk\DingTalkService;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        dd(DingTalkService::instance()->sendNoticeMessage('041158324224196492', [
            'msgtype' => 'link',
            'link' => [
                'messageUrl' => config('ding_talk.ding_url').'/theme.html?t='.time().'#/detail?id=80&single=1',
                'picUrl' => '',
                'title' => '讨论组通知',
                'text' => '张志荣邀请你加入讨论组'
            ]
        ]));
        dd(DingTalkService::instance()->getUserByMobile('13275211062'));
    }
}
