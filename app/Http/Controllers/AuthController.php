<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Log;


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
        // dd($request);
        // Crear el usuario
        $user = new User([
            'usuario' => $request->usuario,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->save();
        $user->assignRole('User');
        // Auth::login($user);
        $token = $user->createToken('AuthToken')->plainTextToken;
        
        Mail::to($user->email)->send(new WelcomeMail($user));
        
        // return redirect()->route('users.index')->with('success', 'Usuario registrado exitosamente.');
        return response()->json([
            'message' => 'Usuario registrado exitosamente.',
            'user' => $user,
            'token' => $token,
        ], 201);
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
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json([
                'message' => 'Inicio de sesión exitoso.',
                'user' => $user,
                'token' => $token,
            ], 200);
            // return redirect()->route('inicio')->with('success', 'Inicio de sesión exitoso');
        }
        
        // Si las credenciales no son válidas, redirigir de vuelta con un error
        // return redirect()->back()->withErrors(['login_error' => 'Las credenciales no son correctas. Por favor, intente nuevamente.']);

        return response()->json(['message' => 'Las credenciales no son correctas.'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente.'], 200);


        // Auth::logout(); // Cierra la sesión del usuario

        // $request->session()->invalidate();
        // $request->session()->regenerateToken(); 

        // return redirect('/')->with('success', 'Sesión cerrada correctamente.');
    }

}