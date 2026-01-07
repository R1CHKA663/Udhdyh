<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('system')->nullable();
            $table->double('sum')->default(0);
            $table->string('system_number')->nullable();
            $table->double('fee_sum')->default(0);
            $table->string('ip')->default();
            $table->string('videocard')->default();
            $table->integer('status')->defalut(0);
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
        Schema::dropIfExists('withdraws');
    }
}
