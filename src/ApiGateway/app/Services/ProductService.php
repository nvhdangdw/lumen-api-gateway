<?php

declare(strict_types = 1);

namespace App\Services;

use App\Traits\RequestService;

use function config;

class ProductService
{
    use RequestService;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $secret;

    /**
     * ProductService constructor.
     */
    public function __construct()
    {
        $this->baseUri = config('services.products.base_uri');
        $this->secret = config('services.products.secret');
    }

    /**
     * @return string
     */
    public function fetchProducts() : string
    {
        return $this->request('GET', '/api/product');
    }

    /**
     * @param $product
     *
     * @return string
     */
    public function fetchProduct($product) : string
    {
        return $this->request('GET', "/api/product/{$product}");
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function createProduct($data) : string
    {
        return $this->request('POST', '/api/product', $data);
    }

    /**
     * @param $product
     * @param $data
     *
     * @return string
     */
    public function updateProduct($product, $data) : string
    {
        return $this->request('PATCH', "/api/product/{$product}", $data);
    }

    /**
     * @param $product
     *
     * @return string
     */
    public function deleteProduct($product) : string
    {
        return $this->request('DELETE', "/api/product/{$product}");
    }
}
