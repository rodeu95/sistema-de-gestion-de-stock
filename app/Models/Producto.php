<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lote;


class Producto extends Model
{
    use HasFactory;
    
    protected $table = 'productos';
    protected $fillable=[
        'codigo',
        'nombre',
        'descripcion',
        'fchVto',
        'precio',
        'stock',
        'total_vendido',
        'unidad',
        'precio_costo',
        'precio_venta',
        'numero_lote',
        'iva',
        'utilidad'
    ];

    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'venta_producto', 'producto_id', 'venta_id')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    public function lotes(){
        return $this->hasMany(Lote::class, 'numero_lote', 'numero_lote');
    }
}
