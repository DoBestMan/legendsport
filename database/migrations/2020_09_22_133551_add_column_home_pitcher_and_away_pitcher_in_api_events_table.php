<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHomePitcherAndAwayPitcherInApiEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_events', function (Blueprint $table) {
            $table->string('pitcher_home')->nullable();
            $table->string('pitcher_away')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_events', function (Blueprint $table) {
            $table->dropColumn(["pitcher_home", "pitcher_away"]);
        });
    }
}
