<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class RegisterAction
{
    use ResponseTrait;

    public function handle(array $requestData): JsonResponse
    {
        $user = User::create([
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'password' => bcrypt($requestData['password']),
        ]);

        return $this->successResponse('Login was successful', [
            'user' => $user,
        ]);
    }
}
