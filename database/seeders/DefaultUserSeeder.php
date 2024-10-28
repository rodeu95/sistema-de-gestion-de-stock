<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'usuario' => 'admin',
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678')
        ]);

        $admin->assignRole('Administrador');
        $admin->givePermissionTo(Permission::all());

        $cajero = User::create([
            'usuario' => 'cajero',
            'name' => 'cajero',
            'email' => 'cajero@cajero.com',
            'password' => Hash::make('12345678')
        ]);

        $cajero->assignRole('Cajero');
        $cajero->givePermissionTo('aplicar-descuento', 'registrar-ingreso', 'ver-productos');
    }
}
