<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\UpdateOrder;
use App\Jobs\CompleteFailedPayment;
use App\Jobs\CloneShops;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->job(new CompleteFailedPayment)->everySixHours();
        $schedule->job(new UpdateOrder)->everyMinute();
        // $schedule->job(new CloneShops)->everyThirtySeconds();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}