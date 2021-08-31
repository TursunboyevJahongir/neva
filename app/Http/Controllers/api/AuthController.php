<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\LoginRequest;
use App\Http\Requests\api\VerifyRequest;
use App\Models\User;
use App\Services\Sms\SendService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private $service;
    use ApiResponser;
    public function __construct(SendService $sendService)
    {
       // $this->service = $sendService;
       // $this->middleware('guest')->except('logout');
    }
    public function authenticate(LoginRequest $request)
    {

        $user = User::query()->firstOrCreate(['phone' => $request->phone]);
        $code = rand(10000, 99999);
        $message = trans('auth.code', ['code' => $code]);

        $user->update([
            'full_name' => $request->name,
            'verify_code' => $code
        ]);
        if (!empty($user) && !empty($user->phone))
            $this->service->sendSMS(substr($user->phone,1), $message);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
    public function verify(VerifyRequest $request)
    {
        $user = User::query()
            ->firstOrCreate(['phone' => $request->phone]);

        if ($user->verifyCode($request->verify_code)) {
            Auth::login($user, true);
            return response()->json([
                'status' => true,
            ]);
        }
    }



    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
