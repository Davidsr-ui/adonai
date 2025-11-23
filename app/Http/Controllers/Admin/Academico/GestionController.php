<?php

namespace App\Http\Controllers\Admin\Academico;

use App\Http\Controllers\Controller;
use App\Models\Gestion;
use Illuminate\Http\Request;

class GestionController extends Controller
{
    public function index()
    {
        $gestiones = Gestion::orderBy('año', 'desc')->get();
        return view('admin.gestiones.index', compact('gestiones'));
    }

    public function create()
    {
        return view('admin.gestiones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'año' => 'nullable|integer|min:2000|max:2100',
            'nombre' => 'required|max:100|unique:gestions',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:Activo,Finalizado,Planificado',
        ], [
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
            'nombre.unique' => 'Ya existe una gestión con ese nombre',
        ]);

        Gestion::create([
            'año' => $request->año,
            'nombre' => $request->nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.gestiones.index')
            ->with('mensaje', 'Gestión creada correctamente')
            ->with('icono', 'success');
    }

    // ✅ ELIMINADO: No necesitas el método show() si no lo usas

    public function edit($id)
    {
        $gestion = Gestion::findOrFail($id);
        return view('admin.gestiones.edit', compact('gestion'));
    }

    public function update(Request $request, $id)
    {
        $gestion = Gestion::findOrFail($id);
        
        $request->validate([
            'año' => 'nullable|integer|min:2000|max:2100',
            'nombre' => 'required|max:100|unique:gestions,nombre,' . $gestion->id,
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:Activo,Finalizado,Planificado',
        ], [
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio',
        ]);

        $gestion->update([
            'año' => $request->año,
            'nombre' => $request->nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.gestiones.index')
            ->with('mensaje', 'Gestión actualizada correctamente')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        try {
            $gestion = Gestion::findOrFail($id);
            $gestion->delete();

            return redirect()->route('admin.gestiones.index')
                ->with('mensaje', 'Gestión eliminada correctamente')
                ->with('icono', 'success');
        } catch (\Exception $e) {
            return redirect()->route('admin.gestiones.index')
                ->with('mensaje', 'Error al eliminar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}