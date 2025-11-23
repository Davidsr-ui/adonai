<?php

namespace App\Http\Controllers\Admin\Academico;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = Curso::with('nivel')->orderBy('nombre', 'asc')->get();
        $niveles = Nivel::where('estado', 'Activo')->orderBy('orden')->get();
        return view('admin.cursos.index', compact('cursos', 'niveles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.cursos.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nivel_id_create' => 'required|exists:nivels,id',
            'nombre_create' => 'required|max:100|unique:cursos,nombre',
            'codigo_create' => 'nullable|max:20|unique:cursos,codigo',
            'area_curricular_create' => 'nullable|max:100',
            'horas_semanales_create' => 'required|integer|min:1|max:40',
            'estado_create' => 'required|in:Activo,Inactivo',
        ], [
            'nivel_id_create.required' => 'El nivel es obligatorio.',
            'nivel_id_create.exists' => 'El nivel seleccionado no existe.',
            'nombre_create.required' => 'El nombre del curso es obligatorio.',
            'nombre_create.unique' => 'Ya existe un curso con este nombre.',
            'codigo_create.unique' => 'Ya existe un curso con este código.',
            'horas_semanales_create.required' => 'Las horas semanales son obligatorias.',
            'horas_semanales_create.integer' => 'Las horas semanales deben ser un número entero.',
        ]);
        
        try {
            $curso = new Curso();
            $curso->nivel_id = $request->nivel_id_create;
            $curso->nombre = $request->nombre_create;
            $curso->codigo = $request->codigo_create;
            $curso->area_curricular = $request->area_curricular_create;
            $curso->horas_semanales = $request->horas_semanales_create;
            $curso->estado = $request->estado_create;
            $curso->save();
            
            return redirect()->route('admin.cursos.index')
                ->with('mensaje', 'Curso creado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.cursos.index')
                ->with('mensaje', 'Error al crear el curso: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Curso $curso)
    {
        // Cargar relaciones para mostrar información adicional
        $curso->load(['nivel', 'docentes.persona', 'horarios']);
        return view('admin.cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.cursos.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        $validate = Validator::make($request->all(), [
            'nivel_id' => 'required|exists:nivels,id',
            'nombre' => 'required|max:100|unique:cursos,nombre,' . $curso->id,
            'codigo' => 'nullable|max:20|unique:cursos,codigo,' . $curso->id,
            'area_curricular' => 'nullable|max:100',
            'horas_semanales' => 'required|integer|min:1|max:40',
            'estado' => 'required|in:Activo,Inactivo',
        ], [
            'nivel_id.required' => 'El nivel es obligatorio.',
            'nivel_id.exists' => 'El nivel seleccionado no existe.',
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nombre.unique' => 'Ya existe un curso con este nombre.',
            'codigo.unique' => 'Ya existe un curso con este código.',
            'horas_semanales.required' => 'Las horas semanales son obligatorias.',
            'horas_semanales.integer' => 'Las horas semanales deben ser un número entero.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $curso->id);
        }

        try {
            $curso->nivel_id = $request->nivel_id;
            $curso->nombre = $request->nombre;
            $curso->codigo = $request->codigo;
            $curso->area_curricular = $request->area_curricular;
            $curso->horas_semanales = $request->horas_semanales;
            $curso->estado = $request->estado;
            $curso->save();

            return redirect()->route('admin.cursos.index')
                ->with('mensaje', 'Curso actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.cursos.index')
                ->with('mensaje', 'Error al actualizar el curso: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        try {
            // Verificar si el curso tiene asignaciones activas
            $tieneDocentes = $curso->docentes()->exists();
            $tieneMatriculas = $curso->matriculas()->exists();

            if ($tieneDocentes || $tieneMatriculas) {
                return redirect()->route('admin.cursos.index')
                    ->with('mensaje', 'No se puede eliminar el curso porque tiene docentes asignados o matrículas asociadas. Considere cambiar el estado a Inactivo.')
                    ->with('icono', 'error');
            }

            $curso->delete();

            return redirect()->route('admin.cursos.index')
                ->with('mensaje', 'Curso eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.cursos.index')
                ->with('mensaje', 'Error al eliminar el curso: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}