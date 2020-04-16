<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentEventsTable extends Migration
{
    public function up()
    {
        Schema::create("tournament_events", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedBigInteger("tournament_id");
            $table->unsignedBigInteger("api_event_id");
            $table->timestamps();

            $table
                ->foreign("tournament_id")
                ->references("id")
                ->on("tournaments");
            $table
                ->foreign("api_event_id")
                ->references("id")
                ->on("api_events");
        });
    }

    public function down()
    {
        Schema::dropIfExists("tournament_events");
    }
}
