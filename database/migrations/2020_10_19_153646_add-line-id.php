<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLineId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE api_event_odds ADD external_id VARCHAR(255) DEFAULT NULL");
        DB::statement("UPDATE api_event_odds set external_id = ''");
        DB::statement('ALTER TABLE api_event_odds CHANGE external_id external_id VARCHAR(255) NOT NULL');
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
