<?php

use Faker\Generator as Faker;
use App\Store;
use App\User;
use App\Customer;

$factory->define(App\Order::class, function (Faker $faker) {
    $customers = Customer::get();
    $customer_ids = array();
    foreach ($customers as $customer) {
        $customer_ids[] = $customer->customer_id;
    }
    $store = Store::first();
    $user = User::where("store_id",$store->store_id)->where('group_user_id', '!=' , 1)->first();
    return [
        "customer_id" => $customer_ids[rand(0,count($customer_ids) - 1)],
        "user_id" => $user->id,
        "store_id" => $store->store_id,
        "total_tax" => 0,
        "total_discount" => 0,
        "total_amount" => rand(10,20),
        "vouchers_redemned" => 0,
        "promotion_codes" => ""
    ];
});
