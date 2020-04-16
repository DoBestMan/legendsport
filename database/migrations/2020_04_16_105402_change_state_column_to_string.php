<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeStateColumnToString extends Migration
{
    public function up()
    {
        Schema::table("tournaments", function (Blueprint $table) {
            $table->string("state")->change();
        });

        DB::table("tournaments")
            ->where("state", "Complete")
            ->update(["state" => "Completed"]);
    }

    public function down()
    {
        Schema::table("tournaments", function (Blueprint $table) {
            $table
                ->enum("state", [
                    "Announced",
                    "Registering",
                    "Late registering",
                    "Running",
                    "Complete",
                    "Cancel",
                ])
                ->change();
        });
    }
}
