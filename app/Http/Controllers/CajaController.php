<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caja;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\User;


class CajaController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('permission:abrir-caja', ['only'=>'abrir']);
        $this->middleware('permission:cerrar-caja', ['only'=>'cerrar']);
    }
    

    public function abrir()
    {
  
        
        Caja::updateOrCreate(['id' => 1], ['estado' => true]);

        return redirect()->route('inicio')->with('info', 'Caja abierta')->with('user', Auth::user());

    }

    public function cerrar()
    {
    
    // Cambiar el estado de la caja
        Caja::updateOrCreate(['id' => 1], ['estado' => false]);

        return redirect()->route('inicio')->with('info', 'Caja cerrada. No puede registrar más ventas.')->with('user', Auth::user());

        }

}
