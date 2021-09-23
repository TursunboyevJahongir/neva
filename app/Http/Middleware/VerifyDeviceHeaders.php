<?php

namespace App\Http\Middleware;

use App\Enums\DeviceHeadersEnum;
use App\Http\Request;
use Closure;

class VerifyDeviceHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('telescope/*') && !$request->hasHeaders(DeviceHeadersEnum::toArray())) {
            return response()->json([
                'status' => false,
                'message' => __('All headers not sent!'),
                'data' => [],
                'errors' => []
            ], 400);
        }
        //change language by header
        config()->set('app.locale', $request->getDeviceLang());

        return $next($request);
    }
}
