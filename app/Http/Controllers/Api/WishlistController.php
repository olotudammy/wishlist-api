<?php

namespace App\Http\Controllers\Api;

use App\Actions\Wishlist\DeleteWishlistAction;
use App\Actions\Wishlist\ListWishlistAction;
use App\Actions\Wishlist\StoreWishlistAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wishlist\StoreWishlistRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    use ResponseTrait;

    public function index(): JsonResponse
    {
        try {
            $result = (new ListWishlistAction)->handle();

            return $this->successResponseWithResource('Successful', $result);
        } catch (\Exception $exception) {
            logger($exception);

            return $this->badRequestResponse('Application error and logged');
        }
    }

    public function store(StoreWishlistRequest $request): JsonResponse
    {
        try {
            return (new StoreWishlistAction)->handle($request->validated());
        } catch (\Exception $exception) {
            logger($exception);

            return $this->badRequestResponse('Application error and logged');
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $result = (new DeleteWishlistAction)->handle($id);

            return $this->successResponseWithResource('Successful', $result);
        } catch (\Exception $exception) {
            logger($exception);

            return $this->badRequestResponse('Application error and logged');
        }
    }
}
