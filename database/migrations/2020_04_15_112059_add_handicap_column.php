<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHandicapColumn extends Migration
{
    public function up()
    {
        Schema::table("tournament_bet_events", function (Blueprint $table) {
            $table->decimal("handicap")->nullable();
        });
    }

    public function down()
    {
        Schema::table("tournament_bet_events", function (Blueprint $table) {
            $table->dropColumn("handicap");
        });
    }
}
