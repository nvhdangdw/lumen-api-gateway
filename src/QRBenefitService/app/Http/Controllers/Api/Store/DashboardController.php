<?php

namespace App\Http\Controllers\Api\Store;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\ResponseData;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->responseData = new ResponseData();
        $this->discount = new DiscountController();
        $this->order = new OrderController();
    }
    //
    public function index($storeID)
    {
        $data = array();

        //Discount
        $totalAvailableDiscounts = $this->discount->getAvailableDiscounts($storeID);
        $totalDiscounts = $this->discount->getTotalDiscounts();

        //Order
        $totalOrders = $this->order->getOrderTotals($storeID);

        $data[] = array(
            'total_orders'                  => $totalOrders,
            'total_available_discounts'     => $totalAvailableDiscounts,
            'total_discounts'               => $totalDiscounts
        );

        return $this->responseData->success($data);
    }
}
