<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redireccionar después del login según el rol del usuario
     */
    protected function authenticated(Request $request, $user)
    {
        // Verificar el rol y redirigir (usando nombres exactos de la BD)
        // Administrador: acepta AMBOS roles (Administrador y admin)
        if ($user->tieneRol('Administrador') || $user->tieneRol('admin')) {
            return redirect()->route('admin.dashboard')
                ->with('mensaje', '¡Bienvenido Administrador!')
                ->with('icono', 'success');
        }

        if ($user->tieneRol('docente')) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', '¡Bienvenido Docente!')
                ->with('icono', 'success');
        }

        if ($user->tieneRol('tutor')) {
            return redirect()->route('tutor.dashboard')
                ->with('mensaje', '¡Bienvenido Tutor!')
                ->with('icono', 'success');
        }

        if ($user->tieneRol('estudiante')) {
            return redirect()->route('estudiante.dashboard')
                ->with('mensaje', '¡Bienvenido Estudiante!')
                ->with('icono', 'success');
        }

        // Si no tiene rol específico, redirigir a /home
        // El fallback de /home se encargará de manejar el caso sin rol
        return redirect()->route('home');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
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