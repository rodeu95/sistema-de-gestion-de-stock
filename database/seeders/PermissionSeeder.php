<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'abrir-caja', 'description' =>  'Puede para abrir la caja']);
        Permission::create(['name' => 'cerrar-caja', 'description' =>  'Puede para cerrar la caja']);
        Permission::create(['name' => 'aplicar-recargo', 'description' =>  'Puede para aplicar recargos']);
        Permission::create(['name' => 'aplicar-descuento', 'description' => 'Puede para aplicar descuentos']);
        Permission::create(['name' => 'registrar-ingreso', 'description' =>  'Puede para registrar ingresos']);
        Permission::create(['name' => 'registrar-egreso', 'description' =>  'Puede para registrar egresos']);
        Permission::create(['name' => 'registrar-venta', 'description' =>  'Puede para registrar ventas']);
        Permission::create(['name' => 'registrar-compra', 'description' =>  'Puede para registrar compras']);
        Permission::create(['name' => 'modificar-precio', 'description' =>  'Puede para modificar precios']);
        Permission::create(['name' => 'eliminar-usuario', 'description' => 'Puede eliminar un usuario']);
        Permission::create(['name' => 'editar-usuario', 'description' => 'Puede editar un usuario']);
        Permission::create(['name' => 'agregar-usuario', 'description' => 'Puede agregar un usuario']);
        Permission::create(['name' => 'ver-productos', 'description' => 'Puede ver la lista de productos']);
        Permission::create(['name' => 'editar-producto', 'description' => 'Puede editar productos']);
        Permission::create(['name' => 'eliminar-producto', 'description' => 'Puede eliminar productos']);
        Permission::create(['name' => 'agregar-producto', 'description' => 'Puede agregar productos']);
    }
}
