<?php

namespace App\Http\Controllers\Admin\Registros;

use App\Http\Controllers\Controller;
use App\Models\TutorEstudiante;
use App\Models\Tutor;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TutorEstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $relaciones = TutorEstudiante::with(['tutor.persona', 'estudiante.persona'])
            ->orderBy('estudiante_id')
            ->orderBy('tipo')
            ->get();
        
        $tutores = Tutor::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        $estudiantes = Estudiante::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        return view('admin.tutor-estudiante.index', compact('relaciones', 'tutores', 'estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.tutor-estudiante.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tutor_id_create' => 'required|exists:tutores,id',
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'relacion_familiar_create' => 'required|in:Padre,Madre,Tutor Legal,Abuelo/a,Tío/a,Hermano/a,Otro',
            'tipo_create' => 'required|in:Principal,Secundario',
            'autorizacion_recojo_create' => 'nullable|boolean',
            'estado_create' => 'required|in:Activo,Inactivo',
        ], [
            'tutor_id_create.required' => 'El tutor es obligatorio.',
            'tutor_id_create.exists' => 'El tutor seleccionado no existe.',
            'estudiante_id_create.required' => 'El estudiante es obligatorio.',
            'estudiante_id_create.exists' => 'El estudiante seleccionado no existe.',
            'relacion_familiar_create.required' => 'La relación familiar es obligatoria.',
            'tipo_create.required' => 'El tipo de relación es obligatorio.',
            'estado_create.required' => 'El estado es obligatorio.',
        ]);
        
        try {
            // Verificar si ya existe la relación
            $existeRelacion = TutorEstudiante::where('tutor_id', $request->tutor_id_create)
                ->where('estudiante_id', $request->estudiante_id_create)
                ->exists();

            if ($existeRelacion) {
                return redirect()->route('admin.tutor-estudiante.index')
                    ->with('mensaje', 'Esta relación tutor-estudiante ya existe.')
                    ->with('icono', 'warning');
            }

            // Si es tipo Principal, verificar que no haya otro tutor principal activo para ese estudiante
            if ($request->tipo_create === 'Principal') {
                $tienePrincipal = TutorEstudiante::where('estudiante_id', $request->estudiante_id_create)
                    ->where('tipo', 'Principal')
                    ->where('estado', 'Activo')
                    ->exists();

                if ($tienePrincipal) {
                    return redirect()->route('admin.tutor-estudiante.index')
                        ->with('mensaje', 'Este estudiante ya tiene un tutor principal activo. Debe cambiar el tipo a Secundario o desactivar el tutor principal existente.')
                        ->with('icono', 'error');
                }
            }

            $relacion = new TutorEstudiante();
            $relacion->tutor_id = $request->tutor_id_create;
            $relacion->estudiante_id = $request->estudiante_id_create;
            $relacion->relacion_familiar = $request->relacion_familiar_create;
            $relacion->tipo = $request->tipo_create;
            $relacion->autorizacion_recojo = $request->has('autorizacion_recojo_create') ? true : false;
            $relacion->estado = $request->estado_create;
            $relacion->save();
            
            return redirect()->route('admin.tutor-estudiante.index')
                ->with('mensaje', 'Relación tutor-estudiante registrada correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.tutor-estudiante.index')
                ->with('mensaje', 'Error al registrar la relación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $relacion = TutorEstudiante::with(['tutor.persona', 'estudiante.persona'])->findOrFail($id);
        return view('admin.tutor-estudiante.show', compact('relacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('admin.tutor-estudiante.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $relacion = TutorEstudiante::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'tutor_id' => 'required|exists:tutores,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'relacion_familiar' => 'required|in:Padre,Madre,Tutor Legal,Abuelo/a,Tío/a,Hermano/a,Otro',
            'tipo' => 'required|in:Principal,Secundario',
            'autorizacion_recojo' => 'nullable|boolean',
            'estado' => 'required|in:Activo,Inactivo',
        ], [
            'tutor_id.required' => 'El tutor es obligatorio.',
            'tutor_id.exists' => 'El tutor seleccionado no existe.',
            'estudiante_id.required' => 'El estudiante es obligatorio.',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe.',
            'relacion_familiar.required' => 'La relación familiar es obligatoria.',
            'tipo.required' => 'El tipo de relación es obligatorio.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            // Verificar si ya existe otra relación igual (excluyendo la actual)
            $existeRelacion = TutorEstudiante::where('tutor_id', $request->tutor_id)
                ->where('estudiante_id', $request->estudiante_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($existeRelacion) {
                return redirect()->route('admin.tutor-estudiante.index')
                    ->with('mensaje', 'Esta relación tutor-estudiante ya existe.')
                    ->with('icono', 'warning');
            }

            // Si cambia a tipo Principal y está Activo, verificar que no haya otro principal activo
            if ($request->tipo === 'Principal' && $request->estado === 'Activo') {
                $tienePrincipal = TutorEstudiante::where('estudiante_id', $request->estudiante_id)
                    ->where('tipo', 'Principal')
                    ->where('estado', 'Activo')
                    ->where('id', '!=', $id)
                    ->exists();

                if ($tienePrincipal) {
                    return redirect()->route('admin.tutor-estudiante.index')
                        ->with('mensaje', 'Este estudiante ya tiene un tutor principal activo.')
                        ->with('icono', 'error');
                }
            }

            $relacion->tutor_id = $request->tutor_id;
            $relacion->estudiante_id = $request->estudiante_id;
            $relacion->relacion_familiar = $request->relacion_familiar;
            $relacion->tipo = $request->tipo;
            $relacion->autorizacion_recojo = $request->has('autorizacion_recojo') ? true : false;
            $relacion->estado = $request->estado;
            $relacion->save();

            return redirect()->route('admin.tutor-estudiante.index')
                ->with('mensaje', 'Relación tutor-estudiante actualizada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.tutor-estudiante.index')
                ->with('mensaje', 'Error al actualizar la relación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $relacion = TutorEstudiante::findOrFail($id);
            $relacion->delete();

            return redirect()->route('admin.tutor-estudiante.index')
                ->with('mensaje', 'Relación tutor-estudiante eliminada correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.tutor-estudiante.index')
                ->with('mensaje', 'Error al eliminar la relación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}