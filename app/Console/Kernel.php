<?php

namespace App\Console;

use App\Jobs\AddBotsToTournaments;
use App\Jobs\SyncMatchesResults;
use App\Jobs\UpdateOdds;
use App\Jobs\UpdateTournamentStates;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(SyncMatchesResults::class)->everyMinute();
        $schedule->job(UpdateOdds::class)->everyMinute();
        $schedule->job(UpdateTournamentStates::class)->everyMinute();
        $schedule->job(AddBotsToTournaments::class)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
