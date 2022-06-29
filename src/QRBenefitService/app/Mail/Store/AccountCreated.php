<?php

namespace App\Mail\Store;

use App\Customer;
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
    public function __construct($email)
    {
        $customer = Customer::where('email', $email)->first();
        $storeName = Auth::user()->store->name;
        $storePhone = Auth::user()->store->phone_number;
        $subject = "{$storeName} account has been created";
        $customerName = $customer->firstname . ' ' . $customer->lastname;
        $this->to($email, $customerName);
        $this->subject($subject);
        // Append data to view
        $this->with([
            'storeName' => $storeName,
            'subject' => $subject,
            'customerName' => $customerName,
            'telephone' => $storePhone
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.store.account_created');
    }
}
