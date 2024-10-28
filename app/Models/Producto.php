<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'total_vendido'
    ];

    public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'venta_producto', 'producto_id', 'venta_id')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
