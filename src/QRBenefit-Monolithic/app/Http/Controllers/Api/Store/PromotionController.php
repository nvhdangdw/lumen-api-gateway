<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Promotion;
use Auth;
use App\Http\ResponseData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
	protected $perPage = 10;
	protected $active = 5;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->responseData = new ResponseData();
	}

	public function getAll(Request $request)
	{
		$user = Auth::user();
		$store = $user->store;
		$input_customer_id = $request->input("customer_id");
		$current_date = date("Y-m-d");

		$data = DB::table('promotion')
			->join('campaign', 'promotion.campaign_id', '=', 'campaign.campaign_id')
			->join('campaign_type', 'campaign_type.campaign_type_id', '=', 'campaign.campaign_type_id')
			->join('promotion_type', 'promotion_type.promotion_type_id', '=', 'promotion.promotion_type_id')
			->join('product', 'product.product_id', '=', 'promotion_type.purchase_product_id')
			->where('campaign.campaign_status_id', '=', 5)
			->where('promotion.store_id', '=', $store->store_id)
			->where("promotion.start_date", "<=", $current_date)
			->where("promotion.end_date", ">=", $current_date)
			->get(['promotion_type.name as promotion_type_name', 'promotion.*', 'purchase_product_id', 'reward_product_id', 'product.name as product_name', 'product.product_type as product_type', 'campaign_type.name as campaign_type_name',
			DB::raw('(SELECT (promotion.reward_trigger_count - COALESCE(SUM(qty),0)) FROM transaction WHERE promotion_codes = promotion.code and product_id = promotion_type.purchase_product_id and customer_id= '.$input_customer_id.' and vouchers_redeemed = 0) as remain_count'),
			DB::raw('(SELECT (promotion.reward_trigger_count - COALESCE(SUM(amount),0)) FROM transaction WHERE promotion_codes = promotion.code and product_id = promotion_type.purchase_product_id and customer_id= '.$input_customer_id.' and vouchers_redeemed = 0) as remain_amount'),
			DB::raw('(SELECT (COUNT(*)) FROM promotion_reward WHERE promotion_reward.promotion_id = promotion.promotion_id and promotion_reward.is_used = 0 and customer_id= '.$input_customer_id.') as reward_available'),
			DB::raw('(SELECT name FROM product WHERE product_id = reward_product_id) as reward_product_name')
		]);

		return $this->responseData->success($data);
	}

	public function index(Request $request) {
		$user = Auth::user();
		$store = $user->store;
		$current_date = date("Y-m-d");

		$query = Promotion::query();
		$query->join('store', 'store.store_id', '=', 'promotion.store_id');
		$query->join('promotion_type', 'promotion_type.promotion_type_id', '=', 'promotion.promotion_type_id');
		$query->select('promotion.*','promotion_type.name as promotion_type_name');
		$query->where("start_date", "<=", $current_date);
		$query->where("end_date", ">=", $current_date);
		$query->where('store.store_id', $store->store_id);
		$promotions = $query->get();

		$data = array();
		foreach ($promotions as $promotion) {
			$store = $promotion->store;
			$status = "";

			if (!empty($promotion->start_date)) {
				if ($current_date < $promotion->start_date)
					$status = "Pending";

				if ($promotion->start_date <= $current_date && $current_date <= $promotion->end_date)
					$status = "Available";

				if ($current_date > $promotion->end_date)
					$status = "Ended";
			}

			$row = array(
				"promotion_id" => $promotion->promotion_id,
				"code" => $promotion->code,
				"description" => $promotion->description,
				"start_date" => implode('-', array_reverse(explode('-', $promotion->start_date))),
				"end_date" => implode('-', array_reverse(explode('-', $promotion->end_date))),
				"promotion_type_name" => $promotion->promotion_type_name,
				"discount_amount" => $promotion->discount_amount,
				"store_name" => $store->name,
				"status" => $status
			);
			$data[] = $row;
		}
		$promotions = collect($data);
		return $this->responseData->success($promotions);
	}

	public function getAvailablePromotions() {
		$user = Auth::user();
		$store = $user->store;

		$query = Promotion::query();
		$current_date = date("Y-m-d");
		$query->select(DB::raw("COUNT(promotion_id) AS total"));
		$query->where("start_date", "<=", $current_date);
		$query->where("end_date", ">=", $current_date);
		$query->where('store_id', $store->store_id);
		$totalAvailablePromotions = $query->first();

		return $totalAvailablePromotions['total'];
	}
}
