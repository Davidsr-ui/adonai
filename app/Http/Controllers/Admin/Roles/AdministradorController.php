<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdministradorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Administrador::with('persona');
        
        // Filtros
        if ($request->has('cargo') && $request->cargo) {
            $query->where('cargo', $request->cargo);
        }
        
        if ($request->has('area') && $request->area) {
            $query->where('area', $request->area);
        }
        
        if ($request->has('estado') && $request->estado !== '') {
            $query->whereHas('persona', function($q) use ($request) {
                $q->where('estado', $request->estado);
            });
        }
        
        if ($request->has('buscar') && $request->buscar) {
            $buscar = $request->buscar;
            $query->whereHas('persona', function($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                  ->orWhere('apellidos', 'like', "%{$buscar}%")
                  ->orWhere('dni', 'like', "%{$buscar}%");
            });
        }
        
        $administradores = $query->orderBy('created_at', 'desc')->get();
        
        // Personas disponibles (que no sean administradores, docentes o estudiantes)
        $personasDisponibles = Persona::whereDoesntHave('administrador')
            ->whereDoesntHave('docente')
            ->whereDoesntHave('estudiante')
            ->where('estado', 'Activo')
            ->get();
        
        // Obtener áreas únicas
        $areas = Administrador::whereNotNull('area')
                              ->distinct()
                              ->pluck('area')
                              ->toArray();
        
        // Estadísticas
        $estadisticas = Administrador::obtenerEstadisticas();
        
        return view('admin.administradores.index', compact('administradores', 'personasDisponibles', 'areas', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.administradores.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'persona_id_create' => 'required|exists:personas,id|unique:administradores,persona_id',
            'cargo_create' => 'required|in:Director,Subdirector,Secretario,Administrativo',
            'area_create' => 'nullable|string|max:100',
            'fecha_asignacion_create' => 'nullable|date',
        ], [
            'persona_id_create.required' => 'La persona es obligatoria.',
            'persona_id_create.exists' => 'La persona seleccionada no existe.',
            'persona_id_create.unique' => 'Esta persona ya está registrada como administrador.',
            'cargo_create.required' => 'El cargo es obligatorio.',
            'cargo_create.in' => 'El cargo seleccionado no es válido.',
            'area_create.max' => 'El área no puede exceder los 100 caracteres.',
            'fecha_asignacion_create.date' => 'La fecha de asignación no es válida.',
        ]);
        
        try {
            DB::beginTransaction();
            
            $administrador = new Administrador();
            $administrador->persona_id = $request->persona_id_create;
            $administrador->cargo = $request->cargo_create;
            $administrador->area = $request->area_create;
            $administrador->fecha_asignacion = $request->fecha_asignacion_create ?? now();
            $administrador->save();
            
            DB::commit();
            
            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Administrador registrado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Error al registrar el administrador: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $administrador = Administrador::with('persona')->findOrFail($id);
        
        return view('admin.administradores.show', compact('administrador'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.administradores.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $administrador = Administrador::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'persona_id' => 'required|exists:personas,id|unique:administradores,persona_id,' . $id,
            'cargo' => 'required|in:Director,Subdirector,Secretario,Administrativo',
            'area' => 'nullable|string|max:100',
            'fecha_asignacion' => 'nullable|date',
        ], [
            'persona_id.required' => 'La persona es obligatoria.',
            'persona_id.exists' => 'La persona seleccionada no existe.',
            'persona_id.unique' => 'Esta persona ya está registrada como administrador.',
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.in' => 'El cargo seleccionado no es válido.',
            'area.max' => 'El área no puede exceder los 100 caracteres.',
            'fecha_asignacion.date' => 'La fecha de asignación no es válida.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            DB::beginTransaction();
            
            $administrador->persona_id = $request->persona_id;
            $administrador->cargo = $request->cargo;
            $administrador->area = $request->area;
            $administrador->fecha_asignacion = $request->fecha_asignacion;
            $administrador->save();
            
            DB::commit();

            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Administrador actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Error al actualizar el administrador: ' . $e->getMessage())
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
            
            $administrador = Administrador::findOrFail($id);
            $administrador->delete();
            
            DB::commit();

            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Administrador eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Error al eliminar el administrador: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Activar administrador
     */
    public function activar(string $id)
    {
        try {
            $administrador = Administrador::findOrFail($id);
            $administrador->activar();

            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Administrador activado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Error al activar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Desactivar administrador
     */
    public function desactivar(string $id)
    {
        try {
            $administrador = Administrador::findOrFail($id);
            $administrador->desactivar();

            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Administrador desactivado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.administradores.index')
                ->with('mensaje', 'Error al desactivar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}