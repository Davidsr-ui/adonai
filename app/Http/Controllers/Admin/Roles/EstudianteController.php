<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Grado;
use App\Models\Curso;      // <--- IMPORTANTE: Necesario para buscar cursos
use App\Models\Matricula;  // <--- IMPORTANTE: Necesario para crear matrículas
use App\Models\Gestion;    // <--- IMPORTANTE: Necesario para saber el año escolar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

// ==========================================
// LIBRERÍAS DE EXCEL
// ==========================================
use App\Imports\MatriculaImport;
use Maatwebsite\Excel\Facades\Excel;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::with(['persona' => function($query) {
            $query->withTrashed();
        }, 'grado', 'tutores'])->get();

        $grados = Grado::activo()->orderBy('nombre')->get();
        return view('admin.estudiantes.index', compact('estudiantes', 'grados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.estudiantes.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dni_create' => 'required|max:20|unique:personas,dni',
            'nombres_create' => 'required|max:100',
            'apellidos_create' => 'required|max:100',
            'fecha_nacimiento_create' => 'required|date|before:today',
            'genero_create' => 'required|in:M,F,Otro',
            'direccion_create' => 'nullable|max:255',
            'telefono_create' => 'nullable|max:20',
            'telefono_emergencia_create' => 'nullable|max:20',
            'estado_create' => 'required|in:Activo,Inactivo',
            'grado_id_create' => 'nullable|exists:grados,id',
            'codigo_estudiante_create' => 'required|max:50|unique:estudiantes,codigo_estudiante',
            'año_ingreso_create' => 'required|integer|min:1900|max:' . date('Y'),
            'condicion_create' => 'required|in:Regular,Irregular,Retirado',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear persona
            $persona = new Persona();
            $persona->dni = $request->dni_create;
            $persona->nombres = $request->nombres_create;
            $persona->apellidos = $request->apellidos_create;
            $persona->fecha_nacimiento = $request->fecha_nacimiento_create;
            $persona->genero = $request->genero_create;
            $persona->direccion = $request->direccion_create;
            $persona->telefono = $request->telefono_create;
            $persona->telefono_emergencia = $request->telefono_emergencia_create;
            $persona->estado = $request->estado_create;

            if ($request->hasFile('foto_perfil')) {
                $file = $request->file('foto_perfil');
                $name = 'persona_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('personas', $name, 'public');
                $persona->foto_perfil = 'personas/' . $name;
            }

            $persona->save();

            // 2. Crear estudiante
            $estudiante = new Estudiante();
            $estudiante->persona_id = $persona->id;
            $estudiante->grado_id = $request->grado_id_create; // Aquí asignamos el grado
            $estudiante->codigo_estudiante = $request->codigo_estudiante_create;
            $estudiante->año_ingreso = $request->año_ingreso_create;
            $estudiante->condicion = $request->condicion_create;
            $estudiante->save();

            // =========================================================
            // MAGIA: MATRICULACIÓN AUTOMÁTICA
            // =========================================================
            // Si el estudiante tiene un grado asignado y está activo
            if ($estudiante->grado_id && $persona->estado == 'Activo') {
                $this->matricularEnCursosDelGrado($estudiante);
            }

            DB::commit();

            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Estudiante creado y matriculado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Error al crear el estudiante: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Estudiante $estudiante)
    {
        $estudiante->load(['persona', 'grado.nivel', 'grado.turno', 'tutores.persona', 'matriculas.curso', 'asistencias', 'comportamientos']);
        return view('admin.estudiantes.show', compact('estudiante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        return redirect()->route('admin.estudiantes.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estudiante $estudiante)
    {
        $validate = Validator::make($request->all(), [
            'dni' => 'required|max:20|unique:personas,dni,' . $estudiante->persona_id,
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:M,F,Otro',
            'direccion' => 'nullable|max:255',
            'telefono' => 'nullable|max:20',
            'telefono_emergencia' => 'nullable|max:20',
            'estado' => 'required|in:Activo,Inactivo',
            'grado_id' => 'nullable|exists:grados,id',
            'codigo_estudiante' => 'required|max:50|unique:estudiantes,codigo_estudiante,' . $estudiante->id,
            'año_ingreso' => 'required|integer|min:1900|max:' . date('Y'),
            'condicion' => 'required|in:Regular,Irregular,Retirado',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $estudiante->id);
        }

        DB::beginTransaction();
        try {
            // Actualizar persona
            $persona = $estudiante->persona;
            $persona->dni = $request->dni;
            $persona->nombres = $request->nombres;
            $persona->apellidos = $request->apellidos;
            $persona->fecha_nacimiento = $request->fecha_nacimiento;
            $persona->genero = $request->genero;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->telefono_emergencia = $request->telefono_emergencia;
            $persona->estado = $request->estado;

            if ($request->hasFile('foto_perfil')) {
                if ($persona->foto_perfil && file_exists(storage_path('app/public/' . $persona->foto_perfil))) {
                    unlink(storage_path('app/public/' . $persona->foto_perfil));
                }
                $file = $request->file('foto_perfil');
                $name = 'persona_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('personas', $name, 'public');
                $persona->foto_perfil = 'personas/' . $name;
            }

            $persona->save();

            // Detectar si cambió el grado para actualizar matrículas
            $gradoAnterior = $estudiante->grado_id;
            
            // Actualizar estudiante
            $estudiante->grado_id = $request->grado_id;
            $estudiante->codigo_estudiante = $request->codigo_estudiante;
            $estudiante->año_ingreso = $request->año_ingreso;
            $estudiante->condicion = $request->condicion;
            $estudiante->save();

            // =========================================================
            // MAGIA: ACTUALIZACIÓN DE MATRÍCULA
            // =========================================================
            // Si cambió de grado o si no tenía matrículas y ahora tiene grado
            if ($estudiante->grado_id && ($gradoAnterior != $estudiante->grado_id || $estudiante->matriculas()->count() == 0)) {
                if ($persona->estado == 'Activo') {
                    $this->matricularEnCursosDelGrado($estudiante);
                }
            }

            DB::commit();

            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Estudiante actualizado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Error al actualizar el estudiante: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        DB::beginTransaction();
        try {
            $persona = $estudiante->persona;

            if ($persona && $persona->foto_perfil) {
                $rutaFoto = storage_path('app/public/' . $persona->foto_perfil);
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }

            if ($persona) {
                $persona->delete();
            }

            $estudiante->delete();

            DB::commit();

            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Estudiante eliminado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Error al eliminar el estudiante: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    public function importarExcel(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new MatriculaImport, $request->file('archivo_excel'));
            
            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Importación masiva completada con éxito.')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Error en la importación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    // =========================================================================
    // FUNCIÓN PRIVADA PARA LA AUTOMATIZACIÓN (LA MAGIA)
    // =========================================================================
    private function matricularEnCursosDelGrado(Estudiante $estudiante)
    {
        // 1. Obtener la gestión activa (Ej: 2025 o 2026)
        // Buscamos una gestión que esté marcada como 'Activo' (o la más reciente)
        $gestionActiva = Gestion::where('estado', 'Activo')->first();

        // Si no hay gestión activa, no podemos matricular (evita error)
        if (!$gestionActiva) {
            return; 
        }

        // 2. Obtener todos los cursos que pertenecen al grado del estudiante
        $cursosDelGrado = Curso::where('grado_id', $estudiante->grado_id)->get();

        // 3. Crear las matrículas
        foreach ($cursosDelGrado as $curso) {
            // firstOrCreate: Si ya existe la matrícula, no hace nada. Si no, la crea.
            // Esto evita errores de duplicados.
            Matricula::firstOrCreate(
                [
                    'estudiante_id' => $estudiante->id,
                    'curso_id'      => $curso->id,
                    'gestion_id'    => $gestionActiva->id,
                ],
                [
                    'grado_id'      => $estudiante->grado_id, // Se guarda al crear
                    'estado'        => 'Matriculado',
                ]
            );
        }
    }
}