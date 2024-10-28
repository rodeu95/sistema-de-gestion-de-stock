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
        $this->middleware('auth')->except(['create', 'store']);
        $this->middleware('permission:eliminar-usuario', ['only' => ['destroy']]);
        $this->middleware('permission:editar-usuario',['only'=>['edit']]);
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
        $input['password'] = Hash::make($request->password);


        $user = User::create($input);
        Auth::login($user);
        
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
        }
        // dd($user->getDirectPermissions());
        if ($user->hasRole('Administrador')) {
            $user->syncPermissions(Permission::all());
        }

        return redirect()->route('users.index')
                ->withSuccess('New user is added successfully.');
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
        $caja = Caja::find(1);
        $cajaAbierta = $caja ? $caja->estado:false;

        if ($user->hasRole('Administrador')){
            if($user->id != Auth::user()->id){
                abort(403, 'NO TIENES PERMISO PARA EDITAR USUARIOS');
            }
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::pluck('name')->all(),  
            'userRoles' => $user->getRoleNames()->all(),
            'cajaAbierta' => $cajaAbierta  
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $input = $request->all();
        $validatedData = $request->validated($input);
        if(!empty($request->password)){
            $input['password'] = Hash::make($request->password);
        }else{
            $input = $request->except('password');
        }
        
        $user->update($input);

        $user->syncRoles($request->role);

        if ($user->hasRole('Cajero') && isset($validated['permissions'])) {
            // Asignar los permisos seleccionados
            $user->syncPermissions($validatedData['permissions']);
        }

        // Si el usuario tiene el rol "Administrador", asignar todos los permisos
        if ($user->hasRole('Administrador')) {
            $user->syncPermissions(Permission::all());
        }

        return redirect()->back()
                ->withSuccess('User is updated successfully.');
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
        return redirect()->route('users.index')
                ->withSuccess('User is deleted successfully.');
    }
}
