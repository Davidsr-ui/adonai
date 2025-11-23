<?php

namespace App\Http\Controllers\Admin\Registros;

use App\Http\Controllers\Controller;
use App\Models\Comportamiento;
use App\Models\Estudiante;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComportamientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Comportamiento::with(['estudiante.persona', 'docente.persona']);
        
        // Filtros
        if ($request->has('fecha') && $request->fecha) {
            $query->whereDate('fecha', $request->fecha);
        }
        
        if ($request->has('estudiante_id') && $request->estudiante_id) {
            $query->where('estudiante_id', $request->estudiante_id);
        }
        
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->has('notificado') && $request->notificado !== '') {
            $query->where('notificado_tutor', $request->notificado);
        }
        
        $comportamientos = $query->orderBy('fecha', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->get();
        
        $estudiantes = Estudiante::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        $docentes = Docente::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        return view('admin.comportamientos.index', compact('comportamientos', 'estudiantes', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.comportamientos.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'docente_id_create' => 'nullable|exists:docentes,id',
            'fecha_create' => 'required|date',
            'descripcion_create' => 'required|string|max:1000',
            'tipo_create' => 'required|in:Positivo,Negativo,Neutro',
            'sancion_create' => 'nullable|string|max:255',
            'notificado_tutor_create' => 'nullable|boolean',
        ], [
            'estudiante_id_create.required' => 'El estudiante es obligatorio.',
            'estudiante_id_create.exists' => 'El estudiante seleccionado no existe.',
            'docente_id_create.exists' => 'El docente seleccionado no existe.',
            'fecha_create.required' => 'La fecha es obligatoria.',
            'fecha_create.date' => 'La fecha no es válida.',
            'descripcion_create.required' => 'La descripción es obligatoria.',
            'descripcion_create.max' => 'La descripción no puede exceder los 1000 caracteres.',
            'tipo_create.required' => 'El tipo es obligatorio.',
            'sancion_create.max' => 'La sanción no puede exceder los 255 caracteres.',
        ]);
        
        try {
            $comportamiento = new Comportamiento();
            $comportamiento->estudiante_id = $request->estudiante_id_create;
            $comportamiento->docente_id = $request->docente_id_create;
            $comportamiento->fecha = $request->fecha_create;
            $comportamiento->descripcion = $request->descripcion_create;
            $comportamiento->tipo = $request->tipo_create;
            $comportamiento->sancion = $request->sancion_create;
            $comportamiento->notificado_tutor = $request->has('notificado_tutor_create') ? true : false;
            
            // Si marca notificado, registrar fecha
            if ($comportamiento->notificado_tutor) {
                $comportamiento->fecha_notificacion = now();
            }
            
            $comportamiento->save();
            
            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Comportamiento registrado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Error al registrar el comportamiento: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $comportamiento = Comportamiento::with(['estudiante.persona', 'estudiante.grado', 'docente.persona'])->findOrFail($id);
        
        // Obtener resumen de comportamientos del estudiante
        $resumen = Comportamiento::obtenerResumenPorEstudiante($comportamiento->estudiante_id);
        
        // Obtener últimos comportamientos
        $ultimosComportamientos = Comportamiento::obtenerUltimosComportamientos($comportamiento->estudiante_id, 10);
        
        return view('admin.comportamientos.show', compact('comportamiento', 'resumen', 'ultimosComportamientos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('admin.comportamientos.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comportamiento = Comportamiento::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'docente_id' => 'nullable|exists:docentes,id',
            'fecha' => 'required|date',
            'descripcion' => 'required|string|max:1000',
            'tipo' => 'required|in:Positivo,Negativo,Neutro',
            'sancion' => 'nullable|string|max:255',
            'notificado_tutor' => 'nullable|boolean',
        ], [
            'estudiante_id.required' => 'El estudiante es obligatorio.',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe.',
            'docente_id.exists' => 'El docente seleccionado no existe.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede exceder los 1000 caracteres.',
            'tipo.required' => 'El tipo es obligatorio.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            $comportamiento->estudiante_id = $request->estudiante_id;
            $comportamiento->docente_id = $request->docente_id;
            $comportamiento->fecha = $request->fecha;
            $comportamiento->descripcion = $request->descripcion;
            $comportamiento->tipo = $request->tipo;
            $comportamiento->sancion = $request->sancion;
            
            $notificadoAntes = $comportamiento->notificado_tutor;
            $comportamiento->notificado_tutor = $request->has('notificado_tutor') ? true : false;
            
            // Si marca notificado y antes no lo estaba, registrar fecha
            if ($comportamiento->notificado_tutor && !$notificadoAntes) {
                $comportamiento->fecha_notificacion = now();
            }
            
            // Si desmarca notificado, limpiar fecha
            if (!$comportamiento->notificado_tutor && $notificadoAntes) {
                $comportamiento->fecha_notificacion = null;
            }
            
            $comportamiento->save();

            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Comportamiento actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Error al actualizar el comportamiento: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $comportamiento = Comportamiento::findOrFail($id);
            $comportamiento->delete();

            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Comportamiento eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Error al eliminar el comportamiento: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Notificar al tutor sobre el comportamiento
     */
    public function notificar($id)
    {
        try {
            $comportamiento = Comportamiento::findOrFail($id);
            $comportamiento->notificarTutor();

            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Tutor notificado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Error al notificar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Cancelar notificación al tutor
     */
    public function cancelarNotificacion($id)
    {
        try {
            $comportamiento = Comportamiento::findOrFail($id);
            $comportamiento->cancelarNotificacion();

            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Notificación cancelada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.comportamientos.index')
                ->with('mensaje', 'Error al cancelar notificación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}