<?php

namespace App\Mail\Store;

use App\Customer;
use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;

class SpendAnAdditionalForReward extends Mailable
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
    public function __construct($email, $remaining_amount)
    {
        $customer = Customer::where('email', $email)->first();
        $oc_store_url = Setting::where('store_id', Auth::user()->store->store_id)->where('key', Setting::OC_STORE_URL)->value('value');
        $customerName = $customer->firstname . ' ' . $customer->lastname;
        $this->to($email, $customerName);
        $subject = "You’re one step closer";
        $this->subject($subject);
        // Append data to view
        $this->with([
            'firstname' => $customer->firstname,
            'oc_store_url' => $oc_store_url,
            'username' => $email,
            'remaining_amount' => $remaining_amount
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
			return $this->view('email.store.'. Auth::user()->store->code() .'.transaction.spend_an_additional_for_reward');
        } else {
            return $this->view('email.store.common.transaction.spend_an_additional_for_reward');
        }
    }
}