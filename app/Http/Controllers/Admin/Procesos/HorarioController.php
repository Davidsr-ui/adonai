<?php

namespace App\Http\Controllers\Admin\Procesos;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\Gestion;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horarios = Horario::with(['gestion', 'curso', 'grado', 'docente'])
                           ->ordenado()
                           ->get();
        $gestiones = Gestion::orderBy('año', 'desc')->get();
        $cursos = Curso::where('estado', 'Activo')->orderBy('nombre')->get();
        $grados = Grado::where('estado', 'Activo')->orderBy('nombre')->get();
        $docentes = Docente::select('docentes.*')
            ->join('personas', 'personas.id', '=', 'docentes.persona_id')
            ->where('personas.estado', 'Activo')
            ->orderBy('personas.apellidos', 'asc')
            ->get();
        
        return view('admin.horarios.index', compact('horarios', 'gestiones', 'cursos', 'grados', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.horarios.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ CORREGIDO: Quitado el _create de los nombres de campos
        $request->validate([
            'gestion_id' => 'required|exists:gestions,id',
            'curso_id' => 'required|exists:cursos,id',
            'grado_id' => 'required|exists:grados,id',
            'docente_id' => 'nullable|exists:docentes,id',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'aula' => 'nullable|max:20',
        ]);
        
        $horario = new Horario();
        $horario->gestion_id = $request->gestion_id;
        $horario->curso_id = $request->curso_id;
        $horario->grado_id = $request->grado_id;
        $horario->docente_id = $request->docente_id;
        $horario->dia_semana = $request->dia_semana;
        $horario->hora_inicio = $request->hora_inicio;
        $horario->hora_fin = $request->hora_fin;
        $horario->aula = $request->aula;
        $horario->save();
        
        return redirect()->route('admin.horarios.index')
            ->with('mensaje', 'Horario creado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        $horario->load(['gestion', 'curso', 'grado', 'docente']);
        return view('admin.horarios.show', compact('horario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        // Vista manejada en el modal del index
        return redirect()->route('admin.horarios.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        $validate = Validator::make($request->all(), [
            'gestion_id' => 'required|exists:gestions,id',
            'curso_id' => 'required|exists:cursos,id',
            'grado_id' => 'required|exists:grados,id',
            'docente_id' => 'nullable|exists:docentes,id',
            'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'aula' => 'nullable|max:20',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $horario->id);
        }

        $horario->gestion_id = $request->gestion_id;
        $horario->curso_id = $request->curso_id;
        $horario->grado_id = $request->grado_id;
        $horario->docente_id = $request->docente_id;
        $horario->dia_semana = $request->dia_semana;
        $horario->hora_inicio = $request->hora_inicio;
        $horario->hora_fin = $request->hora_fin;
        $horario->aula = $request->aula;
        $horario->save();

        return redirect()->route('admin.horarios.index')
            ->with('mensaje', 'Horario actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();

        return redirect()->route('admin.horarios.index')
            ->with('mensaje', 'Horario eliminado correctamente')
            ->with('icono', 'success');
    }
}