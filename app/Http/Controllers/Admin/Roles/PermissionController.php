<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Permission::with('roles');
        
        // Filtros
        if ($request->has('module') && $request->module) {
            $query->where('module', $request->module);
        }
        
        if ($request->has('buscar') && $request->buscar) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('display_name', 'like', "%{$buscar}%")
                  ->orWhere('description', 'like', "%{$buscar}%");
            });
        }
        
        if ($request->has('asignado') && $request->asignado !== '') {
            if ($request->asignado == '1') {
                $query->has('roles');
            } else {
                $query->doesntHave('roles');
            }
        }
        
        $permisos = $query->orderBy('module')->orderBy('display_name')->get();
        
        // Obtener módulos únicos
        $modulos = Permission::obtenerModulos();
        
        // Obtener todos los roles para asignación
        $roles = Role::all();
        
        // Estadísticas
        $estadisticas = Permission::obtenerEstadisticas();
        
        // Permisos agrupados por módulo
        $permisosAgrupados = Permission::obtenerPorModuloAgrupado();
        
        return view('admin.permissions.index', compact(
            'permisos', 
            'modulos', 
            'roles', 
            'estadisticas', 
            'permisosAgrupados'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.permissions.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_create' => 'required|string|max:100|unique:permissions,name',
            'display_name_create' => 'required|string|max:150',
            'description_create' => 'nullable|string',
            'module_create' => 'nullable|string|max:50',
        ], [
            'name_create.required' => 'El nombre del permiso es obligatorio.',
            'name_create.unique' => 'Este permiso ya existe.',
            'name_create.max' => 'El nombre no puede exceder los 100 caracteres.',
            'display_name_create.required' => 'El nombre para mostrar es obligatorio.',
            'display_name_create.max' => 'El nombre para mostrar no puede exceder los 150 caracteres.',
            'module_create.max' => 'El módulo no puede exceder los 50 caracteres.',
        ]);
        
        try {
            DB::beginTransaction();
            
            $permission = new Permission();
            $permission->name = $request->name_create;
            $permission->display_name = $request->display_name_create;
            $permission->description = $request->description_create;
            $permission->module = $request->module_create;
            $permission->save();
            
            // Asignar a roles si se seleccionaron
            if ($request->has('roles_create')) {
                $permission->roles()->attach($request->roles_create);
            }
            
            DB::commit();
            
            return redirect()->route('admin.permissions.index')
                ->with('mensaje', 'Permiso creado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.permissions.index')
                ->with('mensaje', 'Error al crear el permiso: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::with(['roles.users'])->findOrFail($id);
        
        // Obtener todos los roles
        $todosLosRoles = Role::all();
        
        return view('admin.permissions.show', compact('permission', 'todosLosRoles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.permissions.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:permissions,name,' . $id,
            'display_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'module' => 'nullable|string|max:50',
        ], [
            'name.required' => 'El nombre del permiso es obligatorio.',
            'name.unique' => 'Este permiso ya existe.',
            'name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'display_name.required' => 'El nombre para mostrar es obligatorio.',
            'display_name.max' => 'El nombre para mostrar no puede exceder los 150 caracteres.',
            'module.max' => 'El módulo no puede exceder los 50 caracteres.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            DB::beginTransaction();
            
            $permission->name = $request->name;
            $permission->display_name = $request->display_name;
            $permission->description = $request->description;
            $permission->module = $request->module;
            $permission->save();
            
            // Sincronizar roles
            if ($request->has('roles')) {
                $permission->roles()->sync($request->roles);
            } else {
                $permission->roles()->sync([]);
            }
            
            DB::commit();

            return redirect()->route('admin.permissions.index')
                ->with('mensaje', 'Permiso actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.permissions.index')
                ->with('mensaje', 'Error al actualizar el permiso: ' . $e->getMessage())
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
            
            $permission = Permission::findOrFail($id);
            
            // Desasociar de todos los roles
            $permission->roles()->detach();
            
            // Eliminar el permiso
            $permission->delete();
            
            DB::commit();

            return redirect()->route('admin.permissions.index')
                ->with('mensaje', 'Permiso eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.permissions.index')
                ->with('mensaje', 'Error al eliminar el permiso: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Asignar permiso a un rol
     */
    public function asignarRol(Request $request, string $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->asignarARol($request->role_id);

            return redirect()->back()
                ->with('mensaje', 'Permiso asignado al rol correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al asignar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remover permiso de un rol
     */
    public function removerRol(Request $request, string $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->removerDeRol($request->role_id);

            return redirect()->back()
                ->with('mensaje', 'Permiso removido del rol correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('mensaje', 'Error al remover: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Crear permisos CRUD para un módulo
     */
    public function crearCRUD(Request $request)
    {
        $request->validate([
            'modulo' => 'required|string|max:50',
            'entidad' => 'required|string|max:50',
        ]);

        try {
            DB::beginTransaction();
            
            $permisos = Permission::crearPermisoCRUD($request->modulo, $request->entidad);
            
            DB::commit();

            return redirect()->route('admin.permissions.index')
                ->with('mensaje', 'Permisos CRUD creados correctamente: ' . count($permisos) . ' permisos')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.permissions.index')
                ->with('mensaje', 'Error al crear permisos CRUD: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}
 