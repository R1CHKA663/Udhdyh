<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vk_id')->default(1);
            $table->string('name')->default('Чувак');
            $table->string('ip')->default();
            $table->string('img')->nullable();
            $table->double('balance', 15, 2)->default(0);
            $table->double('raceback', 15, 2)->default(0);
            $table->double('deposit', 15, 2)->default(0);
            $table->double('income_repost', 15, 2)->default(0);
            $table->bigInteger('repost')->default(0);
            $table->string('videocard')->nullable();
            $table->integer('bonus_vk')->default(0);
            $table->integer('bonus_tg')->default(0);
            $table->string('day_bonus')->nullable();
            $table->string('hourly_bonus')->nullable();
            $table->bigInteger('tg_id')->nullable();
            $table->integer('clicked')->default(0);
            $table->double('income_all')->default(0);
            $table->integer('referalov')->default(0);
            $table->double('income')->default(0);
            $table->integer('invited')->nullable();
            $table->integer('is_ban')->default(0);
            $table->integer('is_admin')->default(0);
            $table->integer('is_moder')->default(0);
            $table->integer('is_youtuber')->default(0);
            $table->integer('is_promocoder')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
