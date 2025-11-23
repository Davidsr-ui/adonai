<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\Grado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::with(['persona', 'grado', 'tutores'])->get();
        $grados = Grado::activo()->orderBy('nombre')->get();
        return view('admin.estudiantes.index', compact('estudiantes', 'grados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vista manejada en el modal del index
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

            // FOTO DE PERFIL
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Crear persona
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

            // FOTO DE PERFIL
            if ($request->hasFile('foto_perfil')) {
                $file = $request->file('foto_perfil');
                $name = 'persona_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('personas', $name, 'public');
                $persona->foto_perfil = 'personas/' . $name;
            }


            $persona->save();

            // Crear estudiante
            $estudiante = new Estudiante();
            $estudiante->persona_id = $persona->id;
            $estudiante->grado_id = $request->grado_id_create;
            $estudiante->codigo_estudiante = $request->codigo_estudiante_create;
            $estudiante->año_ingreso = $request->año_ingreso_create;
            $estudiante->condicion = $request->condicion_create;
            $estudiante->save();

            DB::commit();

            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Estudiante creado correctamente')
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
        // Vista manejada en el modal del index
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

            // FOTO DE PERFIL
            if ($request->hasFile('foto_perfil')) {

                // BORRAR FOTO ANTERIOR
                if ($persona->foto_perfil && file_exists(storage_path('app/public/' . $persona->foto_perfil))) {
                    unlink(storage_path('app/public/' . $persona->foto_perfil));
                }

                $file = $request->file('foto_perfil');
                $name = 'persona_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('personas', $name, 'public');
                $persona->foto_perfil = 'personas/' . $name;
            }


            $persona->save();

            // Actualizar estudiante
            $estudiante->grado_id = $request->grado_id;
            $estudiante->codigo_estudiante = $request->codigo_estudiante;
            $estudiante->año_ingreso = $request->año_ingreso;
            $estudiante->condicion = $request->condicion;
            $estudiante->save();

            DB::commit();

            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Estudiante actualizado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Error al actualizar el estudiante')
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

            // =====================================================
            // 1. ELIMINAR FOTO DE PERFIL EN STORAGE
            // =====================================================
            if ($persona && $persona->foto_perfil) {

                $rutaFoto = storage_path('app/public/' . $persona->foto_perfil);

                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }

            // =====================================================
            // 2. SOFT DELETE DE LA PERSONA
            // =====================================================
            if ($persona) {
                $persona->delete();
            }

            // =====================================================
            // 3. ELIMINAR REGISTRO DEL ESTUDIANTE
            // =====================================================
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
}
