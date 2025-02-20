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
        Permission::create(['name' => 'abrir-caja', 'description' =>  'Puede abrir la caja']);
        Permission::create(['name' => 'cerrar-caja', 'description' =>  'Puede cerrar la caja']);
        Permission::create(['name' => 'ver-total-caja', 'description' =>  'Puede ver el total en la caja']);
        Permission::create(['name' => 'registrar-venta', 'description' =>  'Puede registrar ventas']);
        Permission::create(['name' => 'anular-venta', 'description' =>  'Puede anular ventas']);
        Permission::create(['name' => 'ver-ventas', 'description' =>  'Puede ver las ventas']);
        Permission::create(['name' => 'editar-venta', 'description' =>  'Puede editar ventas']);
        Permission::create(['name' => 'eliminar-venta', 'description' =>  'Puede eliminar ventas']);
        Permission::create(['name' => 'registrar-compra', 'description' =>  'Puede registrar compras']);
        Permission::create(['name' => 'modificar-precio', 'description' =>  'Puede modificar precios']);
        Permission::create(['name' => 'eliminar-usuario', 'description' => 'Puede eliminar un usuario']);
        Permission::create(['name' => 'editar-usuario', 'description' => 'Puede editar un usuario']);
        Permission::create(['name' => 'agregar-usuario', 'description' => 'Puede agregar un usuario']);
        Permission::create(['name' => 'ver-productos', 'description' => 'Puede ver la lista de productos']);
        Permission::create(['name' => 'editar-producto', 'description' => 'Puede editar productos']);
        Permission::create(['name' => 'deshabilitar-producto', 'description' => 'Puede deshabilitar productos']);
        Permission::create(['name' => 'habilitar-producto', 'description' => 'Puede habilitar productos']);
        Permission::create(['name' => 'agregar-producto', 'description' => 'Puede agregar productos']);
        Permission::create(['name' => 'gestionar-inventario', 'description' => 'Puede gestionar el inventario']);
        Permission::create(['name' => 'exportar-archivos', 'description' => 'Puede exportar archivos']);
        Permission::create(['name' => 'ver-productos-vencidos', 'description' => 'Puede ver productos vencidos']);
        Permission::create(['name' => 'ver-productos-a-vencer', 'description' => 'Puede ver productos a vencer']);

    }
}
