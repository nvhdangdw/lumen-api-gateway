<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->timestamp('created_at')->useCurrent();
            $table->string('route', 64);
            $table->string('method', 32);
            $table->text('payload')->nullable();
            $table->smallInteger('status_code')->nullable();
            $table->text('response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_activity');
    }
}
