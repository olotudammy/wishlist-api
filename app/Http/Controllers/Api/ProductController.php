<?php

namespace App\Http\Controllers\Api;

use App\Actions\Product\ListProductsAction;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use ResponseTrait;

    public function index(): JsonResponse
    {
        try {
            $result = (new ListProductsAction)->handle();

            return $this->successResponseWithResource('Successful', $result);
        } catch (\Exception $exception) {
            logger($exception);

            return $this->badRequestResponse('Application error and logged');
        }
    }
}
