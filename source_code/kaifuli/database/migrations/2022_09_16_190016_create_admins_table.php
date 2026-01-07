<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->double('raceback_procent', 15, 2)->default();
            $table->string('raceback_game')->default();
            $table->string('group_token')->default();
            $table->integer('group_id')->default();
            $table->timestamps();
        });
        \DB::table('admins')->insert([
            'raceback_procent' => 1.5,
            'raceback_game' => 'bubbles',
            'group_id' => 215570389,
            'group_token' => 'vk1.a.phm0ZJ7txgAZmUcV1O__gqRj3-sCwT5KIxFh8b4DX7QV94mT4WI26PoW6vwwCL11axmMiz7xezF4-e682YQRPsOV_Nwo0zHat3aCXlPR7IZ4xPbpqQjlkvveyrwwQVLmSC65OBtQStHMn0CXSc-0Gaq3oCdGPQEepC2gPsq9gAb_Zd8z6CXv7w51Rs3gfIwi'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
