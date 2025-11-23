<?php

namespace App\Http\Controllers\Admin\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matriculas = Matricula::with(['estudiante.persona', 'curso', 'grado.nivel', 'gestion'])
            ->orderBy('gestion_id', 'desc')
            ->orderBy('grado_id')
            ->get();

        $estudiantes = Estudiante::with('persona')->whereHas('persona', function ($query) {
            $query->where('estado', 'Activo');
        })->get();

        $cursos = Curso::where('estado', 'Activo')->orderBy('nombre')->get();
        $grados = Grado::with('nivel')->where('estado', 'Activo')->orderBy('nivel_id')->orderBy('nombre')->get();
        $gestiones = Gestion::orderBy('año', 'desc')->get();

        return view('admin.matriculas.index', compact('matriculas', 'estudiantes', 'cursos', 'grados', 'gestiones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.matriculas.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'curso_id_create' => 'required|exists:cursos,id',
            'grado_id_create' => 'required|exists:grados,id',
            'gestion_id_create' => 'required|exists:gestions,id',
            'estado_create' => 'required|in:Matriculado,Retirado,Aprobado,Desaprobado',
        ], [
            'estudiante_id_create.required' => 'El estudiante es obligatorio.',
            'estudiante_id_create.exists' => 'El estudiante seleccionado no existe.',
            'curso_id_create.required' => 'El curso es obligatorio.',
            'curso_id_create.exists' => 'El curso seleccionado no existe.',
            'grado_id_create.required' => 'El grado es obligatorio.',
            'grado_id_create.exists' => 'El grado seleccionado no existe.',
            'gestion_id_create.required' => 'La gestión es obligatoria.',
            'gestion_id_create.exists' => 'La gestión seleccionada no existe.',
            'estado_create.required' => 'El estado es obligatorio.',
        ]);

        try {
            // Verificar si ya existe la matrícula

            $estudiante = Estudiante::findOrFail($request->estudiante_id_create);
            if (!$estudiante->puedeMatricularse()) {
                return redirect()->route('admin.matriculas.index')
                    ->with('mensaje', 'El estudiante con condición "' . $estudiante->condicion . '" no puede matricularse. Solo estudiantes Regular o Irregular pueden matricularse.')
                    ->with('icono', 'warning');
            }

            $existeMatricula = Matricula::where('estudiante_id', $request->estudiante_id_create)
                ->where('curso_id', $request->curso_id_create)
                ->where('gestion_id', $request->gestion_id_create)
                ->exists();

            if ($existeMatricula) {
                return redirect()->route('admin.matriculas.index')
                    ->with('mensaje', 'Esta matrícula ya existe.')
                    ->with('icono', 'warning');
            }

            // Verificar capacidad del grado
            $grado = Grado::findOrFail($request->grado_id_create);
            if ($grado->esta_lleno) {
                return redirect()->route('admin.matriculas.index')
                    ->with('mensaje', 'El grado ha alcanzado su capacidad máxima.')
                    ->with('icono', 'error');
            }

            $matricula = new Matricula();
            $matricula->estudiante_id = $request->estudiante_id_create;
            $matricula->curso_id = $request->curso_id_create;
            $matricula->grado_id = $request->grado_id_create;
            $matricula->gestion_id = $request->gestion_id_create;
            $matricula->estado = $request->estado_create;
            $matricula->save();

            return redirect()->route('admin.matriculas.index')
                ->with('mensaje', 'Matrícula registrada correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return redirect()->route('admin.matriculas.index')
                ->with('mensaje', 'Error al registrar la matrícula: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $matricula = Matricula::with(['estudiante.persona', 'curso', 'grado.nivel', 'gestion', 'notas'])->findOrFail($id);
        return view('admin.matriculas.show', compact('matricula'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('admin.matriculas.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'curso_id' => 'required|exists:cursos,id',
            'grado_id' => 'required|exists:grados,id',
            'gestion_id' => 'required|exists:gestions,id',
            'estado' => 'required|in:Matriculado,Retirado,Aprobado,Desaprobado',
        ], [
            'estudiante_id.required' => 'El estudiante es obligatorio.',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe.',
            'curso_id.required' => 'El curso es obligatorio.',
            'curso_id.exists' => 'El curso seleccionado no existe.',
            'grado_id.required' => 'El grado es obligatorio.',
            'grado_id.exists' => 'El grado seleccionado no existe.',
            'gestion_id.required' => 'La gestión es obligatoria.',
            'gestion_id.exists' => 'La gestión seleccionada no existe.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            // Verificar si ya existe otra matrícula igual (excluyendo la actual)
            $existeMatricula = Matricula::where('estudiante_id', $request->estudiante_id)
                ->where('curso_id', $request->curso_id)
                ->where('gestion_id', $request->gestion_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($existeMatricula) {
                return redirect()->route('admin.matriculas.index')
                    ->with('mensaje', 'Esta matrícula ya existe.')
                    ->with('icono', 'warning');
            }

            // Si cambió de grado, verificar capacidad del nuevo grado
            if ($matricula->grado_id != $request->grado_id) {
                $nuevoGrado = Grado::findOrFail($request->grado_id);
                if ($nuevoGrado->esta_lleno) {
                    return redirect()->route('admin.matriculas.index')
                        ->with('mensaje', 'El nuevo grado ha alcanzado su capacidad máxima.')
                        ->with('icono', 'error');
                }
            }

            $matricula->estudiante_id = $request->estudiante_id;
            $matricula->curso_id = $request->curso_id;
            $matricula->grado_id = $request->grado_id;
            $matricula->gestion_id = $request->gestion_id;
            $matricula->estado = $request->estado;
            $matricula->save();

            return redirect()->route('admin.matriculas.index')
                ->with('mensaje', 'Matrícula actualizada correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return redirect()->route('admin.matriculas.index')
                ->with('mensaje', 'Error al actualizar la matrícula: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $matricula = Matricula::findOrFail($id);

            // Verificar si tiene notas registradas
            if ($matricula->notas()->exists()) {
                return redirect()->route('admin.matriculas.index')
                    ->with('mensaje', 'No se puede eliminar la matrícula porque tiene notas registradas.')
                    ->with('icono', 'error');
            }

            $matricula->delete();

            return redirect()->route('admin.matriculas.index')
                ->with('mensaje', 'Matrícula eliminada correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return redirect()->route('admin.matriculas.index')
                ->with('mensaje', 'Error al eliminar la matrícula: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}
