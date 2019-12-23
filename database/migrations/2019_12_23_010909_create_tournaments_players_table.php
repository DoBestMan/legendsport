<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsPlayersTable extends Migration
{
    public function up()
    {
        Schema::create('tournaments_players', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedSmallInteger('user_id');
            $table->unsignedMediumInteger('config_id');
            $table->timestamps();
        });

        Schema::table('tournaments_players', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('tournaments');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('config_id')->references('id')->on('config');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments_players');
    }
}
