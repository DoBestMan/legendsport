<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LineOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE api_events ADD offers JSON DEFAULT NULL COMMENT '(DC2Type:json_array)'");
        DB::statement("UPDATE api_events set offers = '{}'");
        DB::statement("ALTER TABLE api_events CHANGE offers offers JSON NOT NULL COMMENT '(DC2Type:json_array)'");
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
