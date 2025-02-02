<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\IndexRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        try {
            $result = $this->productService->list($request->validated());

            if ($result->isEmpty()) {
                return response()->json(['error' => 'No products found'], 404);
            }
            return response()->json($result);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
