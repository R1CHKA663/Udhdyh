<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->double('dice')->default(0);
            $table->double('bubbles')->default(0);
            $table->double('mines')->default(0);
            $table->double('normal_dice')->default(0);
            $table->double('normal_bubbles')->default(0);
            $table->double('normal_mines')->default(0);
            $table->double('income_dice')->default(0);
            $table->double('income_bubbles')->default(0);
            $table->double('income_mines')->default(0);
            $table->double('fee_dice')->default(0);
            $table->double('fee_bubbles')->default(0);
            $table->double('fee_mines')->default(0);
            $table->timestamps();
        });
        \DB::table('banks')->insert([
            'dice' => 550,
            'bubbles' => 550,
            'mines' => 550,
            'normal_dice' => 550,
            'normal_bubbles' => 550,
            'normal_mines' => 550,
            'income_dice' => 0,
            'income_bubbles' => 0,
            'income_mines' => 0,
            'fee_dice' => 10,
            'fee_mines' => 10,
            'fee_bubbles' => 10
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
