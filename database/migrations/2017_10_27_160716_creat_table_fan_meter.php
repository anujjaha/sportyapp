<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTableFanMeter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_fan_meter', function (Blueprint $table) 
        {
            $table->increments('id')->unsigned();
            $table->integer('user_id');
            $table->integer('game_id');
            $table->integer('home_team_id');
            $table->integer('away_team_id');
            $table->integer('follow_team');
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
        //
    }
}
