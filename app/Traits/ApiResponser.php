<?php
/**
 * Author Maxamadjonov Jaxongir.
 * https://github.com/jtscorpjaxon
 * Date: 12.08.2021
 * Time: 11:06
 */

namespace App\Traits;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/

trait ApiResponser
{
    /**
     * Return a success JSON response.
     *
     * @param  array|string $data
     * @param  string $message
     * @param  int|null $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, string $message = '', int $code = 200)
    {
        try {
            return response()->json([
                    'status' => 'Success',
                    'message' => $message,
                ] + ($data->toArray()? :['data'=>[]] )
                , $code);
        }
        catch (\Throwable $e) {
            header('Content-Type: application/json');
            die(json_encode([
                    'status' => 'Success',
                    'message' => $message,
                    'data'=>$data ?? []
                ], JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Return an error JSON response.
     *
     * @param  string $message
     * @param  int $code
     * @param  array|string|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message = null, int $code, $data = null)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
        ]+ $data->toArray(), $code);
    }

}