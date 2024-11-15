<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User([
            'usuario' => 'admin',
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678')
        ]);
        $admin->save();
        $admin->assignRole('Administrador');
        $permissions = Permission::all();
        foreach($permissions as $permission){
            $admin->givePermissionTo($permission);
        };

        Auth::login($admin);

        $cajero = new User([
            'usuario' => 'cajero',
            'name' => 'cajero',
            'email' => 'cajero@cajero.com',
            'password' => Hash::make('12345678')
        ]);
        $cajero->save();
        $cajero->assignRole('Cajero');
        $cajero->givePermissionTo('aplicar-descuento', 'registrar-ingreso', 'ver-productos');

        Auth::login($cajero);

        
    }
}
