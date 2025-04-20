<?php

namespace App\Actions\Auth;

use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LoginAction
{
    use ResponseTrait;

    const TOKEN_EXPIRES_IN_MINUTES = 15;

    public function handle(array $requestData): JsonResponse
    {
        if (! Auth::attempt([
            'email' => $requestData['email'],
            'password' => $requestData['password'],
        ])) {
            return $this->invalidResponse('Invalid login credentials');
        }

        $user = request()->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->tokens()->latest()->update([
            'expires_at' => now()->addMinutes(self::TOKEN_EXPIRES_IN_MINUTES),
        ]);

        $refreshCode = Str::random(8);
        Cache::put($refreshCode, $user);

        return $this->successResponse('Login was successful', [
            'token' => $token,
            'user' => $user,
            'token_refresh_code' => $refreshCode,
            'token_expires' => now()->addMinutes(self::TOKEN_EXPIRES_IN_MINUTES),
        ]);

    }

    public function refreshToken($refreshCode): JsonResponse
    {
        if (Cache::has($refreshCode)) {
            $user = Cache::get($refreshCode);
            $newRefreshTokenCode = Str::random(8);

            $cleanToken = $user->createToken('auth_token')->plainTextToken;
            $user->tokens()->latest()->update([
                'expires_at' => Carbon::now()->addMinutes(self::TOKEN_EXPIRES_IN_MINUTES),
            ]);

            Cache::put($newRefreshTokenCode, $user);
            Cache::forget($refreshCode);

            return $this->successResponse('Login successful', [
                'token' => $cleanToken,
                'token_expires' => Carbon::now()->addMinutes(15),
                'token_refresh_code' => $newRefreshTokenCode,
            ]);
        }

        return $this->failedResponse('Invalid refresh token code supplied');
    }
}
