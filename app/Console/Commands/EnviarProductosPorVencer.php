<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EmailController;

class EnviarProductosPorVencer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enviar-productos-por-vencer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía un correo con los productos por vencer a los administradores.';
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emailController = new EmailController();
        $emailController->enviarAvisoProductosPorVencer();

        $this->info('Correos enviados a los administradores.');
    }
}
