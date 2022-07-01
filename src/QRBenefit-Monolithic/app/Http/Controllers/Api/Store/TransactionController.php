<?php

namespace App\Http\Controllers\Api\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\PromotionReward;
use Illuminate\Support\Facades\DB;
use App\Http\ResponseData;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Customer;
use App\Product;
use App\Promotion;

class TransactionController extends Controller
{
	protected $perPage = 10;
	private $responseData;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->responseData = new ResponseData();
		$this->email = new EmailController();
	}

	public function create(Request $request) {
		$transaction_data = $request->all();
		$validator = Validator::make($transaction_data, [
			'customer_id' => 'required',
			'total_tax' => 'required',
			'total_discount' => 'required',
			'total_amount' => 'required',
			'vouchers_redeemed' => 'required',
			'promotion_codes' => 'required',
			'product_id' => 'required',
			'qty' => 'required',
			'promotion_id' => 'required',
			'is_used' => 'required',
			'is_expired' => 'required',
			'amount' => 'required',
			'remain_count' => 'required',
			'number_of_redemption' => 'required',
			'remain_amount' => 'required',
			'purchase_amount' => 'required',
			'reward_available' => 'required',
		]);
		// Check validation failure
		if ($validator->fails()) {
			$errors = $validator->errors();
			return $this->responseData->error($errors);
		}

		if (!Auth::user()->store->sendEmail()) $this->email = null;

		$user = Auth::user();
		$transaction_data['user_id'] = $user->id;
		$store = $user->store;
		$store_id = $store->store_id;
		$transaction_data['store_id'] = $store_id;

		$customer_id		= $request->input("customer_id");
		$customer			= Customer::find($customer_id);
		$is_expired			= $request->input("is_expired");

		$promotion_id		= $request->input("promotion_id");

		$query_promotion = Promotion::query();
		$query_promotion->join('store', 'store.store_id', '=', 'promotion.store_id');
		$query_promotion->join('campaign', 'campaign.campaign_id', '=', 'promotion.campaign_id');
		$query_promotion->join('campaign_type', 'campaign_type.campaign_type_id', '=', 'campaign.campaign_type_id');
		$query_promotion->join('promotion_type', 'promotion_type.promotion_type_id', '=', 'promotion.promotion_type_id');
		$query_promotion->select('promotion.*','campaign_type.name as campaign_type_name', 'promotion_type.name as promotion_type_name', 'promotion_type.reward_product_id as reward_product_id');
		$query_promotion->where('store.store_id', $store_id);
		$query_promotion->where('promotion.promotion_id', $promotion_id);
		$promotion = $query_promotion->first();
		$campaign_type_name = $promotion->campaign_type_name;
		$reward_trigger_count = $promotion->reward_trigger_count;

		$remain_count			= $request->input("remain_count");
		$vouchers_redeemed		= $request->input("vouchers_redeemed");
		$is_used				= $request->input("is_used");

		$number_of_redemption	= $request->input("number_of_redemption");
		$remain_amount			= $request->input("remain_amount");
		$purchase_amount		= $request->input("purchase_amount");
		$reward_available		= $request->input("total_reward_available") - $request->input("reward_available");
		$left_amount			= 0;

		$product_id = $request->input("product_id");

		$product = Product::find($product_id);
		$product_type = $product->product_type;

		$reward_product = Product::find($promotion->reward_product_id);

		if (($campaign_type_name == 'Percentage Discount' || $campaign_type_name == 'Dollar Discount') && $product_type = 'dining') {

			$transaction_data['vouchers_redeemed'] = 0;
			$this->addTransaction($transaction_data);

			if ($this->email) $this->email->sendMailDiningClaimed($customer->email) ;

			return $this->responseData->success(array());
		}

		if ($is_used && $vouchers_redeemed && !$is_expired) {
			$this->redeemPromotionReward($customer_id, $promotion_id);

			// Get all transactions of current customer and product
			$transactions = Transaction::where('customer_id', '=', $customer_id)
			->where('product_id', '=', $product_id)
			->where('vouchers_redeemed', '=', 0)
			->get();

			foreach ($transactions as $transaction) {
				$transaction->vouchers_redeemed = 1;
				$transaction->save();
			}
			if ($this->email) $this->email->sendMailGolfRewardClaimed($customer->email);
			return $this->responseData->success(array());
		}

		if ($campaign_type_name == 'Buy X get Y Free' && $product_type == 'golf_game') {

			$transaction_data['amount'] = $transaction_data['qty'] * $product->price;
			$transaction_id = $this->addTransaction($transaction_data);

			if ($remain_count == 0) {
				$this->addPromotionReward($transaction_id, $customer_id, $promotion_id, 0);
			} else {

				if ($promotion_id == 1) {
					if ($this->email) $this->email->sendMailRewardIncrementSmall($customer->email, $remain_count);
				}

				if ($promotion_id == 2) {
					if ($this->email) $this->email->sendMailRewardIncrementLarge($customer->email, $remain_count);
				}
			}
			return $this->responseData->success(array());
		}

		if ($campaign_type_name == 'Buy X get Y Free' && $product_type == 'purchase') {

			if ($purchase_amount >= $remain_amount) {

				$left_amount = (int)(($purchase_amount - $remain_amount) % $reward_trigger_count);

				$transactions = Transaction::where('customer_id', '=', $customer_id)
				->where('product_id', '=', $product_id)
				->where('vouchers_redeemed', '=', 0)
				->where('promotion_codes', '=', $promotion->code)
				->get();

				foreach ($transactions as $transaction) {
					$transaction->vouchers_redeemed = 1;
					$transaction->save();
				}

				$transaction_data['vouchers_redeemed'] = 1;
				$transaction_data['amount'] = $purchase_amount - $left_amount;
				$transaction_id = $this->addTransaction($transaction_data);

				if ($left_amount) {

					$transaction_data['vouchers_redeemed'] = 0;
					$transaction_data['amount'] = $left_amount;
					$transaction_id = $this->addTransaction($transaction_data);
				}

				if ($reward_available) {
					for ($index = 1; $index <= $reward_available; $index++) {
						$this->addPromotionReward($transaction_id, $customer_id, $promotion_id, 0);
					}
				}
				$left_amount = $reward_trigger_count - $left_amount;

			} else {
				$left_amount = $remain_amount - $purchase_amount;
				if ($purchase_amount > 0) {
					$transaction_data['vouchers_redeemed'] = 0;
					$transaction_data['amount'] = $purchase_amount;
					$transaction_id = $this->addTransaction($transaction_data);
				}
			}

			if ($number_of_redemption) {
				for ($index = 1; $index <= $number_of_redemption; $index++) {
					$this->redeemPromotionReward($customer_id, $promotion_id);
				}
			}

			$left_reward = $request->input("total_reward_available") - $number_of_redemption;

			if ($purchase_amount < $remain_amount) {
				if ($request->input("total_reward_available")) {
					if ($number_of_redemption) {
						if ($this->email) $this->email->sendMailPurchaseGolfRewardClaimed($customer->email, $number_of_redemption, $left_reward, 0);
					} else {
						if ($this->email) $this->email->remindForReward($customer->email, $promotion->promotion_type_name, $left_reward, $reward_product->name, 0);
					}
				} else {
					if ($this->email) $this->email->sendMailSpendAnAdditionalForReward($customer->email, $left_amount);
				}
			}

			if ($purchase_amount == $remain_amount) {
				if ($number_of_redemption) {
					if ($this->email) $this->email->sendMailPurchaseGolfRewardClaimed($customer->email, $number_of_redemption, $left_reward, 0);
				} else {
					if ($this->email) $this->email->remindForReward($customer->email, $promotion->promotion_type_name, $left_reward, $reward_product->name, 0);
				}
			}

			if ($purchase_amount > $remain_amount) {
				if ($number_of_redemption) {
					if ($this->email) $this->email->sendMailPurchaseGolfRewardClaimed($customer->email, $number_of_redemption, $left_reward, 0);
				} else {
					if ($this->email) $this->email->remindForReward($customer->email, $promotion->promotion_type_name, $left_reward, $reward_product->name, 0);
				}
			}
			return $this->responseData->success(array());
		}

		return $this->responseData->success(array());
	}

	protected function addTransaction($data) {
		$transaction = new Transaction;
		$transaction->customer_id = $data['customer_id'];
		$transaction->store_id = $data['store_id'];
		$transaction->user_id = $data['user_id'];
		$transaction->total_discount = $data['total_discount'];
		$transaction->total_amount = $data['total_amount'];
		$transaction->vouchers_redeemed = $data['vouchers_redeemed'];
		$transaction->product_id = $data['product_id'];
		$transaction->promotion_codes = $data['promotion_codes'];
		$transaction->amount = $data['amount'];
		$transaction->qty = $data['qty'];

		$transaction->save();

		return $transaction->transaction_id;
	}

	protected function addPromotionReward($transaction_id, $customer_id, $promotion_id, $is_used) {
		$promotion_reward = new PromotionReward;
		$promotion_reward->transaction_id = $transaction_id;
		$promotion_reward->customer_id = $customer_id;
		$promotion_reward->promotion_id = $promotion_id;
		$promotion_reward->is_used = $is_used;
		$promotion_reward->is_expired = 0;
		$promotion_reward->save();
	}

	protected function redeemPromotionReward($customer_id, $promotion_id) {
		$promotion_reward = PromotionReward::where('customer_id', '=', $customer_id)
			->where('promotion_id', '=', $promotion_id)
			->where('is_used', '=', 0)
			->where('is_expired', '=', 0)
			->orderBy('created_at', 'ASC')
			->first();
		$promotion_reward->is_used = 1;
		$promotion_reward->save();
	}

	public function index(Request $request) {
		$user = Auth::user();
		$store = $user->store;

		$query = Transaction::query();
		$query->join('customer', 'customer.customer_id', '=', 'transaction.customer_id');
		$query->join('store', 'store.store_id', '=', 'transaction.store_id');
		$query->join('product', 'product.product_id', '=', 'transaction.product_id');
		$query->select('transaction.*',DB::raw('concat(customer.firstname," ",customer.lastname) as customer_name'), 'product.name as product_name', 'product.price as product_price');
		$query->where('store.store_id', $store->store_id);
		$transactions = $query->get();

		$data = array();
		foreach ($transactions as $transaction) {
			$customer = $transaction->customer;
			$store = $transaction->store;
			$user = $transaction->user;
			$row = array(
				"transaction_id" => $transaction->transaction_id,
				"date" => $transaction->created_at->format('d-m-Y H:i:s'),
				"vouchers_redeemed" => $transaction->vouchers_redeemed,
				"promotion_codes" => $transaction->promotion_codes,
				"customer" => array(
					"customer_id" => $customer ? $customer->customer_id : "",
					"customer_name" => $transaction ? $transaction->customer_name : "",
					"email" => $customer ? $customer->email : "",
					"telephone" => $customer ? $customer->telephone : ""
				),
				"store" => array(
					"store_id" => $store->store_id,
					"name" => $store->name,
					"email" =>$store->email
				),
				"total_tax" => $transaction->total_tax,
				"total_discount" => $transaction->total_discount,
				"total_amount" => $transaction->total_amount,
				"amount" => $transaction->amount,
				"qty" => $transaction->qty,
				"product_name" => $transaction->product_name,
				"product_price" => $transaction->product_price,

			);
			$data[] = $row;
		}
		$transactions = collect($data);
		return $this->responseData->success($transactions);
	}

}
