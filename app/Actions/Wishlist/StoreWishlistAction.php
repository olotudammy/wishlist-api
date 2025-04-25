<?php

namespace App\Actions\Wishlist;

use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class StoreWishlistAction
{
    use ResponseTrait;

    public function handle(array $requestData): JsonResponse
    {
        $user = auth()->user();

        if ($user->wishlist()->where('product_id', $requestData['product_id'])->exists()) {
            return $this->existsResponse('Already added to wishlist');
        }

        $result = $user->wishlist()->firstOrCreate([
            'product_id' => $requestData['product_id'],
        ]);

        return $this->successResponseWithResource('Successful', $result);
    }
}
