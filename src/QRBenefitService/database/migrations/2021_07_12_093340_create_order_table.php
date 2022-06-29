<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order', function (Blueprint $table) {
			$table->increments('order_id');
			$table->integer('customer_id')->unsigned();
			$table->integer('store_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->decimal('total_tax', 10, 2)->nullable();
			$table->decimal('total_discount', 10, 2)->nullable();
			$table->decimal('total_amount', 10, 2)->nullable();
			$table->integer('discount_id')->unsigned();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('order');
	}
}
