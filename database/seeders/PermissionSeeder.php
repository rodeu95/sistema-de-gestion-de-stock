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
        Permission::create(['name' => 'ver-lotes', 'description' =>  'Puede ver los lotes']);
        Permission::create(['name' => 'ver-usuarios', 'description' => 'Puede ver la lista de usuarios']);
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
        Permission::create(['name' => 'ver-proveedores', 'description' => 'Puede ver proveedores']);
        Permission::create(['name' => 'agregar-proveedor', 'description' => 'Puede agregar proveedor']);
        Permission::create(['name' => 'editar-proveedor', 'description' => 'Puede editar proveedor']);
        Permission::create(['name' => 'deshabilitar-proveedor', 'description' => 'Puede deshabilitar proveedor']);
        Permission::create(['name' => 'habilitar-proveedor', 'description' => 'Puede habilitar proveedor']);
    }
}
