<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BotBetCounters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            ALTER TABLE tournament_events
                ADD bot_bets_placed INT DEFAULT NULL,
                ADD bot_bets_graded INT DEFAULT NULL
        ');

        DB::statement('Update tournament_events set bot_bets_placed = 0, bot_bets_graded = 0');

        DB::statement('
            ALTER TABLE tournament_events
                CHANGE bot_bets_placed bot_bets_placed INT NOT NULL,
                CHANGE bot_bets_graded bot_bets_graded INT NOT NULL
        ');

        DB::statement('
            ALTER TABLE tournaments
                ADD bot_bets_placed INT DEFAULT NULL,
                ADD bot_bets_graded INT DEFAULT NULL
        ');

        DB::statement('Update tournaments set bot_bets_placed = 0, bot_bets_graded = 0');

        DB::statement('
            ALTER TABLE tournaments
                CHANGE bot_bets_placed bot_bets_placed INT NOT NULL,
                CHANGE bot_bets_graded bot_bets_graded INT NOT NULL
        ');
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
