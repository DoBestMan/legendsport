<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentBetEventsTable extends Migration
{
    public function up()
    {
        Schema::create("tournament_bet_events", function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->charset = "utf8";
            $table->collation = "utf8_unicode_ci";

            $table->bigIncrements("id");
            $table->unsignedBigInteger("tournament_bet_id");
            $table->unsignedBigInteger("tournament_event_id");
            $table->smallInteger("odd");
            $table->enum("type", [
                "money_line_home",
                "money_line_away",
                "spread_home",
                "spread_away",
                "total_under",
                "total_over",
            ]);
            $table->enum("status", ["win", "loss", "pending"]);
            $table->timestamps();

            $table
                ->foreign("tournament_bet_id")
                ->references("id")
                ->on("tournament_bets");
            $table
                ->foreign("tournament_event_id")
                ->references("id")
                ->on("tournament_events");
        });
    }

    public function down()
    {
        Schema::dropIfExists("tournament_bet_events");
    }
}
