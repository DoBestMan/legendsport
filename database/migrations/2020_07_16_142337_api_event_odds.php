<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiEventOdds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE TABLE api_event_odds (
                id INT auto_increment NOT NULL,
                api_event_id BIGINT UNSIGNED DEFAULT NULL,
                bet_type VARCHAR(255) NOT NULL,
                odds INT NOT NULL,
                handicap VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:DecimalObject)\',
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                INDEX IDX_1D23E52AA2CC2204 (api_event_id),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
        ');

        DB::statement('ALTER TABLE api_event_odds ADD CONSTRAINT FK_1D23E52AA2CC2204 FOREIGN KEY (api_event_id) REFERENCES api_events (id)');
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
