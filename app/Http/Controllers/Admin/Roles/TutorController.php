<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TutorController extends Controller
{
    public function index()
    {
        $tutores = Tutor::with(['persona', 'estudiantes'])->get();
        return view('admin.tutores.index', compact('tutores'));
    }

    public function create()
    {
        return redirect()->route('admin.tutores.index');
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
            'codigo_tutor_create' => 'nullable|max:50|unique:tutores,codigo_tutor',
            'ocupacion_create' => 'nullable|max:100',
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

            // ✅ CREAR USUARIO PARA EL TUTOR (si no existe)
            $persona->crearUsuarioSiNoExiste('tutor', $request->dni_create);

            $tutor = new Tutor();
            $tutor->persona_id = $persona->id;
            $tutor->codigo_tutor = $request->codigo_tutor_create;
            $tutor->ocupacion = $request->ocupacion_create;
            $tutor->save();

            DB::commit();

            return redirect()->route('admin.tutores.index')
                ->with('mensaje', 'Tutor creado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.tutores.index')
                ->with('mensaje', 'Error al crear el tutor: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    public function show($id)
    {
        $tutor = Tutor::findOrFail($id);
        $tutor->load(['persona', 'estudiantes.persona']);
        return view('admin.tutores.show', compact('tutor'));
    }

    public function edit($id)
    {
        return redirect()->route('admin.tutores.index');
    }

    public function update(Request $request, $id)
    {
        $tutor = Tutor::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'dni' => 'required|max:20|unique:personas,dni,' . $tutor->persona_id,
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:M,F,Otro',
            'direccion' => 'nullable|max:255',
            'telefono' => 'nullable|max:20',
            'telefono_emergencia' => 'nullable|max:20',
            'estado' => 'required|in:Activo,Inactivo',
            'codigo_tutor' => 'nullable|max:50|unique:tutores,codigo_tutor,' . $tutor->id,
            'ocupacion' => 'nullable|max:100',
            'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $tutor->id);
        }

        DB::beginTransaction();
        try {
            if (!$tutor->persona) {
                throw new \Exception('El tutor no tiene una persona asociada');
            }

            $persona = $tutor->persona;
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

            // ✅ Si la persona no tiene usuario, créalo (por si acaso)
            if (!$persona->user_id) {
                $persona->crearUsuarioSiNoExiste('tutor', $request->dni);
            }

            $tutor->codigo_tutor = $request->codigo_tutor;
            $tutor->ocupacion = $request->ocupacion;
            $tutor->save();

            DB::commit();

            return redirect()->route('admin.tutores.index')
                ->with('mensaje', 'Tutor actualizado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.tutores.index')
                ->with('mensaje', 'Error al actualizar el tutor: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    public function destroy($id)
    {
        $tutor = Tutor::with('persona')->findOrFail($id);

        DB::beginTransaction();
        try {
            $persona = $tutor->persona;

            if ($persona && $persona->foto_perfil) {
                $rutaFoto = storage_path('app/public/' . $persona->foto_perfil);
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }

            $tutor->delete();

            if ($persona) {
                $persona->delete();
            }

            DB::commit();

            return redirect()->route('admin.tutores.index')
                ->with('mensaje', 'Tutor eliminado correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.tutores.index')
                ->with('mensaje', 'Error al eliminar el tutor: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}