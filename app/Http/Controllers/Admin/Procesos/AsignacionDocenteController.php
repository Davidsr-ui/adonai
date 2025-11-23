<?php

namespace App\Http\Controllers\Admin\Procesos;

use App\Http\Controllers\Controller;
use App\Models\DocenteCurso;
use App\Models\Docente;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsignacionDocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asignaciones = DocenteCurso::with(['docente.persona', 'curso', 'grado.nivel', 'gestion'])
            ->orderBy('gestion_id', 'desc')
            ->orderBy('grado_id')
            ->get();
        
        $docentes = Docente::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        $cursos = Curso::where('estado', 'Activo')->orderBy('nombre')->get();
        $grados = Grado::with('nivel')->where('estado', 'Activo')->orderBy('nivel_id')->orderBy('nombre')->get();
        $gestiones = Gestion::orderBy('año', 'desc')->get();
        
        return view('admin.asignaciones.index', compact('asignaciones', 'docentes', 'cursos', 'grados', 'gestiones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.asignaciones.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'docente_id_create' => 'required|exists:docentes,id',
            'curso_id_create' => 'required|exists:cursos,id',
            'grado_id_create' => 'required|exists:grados,id',
            'gestion_id_create' => 'required|exists:gestions,id',
            'es_tutor_aula_create' => 'nullable|boolean',
        ], [
            'docente_id_create.required' => 'El docente es obligatorio.',
            'docente_id_create.exists' => 'El docente seleccionado no existe.',
            'curso_id_create.required' => 'El curso es obligatorio.',
            'curso_id_create.exists' => 'El curso seleccionado no existe.',
            'grado_id_create.required' => 'El grado es obligatorio.',
            'grado_id_create.exists' => 'El grado seleccionado no existe.',
            'gestion_id_create.required' => 'La gestión es obligatoria.',
            'gestion_id_create.exists' => 'La gestión seleccionada no existe.',
        ]);
        
        try {
            // Verificar si ya existe la asignación
            $existeAsignacion = DocenteCurso::where('docente_id', $request->docente_id_create)
                ->where('curso_id', $request->curso_id_create)
                ->where('grado_id', $request->grado_id_create)
                ->where('gestion_id', $request->gestion_id_create)
                ->exists();

            if ($existeAsignacion) {
                return redirect()->route('admin.asignaciones.index')
                    ->with('mensaje', 'Esta asignación ya existe.')
                    ->with('icono', 'warning');
            }

            // Si se marca como tutor de aula, verificar que no haya otro tutor para ese grado/gestión
            if ($request->has('es_tutor_aula_create') && $request->es_tutor_aula_create) {
                $tieneTutor = DocenteCurso::where('grado_id', $request->grado_id_create)
                    ->where('gestion_id', $request->gestion_id_create)
                    ->where('es_tutor_aula', true)
                    ->exists();

                if ($tieneTutor) {
                    return redirect()->route('admin.asignaciones.index')
                        ->with('mensaje', 'Este grado ya tiene un tutor de aula asignado para esta gestión.')
                        ->with('icono', 'error');
                }
            }

            $asignacion = new DocenteCurso();
            $asignacion->docente_id = $request->docente_id_create;
            $asignacion->curso_id = $request->curso_id_create;
            $asignacion->grado_id = $request->grado_id_create;
            $asignacion->gestion_id = $request->gestion_id_create;
            $asignacion->es_tutor_aula = $request->has('es_tutor_aula_create') ? true : false;
            $asignacion->save();
            
            return redirect()->route('admin.asignaciones.index')
                ->with('mensaje', 'Asignación creada correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.asignaciones.index')
                ->with('mensaje', 'Error al crear la asignación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asignacion = DocenteCurso::with(['docente.persona', 'curso', 'grado.nivel', 'gestion'])->findOrFail($id);
        return view('admin.asignaciones.show', compact('asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.asignaciones.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asignacion = DocenteCurso::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'docente_id' => 'required|exists:docentes,id',
            'curso_id' => 'required|exists:cursos,id',
            'grado_id' => 'required|exists:grados,id',
            'gestion_id' => 'required|exists:gestions,id',
            'es_tutor_aula' => 'nullable|boolean',
        ], [
            'docente_id.required' => 'El docente es obligatorio.',
            'docente_id.exists' => 'El docente seleccionado no existe.',
            'curso_id.required' => 'El curso es obligatorio.',
            'curso_id.exists' => 'El curso seleccionado no existe.',
            'grado_id.required' => 'El grado es obligatorio.',
            'grado_id.exists' => 'El grado seleccionado no existe.',
            'gestion_id.required' => 'La gestión es obligatoria.',
            'gestion_id.exists' => 'La gestión seleccionada no existe.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            // Verificar si ya existe otra asignación igual (excluyendo la actual)
            $existeAsignacion = DocenteCurso::where('docente_id', $request->docente_id)
                ->where('curso_id', $request->curso_id)
                ->where('grado_id', $request->grado_id)
                ->where('gestion_id', $request->gestion_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($existeAsignacion) {
                return redirect()->route('admin.asignaciones.index')
                    ->with('mensaje', 'Esta asignación ya existe.')
                    ->with('icono', 'warning');
            }

            // Si se marca como tutor de aula, verificar que no haya otro tutor
            if ($request->has('es_tutor_aula') && $request->es_tutor_aula) {
                $tieneTutor = DocenteCurso::where('grado_id', $request->grado_id)
                    ->where('gestion_id', $request->gestion_id)
                    ->where('es_tutor_aula', true)
                    ->where('id', '!=', $id)
                    ->exists();

                if ($tieneTutor) {
                    return redirect()->route('admin.asignaciones.index')
                        ->with('mensaje', 'Este grado ya tiene un tutor de aula asignado para esta gestión.')
                        ->with('icono', 'error');
                }
            }

            $asignacion->docente_id = $request->docente_id;
            $asignacion->curso_id = $request->curso_id;
            $asignacion->grado_id = $request->grado_id;
            $asignacion->gestion_id = $request->gestion_id;
            $asignacion->es_tutor_aula = $request->has('es_tutor_aula') ? true : false;
            $asignacion->save();

            return redirect()->route('admin.asignaciones.index')
                ->with('mensaje', 'Asignación actualizada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.asignaciones.index')
                ->with('mensaje', 'Error al actualizar la asignación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $asignacion = DocenteCurso::findOrFail($id);
            $asignacion->delete();

            return redirect()->route('admin.asignaciones.index')
                ->with('mensaje', 'Asignación eliminada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.asignaciones.index')
                ->with('mensaje', 'Error al eliminar la asignación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}