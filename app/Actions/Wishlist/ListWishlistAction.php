<?php

namespace App\Actions\Wishlist;

use App\Http\Resources\WishlistResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListWishlistAction
{
    public function handle(): AnonymousResourceCollection
    {
        return WishlistResource::collection(
            auth()->user()->wishlist()
                ->with(['product', 'user'])
                ->orderBy('id', 'desc')
                ->get()
        );
    }
}
