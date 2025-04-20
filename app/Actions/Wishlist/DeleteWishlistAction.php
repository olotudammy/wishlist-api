<?php

namespace App\Actions\Wishlist;

use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class DeleteWishlistAction
{
    use ResponseTrait;

    public function handle(int $productId): JsonResponse
    {
        $product = Product::where('id', $productId)->first();

        if (! $product) {
            return $this->notFoundResponse('Invalid product');
        }

        auth()->user()->wishlist()->where('product_id', $productId)->delete();

        return $this->successResponse('Removed from wishlist');
    }
}
