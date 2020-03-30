<?php

use App\Models\TournamentEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTournamentEventsTable extends Migration
{
    public function up()
    {
        Schema::create(TournamentEvent::table(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('api_event_id');
            $table->timestamps();

            $table
                ->foreign('tournament_id')
                ->references('id')
                ->on('tournaments');
            $table
                ->foreign('api_event_id')
                ->references('id')
                ->on('api_events');
        });
    }

    public function down()
    {
        Schema::dropIfExists(TournamentEvent::table());
    }
}
