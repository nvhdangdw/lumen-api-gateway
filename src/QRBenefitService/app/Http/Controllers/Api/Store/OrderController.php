<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Discount;
use App\OrderDiscount;
use App\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\ResponseData;
use Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
	protected $perPage = 10;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->responseData = new ResponseData();
	}

	public function index(Request $request) {
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
		$orders = $query->paginate($this->perPage);
		// add filter to link pagination
		$orders->appends(array_merge($filter_parameters, $order_parameters))->links();
		$data = array();
		foreach ($orders as $order) {
			$customer = $order->customer;
			$store = $order->store;
			$user = $order->user;
			$row = array(
				"order_id" => $order->order_id,
				"date" => $order->created_at->format('d-m-Y'),
				"vouchers_redemned" => $order->vouchers_redemned,
				"promotion_codes" => $order->promotion_codes,
				"customer" => array(
					"customer_id" => $customer ? $customer->customer_id : "",
					"firstname" => $customer ? $customer->firstname : "",
					"lastname" => $customer ? $customer->lastname : "",
					"email" => $customer ? $customer->email : "",
					"telephone" => $customer ? $customer->telephone : ""
				),
				"store" => array(
					"store_id" => $store->store_id,
					"name" => $store->name,
					"email" =>$store->email
				),
				"total_tax" => $order->total_tax,
				"total_discount" => $order->total_discount,
				"total_amount" => $order->total_amount
			);
			$data[] = $row;
		}
		$orders->setCollection(collect($data));
		return $this->responseData->success($orders);
	}

	public function info($id) {
		$order = Order::find($id);
		if ($order) {
			$customer = $order->customer;
			$store = $order->store;
			$discount = array();
			if ($order->discount) {
				$discount = $order->discount;
			}
			$data = array(
				"order_id" => $order->order_id,
				"date" => $order->created_at->format('d-m-Y'),
				"vouchers_redemned" => $order->vouchers_redemned,
				"promotion_codes" => $order->promotion_codes,
				"discounts" => $order
					->orderDiscounts()
					->get(['order_id', 'discount_id'])
					->load(['discount' => function($q) {
						$q->select(['discount_id', 'code', 'type', 'discount_amount']);
					}])
					->map(function($item) {
						return $item->discount;
					}),
				"customer" => array(
					"customer_id" => $customer ? $customer->customer_id : "",
					"firstname" => $customer ? $customer->firstname : "",
					"lastname" => $customer ? $customer->lastname : "",
					"email" => $customer ? $customer->email : "",
					"telephone" => $customer ? $customer->telephone : ""
				),
				"store" => array(
					"store_id" => $store->store_id,
					"name" => $store->name,
					"email" =>$store->email
				),
				"user" => array(
					"name" => $order->user->name,
					"email" => $order->user->email
				),
				"total_tax" => $order->total_tax,
				"total_discount" => $order->total_discount,
				"total_amount" => $order->total_amount
			);
			return $this->responseData->success($data);
		} else {
			$errors = array(
				"order" => ["order not exist"],
			);
			return $this->responseData->error($errors);
		}
	}

	public function create(Request $request) {
		$user = Auth::user();
		$store = $user->store;
		$order_data = $request->all();
		$validator = Validator::make($order_data, [
			'customer_id' => 'required|integer|min:1',
			'firstname' => 'required|string|max:50',
			'lastname' => 'required|string|max:50',
			'email' => 'required|email|max:50',
			'total_amount' => 'required|numeric|between:0.00,999999.99',
			'telephone' => 'required',
			'total_tax' => 'nullable|numeric|between:0,999999.99',
			'total_discount' => 'nullable|numeric|between:0,999999.99',
			'discount_ids' => 'required_if:total_amount,=,0|array',
		], [
			'discount_ids.required' => __('validation.required', [
				'attribute' => 'promotion'
			]),
			'discount_ids.array' => __('validation.required', [
				'attribute' => 'promotion'
			]),
			'discount_ids.required_if' => __('validation.required', [
				'attribute' => 'promotion'
			]),
			'telephone.regex' => __('validation.telephone_incorrect'),
		]);
		// Check validation failure
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->responseData->error($errors);
		}
		$promotion_Codes = array();
		$vouchers_redemned = 0;
		if( !empty($order_data['discount_ids']) ) {
			$vouchers_redemned = count($order_data['discount_ids']);
			foreach ($order_data['discount_ids'] as $discount_id) {
				$discount = Discount::where('discount_id', $discount_id)->where('store_id', $store->store_id)->first();
				if (!$discount) {
					$discount_incorrect[] = str_replace(":attribute",  $discount_id , trans('validation.discount_incorrect'));
					$errors = array(
						"discount" => $discount_incorrect,
					);
					return $this->responseData->error($errors);
				} else {
					$promotion_Codes[] = $discount->code;
				}
			}
		}

		$customer = Customer::find($order_data['customer_id']);
		if (!$customer) {
			$customer = new Customer;
			$customer->customer_id = $order_data['customer_id'];
		}
		$customer->firstname = $order_data['firstname'];
		$customer->lastname = $order_data['lastname'];
		$customer->email = $order_data['email'];
		$customer->telephone = $order_data['telephone'];
		$customer->save();
		$order = new Order;
		$order->customer_id = $customer->customer_id;
		$order->store_id = $store->store_id;
		$order->user_id = $user->id;
		$order->total_tax = $order_data['total_tax'] ?? 0;
		$order->vouchers_redemned = $vouchers_redemned;
		$order->promotion_codes = implode(",",$promotion_Codes);
		$order->total_discount = $order_data['total_discount'] ?? 0;
		$order->total_amount = $order_data['total_amount'] ?? 0;
		$order->save();

		if( !empty($order_data['discount_ids']) ) {
			foreach ($order_data['discount_ids'] as $discount_id) {
				$orderDiscount = new OrderDiscount;
				$orderDiscount->order_id= $order->order_id;
				$orderDiscount->discount_id= $discount_id;
				$orderDiscount->save();
			}
		}
		return $this->responseData->success($order);
	}

	public function getOrderTotals($storeID) {
		$totalOrders = Order::select('order_id')->where('store_id', $storeID)->get()->count();

		return $totalOrders;
	}

}
