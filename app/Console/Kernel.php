<?php

namespace App\Console;

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
        'App\Console\Commands\scrapeZing',
        'App\Console\Commands\scrape24h',
        'App\Console\Commands\scrapeKenh14',
        'App\Console\Commands\scrapeVnx',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('scrape:zing')
        //     ->everyMinute();
        // $schedule->command('scrape:24h')
        //     ->everyMinute();
        // $schedule->command('scrape:kenh14')
        //     ->everyMinute();
        $schedule->command('scrape:vnx')
            ->everyMinute();
        // $schedule->command('inspire')
        //          ->hourly();
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
