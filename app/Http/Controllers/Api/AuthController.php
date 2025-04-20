<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ResponseTrait;

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            return (new LoginAction)->handle($request->validated());
        } catch (\Exception $exception) {
            logger($exception);

            return $this->badRequestResponse('Application error and logged');
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            return (new RegisterAction)->handle($request->validated());
        } catch (\Exception $exception) {
            logger($exception);

            return $this->badRequestResponse('Application error and logged');
        }
    }

    public function logout(): JsonResponse
    {
        try {
            return (new LogoutAction)->handle();
        } catch (\Exception $exception) {
            logger($exception);

            return $this->badRequestResponse('Application error and logged');
        }
    }
}
