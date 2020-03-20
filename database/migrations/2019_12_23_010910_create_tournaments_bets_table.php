<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsBetsTable extends Migration
{
    public function up()
    {
        Schema::create('tournaments_bets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('tournament_player_id');
            $table->mediumInteger('chips_wager');
            $table->mediumInteger('chips_win');
            $table->enum('status', ['Win', 'Lost']);
            $table->timestamps();

            $table
                ->foreign('tournament_player_id')
                ->references('id')
                ->on('tournaments_players');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments_bets');
    }
}
