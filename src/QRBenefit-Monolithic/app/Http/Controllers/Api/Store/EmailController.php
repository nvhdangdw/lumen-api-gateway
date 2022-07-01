<?php

namespace App\Http\Controllers\Api\Store;
use App\Mail\Store\DiningClaimed;
use App\Mail\Store\GolfRewardClaimed;
use App\Mail\Store\RewardIncrementSmall;
use App\Mail\Store\RewardIncrementLarge;
use App\Mail\Store\RemindForReward;
use App\Mail\Store\PurchaseGolfRewardClaimed;
use App\Mail\Store\SpendAnAdditionalForReward;
use App\Http\Controllers\Controller;
use App\Http\ResponseData;
use Mail;

class EmailController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->responseData = new ResponseData();
	}

	public function sendMailDiningClaimed($email) {
		Mail::send(new DiningClaimed($email));

		return;
	}

	public function sendMailGolfRewardClaimed($email) {
		Mail::send(new GolfRewardClaimed($email));

		return;
	}

	public function sendMailRewardIncrementLarge($email, $remaining_amount) {
		Mail::send(new RewardIncrementLarge($email, $remaining_amount));

		return;
	}

	public function sendMailRewardIncrementSmall($email, $remaining_amount) {
		Mail::send(new RewardIncrementSmall($email, $remaining_amount));

		return;
	}

	public function remindForReward($email, $promotion_type_name, $available_rewards, $reward_product_name, $left_amount) {
		Mail::send(new RemindForReward($email, $promotion_type_name, $available_rewards, $reward_product_name, $left_amount));

		return;
	}

	public function sendMailPurchaseGolfRewardClaimed($email, $number_of_redemption, $left_reward, $left_amount) {
		Mail::send(new PurchaseGolfRewardClaimed($email, $number_of_redemption, $left_reward, $left_amount));

		return;
	}

	public function sendMailSpendAnAdditionalForReward($email, $remaining_amount) {
		Mail::send(new SpendAnAdditionalForReward($email, $remaining_amount));

		return;
	}

}