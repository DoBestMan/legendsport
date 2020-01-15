<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsEventsTable extends Migration
{
    public function up()
    {
        Schema::create('tournaments_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tournament_id');
            $table->integer('api_event_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments_events');
    }
}
