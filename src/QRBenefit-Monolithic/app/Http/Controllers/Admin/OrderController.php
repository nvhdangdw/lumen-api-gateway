<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Customer;
use Illuminate\Support\Facades\DB;

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
	}

	public function index( Request $request){
		$query = Order::query();
		$query->join('customer', 'customer.customer_id', '=', 'order.customer_id');
		$query->join('store', 'store.store_id', '=', 'order.store_id');
		$query->select('order.*',DB::raw('concat(customer.lastname," ",customer.firstname) as customer_name'));
		$order_fields = ["id","total_tax","total_discount","total_amount","date","customer_name","store_name"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('order.order_id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->from_date) && !empty($request->from_date)) {
			$query->where('order.created_at', '>=', $request->from_date);
			$filter_parameters["from_date"] = $request->from_date;
		}
		if (isset($request->to_date) && !empty($request->to_date)) {
			$query->where('order.created_at', '<=', $request->to_date." 23:59:59");
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
			$query->whereRaw("concat(customer.lastname,' ',customer.firstname) LIKE '%".$request->customer_name."%'");
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
		return view('admin.order.index',['orders' => $orders, 'filter_parameters' => $filter_parameters, "order_parameters" => $order_parameters]);
	}

	public function showViewInfo($id){
		$order = Order::find($id);
		return view('admin.order.info',['order' => $order]);
	}

	public function getOrderList(Request $request){
		$filters = $request->all();
		$respone_data = [];
		$where_array = [];

		//generate where statements in SQL
		foreach ($filters as $filter) {
			$type = $filter['type'] ?? '';
			$operator = $filter['operator'] ?? '=';

			if($filter['value'] == '' || empty($filter['value']) ) continue;


			$value = (strtoupper($operator) == 'LIKE') ? "%{$filter['value']}%" : $filter['value'];
			$where_array[] = [$filter['column'],$operator,$value];
		}

		$order_result = Order::join('customer','customer.customer_id','=','order.customer_id')
				->join('store','store.store_id','=','order.store_id')
				->where($where_array)
				->get(['order.*','customer.firstname','customer.lastname']);
		//prepare data
		foreach ($order_result as $key => $order) {
			$respone_data[] = [
				'order_id' => '#'.$order->store->code.$order->order_id,
				'customer' => $order->customer->firstname. ' ' .$order->customer->lastname,
				'total_amount' => number_format($order->total_amount,2) . '$',
				'total_tax' => number_format($order->total_tax,2) . '$',
				'total_discount' => number_format($order->total_discount,2) . '$',
				'paid' => number_format($order->total_amount + $order->total_tax - $order->total_discount,2) . '$',
				'created_at' => $order->created_at->format('m-d-Y H:i:s'),
			];
		}
		$response = array(
			'status' => 'success',
			'aaData' => $respone_data,
			"iTotalRecords" => count($respone_data),
			"iTotalDisplayRecords" => count($respone_data),
			'msg' => $request->message,
		);

		return response()->json($response);
	}

	public function create(Request $request){

		$order_data = $request->all();

		if($order_data['customer_id'] != 0){
			$validatedData = $request->validate([
					'firstname' => 'required|string|max:50',
					'lastname' => 'required|string|max:50',
					'total_amount' => 'required|numeric|between:0.01,999999.99',
			]);
		} else {
			//insert new customer
			//check duplicated email
			$validatedData = $request->validate([
					'firstname' => 'required|string|max:50',
					'lastname' => 'required|string|max:50',
					'email' => 'required|email|unique:customer|max:50',
					'total_amount' => 'required|numeric|between:0.01,999999.99',
			]);

			$customer = new Customer;

			$customer->firstname = $request->input("firstname");
			$customer->lastname = $request->input("lastname");
			$customer->email = $request->input("email");
			$customer->phone_number = $request->input("phone_number");

			$customer->save();

			$customer_id = $customer->customer_id; // get the inserted customer_id

		}

		$order = new Order;

		$order->customer_id = $order_data['customer_id'];
		$order->store_id = $order_data['store_id'];
		$order->total_tax = $order_data['total_tax'] ?? 0;
		$order->total_discount = $order_data['total_discount'] ?? 0;
		$order->total_amount = $order_data['total_amount'];

		$order->save();
		if($order_data['customer_id'] != 0){
			return view('store.checkout',['status'=>'success']);
		} else {
			//direct to Customer information when new customer is added
			return redirect('/store/customer/'.$customer_id);
		}
	}
}
