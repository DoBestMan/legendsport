<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LiveLineFlags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         */
        DB::statement("ALTER TABLE api_events ADD has_bettable_lines TINYINT(1) NOT NULL, DROP prefix, CHANGE time_status time_status VARCHAR(255) NOT NULL COMMENT '(DC2Type:App\\\\Betting\\\\TimeStatus)'");
        DB::statement("ALTER TABLE tournaments ADD live_lines TINYINT(1) NULL, CHANGE state state VARCHAR(255) NOT NULL COMMENT '(DC2Type:App\\\\Tournament\\\\Enums\\\\TournamentState)'");
        DB::statement("Update tournaments set live_lines = 0");
        DB::statement("ALTER TABLE tournaments CHANGE live_lines live_lines TINYINT(1) NOT NULL");

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
