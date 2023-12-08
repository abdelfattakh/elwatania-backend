<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Models\Category;
use App\Http\Requests\Api\Home\GetProductByCategory;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class HomeController extends ApiController
{
    /**
     * to get products with its categories
     */
    public function get_products(): JsonResponse
    {
        $products = Product::query()
            ->active()
            ->with('category')
            ->simplePaginate();

        if (!$products) {
            return ApiResponse::ResponseSuccess(message: "No Products yet");
        }
        return ApiResponse::ResponseSuccess(data: [
            'products' => $products,
        ]);
    }

    /**
     * to get categories with its children
     */
    public function get_categories_with_children(): JsonResponse
    {
        $categories = Category::active()
            ->where('parent_id', null)
            ->with('children')
            ->simplePaginate();


        if (!$categories) {
            return ApiResponse::ResponseSuccess(message: "No Categories yet");
        }
        return ApiResponse::ResponseSuccess(data: [
            'categories' => $categories,
        ]);
    }

    /**
     * @return JsonResponse
     * get exclusive products
     */
    public function exclusive_products(): JsonResponse
    {
        $products = Product::active()->where('is_exclusive', 1)
            ->with('category')
            ->simplePaginate();
        if (!$products) {
            return ApiResponse::ResponseSuccess(message: "No Exclusive Products Yet");
        }
        return ApiResponse::ResponseSuccess(data: [
            'Exclusive Products' => $products,
        ], message: "your Request has been done successfully");
    }

    /**
     * @param GetProductByCategory $request
     * @param category_id
     * @return JsonResponse
     */
    public function get_products_by_category(GetProductByCategory $request): JsonResponse
    {
        $products = Product::query()
            ->where('category_id', $request->validated('category_id'))->get();

        if (!$products) {
            return ApiResponse::ResponseSuccess(message: "no product yet for this category");
        }

        return ApiResponse::ResponseSuccess(data: [
            'products' => $products
        ]);

    }
}
