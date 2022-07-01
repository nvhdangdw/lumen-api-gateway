<?php

use Illuminate\Database\Seeder;

class DiscountTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$limit = $this->command->ask('Please enter number the limit for discount !!');
		if (is_numeric($limit)){
			factory(App\Discount::class, intval( $limit ))->create();
		}
	}
}
