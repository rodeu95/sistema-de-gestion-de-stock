<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Administrador']);
        $cajero = Role::create(['name' => 'Cajero']);
        $user = Role::create(['name' => 'User']);
        
        $admin->syncPermissions(Permission::all());
        $cajero->syncPermissions([]);
        $user->syncPermissions([]);
    }
}
