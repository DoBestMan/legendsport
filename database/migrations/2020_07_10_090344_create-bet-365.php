<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBet365 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE TABLE bet365_sport (
                id VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                enabled TINYINT(1) NOT NULL,
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        DB::statement('
            CREATE TABLE bet365_league (
                id VARCHAR(255) NOT NULL,
                sport_id VARCHAR(255) DEFAULT NULL,
                name VARCHAR(255) NOT NULL,
                enabled TINYINT(1) NOT NULL,
                INDEX IDX_D6F83561AC78BCF8 (sport_id),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
        ');

        DB::statement('ALTER TABLE bet365_league ADD CONSTRAINT FK_D6F83561AC78BCF8 FOREIGN KEY (sport_id) REFERENCES bet365_sport (id)');

        DB::statement('
            CREATE TABLE bet365_team (
                id VARCHAR(255) NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
        ');

        DB::statement('
            CREATE TABLE bet365_event (
                id VARCHAR(255) NOT NULL,
                league_id VARCHAR(255) DEFAULT NULL,
                home_id VARCHAR(255) DEFAULT NULL,
                away_id VARCHAR(255) DEFAULT NULL,
                time INT NOT NULL,
                INDEX IDX_CF8C40E058AFC4DE (league_id),
                INDEX IDX_CF8C40E028CDC89C (home_id),
                INDEX IDX_CF8C40E08DEF089F (away_id),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
        ');

        DB::statement('ALTER TABLE bet365_event ADD CONSTRAINT FK_CF8C40E058AFC4DE FOREIGN KEY (league_id) REFERENCES bet365_league (id);');
        DB::statement('ALTER TABLE bet365_event ADD CONSTRAINT FK_CF8C40E028CDC89C FOREIGN KEY (home_id) REFERENCES bet365_team (id);');
        DB::statement('ALTER TABLE bet365_event ADD CONSTRAINT FK_CF8C40E08DEF089F FOREIGN KEY (away_id) REFERENCES bet365_team (id);');
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
