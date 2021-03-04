<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * @param null|mixed $data
     * @param int $code
     * @return JsonResponse
     */
    public function success($data = null, $code = 200)
    {
        return new JsonResponse([
            'data' => $data,
            'code' => $code,
            'success' => true,
            'err_msg' => ''
        ]);
    }

    /**
     * @param string $errMsg
     * @param int $code
     * @return JsonResponse
     */
    public function error($errMsg = '', $code = 5001)
    {
        return new JsonResponse([
            'data' => null,
            'code' => $code,
            'success' => false,
            'err_msg' => $errMsg
        ]);
    }
}
