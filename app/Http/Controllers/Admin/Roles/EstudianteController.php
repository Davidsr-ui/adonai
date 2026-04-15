<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Grado;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Imports\MatriculaImport;
use Maatwebsite\Excel\Facades\Excel;

class EstudianteController extends Controller
{
    public function index()
    {
        $estudiantes = Estudiante::with(['persona' => function($query) {
            $query->withTrashed();
        }, 'grado', 'tutores'])->get();

        $grados = Grado::orderBy('nombre')->get();
        return view('admin.estudiantes.index', compact('estudiantes', 'grados'));
    }

    public function create()
    {
        return redirect()->route('admin.estudiantes.index');
    }

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
            'año_ingreso_create' => 'required|integer|min:1900|max:' . date('Y'),
            'condicion_create' => 'required|in:Regular,Irregular,Retirado',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();
        try {
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

            $estudiante = new Estudiante();
            $estudiante->persona_id = $persona->id;
            $estudiante->grado_id = $request->grado_id_create;
            $estudiante->año_ingreso = $request->año_ingreso_create;
            $estudiante->condicion = $request->condicion_create;

            // Generar código único para el estudiante (formato AÑO-CORRELATIVO)
            $año = $estudiante->año_ingreso;
            $ultimo = Estudiante::where('año_ingreso', $año)->max('id') ?? 0;
            $correlativo = str_pad($ultimo + 1, 4, '0', STR_PAD_LEFT);
            $codigo = $año . '-' . $correlativo;
            $estudiante->codigo_estudiante = $codigo;

            $estudiante->save();

            if ($estudiante->grado_id && $persona->estado == 'Activo') {
                $this->matricularEnCursosDelGrado($estudiante);
            }

            DB::commit();

            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Estudiante creado y matriculado correctamente. Código: ' . $codigo)
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Error al crear el estudiante: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    public function show(Estudiante $estudiante)
    {
        $estudiante->load(['persona', 'grado.nivel', 'grado.turno', 'tutores.persona', 'matriculas.curso', 'asistencias', 'comportamientos']);
        return view('admin.estudiantes.show', compact('estudiante'));
    }

    public function edit(Estudiante $estudiante)
    {
        return redirect()->route('admin.estudiantes.index');
    }

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

            $gradoAnterior = $estudiante->grado_id;

            $estudiante->grado_id = $request->grado_id;
            $estudiante->año_ingreso = $request->año_ingreso;
            $estudiante->condicion = $request->condicion;
            // No se actualiza el código, se mantiene el original
            $estudiante->save();

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

    private function matricularEnCursosDelGrado(Estudiante $estudiante)
    {
        $gestionActiva = Gestion::where('estado', 'Activo')->first();
        if (!$gestionActiva) {
            return;
        }

        $cursosDelGrado = Curso::where('grado_id', $estudiante->grado_id)->get();

        foreach ($cursosDelGrado as $curso) {
            Matricula::firstOrCreate(
                [
                    'estudiante_id' => $estudiante->id,
                    'curso_id'      => $curso->id,
                    'gestion_id'    => $gestionActiva->id,
                ],
                [
                    'grado_id'      => $estudiante->grado_id,
                    'estado'        => 'Matriculado',
                ]
            );
        }
    }
}