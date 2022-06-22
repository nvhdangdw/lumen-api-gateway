<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::all();
        return $this->successResponse($orders);
    }

    public function show($order)
    {
        $order = Order::findOrFail($order);
        return $this->successResponse($order);
    }

    public function store(Request $request)
    {
        $rules = [
            'quantity' => 'required|numeric|min:1|max:100',
            'product_id' => 'required|integer',
            'total_price' => 'required|numeric|min:1',
            'discount' => 'numeric'
        ];

        $this->validate($request, $rules);
        $order = Order::create($request->all());
        return $this->successResponse($order);

    }

    public function update(Request $request, $order)
    {
        $rules = [
            'quantity' => 'numeric|min:1|max:100',
            'product_id' => 'integer',
            'total_price' => 'numeric|min:1',
            'discount' => 'numeric'
        ];

        $this->validate($request, $rules);
        $order = Order::findOrFail($order);
        $order = $order->fill($request->all());

        if ($order->isClean()) {
            return $this->errorResponse('at least one value must be change',
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $order->save();
        return $this->successResponse($order);
    }


    public function destroy($order)
    {

        $order = Order::findOrFail($order);
        $order->delete();
        return $this->successResponse($order);
    }


}