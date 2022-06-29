<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Store;

$factory->define(App\Discount::class, function (Faker $faker) {
	$type_ar = ["%","$"];
	$store = Store::first();
	$start_date = $faker->dateTimeBetween('this week', '+6 days');
    $end_date   = $faker->dateTimeBetween($start_date, strtotime('+6 days'));
	return [
		'code' => Str::random(8),
		'type' => $type_ar[rand(0,1)],
		'discount_amount' => rand(1,10),
		'start_date' => $start_date,
		'end_date' => $end_date,
		'store_id' => $store->store_id
	];
});
