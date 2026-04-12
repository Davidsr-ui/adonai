<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MatriculaImport;

class ImportController extends Controller
{
    /**
     * Muestra la vista con el botón de subir Excel
     */
    public function index()
    {
        return view('admin.importar.index');
    }

    /**
     * Procesa el archivo subido
     */
    public function importar(Request $request)
    {
        // Validamos que sea un archivo Excel válido
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // Ejecutamos la importación
            Excel::import(new MatriculaImport, $request->file('archivo_excel'));
            
            return redirect()->back()
                ->with('mensaje', '¡Importación masiva completada con éxito!')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            // Si algo falla (ej: formato de fecha, duplicados raros), avisamos
            return redirect()->back()
                ->with('mensaje', 'Error en la importación: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}