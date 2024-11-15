<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\Caja;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:agregar-usuario', ['only' => ['create','store']]);
        $this->middleware('permission:eliminar-usuario', ['only' => ['destroy']]);
        // $this->middleware('permission:editar-usuario',['only'=>['edit', 'update']]);
    }
    
    public function index()
    {
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        try{
        $users = User::paginate(10);
        return view('users.index', compact('users', 'cajaAbierta'));
        }catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al cargar usuarios: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        return view('users.create', [
            'roles' => Role::all(),
            'permissions' => Permission::all(),
            'cajaAbierta' => $cajaAbierta
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        
        $input = $request->all();
        // dd($request->input('permissions'));
        $input['password'] = Hash::make($request->password);


        $user = new User($input);
        $user->save();

        if(Auth::check()){
            $roleIds = $request->validated(['roles']); // Obtén todos los IDs de los roles

            foreach ($roleIds as $roleId) {
                $role = Role::findOrFail($roleId); // Busca el rol por ID
                $user->assignRole($role->name); // Asigna el rol usando el nombre
            }
            // $user->assignRole($request->validated(['roles']));

            if ($user->hasRole('Cajero') && $request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
                
                if($permissions){
                    $user->syncPermissions($permissions);
                }
            }elseif ($user->hasRole('Administrador')) {
                $allPermissions = Permission::all();
                // dd($allPermissions);
                foreach ($allPermissions as $permission) {
                    $user->givePermissionTo($permission);
                }
            }

        
        }else if (!Auth::check()){
            Auth::login($user);
        }
        // dd($user);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => 'Nuevo usuario agregado'
        ]);

        return redirect()->route('users.index');
                // ->withSuccess('New user is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        return redirect()->route('users.index', compact('cajaAbierta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->id !== Auth::user()->id && !Auth::user()->hasRole('Administrador')) {
            abort(403, 'NO TIENES PERMISO PARA EDITAR ESTE USUARIO');
        }

        $roles = Role::all();
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;
        $permissions = Permission::all();

        if ($user->hasRole('Administrador')){
            if($user->id != Auth::user()->id){
                abort(403, 'NO TIENES PERMISO PARA EDITAR USUARIOS');
            }
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,  
            'userRoles' => $user->getRoleNames()->all(),
            'cajaAbierta' => $cajaAbierta,  
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {

        if ($user->id !== Auth::user()->id && !Auth::user()->hasRole('Administrador')) {
            abort(403, 'NO TIENES PERMISO PARA EDITAR ESTE USUARIO');
        }

        $attributesToUpdate = ['usuario', 'email'];

        foreach($attributesToUpdate as $attribute){
            if ($request->filled($attribute) && $request->input($attribute) !== $user->$attribute) {
                $user->$attribute = $request->input($attribute);
            }
        }
        // Si se proporciona una nueva contraseña, actualízala
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if (Auth::user()->hasRole('Administrador')) {

            $roleIds = $request->validated(['roles']); // Obtén todos los IDs de los roles

            foreach ($roleIds as $roleId) {
                $role = Role::findOrFail($roleId); // Busca el rol por ID
                $user->syncRoles($role->name);
                
                if ($role->name === 'Administrador') {
                    $permissions = Permission::all();
                    foreach($permissions as $permission){
                        $user->givePermissionTo($permission);
                    } 
                }
            }

            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
                
                if($permissions){
                    $user->syncPermissions($permissions);
                }
            }
        }

        $user->update($request->all());
   
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'Usuario actualizado correctamente'
        ]);
        // Redirecciona con un mensaje de éxito
        return redirect()->route('users.index');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->hasRole('Administrador') || $user->id == Auth::user()->id)
        {
            abort(403, 'NO TIENES PERMISO PARA ELIMINAR USUARIOS');
        }

        $user->syncRoles([]);
        $user->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'Usuario eliminado correctamente'
        ]);

        return redirect()->route('users.index');
                
    }
}
