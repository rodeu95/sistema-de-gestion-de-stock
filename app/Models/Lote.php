<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';
    
    protected $fillable = [
        'numero_lote',
        'fecha_vencimiento',

    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'numero_lote', 'numero_lote');
    }

}
