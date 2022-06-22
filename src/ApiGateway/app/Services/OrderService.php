<?php

declare(strict_types = 1);

namespace App\Services;

use App\Traits\RequestService;

use function config;

class OrderService
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

    public function __construct()
    {
        $this->baseUri = config('services.orders.base_uri');
        $this->secret = config('services.orders.secret');
    }

    /**
     * @return string
     */
    public function fetchOrders() : string
    {
        return $this->request('GET', '/api/order');
    }

    /**
     * @param $order
     *
     * @return string
     */
    public function fetchOrder($order) : string
    {
        return $this->request('GET', "/api/order/{$order}");
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function createOrder($data) : string
    {
        return $this->request('POST', '/api/order', $data);
    }

    /**
     * @param $order
     * @param $data
     *
     * @return string
     */
    public function updateOrder($order, $data) : string
    {
        return $this->request('PATCH', "/api/order/{$order}", $data);
    }

    /**
     * @param $order
     *
     * @return string
     */
    public function deleteOrder($order) : string
    {
        return $this->request('DELETE', "/api/order/{$order}");
    }
}
