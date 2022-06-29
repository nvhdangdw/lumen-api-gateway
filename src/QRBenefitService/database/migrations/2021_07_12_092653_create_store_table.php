<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('store', function (Blueprint $table) {
			$table->increments('store_id');
			$table->string('name',60)->nullable();
			$table->string('phone_number',12)->nullable();
			$table->string('email',50)->unique();
			$table->decimal('default_tax', 10, 2)->nullable();
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
		Schema::dropIfExists('supplier');
	}
}
