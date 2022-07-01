<?php

namespace App\Mail\Store;

use App\Customer;
use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RemindForReward extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $email array|string
     * @param $customerName string
     *
     * @return void
     */
    public function __construct($email, $promotion_type_name, $available_rewards, $reward_product_name, $left_amount)
    {
        $customer = Customer::where('email', $email)->first();
        $store_id = Setting::where('key', '=', 'CUSTOMER_GROUP_ID')->where('value', '=', $customer->customer_group_id)->value('store_id') ?? 0;
        $oc_store_url = Setting::where('store_id', '=', $store_id)->where('key', '=', 'OC_STORE_URL')->value('value') ?? 'oc_store_url';
        $customerName = $customer->firstname . ' ' . $customer->lastname;
        $this->to($email, $customerName);
        $subject = "Don't forget your rewards";
        $this->subject($subject);
        // Append data to view
        $this->with([
            'firstname' => $customer->firstname,
            'oc_store_url' => $oc_store_url,
            'username' => $email,
            'promotion_type_name' => $promotion_type_name,
            'available_rewards' => $available_rewards,
            'reward_product_name' => $reward_product_name,
            'left_amount' => $left_amount
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (file_exists( resource_path() . '/views/email/store/'. Auth::user()->store->code()) && !empty(Auth::user()->store->code())) {
			return $this->view('email.store.'. Auth::user()->store->code() .'.transaction.remind_for_reward');
        } else {
            return $this->view('email.store.common.transaction.remind_for_reward');
        }
    }
}