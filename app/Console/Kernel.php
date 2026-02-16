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
        \App\Console\Commands\TransferResponses::class,
        \App\Console\Commands\ImportFormatos::class,
        \App\Console\Commands\ImportProvider::class,
        \App\Console\Commands\FetchProviderUsers::class,
        \App\Console\Commands\CheckProviderNetwork::class,
        // Agrega aquí otros comandos Artisan que hayas creado
        \App\Console\Commands\RouteListOverride::class,
  
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Define tu programación de tareas aquí
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
