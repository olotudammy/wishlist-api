<?php

namespace App\Actions\Product;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListProductsAction
{
    public function handle(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::orderBy('id', 'desc')->get());

    }
}
