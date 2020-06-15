<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TournamentStatusChangeDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("tournaments", function (Blueprint $table) {
            $table->timestamp("registration_deadline")->nullable();
            $table->timestamp("late_registration_deadline")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("tournaments", function (Blueprint $table) {
            $table->dropColumn(["registration_deadline", "late_registration_deadline"]);
        });
    }
}
