<?php

namespace App\Mail\Store;

use App\Customer;
use App\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AccountCreated extends Mailable
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
    public function __construct($email, $password)
    {
        $customer = Customer::where('email', $email)->first();
        $storeName = Auth::user()->store->name;
        $storePhone = Auth::user()->store->phone_number;
        $ocStoreUrl = Setting::where('store_id', Auth::user()->store->store_id)->where('key', Setting::OC_STORE_URL)->value('value');
        $subject = "Welcome to the club";
        $customerName = $customer->firstname . ' ' . $customer->lastname;
        $this->to($email, $customerName);
        $this->subject($subject);
        // Append data to view
        $this->with([
            'storeName' => $storeName,
            'subject' => $subject,
            'customerName' => $customerName,
            'telephone' => $storePhone,
            'firstName' => $customer->firstname,
            'ocStoreUrl' => $ocStoreUrl,
            'email' => $email,
            'password' => $password
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
            return $this->view('email.store.'. Auth::user()->store->code() .'.account_created');
        } else {
            return $this->view('email.store.common.account_created');
        }
    }
}