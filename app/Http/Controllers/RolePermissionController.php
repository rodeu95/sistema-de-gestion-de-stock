<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;


class RolePermissionController extends Controller
{

    public function getPermisosPorRolEdit($id, $userId)
    {
        // Cargar el rol con los permisos
        $role = Role::with('permissions')->find($id);
        

        if (!$role) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }
        if ($userId) {
            // Obtener los permisos del usuario que se está editando
            $user = User::find($userId);
    
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
    
            $permisosUsuario = $user->permissions->pluck('id')->toArray();
        }

        if($role->name === 'Cajero') {
            $permissions = Permission::all();
            
        }else{
            $permisosUsuario = $role->permissions->pluck('id')->toArray();
        }

       // Retornar los permisos directamente
        return response()->json([
            'permissions' => $permissions,
            'permisosUsuario' => $permisosUsuario
        ]);
    }

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
