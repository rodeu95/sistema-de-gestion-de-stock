<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';
    protected $primaryKey = 'numero_lote';
    protected $casts = [
        'numero_lote' => 'string' // Asegura que no se interprete como número
    ];
    
    protected $fillable = [
        'numero_lote',
        'producto_cod',
        'cantidad',
        'fecha_ingreso',
        'fecha_vencimiento',

    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_cod', 'codigo');
    }

}
