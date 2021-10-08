<?php

namespace App\Http\Controllers\api;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\api\Auth\LoginRequest;
use App\Http\Requests\api\Auth\RegistrationRequest;
use App\Http\Requests\api\Auth\ResendSmsConfirmRequest;
use App\Http\Requests\api\Auth\SmsConfirmRequest;
use App\Http\Requests\api\VerifyRequest;
use App\Models\Firebase;
use App\Models\User;
use App\Services\Sms\SmsService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth",
     *     tags={"user"},
     *     summary="Auth user",
     *     description="This can only be done by the logged in user.",
     *     operationId="authUser",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"phone"},
     *       @OA\Property(property="phone", type="string", format="phone", example="998991234567"),
     *    ),
     * ),
     *     @OA\Response(
     *         response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example="true"),
     *              @OA\Property(property="message", type="string", example="Подтверждение по смс отправлено на 998991234567"),
     *          )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="errors", type="string", example="Ваш номер телефона заблокирован. Пожалуйста,повторите попытку через 14 мин 59 сек")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The given data was invalid"),
     *       @OA\Property(property="errors", type="object",
     *     @OA\Property(
     *              property="phone",
     *              type="array",
     *              collectionFormat="multi",
     *      @OA\Items(
     *                 type="string",
     *                 example={"phone обязательное поле для заполнения."},
     *              )
     *           )
     *        )
     *     )
     *  )
     * )
     */
    public function authenticate(LoginRequest $request, SmsService $smsService): JsonResponse
    {
        try {
            $smsService->sendConfirm($request->validated()['phone']);
//            Firebase::query()->firstOrCreate(
//                [
//                    'user_id' => $user->id,
//                    'fcm_token' => $request->validated()['firebase']
//                ],
//            );
            return $this->success(__('sms.confirmation_sent', ['attribute' => $request->validated()['phone']]));
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }

    public function Registration(RegistrationRequest $request, SmsService $smsService): JsonResponse
    {
        User::query()->create($request->validated());
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
     * @param UserService $userService
     * @return JsonResponse
     */
    public function authConfirm(SmsConfirmRequest $request, SmsService $smsService, UserService $userService): JsonResponse
    {
        try {
            if ($smsService->confirm($request->json('phone'), $request->json('code'))) {
                $userWithToken = $userService->generateToken($request->json('phone'));
                Firebase::query()->firstOrCreate(
                    [
                        'user_id' => $userWithToken->id,
                        'fcm_token' => $request->validated()['firebase']
                    ],
                );
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
