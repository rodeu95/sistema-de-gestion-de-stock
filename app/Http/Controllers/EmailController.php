<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProductosPorVencer;
use Carbon\Carbon;
use App\Models\User;

class EmailController extends Controller
{
    public function enviarAvisoProductosPorVencer()
    {
        // Buscar productos que vencerán en 15 días
        $fechaLimite = Carbon::now()->addDays(15);
        $productosPorVencer = Producto::with(['lotes' => function ($query) use ($fechaLimite) {
            $query->where('fecha_vencimiento', '<=', $fechaLimite)
                  ->where('fecha_vencimiento', '>=', Carbon::now())
                  ->where('cantidad', '>', 0);
        }])->get();

        // Verificar si hay productos que notificar
        if ($productosPorVencer->isEmpty()) {
            return 'No hay productos próximos a vencer.';
        }

        // Obtener todos los usuarios con rol de 'administrador'
        $administradores = User::role('Administrador')->get();

        // Enviar correos a los administradores
        foreach ($administradores as $admin) {
            Mail::to($admin->email)->send(new ProductosPorVencer($productosPorVencer));
        }

        return 'Correos enviados a los administradores.';
    }
}
