<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BetExternalId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
        ALTER TABLE tournament_bet_events
        ADD external_id VARCHAR(255) DEFAULT NULL,
        CHANGE status status VARCHAR(255) NOT NULL COMMENT '(DC2Type:App\\\\Tournament\\\\Enums\\\\BetStatus)',
        CHANGE handicap handicap VARCHAR(255) DEFAULT NULL COMMENT '(DC2Type:DecimalObject)';
        ");

        DB::statement("UPDATE tournament_bet_events SET external_id = ''");
        DB::statement("ALTER TABLE tournament_bet_events CHANGE external_id external_id VARCHAR(255) DEFAULT NULL");
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
