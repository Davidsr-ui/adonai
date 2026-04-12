<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Lógica limpia de redirección post-login
     */
    protected function authenticated(Request $request, $user)
    {
        // 1. Validar si el usuario está activo (Opcional, pero recomendado)
        // if ($user->persona && $user->persona->estado === 'Inactivo') {
        //     Auth::logout();
        //     return redirect('/login')->withErrors(['email' => 'Tu cuenta está desactivada.']);
        // }

        // 2. Redirección por ROL (Usamos siempre minúsculas para evitar errores)
        if ($user->tieneRol('administrador') || $user->tieneRol('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->tieneRol('docente')) {
            return redirect()->route('docente.dashboard');
        }

        if ($user->tieneRol('tutor')) {
            return redirect()->route('tutor.dashboard');
        }

        // 🚨 NOTA: Eliminamos la verificación de 'estudiante' porque
        // definimos que ellos NO tienen acceso al sistema.

        // 3. Fallback: Si tiene usuario pero no rol (caso raro)
        return redirect()->route('home');
    }
    public function username()
    {
        return 'email';
    }

    /**
     * Mensajes de validación personalizados
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);
    }

    /**
     * Mensaje de error cuando las credenciales son incorrectas
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/login')
            ->with('mensaje', 'Sesión cerrada correctamente')
            ->with('icono', 'success');
    }
}