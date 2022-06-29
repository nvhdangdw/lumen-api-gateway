<?php

use Illuminate\Database\Seeder;
use App\Store;
use App\Customer;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $store = Store::first();
        $customer = Customer::first();
        if (!$store || !$customer) {
            echo "please insert a new store and customer";
        } else {
            $this->call(DiscountTableSeeder::class);
            $user = User::where("store_id",$store->store_id)->where('group_user_id', '!=' , 1)->first();
            if (!$user) {
                echo "please insert a new user\n";
            } else {
                $this->call(OrderTableSeeder::class);
            }
        }
    }
}
