<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Requests\LoginRequest;
use App\Modules\Information\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mews\Captcha\Captcha;


class AuthController extends Controller
{
    public function __construct(
        private readonly Captcha $captcha,
        private readonly UserInterface $userRepository,
    ) {}

    public function login(LoginRequest $request)

    {
        $credentials = [
            'username' => $request->get('username'),
            'password' => $request->get('password'),
        ];

        $user = $this->userRepository->getByUsername($credentials['username']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return Response::error(
                message: 'Foydalanuvchi nomi yoki parol noto\'g\'ri',
                status: 401
            );
        }

        if ($user->is_active === false) {
            return Response::error(
                message: 'Unauthorized',
                status: 401
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return Response::success(
            data: [
                'token' => $token
            ],
            message: 'Tizimga muvaffaqiyatli kirdingiz',

        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return Response::success(
            message: 'Tizimdan muvaffaqiyatli chiqdingiz',
        );
    }

    public function getMe()
    {
        return Response::success(
            Auth::user()
        );
    }

    public function getCaptcha()
    {
        if (config('captcha.disable')) {
            return Response::success(
                ['captcha' => 'OPTIONAL']
            );
        }
        $captcha = $this->captcha->create('math', true);

        return Response::success(['captcha' => $captcha]);
    }
}
