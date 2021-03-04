<?php


namespace App\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ValidTrait
{
    use ExceptionTrait;

    /**
     * @param  Request|array  $request
     * @param $rules
     * @param  array  $message
     * @param  array  $customAttributes
     * @throws \App\Exceptions\AppException
     */
    public function valid($request, array $rules, array $message = [], array $customAttributes = [])
    {
        $data = $request instanceof Request ? $request->all() : $request;
        $validate = Validator::make($data, $rules, $message, $customAttributes);
        if ($validate->fails()) {
            $this->throwAppException($validate->errors()->first(), 422);
        }
    }
}
