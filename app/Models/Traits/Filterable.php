<?php

namespace App\Models\Traits;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Filterable
 * @package App\Models\Traits
 * @method static Builder filter(QueryFilter $filter)
 */
trait Filterable
{
    /**
     * 条件过滤
     * @param  mixed  $query
     * @param  QueryFilter  $filters
     * @return mixed
     * @throw \App\Exceptions\ResponseCodeException
     */
    public function scopeFilter($query, QueryFilter $filters)
    {
        $filters->apply($query);
    }

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format($this->getDateFormat());
    }
}
