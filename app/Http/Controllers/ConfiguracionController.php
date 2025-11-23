<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configuracion = Configuracion::first();
        return view('admin.configuracion.index', compact('configuracion'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'email' => 'required|email',
            'logo' => 'image|mimes:jpeg,png,jpg',
        ]);

        // Buscar si existe la configuración
        $configuracion = Configuracion::first();

        if (!$configuracion) {
            // Crear nueva
            $configuracion = new Configuracion();
        }

        // Actualizar campos
        $configuracion->nombre = $request->nombre;
        $configuracion->descripcion = $request->descripcion;
        $configuracion->direccion = $request->direccion;
        $configuracion->telefono = $request->telefono;
        $configuracion->email = $request->email;
        $configuracion->web = $request->web;

        // Manejo de logo
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($configuracion->logo && file_exists(public_path($configuracion->logo))) {
                unlink(public_path($configuracion->logo));
            }

            // Crear carpeta si no existe
            $rutaDestino = public_path('uploads/logos');
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0755, true);
            }

            // Guardar nuevo logo
            $logoFile = $request->file('logo');
            $nombreLogo = time() . '_' . $logoFile->getClientOriginalName();
            $logoFile->move($rutaDestino, $nombreLogo);
            $configuracion->logo = '/uploads/logos/' . $nombreLogo;
        }

        $configuracion->save();

        return redirect()->route('admin.configuracion.index')
        ->with('mensaje', 'Configuración guardada correctamente')
        ->with('icono', 'success');
    }
    
}
