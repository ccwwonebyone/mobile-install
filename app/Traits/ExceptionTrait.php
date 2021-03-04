<?php


namespace App\Traits;


use App\Exceptions\AppException;

trait ExceptionTrait
{
    /**
     * @param  string  $errMsg
     * @param  int  $code
     * @throws AppException
     */
    public function throwAppException($errMsg = '', $code = 5001)
    {
        throw new AppException($errMsg, $code);
    }
}
