<?php


namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    /**
     * @var Builder
     */
    protected $builder;

    protected $filters = [];

    /**
     * @var Request|null
     */
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
        $this->setFilters($this->request->all());
    }

    /**
     * @param  Builder  $builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters as $field => $value) {
            if (empty($value)) {
                continue;
            }
            $fieldCamel = Str::camel($field);
            $method = method_exists($this, $fieldCamel) ? $fieldCamel : (method_exists($this, $field) ? $field : '');
            if (!$method) {
                continue;
            }
            if (is_array($value)) {
                call_user_func_array([$this, $method], array_filter([$value]));
            } else {
                call_user_func([$this, $method], $value);
            }
        }
    }


    public function setFilters($filters = [])
    {
        $this->filters = $filters;
    }
}
