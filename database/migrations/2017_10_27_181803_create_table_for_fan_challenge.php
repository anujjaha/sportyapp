<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableForFanChallenge extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_fan_challenge', function (Blueprint $table) 
        {
            $table->increments('id')->unsigned();
            $table->integer('user_id');
            $table->integer('game_id')->nullable();
            $table->integer('home_team_id')->nullable();;
            $table->integer('away_team_id')->nullable();;
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
