<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Discount;
use App\Supplier;
use Auth;
use App\Http\ResponseData;
use App\Promotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
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

	public function getAll()
	{
		$user = Auth::user();
		$store = $user->store;
		$query = Discount::query();
		$current_date = date("Y-m-d");
		$query->where('store_id', $store->store_id);
		$query->where('start_date', '<=', $current_date);
		$query->where('end_date', '>=', $current_date);
		$discounts = $query->get();
		$data = array();
		foreach ($discounts as $discount) {
			$row = array(
				"discount_id" => $discount->discount_id,
				"code" => $discount->code,
				"start_date" => implode('-', array_reverse(explode('-', $discount->start_date))),
				"end_date" => implode('-', array_reverse(explode('-', $discount->end_date))),
				"type" => $discount->type,
				"discount_amount" => $discount->discount_amount,
				"store_name" => $store->name,
				"status" => 'available'
			);
			$data[] = $row;
		}
		return $this->responseData->success($data);
	}
	public function index(Request $request)
	{
		$user = Auth::user();
		$store = $user->store;
		$query = Discount::query();
		$query->join('store', 'store.store_id', '=', 'discount.store_id');
		$query->select('discount.*');
		$query->where('store.store_id', $store->store_id);
		$order_fields = ["id", "code", "type", "discount_amount", "store_name", "start_date", "end_date"];
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
			$query->where('discount.type', $request->type);
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
		if (isset($request->status) && !empty($request->status)) {
			$current_date = date("Y-m-d");
			$status_arr = explode(',', $request->status);
			$query->where(function ($query) use ($current_date, $status_arr) {
				foreach ($status_arr as $status) {
					$query->orWhere(function ($query) use ($current_date, $status) {
						if (strtolower($status) == "pending") {
							$query->where('discount.start_date', '>', $current_date);
						}

						if (strtolower($status) == "available") {
							$query->where('discount.start_date', '<=', $current_date);
							$query->where('discount.end_date', '>=', $current_date);
						}

						if (strtolower($status) == "ended") {
							$query->where('discount.end_date', '<', $current_date);
						}
					});
				}
			});
		}
		if (isset($request->from_start_date) && !empty($request->from_start_date)) {
			$query->where('discount.start_date', '>=', implode('-', array_reverse(explode('-', $request->from_start_date))));
			$filter_parameters["from_start_date"] = $request->from_start_date;
		}
		if (isset($request->to_start_date) && !empty($request->to_start_date)) {
			$query->where('discount.start_date', '<=', implode('-', array_reverse(explode('-', $request->to_start_date))));
			$filter_parameters["to_start_date"] = $request->to_start_date;
		}

		if (isset($request->from_end_date) && !empty($request->from_end_date)) {
			$query->where('discount.end_date', '>=', implode('-', array_reverse(explode('-', $request->from_end_date))));
			$filter_parameters["from_end_date"] = $request->from_end_date;
		}
		if (isset($request->to_end_date) && !empty($request->to_end_date)) {
			$query->where('discount.end_date', '<=', implode('-', array_reverse(explode('-', $request->to_end_date))));
			$filter_parameters["to_end_date"] = $request->to_end_date;
		}

		if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
			$order = "asc";
			if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
				$order = "desc";
			}
			$order_parameters['sort'] = $request->sort;
			$order_parameters['order'] = $order;
			if ($request->sort == 'id') {
				$query->orderBy("discount.discount_id", $order);
			} else if ($request->sort == "store_name") {
				$query->orderBy("store.name", $order);
			} else {
				$query->orderBy('discount.' . $request->sort, $order);
			}
		}
		if (empty($order_parameters))
			$query->orderBy('discount.created_at', 'decs');
		$discounts = $query->paginate($this->perPage);
		// add filter to link pagination
		$discounts->appends(array_merge($filter_parameters, $order_parameters))->links();
		$data = array();
		foreach ($discounts as $discount) {
			$store = $discount->store;
			$status = "";
			$current_date = date("Y-m-d");
			if (!empty($discount->start_date)) {
				if ($current_date < $discount->start_date)
					$status = "Pending";

				if ($discount->start_date <= $current_date && $current_date <= $discount->end_date)
					$status = "Available";

				if ($current_date > $discount->end_date)
					$status = "Ended";
			}

			$row = array(
				"discount_id" => $discount->discount_id,
				"code" => $discount->code,
				"description" => $discount->description,
				"start_date" => implode('-', array_reverse(explode('-', $discount->start_date))),
				"end_date" => implode('-', array_reverse(explode('-', $discount->end_date))),
				"type" => $discount->type,
				"discount_amount" => $discount->discount_amount,
				"store_name" => $store->name,
				"status" => $status
			);
			$data[] = $row;
		}
		$discounts->setCollection(collect($data));
		return $this->responseData->success($discounts);
	}

	public function create(Request $request)
	{
		$user = Auth::user();
		$store = $user->store;
		$check_percent = "";
		if ($request->input("type") == "%") {
			$check_percent = "|max:100";
		}
		$validator = Validator::make($request->all(), [
			'code' => 'required|string|max:30',
			'description' => 'nullable|string',
			'type' => array('required', 'string', 'regex:/^((\$)|(%))$/i'),
			'discount_amount' => 'required|numeric|min:0' . $check_percent,
			'start_date' => 'required:date|date_format:d-m-Y',
			'end_date' => 'required:date|date_format:d-m-Y|after_or_equal:start_date',
		]);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->responseData->error($errors);
		}
		$discount = new Discount;

		$discount->code = $request->input("code");
		$discount->description = $request->input("description");
		$discount->type = $request->input("type");
		$discount->store_id = $store->store_id;
		$discount->discount_amount = $request->input("discount_amount");
		$discount->start_date = implode('-', array_reverse(explode('-', $request->input("start_date"))));
		$discount->end_date = implode('-', array_reverse(explode('-', $request->input("end_date"))));;

		$discount->save();
		return $this->responseData->success($discount);
	}

	public function info($id, Request $request)
	{
		$user = Auth::user();
		$store = $user->store;
		$discount = Discount::where('discount_id', $id)->where('store_id', $store->store_id)->first();
		if (!$discount) {
			$errors = array(
				"discount" => ["discount not exist"],
			);
			return $this->responseData->error($errors);
		}
		$status = "";
		$current_date = date("Y-m-d");
		if (!empty($discount->start_date)) {
			if ($current_date < $discount->start_date)
				$status = "Pending";

			if ($discount->start_date <= $current_date && $current_date <= $discount->end_date)
				$status = "Available";

			if ($current_date > $discount->end_date)
				$status = "Ended";
		}
		$result = array(
			"discount_id" => $discount->discount_id,
			"code" => $discount->code,
			"description" => $discount->description,
			"type" => $discount->type,
			"discount_amount" => $discount->discount_amount,
			"store_name" => $store->name,
			"start_date" => implode('-', array_reverse(explode('-', $discount->start_date))),
			"end_date" => implode('-', array_reverse(explode('-', $discount->end_date))),
			"status" => $status
		);
		return $this->responseData->success($result);
	}

	public function update($id, Request $request)
	{
		$discount = Discount::find($id);
		if (!$discount) {
			$errors = array(
				"discount" => ["Discount not exist"],
			);
			return $this->responseData->error($errors);
		}
		$user = Auth::user();
		$store = $user->store;
		$check_percent = "";
		if ($request->input("type") == "%") {
			$check_percent = "|max:100";
		}
		$validator = Validator::make($request->all(), [
			'code' => 'required|string|max:30',
			'description' => 'nullable|string',
			'type' => array('required', 'string', 'regex:/^((\$)|(%))$/i'),
			'discount_amount' => 'required|numeric|min:0' . $check_percent,
			'start_date' => 'required:date|date_format:d-m-Y',
			'end_date' => 'required:date|date_format:d-m-Y|after_or_equal:start_date',
		]);
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->responseData->error($errors);
		}
		$discount->code = $request->input("code");
		$discount->description = $request->input("description");
		$discount->type = $request->input("type");
		$discount->discount_amount = $request->input("discount_amount");
		$discount->start_date = implode('-', array_reverse(explode('-', $request->input("start_date"))));
		$discount->end_date = implode('-', array_reverse(explode('-', $request->input("end_date"))));;

		$discount->save();
		return $this->responseData->success($discount);
	}

	public function delete($id, Request $request)
	{
		Discount::destroy($id);

		return $this->responseData->success([]);
	}

	public function deleteList(Request $request)
	{
		$discount_ids = explode(',', $request->discount_ids);
		if (!empty($discount_ids)) {
			$user = Auth::user();
			$store = $user->store;
			$query = Discount::query();
			$query->whereIn('discount_id', $discount_ids);
			$query->where("store_id", $store->store_id);
			$query->delete();
		}

		return $this->responseData->success([]);
	}

	public function getAvailableDiscounts($storeID)
	{
		// Discount
		$queryDiscount = Discount::query();
		$current_date = date("Y-m-d");
		$queryDiscount->select(DB::raw("COUNT(discount_id) AS total"));
		$queryDiscount->where("start_date", "<=", $current_date);
		$queryDiscount->where("end_date", ">=", $current_date);
		$queryDiscount->where('store_id', $storeID);
		$totalAvailableDiscount = $queryDiscount->first();

		return $totalAvailableDiscount['total'];
	}

	public function getTotalDiscounts()
	{
		// Discount
		$totalDiscount = Discount::select('discount_id')->get()->count();

		return $totalDiscount;
	}

	// public function getPromotions(Request $request) {
	// 	$user = Auth::user();
	// 	$store = $user->store;
	// 	$order_parameters = array();
	// 	$filter_parameters = array();
	// 	$order_fields = ["id", "code", "discount_amount", "store_name", "start_date", "end_date"];

	// 	$query = Promotion::query();
	// 	$query->join('store', 'store.store_id', '=', 'promotion.store_id');
	// 	$query->join('promotion_type', 'promotion_type.promotion_type_id', '=', 'promotion.promotion_type_id');
	// 	$query->select('promotion.*','promotion_type.name as promotion_type_name');
	// 	$query->where('store.store_id', $store->store_id);

	// 	if (isset($request->id) && !empty($request->id)) {
	// 		$query->where('promotion.promotion_id', 'LIKE', '%' . $request->id . '%');
	// 		$filter_parameters["id"] = $request->id;
	// 	}
	// 	if (isset($request->code) && !empty($request->code)) {
	// 		$query->where('promotion.code', 'LIKE', '%' . $request->code . '%');
	// 		$filter_parameters["code"] = $request->code;
	// 	}

	// 	if (isset($request->discount_amount)) {
	// 		$compare = '>=';
	// 		if ($request->discount_amount_compare == "<=") {
	// 			$compare = '<=';
	// 		}
	// 		if ($request->discount_amount_compare == "=") {
	// 			$compare = '=';
	// 		}
	// 		$filter_parameters["discount_amount_compare"] = $compare;
	// 		$query->where('promotion.discount_amount', $compare, $request->discount_amount);
	// 		$filter_parameters["discount_amount"] = $request->discount_amount;
	// 	}
	// 	if (isset($request->status) && !empty($request->status)) {
	// 		$current_date = date("Y-m-d");
	// 		$status_arr = explode(',', $request->status);
	// 		$query->where(function ($query) use ($current_date, $status_arr) {
	// 			foreach ($status_arr as $status) {
	// 				$query->orWhere(function ($query) use ($current_date, $status) {
	// 					if (strtolower($status) == "pending") {
	// 						$query->where('promotion.start_date', '>', $current_date);
	// 					}

	// 					if (strtolower($status) == "available") {
	// 						$query->where('promotion.start_date', '<=', $current_date);
	// 						$query->where('promotion.end_date', '>=', $current_date);
	// 					}

	// 					if (strtolower($status) == "ended") {
	// 						$query->where('promotion.end_date', '<', $current_date);
	// 					}
	// 				});
	// 			}
	// 		});
	// 	}
	// 	if (isset($request->from_start_date) && !empty($request->from_start_date)) {
	// 		$query->where('promotion.start_date', '>=', implode('-', array_reverse(explode('-', $request->from_start_date))));
	// 		$filter_parameters["from_start_date"] = $request->from_start_date;
	// 	}
	// 	if (isset($request->to_start_date) && !empty($request->to_start_date)) {
	// 		$query->where('promotion.start_date', '<=', implode('-', array_reverse(explode('-', $request->to_start_date))));
	// 		$filter_parameters["to_start_date"] = $request->to_start_date;
	// 	}

	// 	if (isset($request->from_end_date) && !empty($request->from_end_date)) {
	// 		$query->where('promotion.end_date', '>=', implode('-', array_reverse(explode('-', $request->from_end_date))));
	// 		$filter_parameters["from_end_date"] = $request->from_end_date;
	// 	}
	// 	if (isset($request->to_end_date) && !empty($request->to_end_date)) {
	// 		$query->where('promotion.end_date', '<=', implode('-', array_reverse(explode('-', $request->to_end_date))));
	// 		$filter_parameters["to_end_date"] = $request->to_end_date;
	// 	}

	// 	if (isset($request->sort) && !empty($request->sort) && in_array($request->sort, $order_fields)) {
	// 		$order = "asc";
	// 		if (isset($request->order) && !empty($request->order) && strtolower($request->order) == "desc") {
	// 			$order = "desc";
	// 		}
	// 		$order_parameters['sort'] = $request->sort;
	// 		$order_parameters['order'] = $order;
	// 		if ($request->sort == 'id') {
	// 			$query->orderBy("promotion.promotion_id", $order);
	// 		} else if ($request->sort == "store_name") {
	// 			$query->orderBy("store.name", $order);
	// 		} else {
	// 			$query->orderBy('promotion.' . $request->sort, $order);
	// 		}
	// 	}
	// 	if (empty($order_parameters))
	// 		$query->orderBy('promotion.created_at', 'decs');
	// 	$promotions = $query->paginate($this->perPage);
	// 	// add filter to link pagination
	// 	$promotions->appends(array_merge($filter_parameters, $order_parameters))->links();
	// 	$data = array();
	// 	foreach ($promotions as $promotion) {
	// 		$store = $promotion->store;
	// 		$status = "";
	// 		$current_date = date("Y-m-d");
	// 		if (!empty($promotion->start_date)) {
	// 			if ($current_date < $promotion->start_date)
	// 				$status = "Pending";

	// 			if ($promotion->start_date <= $current_date && $current_date <= $promotion->end_date)
	// 				$status = "Available";

	// 			if ($current_date > $promotion->end_date)
	// 				$status = "Ended";
	// 		}

	// 		$row = array(
	// 			"promotion_id" => $promotion->promotion_id,
	// 			"code" => $promotion->code,
	// 			"description" => $promotion->description,
	// 			"start_date" => implode('-', array_reverse(explode('-', $promotion->start_date))),
	// 			"end_date" => implode('-', array_reverse(explode('-', $promotion->end_date))),
	// 			"promotion_type_name" => $promotion->promotion_type_name,
	// 			"discount_amount" => $promotion->discount_amount,
	// 			"store_name" => $store->name,
	// 			"status" => $status
	// 		);
	// 		$data[] = $row;
	// 	}
	// 	$promotions->setCollection(collect($data));
	// 	return $this->responseData->success($promotions);
	// }
}
