<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use LDAP\Result;

class OrderController extends Controller
{
    /**
     * @var \App\Services\OrderService
     */
    protected $orderService;

    /**
     * @var \App\Services\ProductService
     */
    protected $productService;

    /**
     * OrderController constructor.
     *
     * @param \App\Services\OrderService   $orderService
     * @param \App\Services\ProductService $productService
     */
    public function __construct(OrderService $orderService, ProductService $productService)
    {
        $this->orderService = $orderService;
        $this->productService = $productService;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if ($res = Redis::get('order:index')) {
            return $res;
        }

        $response = $this->successResponse($this->orderService->fetchOrders());
        Redis::set('order:index', $response->getContent());
        return $response;
    }

    /**
     * @param $order
     *
     * @return mixed
     */
    public function show($order)
    {
        return $this->successResponse($this->orderService->fetchOrder($order));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->successResponse($this->orderService->createOrder($request->all()));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $order
     *
     * @return mixed
     */
    public function update(Request $request, $order)
    {
        return $this->successResponse($this->orderService->updateOrder($order, $request->all()));
    }

    /**
     * @param $order
     *
     * @return mixed
     */
    public function destroy($order)
    {
        return $this->successResponse($this->orderService->deleteOrder($order));
    }
}
