<?php

namespace App\Http\Controllers\Admin\Registros;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asistencia::with(['estudiante.persona', 'curso', 'docente.persona']);
        
        // Filtros
        if ($request->has('fecha') && $request->fecha) {
            $query->whereDate('fecha', $request->fecha);
        }
        
        if ($request->has('estudiante_id') && $request->estudiante_id) {
            $query->where('estudiante_id', $request->estudiante_id);
        }
        
        if ($request->has('curso_id') && $request->curso_id) {
            $query->where('curso_id', $request->curso_id);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        $asistencias = $query->orderBy('fecha', 'desc')
                            ->orderBy('estudiante_id')
                            ->get();
        
        $estudiantes = Estudiante::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        $cursos = Curso::where('estado', 'Activo')->orderBy('nombre')->get();
        $docentes = Docente::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        return view('admin.asistencias.index', compact('asistencias', 'estudiantes', 'cursos', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.asistencias.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'curso_id_create' => 'required|exists:cursos,id',
            'docente_id_create' => 'nullable|exists:docentes,id',
            'fecha_create' => 'required|date',
            'estado_create' => 'required|in:Presente,Ausente,Tardanza,Justificado',
            'observaciones_create' => 'nullable|string|max:500',
        ], [
            'estudiante_id_create.required' => 'El estudiante es obligatorio.',
            'estudiante_id_create.exists' => 'El estudiante seleccionado no existe.',
            'curso_id_create.required' => 'El curso es obligatorio.',
            'curso_id_create.exists' => 'El curso seleccionado no existe.',
            'docente_id_create.exists' => 'El docente seleccionado no existe.',
            'fecha_create.required' => 'La fecha es obligatoria.',
            'fecha_create.date' => 'La fecha no es válida.',
            'estado_create.required' => 'El estado es obligatorio.',
        ]);
        
        try {
            // Verificar si ya existe asistencia para ese estudiante, curso y fecha
            $existeAsistencia = Asistencia::where('estudiante_id', $request->estudiante_id_create)
                ->where('curso_id', $request->curso_id_create)
                ->whereDate('fecha', $request->fecha_create)
                ->exists();

            if ($existeAsistencia) {
                return redirect()->route('admin.asistencias.index')
                    ->with('mensaje', 'Ya existe un registro de asistencia para este estudiante en este curso en la fecha seleccionada.')
                    ->with('icono', 'warning');
            }

            $asistencia = new Asistencia();
            $asistencia->estudiante_id = $request->estudiante_id_create;
            $asistencia->curso_id = $request->curso_id_create;
            $asistencia->docente_id = $request->docente_id_create;
            $asistencia->fecha = $request->fecha_create;
            $asistencia->estado = $request->estado_create;
            $asistencia->observaciones = $request->observaciones_create;
            $asistencia->save();
            
            return redirect()->route('admin.asistencias.index')
                ->with('mensaje', 'Asistencia registrada correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.asistencias.index')
                ->with('mensaje', 'Error al registrar la asistencia: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asistencia = Asistencia::with(['estudiante.persona', 'curso', 'docente.persona'])->findOrFail($id);
        return view('admin.asistencias.show', compact('asistencia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('admin.asistencias.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asistencia = Asistencia::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'curso_id' => 'required|exists:cursos,id',
            'docente_id' => 'nullable|exists:docentes,id',
            'fecha' => 'required|date',
            'estado' => 'required|in:Presente,Ausente,Tardanza,Justificado',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'estudiante_id.required' => 'El estudiante es obligatorio.',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe.',
            'curso_id.required' => 'El curso es obligatorio.',
            'curso_id.exists' => 'El curso seleccionado no existe.',
            'docente_id.exists' => 'El docente seleccionado no existe.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha no es válida.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            // Verificar si ya existe otra asistencia igual (excluyendo la actual)
            $existeAsistencia = Asistencia::where('estudiante_id', $request->estudiante_id)
                ->where('curso_id', $request->curso_id)
                ->whereDate('fecha', $request->fecha)
                ->where('id', '!=', $id)
                ->exists();

            if ($existeAsistencia) {
                return redirect()->route('admin.asistencias.index')
                    ->with('mensaje', 'Ya existe un registro de asistencia para este estudiante en este curso en la fecha seleccionada.')
                    ->with('icono', 'warning');
            }

            $asistencia->estudiante_id = $request->estudiante_id;
            $asistencia->curso_id = $request->curso_id;
            $asistencia->docente_id = $request->docente_id;
            $asistencia->fecha = $request->fecha;
            $asistencia->estado = $request->estado;
            $asistencia->observaciones = $request->observaciones;
            $asistencia->save();

            return redirect()->route('admin.asistencias.index')
                ->with('mensaje', 'Asistencia actualizada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.asistencias.index')
                ->with('mensaje', 'Error al actualizar la asistencia: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $asistencia = Asistencia::findOrFail($id);
            $asistencia->delete();

            return redirect()->route('admin.asistencias.index')
                ->with('mensaje', 'Asistencia eliminada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.asistencias.index')
                ->with('mensaje', 'Error al eliminar la asistencia: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Registro masivo de asistencias por fecha y curso
     */
    public function registroMasivo(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'fecha' => 'required|date',
            'docente_id' => 'nullable|exists:docentes,id',
        ]);

        try {
            $curso = Curso::findOrFail($request->curso_id);
            
            // Obtener estudiantes matriculados en el curso
            $estudiantes = Estudiante::whereHas('matriculas', function($query) use ($request) {
                $query->where('curso_id', $request->curso_id)
                      ->where('estado', 'Matriculado');
            })->get();

            if ($estudiantes->isEmpty()) {
                return redirect()->route('admin.asistencias.index')
                    ->with('mensaje', 'No hay estudiantes matriculados en este curso.')
                    ->with('icono', 'warning');
            }

            DB::beginTransaction();

            foreach ($estudiantes as $estudiante) {
                // Verificar si ya existe asistencia
                $existe = Asistencia::where('estudiante_id', $estudiante->id)
                    ->where('curso_id', $request->curso_id)
                    ->whereDate('fecha', $request->fecha)
                    ->exists();

                if (!$existe) {
                    Asistencia::create([
                        'estudiante_id' => $estudiante->id,
                        'curso_id' => $request->curso_id,
                        'docente_id' => $request->docente_id,
                        'fecha' => $request->fecha,
                        'estado' => 'Presente', // Por defecto todos presentes
                        'observaciones' => 'Registro masivo',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.asistencias.index')
                ->with('mensaje', 'Asistencias registradas masivamente correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.asistencias.index')
                ->with('mensaje', 'Error en el registro masivo: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}