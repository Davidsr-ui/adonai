<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::with(['users', 'permissions']);
        
        // Filtros
        if ($request->has('buscar') && $request->buscar) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('display_name', 'like', "%{$buscar}%")
                  ->orWhere('description', 'like', "%{$buscar}%");
            });
        }
        
        if ($request->has('tiene_usuarios') && $request->tiene_usuarios !== '') {
            if ($request->tiene_usuarios == '1') {
                $query->has('users');
            } else {
                $query->doesntHave('users');
            }
        }
        
        if ($request->has('tiene_permisos') && $request->tiene_permisos !== '') {
            if ($request->tiene_permisos == '1') {
                $query->has('permissions');
            } else {
                $query->doesntHave('permissions');
            }
        }
        
        $roles = $query->orderBy('created_at', 'desc')->get();
        
        // Obtener todos los permisos agrupados por módulo
        // ✅ CORREGIDO: Usar obtenerAgrupadosPorModulo() en lugar de obtenerPorModuloAgrupado()
        $permisosAgrupados = Permission::obtenerAgrupadosPorModulo();
        
        // Obtener todos los usuarios
        $usuarios = User::all();
        
        // Estadísticas
        $estadisticas = Role::obtenerEstadisticas();
        
        return view('admin.roles.index', compact(
            'roles',
            'permisosAgrupados',
            'usuarios',
            'estadisticas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.roles.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_create' => 'required|string|max:50|unique:roles,name',
            'display_name_create' => 'required|string|max:100',
            'description_create' => 'nullable|string',
        ], [
            'name_create.required' => 'El nombre del rol es obligatorio.',
            'name_create.unique' => 'Este rol ya existe.',
            'name_create.max' => 'El nombre no puede exceder los 50 caracteres.',
            'display_name_create.required' => 'El nombre para mostrar es obligatorio.',
            'display_name_create.max' => 'El nombre para mostrar no puede exceder los 100 caracteres.',
        ]);
        
        try {
            DB::beginTransaction();
            
            $role = new Role();
            $role->name = $request->name_create;
            $role->display_name = $request->display_name_create;
            $role->description = $request->description_create;
            $role->save();
            
            // Asignar permisos si se seleccionaron
            if ($request->has('permissions_create')) {
                $role->permissions()->attach($request->permissions_create);
            }
            
            DB::commit();
            
            return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Rol creado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Error al crear el rol: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with(['users.persona', 'permissions'])->findOrFail($id);
        
        // Obtener todos los permisos agrupados por módulo
        // ✅ CORREGIDO: Usar obtenerAgrupadosPorModulo() en lugar de obtenerPorModuloAgrupado()
        $permisosAgrupados = Permission::obtenerAgrupadosPorModulo();
        
        // Obtener todos los usuarios
        $todosLosUsuarios = User::with('persona')->get();
        
        return view('admin.roles.show', compact('role', 'permisosAgrupados', 'todosLosUsuarios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.roles.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:roles,name,' . $id,
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.unique' => 'Este rol ya existe.',
            'name.max' => 'El nombre no puede exceder los 50 caracteres.',
            'display_name.required' => 'El nombre para mostrar es obligatorio.',
            'display_name.max' => 'El nombre para mostrar no puede exceder los 100 caracteres.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            DB::beginTransaction();
            
            $role->name = $request->name;
            $role->display_name = $request->display_name;
            $role->description = $request->description;
            $role->save();
            
            // Sincronizar permisos
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            } else {
                $role->permissions()->sync([]);
            }
            
            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Rol actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Error al actualizar el rol: ' . $e->getMessage())
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
            
            $role = Role::findOrFail($id);
            
            // Verificar si tiene usuarios asignados
            if ($role->users()->count() > 0) {
                return redirect()->route('admin.roles.index')
                    ->with('mensaje', 'No se puede eliminar el rol porque tiene usuarios asignados')
                    ->with('icono', 'error');
            }
            
            // Desasociar todos los permisos
            $role->permissions()->detach();
            
            // Eliminar el rol
            $role->delete();
            
            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Rol eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.roles.index')
                ->with('mensaje', 'Error al eliminar el rol: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Asignar permiso al rol
     */
    public function asignarPermiso(Request $request, string $id)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->asignarPermiso($request->permission_id);

            return redirect()->back()
                ->with('mensaje', 'Permiso asignado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al asignar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remover permiso del rol
     */
    public function removerPermiso(Request $request, string $id)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->removerPermiso($request->permission_id);

            return redirect()->back()
                ->with('mensaje', 'Permiso removido correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al remover: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Asignar usuario al rol
     */
    public function asignarUsuario(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->asignarUsuario($request->user_id);

            return redirect()->back()
                ->with('mensaje', 'Usuario asignado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al asignar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remover usuario del rol
     */
    public function removerUsuario(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->removerUsuario($request->user_id);

            return redirect()->back()
                ->with('mensaje', 'Usuario removido correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al remover: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}