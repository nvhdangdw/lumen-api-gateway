<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Discount;
use App\store;

class DiscountController extends Controller
{
	protected $perPage = 5;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

	public function index(Request $request){
		$query = Discount::query();
		$query->join('store', 'store.store_id', '=', 'discount.store_id');
		$query->select('discount.*');
		$order_fields = ["id", "code", "type", "discount_amount", "store_name"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('discount.discount_id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->code) && !empty($request->code)) {
			$query->where('discount.code', 'LIKE', '%' . $request->code . '%');
			$filter_parameters["code"] = $request->code;
		}
		if (isset($request->type) && !empty($request->type)) {
			$query->where('discount.type', $request->type );
			$filter_parameters["type"] = $request->type;
		}
		if (isset($request->discount_amount)) {
			$compare = '>=';
			if ($request->discount_amount_compare == "<=") {
				$compare = '<=';
			}
			if ($request->discount_amount_compare == "=") {
				$compare = '=';
			}
			$filter_parameters["discount_amount_compare"] = $compare;
			$query->where('discount.discount_amount', $compare, $request->discount_amount);
			$filter_parameters["discount_amount"] = $request->discount_amount;
		}
		if (isset($request->store_name)) {
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
				$query->orderBy("discount.discount_id",$order);
			} else if($request->sort == "store_name") {
				$query->orderBy("store.name",$order);
			} else {
				$query->orderBy('discount.'.$request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('discount.created_at','decs');
		$discounts = $query->paginate($this->perPage);
		// add filter to link pagination
		$discounts->appends(array_merge($filter_parameters, $order_parameters))->links();
		return view('admin.discount.index',['discounts' => $discounts,'filter_parameters' => $filter_parameters, "order_parameters" => $order_parameters]);
	}

	public function showViewAdd(){
		$stores = Store::all();
		return view('admin.discount.add',["stores" => $stores]);
	}

	public function create(Request $request){
		$store = Store::findOrFail($request->input("store_id"));
		$check_percent = "";
		if ($request->input("type") == "%") {
			$check_percent = "|max:100";
		}
		$validatedData = $request->validate([
			'code' => 'required|string|max:30',
			'description' => 'nullable|string',
			'type' => array('required','string','regex:/^((\$)|(%))$/i'),
			'discount_amount' => 'required|numeric|min:0'.$check_percent,
			'store_id' => 'required|numeric',
		]);
		$discount = new Discount;

		$discount->code = $request->input("code");
		$discount->description = $request->input("description");
		$discount->type = $request->input("type");
		$discount->store_id = $request->input("store_id");
		$discount->discount_amount = $request->input("discount_amount");

		$discount->save();
		return redirect('/admin/discount');
	}

	public function showViewEdit($id){
		$stores = Store::all();
		$discount = Discount::find($id);
		return view('admin.discount.edit',['discount' => $discount, "stores" => $stores]);
	}

	public function update($id, Request $request){
		$discount = Discount::find($id);
		$store = Store::findOrFail($request->input("store_id"));
		$check_percent = "";
		if ($request->input("type") == "%") {
			$check_percent = "|max:100";
		}
		if (!$discount)
			return redirect()->route('admin_discount_show_view_edit',['id' => $id]);
		$validatedData = $request->validate([
			'code' => 'required|string|max:30',
			'description' => 'nullable|string',
			'type' => 'required|string|max:30',
			'discount_amount' => 'required|numeric|min:0'.$check_percent,
			'store_id' => 'required|numeric',
		]);

		$discount->code = $request->input("code");
		$discount->description = $request->input("description");
		$discount->type = $request->input("type");
		$discount->store_id = $request->input("store_id");
		$discount->discount_amount = $request->input("discount_amount");

		$discount->save();
		return redirect('/admin/discount');
	}

	public function delete(Request $request){
		$message = '';
		if ($request->isMethod('post')) {
			$ids = $request->input("selected");
			if(!empty($ids)){
				Discount::destroy($ids);
				$message = 'Delete successful';
			}
		}
		$query = Discount::query();
		$query->join('store', 'store.store_id', '=', 'discount.store_id');
		$query->select('discount.*');
		$order_fields = ["id", "code", "type", "discount_amount", "store_name"];
		$order_parameters = array();
		$filter_parameters = array();
		if (isset($request->id) && !empty($request->id)) {
			$query->where('discount.discount_id', 'LIKE', '%' . $request->id . '%');
			$filter_parameters["id"] = $request->id;
		}
		if (isset($request->code) && !empty($request->code)) {
			$query->where('discount.code', 'LIKE', '%' . $request->code . '%');
			$filter_parameters["code"] = $request->code;
		}
		if (isset($request->type) && !empty($request->type)) {
			$query->where('discount.type', $request->type );
			$filter_parameters["type"] = $request->type;
		}
		if (isset($request->discount_amount)) {
			$compare = '>=';
			if ($request->discount_amount_compare == "<=") {
				$compare = '<=';
			}
			if ($request->discount_amount_compare == "=") {
				$compare = '=';
			}
			$filter_parameters["discount_amount_compare"] = $compare;
			$query->where('discount.discount_amount', $compare, $request->discount_amount);
			$filter_parameters["discount_amount"] = $request->discount_amount;
		}
		if (isset($request->store_name)) {
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
				$query->orderBy("discount.discount_id",$order);
			} else if($request->sort == "store_name") {
				$query->orderBy("store.name",$order);
			} else {
				$query->orderBy('discount.'.$request->sort,$order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('discount.created_at','decs');
		$discounts = $query->paginate($this->perPage);
		// add filter to link pagination
		$discounts->appends(array_merge($filter_parameters, $order_parameters))->links();
		return view('admin.discount.index',['discounts' => $discounts,'filter_parameters' => $filter_parameters, "order_parameters" => $order_parameters, 'message' => $message]);
	}

	public function showViewInfo($id){
		$discount = Discount::find($id);
		return view('admin.discount.info',['discount' => $discount]);
	}
}
