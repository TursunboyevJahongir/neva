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
        $this->service = $sendService;
        $this->middleware('guest')->except('logout');
    }
    public function authenticate(LoginRequest $request)
    {
        $login='user'.substr($request->phone,5);
        $user = User::query()->firstOrCreate(
            ['phone' => $request->phone],
            ['login'=>$login,'email'=>$login."@neva.uz",'password'=>bcrypt('12345678')]
            );
        $code = rand(10000, 99999);
        $message = /*trans('auth.code', ['code' =>*/ $code/*])*/;
        $message='NEVA:Ваш код для авторизации - '.$message.PHP_EOL.'В случае возникновения вопросов, свяжитесь пожалуйста по номеру +998781488008';
        $user->update([
            'full_name' => $request->name,
            'verify_code' => $code
        ]);
        $result='';
        if (!empty($user) && !empty($user->phone))
            $result=$this->service->sendSMS(substr($user->phone,1), $message);

        return response()->json([
            'status' => true,
        ]);
    }
    public function verify(VerifyRequest $request)
    {
        $user = User::query()
            ->find(['phone' => $request->phone]);

        if ($user->isNotEmpty() && $user->verifyCode($request->verify_code)) {
            $token = $user->createToken(time())->plainTextToken;
            $user->active=true;
            return response()->json([
                'status' => true,
                'token' => $token
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Неправильный код'
        ],401);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
       // auth()->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }
}
