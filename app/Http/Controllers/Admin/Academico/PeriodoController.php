<?php

namespace App\Http\Controllers\Admin\Academico;

use App\Http\Controllers\Controller;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Gestion;

class PeriodoController extends Controller
{
    public function index()
    {
        $gestiones = Gestion::with('periodos')->orderBy('año', 'desc')->get();
        return view('admin.periodos.index', compact('gestiones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gestion_id_create' => 'required|exists:gestions,id',
            'nombre_create' => 'required|max:50',
            'numero_create' => 'required|integer|min:1',
            'fecha_inicio_create' => 'required|date',
            'fecha_fin_create' => 'required|date|after:fecha_inicio_create',
            'estado_create' => 'required|in:Activo,Finalizado,Planificado',
        ], [
            'gestion_id_create.required' => 'La gestión es obligatoria',
            'gestion_id_create.exists' => 'La gestión seleccionada no existe',
            'numero_create.required' => 'El número de periodo es obligatorio',
            'fecha_fin_create.after' => 'La fecha fin debe ser posterior a la fecha de inicio',
        ]);

        // Verificar que no exista el mismo número para la misma gestión
        $existePeriodo = Periodo::where('gestion_id', $request->gestion_id_create)
            ->where('numero', $request->numero_create)
            ->exists();

        if ($existePeriodo) {
            return redirect()->back()
                ->withErrors(['numero_create' => 'Ya existe un periodo con ese número para esta gestión'])
                ->withInput()
                ->with('modal_open', 'create');
        }

        Periodo::create([
            'gestion_id' => $request->gestion_id_create,
            'nombre' => $request->nombre_create,
            'numero' => $request->numero_create,
            'fecha_inicio' => $request->fecha_inicio_create,
            'fecha_fin' => $request->fecha_fin_create,
            'estado' => $request->estado_create,
        ]);

        return redirect()->route('admin.periodos.index')
            ->with('mensaje', 'Periodo creado correctamente')
            ->with('icono', 'success');
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'gestion_id' => 'required|exists:gestions,id',
            'nombre' => 'required|max:50',
            'numero' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:Activo,Finalizado,Planificado',
        ], [
            'gestion_id.required' => 'La gestión es obligatoria',
            'gestion_id.exists' => 'La gestión seleccionada no existe',
            'numero.required' => 'El número de periodo es obligatorio',
            'fecha_fin.after' => 'La fecha fin debe ser posterior a la fecha de inicio',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        // Verificar que no exista el mismo número para la misma gestión (excluyendo el actual)
        $existePeriodo = Periodo::where('gestion_id', $request->gestion_id)
            ->where('numero', $request->numero)
            ->where('id', '!=', $id)
            ->exists();

        if ($existePeriodo) {
            return redirect()->back()
                ->withErrors(['numero' => 'Ya existe un periodo con ese número para esta gestión'])
                ->withInput()
                ->with('modal_id', $id);
        }

        $periodo = Periodo::findOrFail($id);
        $periodo->update([
            'gestion_id' => $request->gestion_id,
            'nombre' => $request->nombre,
            'numero' => $request->numero,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.periodos.index')
            ->with('mensaje', 'Periodo actualizado correctamente')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $periodo = Periodo::findOrFail($id);
        $periodo->delete();

        return redirect()->route('admin.periodos.index')
            ->with('mensaje', 'Periodo eliminado correctamente')
            ->with('icono', 'success');
    }
}