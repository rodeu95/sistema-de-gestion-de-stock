<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;


class RolePermissionController extends Controller
{

    public function getPermisosPorRol($id)
    {
        // Cargar el rol con los permisos
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }
        
        if($role->name === 'Cajero') {
            $permissions = Permission::all();
        }else{
            $permissions = $role->permissions;
        }

        // Retornar los permisos directamente
        return response()->json($permissions);
    }

}
