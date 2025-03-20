<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable=[
        'nombre',
        'contacto',
        'telefono',
        'email',
        'direccion',
        'cuit',
    ];
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_proveedor')
                    ->withPivot('precio')
                    ->withTimestamps();
    }

    public function categorias(){
        return $this->belongsToMany(Categoria::class, 'proveedor_categoria');
    }
}
