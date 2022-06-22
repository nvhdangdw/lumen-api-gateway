<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    $quantity = $faker->numberBetween(1, 10);
    return [
        'quantity' => $quantity,
        'discount' => $faker->numberBetween(1, 30),
        'total_price' => ($quantity * $faker->numberBetween(1, 200)),
        'product_id' => $faker->numberBetween(1, 50),
    ];
});
