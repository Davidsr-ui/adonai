<?php

namespace App\Http\Controllers\Admin\Registros;

use App\Http\Controllers\Controller;
use App\Models\Nota;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Nota::with(['matricula.estudiante.persona', 'matricula.curso', 'periodo', 'docente.persona']);
        
        // Filtros
        if ($request->has('estudiante_id') && $request->estudiante_id) {
            $query->whereHas('matricula', function($q) use ($request) {
                $q->where('estudiante_id', $request->estudiante_id);
            });
        }
        
        if ($request->has('curso_id') && $request->curso_id) {
            $query->whereHas('matricula', function($q) use ($request) {
                $q->where('curso_id', $request->curso_id);
            });
        }
        
        if ($request->has('periodo_id') && $request->periodo_id) {
            $query->where('periodo_id', $request->periodo_id);
        }
        
        if ($request->has('tipo_evaluacion') && $request->tipo_evaluacion) {
            $query->where('tipo_evaluacion', $request->tipo_evaluacion);
        }
        
        $notas = $query->orderBy('periodo_id', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        $estudiantes = Estudiante::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        $cursos = Curso::where('estado', 'Activo')->orderBy('nombre')->get();
       $periodos = Periodo::with('gestion')
    ->get()
    ->sortByDesc('gestion.año')
    ->sortByDesc('numero');
        $docentes = Docente::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        $matriculas = Matricula::with(['estudiante.persona', 'curso'])->where('estado', 'Matriculado')->get();
        
        return view('admin.notas.index', compact('notas', 'estudiantes', 'cursos', 'periodos', 'docentes', 'matriculas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.notas.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'matricula_id_create' => 'required|exists:matriculas,id',
            'periodo_id_create' => 'required|exists:periodos,id',
            'docente_id_create' => 'required|exists:docentes,id',
            'nota_practica_create' => 'nullable|numeric|min:0|max:20',
            'nota_teoria_create' => 'nullable|numeric|min:0|max:20',
            'nota_final_create' => 'required|numeric|min:0|max:20',
            'tipo_evaluacion_create' => 'required|in:Parcial,Final,Práctica,Oral,Trabajo',
            'descripcion_create' => 'nullable|string|max:500',
            'observaciones_create' => 'nullable|string|max:500',
            'fecha_evaluacion_create' => 'nullable|date',
            'visible_tutor_create' => 'nullable|boolean',
        ], [
            'matricula_id_create.required' => 'La matrícula es obligatoria.',
            'matricula_id_create.exists' => 'La matrícula seleccionada no existe.',
            'periodo_id_create.required' => 'El periodo es obligatorio.',
            'periodo_id_create.exists' => 'El periodo seleccionado no existe.',
            'docente_id_create.required' => 'El docente es obligatorio.',
            'docente_id_create.exists' => 'El docente seleccionado no existe.',
            'nota_practica_create.numeric' => 'La nota práctica debe ser un número.',
            'nota_practica_create.min' => 'La nota práctica debe ser mayor o igual a 0.',
            'nota_practica_create.max' => 'La nota práctica debe ser menor o igual a 20.',
            'nota_teoria_create.numeric' => 'La nota teoría debe ser un número.',
            'nota_teoria_create.min' => 'La nota teoría debe ser mayor o igual a 0.',
            'nota_teoria_create.max' => 'La nota teoría debe ser menor o igual a 20.',
            'nota_final_create.required' => 'La nota final es obligatoria.',
            'nota_final_create.numeric' => 'La nota final debe ser un número.',
            'nota_final_create.min' => 'La nota final debe ser mayor o igual a 0.',
            'nota_final_create.max' => 'La nota final debe ser menor o igual a 20.',
            'tipo_evaluacion_create.required' => 'El tipo de evaluación es obligatorio.',
            'fecha_evaluacion_create.date' => 'La fecha de evaluación no es válida.',
        ]);
        
        try {
            $nota = new Nota();
            $nota->matricula_id = $request->matricula_id_create;
            $nota->periodo_id = $request->periodo_id_create;
            $nota->docente_id = $request->docente_id_create;
            $nota->nota_practica = $request->nota_practica_create;
            $nota->nota_teoria = $request->nota_teoria_create;
            $nota->nota_final = $request->nota_final_create;
            $nota->tipo_evaluacion = $request->tipo_evaluacion_create;
            $nota->descripcion = $request->descripcion_create;
            $nota->observaciones = $request->observaciones_create;
            $nota->fecha_evaluacion = $request->fecha_evaluacion_create;
            $nota->visible_tutor = $request->has('visible_tutor_create') ? true : false;
            
            // Si marca visible para tutor, publicar automáticamente
            if ($nota->visible_tutor) {
                $nota->fecha_publicacion = now();
            }
            
            $nota->save();
            
            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Nota registrada correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Error al registrar la nota: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $nota = Nota::with(['matricula.estudiante.persona', 'matricula.curso', 'matricula.grado', 'periodo', 'docente.persona'])->findOrFail($id);
        return view('admin.notas.show', compact('nota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('admin.notas.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'matricula_id' => 'required|exists:matriculas,id',
            'periodo_id' => 'required|exists:periodos,id',
            'docente_id' => 'required|exists:docentes,id',
            'nota_practica' => 'nullable|numeric|min:0|max:20',
            'nota_teoria' => 'nullable|numeric|min:0|max:20',
            'nota_final' => 'required|numeric|min:0|max:20',
            'tipo_evaluacion' => 'required|in:Parcial,Final,Práctica,Oral,Trabajo',
            'descripcion' => 'nullable|string|max:500',
            'observaciones' => 'nullable|string|max:500',
            'fecha_evaluacion' => 'nullable|date',
            'visible_tutor' => 'nullable|boolean',
        ], [
            'matricula_id.required' => 'La matrícula es obligatoria.',
            'matricula_id.exists' => 'La matrícula seleccionada no existe.',
            'periodo_id.required' => 'El periodo es obligatorio.',
            'periodo_id.exists' => 'El periodo seleccionado no existe.',
            'docente_id.required' => 'El docente es obligatorio.',
            'docente_id.exists' => 'El docente seleccionado no existe.',
            'nota_final.required' => 'La nota final es obligatoria.',
            'tipo_evaluacion.required' => 'El tipo de evaluación es obligatorio.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            $nota->matricula_id = $request->matricula_id;
            $nota->periodo_id = $request->periodo_id;
            $nota->docente_id = $request->docente_id;
            $nota->nota_practica = $request->nota_practica;
            $nota->nota_teoria = $request->nota_teoria;
            $nota->nota_final = $request->nota_final;
            $nota->tipo_evaluacion = $request->tipo_evaluacion;
            $nota->descripcion = $request->descripcion;
            $nota->observaciones = $request->observaciones;
            $nota->fecha_evaluacion = $request->fecha_evaluacion;
            $nota->visible_tutor = $request->has('visible_tutor') ? true : false;
            
            // Si marca visible para tutor y no estaba publicada, publicar
            if ($nota->visible_tutor && !$nota->fecha_publicacion) {
                $nota->fecha_publicacion = now();
            }
            
            // Si desmarca visible, despublicar
            if (!$nota->visible_tutor) {
                $nota->fecha_publicacion = null;
            }
            
            $nota->save();

            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Nota actualizada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Error al actualizar la nota: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $nota = Nota::findOrFail($id);
            $nota->delete();

            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Nota eliminada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Error al eliminar la nota: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Publicar nota para que sea visible a tutores
     */
    public function publicar($id)
    {
        try {
            $nota = Nota::findOrFail($id);
            $nota->publicar();

            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Nota publicada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Error al publicar la nota: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Despublicar nota
     */
    public function despublicar($id)
    {
        try {
            $nota = Nota::findOrFail($id);
            $nota->despublicar();

            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Nota despublicada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.notas.index')
                ->with('mensaje', 'Error al despublicar la nota: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}