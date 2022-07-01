<?php

namespace App\Http\Controllers\Api\Store;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\ResponseData;

class DashboardController extends Controller
{
    private $responseData;
    private $discount;
    private $order;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->responseData = new ResponseData();
        $this->promotion = new PromotionController();
        // $this->order = new OrderController();
    }
    //
    public function index($storeID)
    {
        $data = array();

        //Promotion
        $totalAvailablePromotions = $this->promotion->getAvailablePromotions($storeID);
        // $totalDiscounts = $this->discount->getTotalDiscounts();

        //Order
        // $totalOrders = $this->order->getOrderTotals($storeID);

        $data[] = array(
            // 'total_orders'                  => $totalOrders,
            'total_available_promotions'     => $totalAvailablePromotions,
            // 'total_discounts'               => $totalDiscounts
        );

        return $this->responseData->success($data);
    }
}
