<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BetCounters extends Migration
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
                ADD bets_placed INT DEFAULT NULL,
                ADD bets_graded INT DEFAULT NULL
        ');

        DB::statement('Update tournament_events set bets_placed = 0, bets_graded = 0');

        DB::statement('
            ALTER TABLE tournament_events
                CHANGE bets_placed bets_placed INT NOT NULL,
                CHANGE bets_graded bets_graded INT NOT NULL
        ');

        DB::statement('
            ALTER TABLE tournaments
                ADD bets_placed INT DEFAULT NULL,
                ADD bets_graded INT DEFAULT NULL
        ');

        DB::statement('Update tournaments set bets_placed = 0, bets_graded = 0');

        DB::statement('
            ALTER TABLE tournaments
                CHANGE bets_placed bets_placed INT NOT NULL,
                CHANGE bets_graded bets_graded INT NOT NULL
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
