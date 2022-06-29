<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Order;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\ResponseData;

class ExportController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->responseData = new ResponseData();
	}

	public function order(Request $request)
	{
		$user = Auth::user();
		$store = $user->store;
		$query = Order::query();
		$query->join('customer', 'customer.customer_id', '=', 'order.customer_id');
		$query->join('store', 'store.store_id', '=', 'order.store_id');
		$query->select('order.*',DB::raw('concat(customer.firstname," ",customer.lastname) as customer_name'));
		$order_fields = ["id","total_tax","total_discount","total_amount","date","customer_name","store_name","vouchers_redemned"];
		$query->where('store.store_id', $store->store_id);
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('order.order_id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->from_date) && !empty($request->from_date)) {
			$query->where('order.created_at', '>=', implode('-', array_reverse(explode('-', $request->from_date))));
			$filter_parameters["from_date"] = $request->from_date;
		}
		if (isset($request->to_date) && !empty($request->to_date)) {
			$query->where('order.created_at', '<=', implode('-', array_reverse(explode('-', $request->to_date)))." 23:59:59");
			$filter_parameters["to_date"] = $request->to_date;
		}
		if (isset($request->total_amount)) {
			$compare = '>=';
			if ($request->total_amount_compare == "<=") {
				$compare = '<=';
			}
			if ($request->total_amount_compare == "=") {
				$compare = '=';
			}
			$filter_parameters["total_amount_compare"] = $compare;
			$query->where('order.total_amount', $compare, $request->total_amount);
			$filter_parameters["total_amount"] = $request->total_amount;
		}

		if (isset($request->customer_name) && !empty($request->customer_name)) {
			$query->whereRaw("concat(customer.firstname,' ',customer.lastname) LIKE '%".$request->customer_name."%'");
			$filter_parameters["customer_name"] = $request->customer_name;
		}

		if (isset($request->store_name) && !empty($request->store_name)) {
			$query->where('store.name', 'LIKE', '%' . $request->store_name . '%');
			$filter_parameters["store_name"] = $request->store_name;
		}

		if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
			$order = "asc";
			if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
				$order = "desc";
			}
			$order_parameters['sort'] = $request->sort;
			$order_parameters['order'] = $order;
			if ($request->sort == 'id'){
				$query->orderBy("order.order_id",$order);
			} else if ($request->sort == 'date'){
				$query->orderBy("order.created_at",$order);
			} else if ($request->sort == 'customer_name'){
				$query->orderBy("customer_name",$order);
			} else if ($request->sort == 'store_name') {
				$query->orderBy("store.name",$order);
			} else {
				$query->orderBy("order.".$request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('order.created_at','decs');
		$orders = $query->get();
        $data = array();
        foreach ($orders as $order) {
            $row = array(
                "Name" => $order->customer ? ($order->customer->firstname.' '.$order->customer->lastname) : "",
                "Total Amount" => $order->total_amount,
                "Tax" => $order->total_tax,
                "Discounted Amount" => $order->total_discount,
                "Paid" => number_format(max(0,$order->total_amount + $order->total_tax - $order->total_discount), 2, '.', ''),
                "Vouchers Redemned" => $order->vouchers_redemned,
                "Promotion Codes" => $order->promotion_codes,
                "Created Date" => $order->created_at->format('d-m-Y')
            );
            array_push($data, $row);
        }
		return $this->responseData->success($data);
	}

}
