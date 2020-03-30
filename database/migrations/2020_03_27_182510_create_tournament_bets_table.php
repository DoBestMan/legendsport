<?php

use App\Models\TournamentBet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTournamentBetsTable extends Migration
{
    public function up()
    {
        Schema::create(TournamentBet::table(), function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('tournament_player_id');
            $table->mediumInteger('chips_wager');
            $table->timestamps();

            $table
                ->foreign('tournament_id')
                ->references('id')
                ->on('tournaments');
            $table
                ->foreign('tournament_player_id')
                ->references('id')
                ->on('tournament_players');
        });
    }

    public function down()
    {
        Schema::dropIfExists(TournamentBet::table());
    }
}
