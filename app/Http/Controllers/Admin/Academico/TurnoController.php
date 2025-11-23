<?php

namespace App\Http\Controllers\Admin\Academico;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turnos = Turno::orderBy('hora_inicio', 'asc')->get();
        return view('admin.turnos.index', compact('turnos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.turnos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'estado' => 'required|in:activo,inactivo',
            'descripcion' => 'nullable|string',
        ], [
            'hora_fin.after' => 'La hora fin debe ser posterior a la hora de inicio.',
        ]);

        $turno = new Turno();
        $turno->nombre = $request->nombre;
        $turno->hora_inicio = $request->hora_inicio;
        $turno->hora_fin = $request->hora_fin;
        $turno->estado = $request->estado;
        $turno->descripcion = $request->descripcion;
        $turno->save();

        return redirect()->route('admin.turnos.index')
            ->with('mensaje', 'Turno creado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Turno $turno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $turno = Turno::findOrFail($id);
        return view('admin.turnos.edit', compact('turno'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'estado' => 'required|in:activo,inactivo',
            'descripcion' => 'nullable|string',
        ], [
            'hora_fin.after' => 'La hora fin debe ser posterior a la hora de inicio.',
        ]);

        $turno = Turno::findOrFail($id);
        $turno->nombre = $request->nombre;
        $turno->hora_inicio = $request->hora_inicio;
        $turno->hora_fin = $request->hora_fin;
        $turno->estado = $request->estado;
        $turno->descripcion = $request->descripcion;
        $turno->save();

        return redirect()->route('admin.turnos.index')
            ->with('mensaje', 'Turno actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->delete();

        return redirect()->route('admin.turnos.index')
            ->with('mensaje', 'Turno eliminado correctamente')
            ->with('icono', 'success');
    }
}
