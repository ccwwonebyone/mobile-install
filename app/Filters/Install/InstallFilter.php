<?php


namespace App\Filters\Install;


use App\Filters\QueryFilter;

class InstallFilter extends QueryFilter
{
    public function installDate(array $installDate)
    {
        $this->builder->whereBetween('install_date', $installDate);
    }

    public function account($account)
    {
        $this->builder->where('account', 'like', '%'.$account.'%');
    }

    public function address($address)
    {
        $this->builder->where('address', 'like', '%'.$address.'%');
    }

    public function remark($remark)
    {
        $this->builder->where('remark', 'like', '%'.$remark.'%');
    }
}
