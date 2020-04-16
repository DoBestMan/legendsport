<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentPlayersTable extends Migration
{
    public function up()
    {
        Schema::create("tournament_players", function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->charset = "utf8";
            $table->collation = "utf8_unicode_ci";

            $table->bigIncrements("id");
            $table->unsignedBigInteger("tournament_id");
            $table->unsignedSmallInteger("user_id");
            $table->unsignedInteger("chips");
            $table->timestamps();

            $table
                ->foreign("tournament_id")
                ->references("id")
                ->on("tournaments");
            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users");

            $table->unique(["tournament_id", "user_id"], "tournament_id_user_id");
        });
    }

    public function down()
    {
        Schema::dropIfExists("tournament_players");
    }
}
