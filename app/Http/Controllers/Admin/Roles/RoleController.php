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
        
        // 1. Obtener todos los permisos agrupados por módulo
        $permisosAgrupados = Permission::all()->groupBy('module');
        
        // 2. Obtener todos los usuarios
        $usuarios = User::all();
        
        // 3. Estadísticas: Calculamos los valores una vez
        $totalRoles = Role::count();
        $totalAsignacionesUsuarios = DB::table('model_has_roles')->count();
        $totalAsignacionesPermisos = DB::table('role_has_permissions')->count();
        $rolesConUsuarios = Role::has('users')->count();
        $rolesConPermisos = Role::has('permissions')->count();

        // 4. PREPARAMOS EL ARRAY PARA CUBRIR TODOS LOS CASOS POSIBLES
        $estadisticas = [
            // Nombres que pide tu vista actual (error actual)
            'total' => $totalRoles,
            'con_usuarios' => $rolesConUsuarios, 
            'con_permisos' => $rolesConPermisos,

            // Nombres que pedía en el error anterior (por compatibilidad)
            'total_roles' => $totalRoles,
            'total_usuarios_asignados' => $totalAsignacionesUsuarios,
            'total_permisos_asignados' => $totalAsignacionesPermisos,
        ];
        
        return view('admin.Roles.index', compact(
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
        return redirect()->route('admin.Roles.index');
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
            $role->guard_name = 'web';
            $role->save();
            
            if ($request->has('permissions_create')) {
                $role->permissions()->sync($request->permissions_create);
            }
            
            DB::commit();
            
            return redirect()->route('admin.Roles.index')
                ->with('mensaje', 'Rol creado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.Roles.index')
                ->with('mensaje', 'Error al crear el rol: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with(['users', 'permissions'])->findOrFail($id);
        
        $permisosAgrupados = Permission::all()->groupBy('module');
        
        if (method_exists(new User(), 'persona')) {
            $todosLosUsuarios = User::with('persona')->get();
        } else {
            $todosLosUsuarios = User::all();
        }
        
        return view('admin.Roles.show', compact('role', 'permisosAgrupados', 'todosLosUsuarios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.Roles.index');
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
            
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            } else {
                $role->permissions()->detach();
            }
            
            DB::commit();

            return redirect()->route('admin.Roles.index')
                ->with('mensaje', 'Rol actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.Roles.index')
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
            
            if ($role->users()->count() > 0) {
                return redirect()->route('admin.Roles.index')
                    ->with('mensaje', 'No se puede eliminar el rol porque tiene usuarios asignados')
                    ->with('icono', 'error');
            }
            
            $role->permissions()->detach();
            $role->delete();
            
            DB::commit();

            return redirect()->route('admin.Roles.index')
                ->with('mensaje', 'Rol eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.Roles.index')
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
            $permission = Permission::findOrFail($request->permission_id);
            
            $role->givePermissionTo($permission);

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
            $permission = Permission::findOrFail($request->permission_id);
            
            $role->revokePermissionTo($permission);

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
            $user = User::findOrFail($request->user_id);
            $user->assignRole($role);

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
            $user = User::findOrFail($request->user_id);
            $user->removeRole($role);

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