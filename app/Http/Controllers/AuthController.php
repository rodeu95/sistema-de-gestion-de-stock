<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm(){
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function store(Request $request)
    {
    // Validación del formulario
        $request->validate([
            'usuario' => 'required|string|max:100',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el usuario
        $user = User::create([
            'usuario' => $request->usuario,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('User');
        Auth::login($user);
        return redirect()->route('users.index')->with('success', 'Usuario registrado exitosamente.');
    }

    public function login(Request $request)
    {
        // Validar los campos del formulario de inicio de sesión
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        // Obtener las credenciales ingresadas por el usuario
        $credentials = [
            'usuario' => $request->usuario,
            'password' => $request->password, // Laravel requiere que la clave se llame 'password'
        ];

        // Intentar autenticar al usuario con las credenciales ingresadas
        if (Auth::attempt($credentials)) {
            return redirect()->route('inicio')->with('success', 'Inicio de sesión exitoso');
        }

        // Si las credenciales no son válidas, redirigir de vuelta con un error
        return redirect()->back()->withErrors(['login_error' => 'Las credenciales no son correctas. Por favor, intente nuevamente.']);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión del usuario

        $request->session()->invalidate(); // Invalida la sesión actual
        $request->session()->regenerateToken(); // Regenera el token CSRF

        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }

}