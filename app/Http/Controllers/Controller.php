<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use App\Traits\UserTrait;
use App\Traits\ValidTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ResponseTrait, UserTrait, ValidTrait;
}
