<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTournamentBetEventStatusToString extends Migration
{
    public function up()
    {
        Schema::table("tournament_bet_events", function (Blueprint $table) {
            $table->string("status")->change();
        });
    }

    public function down()
    {
        Schema::table("tournament_bet_events", function (Blueprint $table) {
            $table->enum("status", ["win", "loss", "pending"])->change();
        });
    }
}
