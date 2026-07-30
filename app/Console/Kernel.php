<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('trip:close')->everyMinute();

        // Original live schedule:
        $schedule->command('trip:close-reminder')->hourly()->between('21:00', '23:59')->timezone('Asia/Kolkata');
        $schedule->command('party-visit:checkout-reminder')->hourly()->timezone('Asia/Kolkata');
        // $schedule->command('trip:close-reminder')->everyMinute()->timezone('Asia/Kolkata');
        // $schedule->command('party-visit:checkout-reminder')->everyMinute()->timezone('Asia/Kolkata');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
