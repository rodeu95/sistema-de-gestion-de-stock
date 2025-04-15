<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;
use App\Mail\NotificacionVencimiento;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class NotificarProductosPorVencer extends Command
{
    protected $signature = 'productos:notificar-vencimiento';
    protected $description = 'Envía un email con los productos próximos a vencer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Definir cuántos días antes se debe notificar (ejemplo: 15 días)
        $diasAntes = 15;
        $fechaLimite = Carbon::now()->addDays($diasAntes);

        // Buscar solo productos que tienen al menos un lote próximo a vencer
        $productos = Producto::whereHas('lotes', function ($query) use ($fechaLimite) {
            $query->whereBetween('fecha_vencimiento', [Carbon::now(), $fechaLimite])
                ->where('cantidad', '>', 0);
        })->with(['lotes' => function ($query) use ($fechaLimite) {
            $query->whereBetween('fecha_vencimiento', [Carbon::now(), $fechaLimite])
                ->where('cantidad', '>', 0);
        }])->get();

        if ($productos->isEmpty()) {
            $this->info('No hay productos con lotes próximos a vencer.');
            return;
        }

        // Obtener correos de los administradores
        $destinatarios = User::role('Administrador')->pluck('email');

        if ($destinatarios->isEmpty()) {
            $this->info('No hay administradores para notificar.');
            return;
        }

        // Enviar email a los administradores
        Mail::to($destinatarios)->send(new NotificacionVencimiento($productos));

        $this->info('Notificación de productos próximos a vencer enviada.');
    }

}
