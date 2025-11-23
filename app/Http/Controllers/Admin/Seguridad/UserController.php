<?php

namespace App\Http\Controllers\Admin\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['persona', 'roles']);
        
        // Filtros
        if ($request->has('buscar') && $request->buscar) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%")
                  ->orWhereHas('persona', function($q2) use ($buscar) {
                      $q2->where('nombres', 'like', "%{$buscar}%")
                         ->orWhere('apellidos', 'like', "%{$buscar}%")
                         ->orWhere('dni', 'like', "%{$buscar}%");
                  });
            });
        }
        
        if ($request->has('rol') && $request->rol) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->rol);
            });
        }
        
        if ($request->has('estado') && $request->estado !== '') {
            if ($request->estado == 'activo') {
                $query->whereHas('persona', function($q) {
                    $q->where('estado', 'Activo');
                });
            } elseif ($request->estado == 'inactivo') {
                $query->whereHas('persona', function($q) {
                    $q->where('estado', 'Inactivo');
                });
            }
        }
        
        if ($request->has('verificado') && $request->verificado !== '') {
            if ($request->verificado == '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }
        
        if ($request->has('tiene_persona') && $request->tiene_persona !== '') {
            if ($request->tiene_persona == '1') {
                $query->has('persona');
            } else {
                $query->doesntHave('persona');
            }
        }
        
        $usuarios = $query->orderBy('created_at', 'desc')->get();
        
        // Obtener todos los roles
        $roles = Role::all();
        
        // Obtener personas sin usuario (SOLO docentes, tutores y administradores - SIN estudiantes)
        $personasSinUsuario = Persona::whereNull('user_id')
                                     ->where('estado', 'Activo')
                                     ->where(function($query) {
                                         $query->whereHas('docente')
                                               ->orWhereHas('tutor')
                                               ->orWhereHas('administrador');
                                     })
                                     ->orderBy('apellidos')
                                     ->get();
        
        // Estadísticas
        $estadisticas = User::obtenerEstadisticas();
        
        return view('admin.usuarios.index', compact(
            'usuarios',
            'roles',
            'personasSinUsuario',
            'estadisticas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.usuarios.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'persona_id' => 'nullable|exists:personas,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'Este email ya está registrado.',
            'email.email' => 'El email debe ser válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'persona_id.exists' => 'La persona seleccionada no existe.',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Crear usuario
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            
            if ($request->verificar_email) {
                $user->email_verified_at = now();
            }
            
            $user->save();
            
            // Vincular con persona si se seleccionó
            if ($request->persona_id) {
                $persona = Persona::find($request->persona_id);
                $persona->user_id = $user->id;
                $persona->save();
            }
            
            // Asignar roles
            if ($request->has('roles')) {
                $user->roles()->attach($request->roles);
            }
            
            DB::commit();
            
            return redirect()->route('admin.usuarios.index')
                ->with('mensaje', 'Usuario creado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.usuarios.index')
                ->with('mensaje', 'Error al crear el usuario: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = User::with(['persona', 'roles.permissions'])
                       ->findOrFail($id);
        
        $todosLosRoles = Role::all();
        
        // Obtener personas sin usuario (SOLO docentes, tutores y administradores - SIN estudiantes)
        $personasSinUsuario = Persona::whereNull('user_id')
                                     ->where('estado', 'Activo')
                                     ->where(function($query) {
                                         $query->whereHas('docente')
                                               ->orWhereHas('tutor')
                                               ->orWhereHas('administrador');
                                     })
                                     ->orderBy('apellidos')
                                     ->get();

        // Si el usuario actual tiene persona, añadirla al listado para poder mantenerla
        if ($usuario->persona) {
            $personasSinUsuario->prepend($usuario->persona);
        }

        return view('admin.usuarios.show', compact('usuario', 'todosLosRoles', 'personasSinUsuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.usuarios.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'persona_id' => 'nullable|exists:personas,id',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'Este email ya está registrado.',
            'email.email' => 'El email debe ser válido.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'persona_id.exists' => 'La persona seleccionada no existe.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            DB::beginTransaction();
            
            $user->name = $request->name;
            $user->email = $request->email;
            
            // Actualizar contraseña solo si se proporciona
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();
            
            // Actualizar persona
            if ($request->persona_id) {
                // Desvincular persona anterior si existe
                if ($user->persona && $user->persona->id != $request->persona_id) {
                    $personaAnterior = $user->persona;
                    $personaAnterior->user_id = null;
                    $personaAnterior->save();
                }
                
                // Vincular nueva persona
                $persona = Persona::find($request->persona_id);
                $persona->user_id = $user->id;
                $persona->save();
            } else {
                // Si no se selecciona persona, desvincular la actual
                if ($user->persona) {
                    $personaAnterior = $user->persona;
                    $personaAnterior->user_id = null;
                    $personaAnterior->save();
                }
            }
            
            // Sincronizar roles
            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            } else {
                $user->roles()->sync([]);
            }
            
            DB::commit();

            return redirect()->route('admin.usuarios.index')
                ->with('mensaje', 'Usuario actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.usuarios.index')
                ->with('mensaje', 'Error al actualizar el usuario: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            // Desvincular persona si existe
            if ($user->persona) {
                $persona = $user->persona;
                $persona->user_id = null;
                $persona->save();
            }
            
            // Desasociar roles
            $user->roles()->detach();
            
            // Eliminar el usuario
            $user->delete();
            
            DB::commit();

            return redirect()->route('admin.usuarios.index')
                ->with('mensaje', 'Usuario eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.usuarios.index')
                ->with('mensaje', 'Error al eliminar el usuario: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Verificar email del usuario
     */
    public function verificarEmail(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->email_verified_at = now();
            $user->save();

            return redirect()->back()
                ->with('mensaje', 'Email verificado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al verificar email: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Quitar verificación de email
     */
    public function quitarVerificacionEmail(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->email_verified_at = null;
            $user->save();

            return redirect()->back()
                ->with('mensaje', 'Verificación de email removida')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Cambiar contraseña del usuario
     */
    public function cambiarPassword(Request $request, string $id)
    {
        $request->validate([
            'nueva_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'nueva_password.required' => 'La nueva contraseña es obligatoria.',
            'nueva_password.confirmed' => 'Las contraseñas no coinciden.',
            'nueva_password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->password = Hash::make($request->nueva_password);
            $user->save();

            return redirect()->back()
                ->with('mensaje', 'Contraseña actualizada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al cambiar contraseña: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Activar usuario (a través de su persona)
     */
    public function activar(string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->persona) {
                $user->persona->estado = 'Activo';
                $user->persona->save();
            }

            return redirect()->back()
                ->with('mensaje', 'Usuario activado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Desactivar usuario (a través de su persona)
     */
    public function desactivar(string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->persona) {
                $user->persona->estado = 'Inactivo';
                $user->persona->save();
            }

            return redirect()->back()
                ->with('mensaje', 'Usuario desactivado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Vincular usuario con persona
     */
    public function vincularPersona(Request $request, string $id)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
        ]);

        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            // Desvincular persona anterior si existe
            if ($user->persona) {
                $personaAnterior = $user->persona;
                $personaAnterior->user_id = null;
                $personaAnterior->save();
            }
            
            // Vincular nueva persona
            $persona = Persona::find($request->persona_id);
            
            // Verificar que la persona no esté ya vinculada a otro usuario
            if ($persona->user_id && $persona->user_id != $user->id) {
                return redirect()->back()
                    ->with('mensaje', 'Esta persona ya está vinculada a otro usuario')
                    ->with('icono', 'error');
            }
            
            $persona->user_id = $user->id;
            $persona->save();
            
            DB::commit();

            return redirect()->back()
                ->with('mensaje', 'Persona vinculada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('mensaje', 'Error al vincular persona: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Desvincular persona del usuario
     */
    public function desvincularPersona(string $id)
    {
        try {
            DB::beginTransaction();
            
            $user = User::findOrFail($id);
            
            if ($user->persona) {
                $persona = $user->persona;
                $persona->user_id = null;
                $persona->save();
            }
            
            DB::commit();

            return redirect()->back()
                ->with('mensaje', 'Persona desvinculada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('mensaje', 'Error al desvincular persona: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}