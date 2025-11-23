<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docentes = Docente::with('persona')->get();
        return view('admin.docentes.index', compact('docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.docentes.index');
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
            'codigo_docente_create' => 'required|max:50|unique:docentes,codigo_docente',
            'especialidad_create' => 'nullable|max:100',
            'fecha_contratacion_create' => 'required|date',
            'tipo_contrato_create' => 'required|in:Nombrado,Contratado,Temporal',

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

            // Crear docente
            $docente = new Docente();
            $docente->persona_id = $persona->id;
            $docente->codigo_docente = $request->codigo_docente_create;
            $docente->especialidad = $request->especialidad_create;
            $docente->fecha_contratacion = $request->fecha_contratacion_create;
            $docente->tipo_contrato = $request->tipo_contrato_create;
            $docente->save();

            DB::commit();

            return redirect()->route('admin.docentes.index')
                ->with('mensaje', 'Docente creado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.docentes.index')
                ->with('mensaje', 'Error al crear el docente')
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Docente $docente)
    {
        $docente->load('persona');
        return view('admin.docentes.show', compact('docente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Docente $docente)
    {
        return redirect()->route('admin.docentes.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Docente $docente)
    {
        $validate = Validator::make($request->all(), [
            'dni' => 'required|max:20|unique:personas,dni,' . $docente->persona_id,
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:M,F,Otro',
            'direccion' => 'nullable|max:255',
            'telefono' => 'nullable|max:20',
            'telefono_emergencia' => 'nullable|max:20',
            'estado' => 'required|in:Activo,Inactivo',
            'codigo_docente' => 'required|max:50|unique:docentes,codigo_docente,' . $docente->id,
            'especialidad' => 'nullable|max:100',
            'fecha_contratacion' => 'required|date',
            'tipo_contrato' => 'required|in:Nombrado,Contratado,Temporal',

            // FOTO DE PERFIL
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $docente->id);
        }

        DB::beginTransaction();
        try {
            // Actualizar persona
            $persona = $docente->persona;
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

            // Actualizar docente
            $docente->codigo_docente = $request->codigo_docente;
            $docente->especialidad = $request->especialidad;
            $docente->fecha_contratacion = $request->fecha_contratacion;
            $docente->tipo_contrato = $request->tipo_contrato;
            $docente->save();

            DB::commit();

            return redirect()->route('admin.docentes.index')
                ->with('mensaje', 'Docente actualizado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.docentes.index')
                ->with('mensaje', 'Error al actualizar el docente')
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Docente $docente)
    {
        DB::beginTransaction();
        try {
            $persona = $docente->persona;

            // ===========================================
            // 1. ELIMINAR FOTO DE PERFIL DEL STORAGE
            // ===========================================
            if ($persona && $persona->foto_perfil) {
                $ruta = storage_path('app/public/' . $persona->foto_perfil);

                if (file_exists($ruta)) {
                    unlink($ruta);
                }
            }

            // ===========================================
            // 2. ELIMINAR PERSONA (soft delete)
            // ===========================================
            if ($persona) {
                $persona->delete();
            }

            // ===========================================
            // 3. ELIMINAR DOCENTE (VERY IMPORTANT)
            // ===========================================
            $docente->delete();

            DB::commit();

            return redirect()->route('admin.docentes.index')
                ->with('mensaje', 'Docente eliminado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.docentes.index')
                ->with('mensaje', 'Error al eliminar el docente')
                ->with('icono', 'error');
        }
    }
}
