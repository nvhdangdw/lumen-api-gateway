<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Op727UpdateFieldsOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('discount_id');
            $table->integer('vouchers_redemned')->unsigned();
            $table->string('promotion_codes',255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->integer('discount_id')->unsigned();
            $table->dropColumn('vouchers_redemned');
            $table->dropColumn('promotion_codes');
        });
    }
}
