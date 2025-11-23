<?php

namespace App\Http\Controllers\Admin\Academico;

use App\Http\Controllers\Controller;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GradoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grados = Grado::with(['nivel', 'turno'])->orderBy('nivel_id')->orderBy('nombre')->get();
        $niveles = Nivel::where('estado', 'Activo')->orderBy('orden')->get();
        $turnos = Turno::where('estado', 'activo')->get();
        return view('admin.grados.index', compact('grados', 'niveles', 'turnos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.grados.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nivel_id_create' => 'required|exists:nivels,id',
            'turno_id_create' => 'nullable|exists:turnos,id',
            'nombre_create' => 'required|max:100',
            'seccion_create' => 'nullable|max:10',
            'capacidad_maxima_create' => 'required|integer|min:1|max:100',
            'estado_create' => 'required|in:Activo,Inactivo',
        ], [
            'nivel_id_create.required' => 'El nivel es obligatorio.',
            'nivel_id_create.exists' => 'El nivel seleccionado no existe.',
            'turno_id_create.exists' => 'El turno seleccionado no existe.',
            'nombre_create.required' => 'El nombre del grado es obligatorio.',
            'nombre_create.max' => 'El nombre no puede exceder los 100 caracteres.',
            'capacidad_maxima_create.required' => 'La capacidad máxima es obligatoria.',
            'capacidad_maxima_create.integer' => 'La capacidad debe ser un número entero.',
            'capacidad_maxima_create.min' => 'La capacidad mínima es 1 estudiante.',
            'capacidad_maxima_create.max' => 'La capacidad máxima es 100 estudiantes.',
        ]);
        
        try {
            $grado = new Grado();
            $grado->nivel_id = $request->nivel_id_create;
            $grado->turno_id = $request->turno_id_create;
            $grado->nombre = $request->nombre_create;
            $grado->seccion = $request->seccion_create;
            $grado->capacidad_maxima = $request->capacidad_maxima_create;
            $grado->estado = $request->estado_create;
            $grado->save();
            
            return redirect()->route('admin.grados.index')
                ->with('mensaje', 'Grado creado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.grados.index')
                ->with('mensaje', 'Error al crear el grado: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Grado $grado)
    {
        // Cargar relaciones para mostrar información adicional
        $grado->load(['nivel', 'turno', 'estudiantes.persona', 'horarios', 'matriculas']);
        return view('admin.grados.show', compact('grado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grado $grado)
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.grados.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grado $grado)
    {
        $validate = Validator::make($request->all(), [
            'nivel_id' => 'required|exists:nivels,id',
            'turno_id' => 'nullable|exists:turnos,id',
            'nombre' => 'required|max:100',
            'seccion' => 'nullable|max:10',
            'capacidad_maxima' => 'required|integer|min:1|max:100',
            'estado' => 'required|in:Activo,Inactivo',
        ], [
            'nivel_id.required' => 'El nivel es obligatorio.',
            'nivel_id.exists' => 'El nivel seleccionado no existe.',
            'turno_id.exists' => 'El turno seleccionado no existe.',
            'nombre.required' => 'El nombre del grado es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'capacidad_maxima.required' => 'La capacidad máxima es obligatoria.',
            'capacidad_maxima.integer' => 'La capacidad debe ser un número entero.',
            'capacidad_maxima.min' => 'La capacidad mínima es 1 estudiante.',
            'capacidad_maxima.max' => 'La capacidad máxima es 100 estudiantes.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $grado->id);
        }

        try {
            // Verificar que la nueva capacidad no sea menor al número de estudiantes actuales
            $estudiantesActuales = $grado->estudiantes()->count();
            if ($request->capacidad_maxima < $estudiantesActuales) {
                return redirect()->route('admin.grados.index')
                    ->with('mensaje', "No se puede reducir la capacidad a {$request->capacidad_maxima} porque actualmente hay {$estudiantesActuales} estudiantes matriculados.")
                    ->with('icono', 'error');
            }

            $grado->nivel_id = $request->nivel_id;
            $grado->turno_id = $request->turno_id;
            $grado->nombre = $request->nombre;
            $grado->seccion = $request->seccion;
            $grado->capacidad_maxima = $request->capacidad_maxima;
            $grado->estado = $request->estado;
            $grado->save();

            return redirect()->route('admin.grados.index')
                ->with('mensaje', 'Grado actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.grados.index')
                ->with('mensaje', 'Error al actualizar el grado: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grado $grado)
    {
        try {
            // Verificar si el grado tiene estudiantes matriculados
            $tieneEstudiantes = $grado->estudiantes()->exists();
            $tieneMatriculas = $grado->matriculas()->exists();
            $tieneHorarios = $grado->horarios()->exists();
            $tieneDocenteCursos = $grado->docenteCursos()->exists();

            if ($tieneEstudiantes || $tieneMatriculas || $tieneHorarios || $tieneDocenteCursos) {
                return redirect()->route('admin.grados.index')
                    ->with('mensaje', 'No se puede eliminar el grado porque tiene estudiantes matriculados, horarios, asignaciones de docentes o matrículas asociadas. Considere cambiar el estado a Inactivo.')
                    ->with('icono', 'error');
            }

            $grado->delete();

            return redirect()->route('admin.grados.index')
                ->with('mensaje', 'Grado eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.grados.index')
                ->with('mensaje', 'Error al eliminar el grado: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}