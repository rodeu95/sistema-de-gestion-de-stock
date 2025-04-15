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
use Illuminate\Support\Facades\Password;



class AuthController extends Controller
{

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Enviar el enlace para restablecer la contraseña
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Intentar restablecer la contraseña
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }
    public function showLoginForm(){
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function store(Request $request)
    {

        $request->validate([
            'usuario' => 'required|string|max:100|unique:users,usuario',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
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
        Auth::login($user);
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
        $tokens = $request->user()->tokens;
        foreach($tokens as $token){
            $token->delete();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ], 200);

    }

}