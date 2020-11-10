<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BetTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE tournament_bet_events ADD type2 varchar(100) default null AFTER type');
        DB::statement('UPDATE tournament_bet_events Set type2 = type');
        DB::statement("UPDATE tournament_bet_events SET type2 = 'moneyline_away' WHERE type2 = 'money_line_away'");
        DB::statement("UPDATE tournament_bet_events SET type2 = 'moneyline_home' WHERE type2 = 'money_line_home'");
        DB::statement('ALTER TABLE tournament_bet_events DROP type');
        DB::statement('ALTER TABLE tournament_bet_events CHANGE type2 type varchar(100) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
