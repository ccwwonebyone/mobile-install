<?php


namespace App\Filters\Talk;


use App\Filters\QueryFilter;
use App\Services\User\UserService;

class TalkFilter extends QueryFilter
{
    public function status($status)
    {
        $this->builder->where('talks.status', $status);
    }

    public function type($type)
    {
        $this->builder->where('talks.type', $type);
    }

    public function search($search)
    {
        $userIds = UserService::instance()->getUserIdsByName($search);
        $this->builder->where(function ($query) use ($search, $userIds) {
            $query->where('talks.title', 'like', '%'.$search.'%')
                ->orWhere('talks.content', 'like', '%'.$search.'%')
                ->orWhereIn('talks.user_id', $userIds);
        });
    }

    public function userId($userId)
    {
        $this->builder->where('talks.user_id', $userId);
    }
}
