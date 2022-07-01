<?php

use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $limit = $this->command->ask('Please enter number the limit for order !!');
		if (is_numeric($limit)){
			factory(App\Order::class, intval( $limit ))->create();
		}
    }
}
