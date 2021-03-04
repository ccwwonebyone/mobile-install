<?php


namespace App\Exceptions;


use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Throwable;

class AppException extends \Exception
{
    use ResponseTrait;

    public function __construct($message = "", $code = 5001, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function render(Request $request)
    {
        return $this->error($this->getMessage(), $this->getCode());
    }
}
