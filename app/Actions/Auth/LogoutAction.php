<?php

namespace App\Actions\Auth;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class LogoutAction
{
    use ResponseTrait;

    public function handle(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return $this->successResponse('User logged out successfully');
    }
}
