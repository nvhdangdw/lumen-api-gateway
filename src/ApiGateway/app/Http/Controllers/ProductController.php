<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private $productService;

    /**
     * ProductController constructor.
     *
     * @param \App\Services\ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->successResponse($this->productService->fetchProducts());
    }

    /**
     * @param $product
     *
     * @return mixed
     */
    public function show($product)
    {
        return $this->successResponse($this->productService->fetchProduct($product));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->successResponse($this->productService->createProduct($request->all()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $product
     *
     * @return mixed
     */
    public function update(Request $request, $product)
    {
        return $this->successResponse($this->productService->updateProduct($product, $request->all()));
    }

    /**
     * @param $product
     *
     * @return mixed
     */
    public function destroy($product)
    {
        return $this->successResponse($this->productService->deleteProduct($product));
    }
}
