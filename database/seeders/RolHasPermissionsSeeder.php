<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolHasPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = DB::table('permissions')->pluck('id'); // Asumiendo que la tabla de permisos se llama 'permissions'

        $roles = [
            1 => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19],
            2 => [1,7],
            3 => []
        ];

        // Iterar sobre los roles y asignarles todos los permisos
        foreach ($roles as $roleId => $permissionIds) {
            $role = Role::where('id', $roleId)->first();
            if ($role) {
                foreach ($permissionIds as $permissionId) {
                    DB::table('role_has_permissions')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }
    }
    
}
