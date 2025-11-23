<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Taller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TallerController extends Controller
{
    public function index()
    {
        $talleres = Taller::latest()->get();
        return view('admin.talleres.index', compact('talleres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'instructor' => 'required|string|max:255',

            // DURACIÓN POR FECHAS
            'duracion_inicio' => 'required|date',
            'duracion_fin' => 'required|date|after_or_equal:duracion_inicio',

            // HORARIO (HORA INICIO → HORA FIN)
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',

            // CAMPOS EXTRA
            'categoria' => 'nullable|string|max:100',
            'costo' => 'nullable|numeric|min:0',
            'cupos_maximos' => 'required|integer|min:1',

            // IMAGEN
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except('activo');
        $data['activo'] = $request->has('activo') ? 1 : 0;

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('talleres', 'public');
        }

        Taller::create($data);

        return redirect()->route('admin.talleres.index')
            ->with('success', 'Taller creado exitosamente.');
    }

    public function update(Request $request, Taller $taller)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'instructor' => 'required|string|max:255',

            // NUEVA DURACIÓN
            'duracion_inicio' => 'required|date',
            'duracion_fin' => 'required|date|after_or_equal:duracion_inicio',

            // NUEVOS HORARIOS
            'horario_inicio' => 'required|date_format:H:i',
            'horario_fin' => 'required|date_format:H:i|after:horario_inicio',

            // EXTRA
            'categoria' => 'nullable|string|max:100',
            'costo' => 'nullable|numeric|min:0',
            'cupos_maximos' => 'required|integer|min:1',

            // IMAGEN
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except('activo');
        $data['activo'] = $request->has('activo') ? 1 : 0;

        if ($request->hasFile('imagen')) {
            if ($taller->imagen) {
                Storage::disk('public')->delete($taller->imagen);
            }

            $data['imagen'] = $request->file('imagen')->store('talleres', 'public');
        }

        $taller->update($data);

        return redirect()->route('admin.talleres.index')
            ->with('success', 'Taller actualizado exitosamente.');
    }

    public function destroy(Taller $taller)
    {
        if ($taller->imagen) {
            Storage::disk('public')->delete($taller->imagen);
        }

        $taller->delete();

        return redirect()->route('admin.talleres.index')
            ->with('success', 'Taller eliminado exitosamente.');
    }
}
