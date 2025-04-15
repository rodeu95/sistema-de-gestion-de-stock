<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos de Artisan.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define la programación de tareas.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('productos:notificar-vencimiento')->dailyAt('08:00');
    }

}
