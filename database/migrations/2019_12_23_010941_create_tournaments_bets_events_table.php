<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsBetsEventsTable extends Migration
{
    public function up()
    {
        Schema::create('tournaments_bets_events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('tournament_bet_id');
            $table->unsignedBigInteger('tournament_event_id');
            $table->json('bet');
            $table->enum('status', ['Win', 'Lost']);
            $table->timestamps();
        });

        Schema::table('tournaments_bets_events', function (Blueprint $table) {
            $table
                ->foreign('tournament_bet_id')
                ->references('id')
                ->on('tournaments_bets');
            $table
                ->foreign('tournament_event_id')
                ->references('id')
                ->on('tournaments_events');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments_bets_events');
    }
}
