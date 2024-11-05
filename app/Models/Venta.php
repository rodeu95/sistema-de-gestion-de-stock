<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Venta extends Model
{
    use HasFactory;
    protected $table = 'ventas';

    protected $fillable = [
        'monto_total',
        'metodo_pago_id',
        'fecha_venta',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'venta_producto', 'venta_id', 'producto_id')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoDePago::class, 'metodo_pago_id');
    }
}
