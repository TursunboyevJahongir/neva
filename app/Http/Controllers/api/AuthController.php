<?php

namespace App\Http\Controllers\api;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\api\Auth\LoginRequest;
use App\Http\Requests\api\Auth\ResendSmsConfirmRequest;
use App\Http\Requests\api\Auth\SmsConfirmRequest;
use App\Http\Requests\api\VerifyRequest;
use App\Models\User;
use App\Services\Sms\SmsService;
use App\Services\User\KidsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{

    public function authenticate(LoginRequest $request, SmsService $smsService): JsonResponse
    {

        $user = User::query()->firstOrCreate(
            ['phone' => $request->validated()['phone']],
            ['status' => UserStatusEnum::PENDING,
//                'firebase' => $request->validated()['firebase']
            ]
        );
        try {
            $smsService->sendConfirm($request->validated()['phone']);

            return $this->success(__('sms.confirmation_sent', ['attribute' => $request->validated()['phone']]));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }


    /**
     * @param SmsConfirmRequest $request
     * @param SmsService $smsService
     * @param KidsService $userService
     * @return JsonResponse
     */
    public function authConfirm(SmsConfirmRequest $request, SmsService $smsService, KidsService $userService): JsonResponse
    {
        try {
            if ($smsService->confirm($request->json('phone'), $request->json('code'))) {
                $userWithToken = $userService->generateToken($request->json('phone'));
                return $this->success('', $userWithToken);
            }
            return $this->error(__('sms.invalid_code'));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }


    /**
     * @param ResendSmsConfirmRequest $request
     * @param SmsService $smsService
     * @return JsonResponse
     */
    public function resendSms(ResendSmsConfirmRequest $request, SmsService $smsService): JsonResponse
    {
        try {
            $smsService->sendConfirm($request->json('phone'));
            return $this->success(__('sms.confirmation_sent', ['attribute' => $request->json('phone')]));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        // auth()->user()->tokens()->delete();

        return $this->success(__('messages.success'));
    }
}
