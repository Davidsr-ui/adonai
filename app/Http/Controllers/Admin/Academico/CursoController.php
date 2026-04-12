<?php

namespace App\Http\Controllers\Admin\Academico;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Nivel;
use App\Models\Grado; // <--- Importante: Usamos el modelo Grado
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
        // Cargamos 'nivel' y 'grado' para mostrarlos en la tabla
        $cursos = Curso::with(['nivel', 'grado'])->orderBy('nombre', 'asc')->get();
        
        // Ya no mandamos solo niveles, ahora mandamos los GRADOS para el selector (dropdown)
        $grados = Grado::where('estado', 'Activo')->with('nivel')->orderBy('nombre')->get(); 
        
        // Mantenemos $niveles por si lo usas en algún filtro visual, pero el importante es $grados
        $niveles = Nivel::where('estado', 'Activo')->orderBy('orden')->get();

        return view('admin.cursos.index', compact('cursos', 'niveles', 'grados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.cursos.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Validamos que se envíe un grado válido
            'grado_id_create' => 'required|exists:grados,id', 
            'nombre_create' => 'required|max:100', // Quitamos unique global, lo validamos abajo
            'codigo_create' => 'nullable|max:20|unique:cursos,codigo',
            'area_curricular_create' => 'nullable|max:100',
            'horas_semanales_create' => 'required|integer|min:1|max:40',
            'estado_create' => 'required|in:Activo,Inactivo',
        ], [
            'grado_id_create.required' => 'El grado es obligatorio.',
            'grado_id_create.exists' => 'El grado seleccionado no existe.',
            'nombre_create.required' => 'El nombre del curso es obligatorio.',
            'codigo_create.unique' => 'Ya existe un curso con este código.',
            'horas_semanales_create.required' => 'Las horas semanales son obligatorias.',
            'horas_semanales_create.integer' => 'Las horas semanales deben ser un número entero.',
        ]);
        
        // Validación manual: No permitir dos cursos con el mismo nombre EN EL MISMO GRADO
        $existeEnGrado = Curso::where('grado_id', $request->grado_id_create)
                              ->where('nombre', $request->nombre_create)
                              ->exists();

        if($existeEnGrado) {
            return redirect()->back()
                ->with('mensaje', 'Ya existe un curso llamado "' . $request->nombre_create . '" en el grado seleccionado.')
                ->with('icono', 'error')
                ->withInput();
        }

        try {
            // Buscamos el grado seleccionado
            $grado = Grado::findOrFail($request->grado_id_create);

            $curso = new Curso();
            // Asignamos el grado seleccionado
            $curso->grado_id = $grado->id;
            // Asignamos automáticamente el nivel al que pertenece ese grado
            $curso->nivel_id = $grado->nivel_id; 
            
            $curso->nombre = $request->nombre_create;
            $curso->codigo = $request->codigo_create;
            $curso->area_curricular = $request->area_curricular_create;
            $curso->horas_semanales = $request->horas_semanales_create;
            $curso->estado = $request->estado_create;
            $curso->save();
            
            return redirect()->route('admin.cursos.index')
                ->with('mensaje', 'Curso creado correctamente para el grado: ' . $grado->nombre)
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
        // Cargamos relaciones para mostrar información adicional
        $curso->load(['nivel', 'grado', 'docentes.persona', 'horarios']);
        return view('admin.cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        return redirect()->route('admin.cursos.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        $validate = Validator::make($request->all(), [
            'grado_id' => 'required|exists:grados,id',
            'nombre' => 'required|max:100',
            // El código es único excepto para este mismo curso
            'codigo' => 'nullable|max:20|unique:cursos,codigo,' . $curso->id,
            'area_curricular' => 'nullable|max:100',
            'horas_semanales' => 'required|integer|min:1|max:40',
            'estado' => 'required|in:Activo,Inactivo',
        ], [
            'grado_id.required' => 'El grado es obligatorio.',
            'nombre.required' => 'El nombre del curso es obligatorio.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $curso->id);
        }

        try {
            // Buscamos el grado nuevo para actualizar también el nivel si cambió
            $grado = Grado::findOrFail($request->grado_id);

            $curso->grado_id = $grado->id;
            $curso->nivel_id = $grado->nivel_id; // Sincronizamos nivel
            
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