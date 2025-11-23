<?php

namespace App\Http\Controllers\Admin\Registros;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Periodo;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reporte::with(['estudiante.persona', 'docente.persona', 'periodo', 'gestion']);
        
        // Filtros
        if ($request->has('estudiante_id') && $request->estudiante_id) {
            $query->where('estudiante_id', $request->estudiante_id);
        }
        
        if ($request->has('periodo_id') && $request->periodo_id) {
            $query->where('periodo_id', $request->periodo_id);
        }
        
        if ($request->has('gestion_id') && $request->gestion_id) {
            $query->where('gestion_id', $request->gestion_id);
        }
        
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->has('visible') && $request->visible !== '') {
            $query->where('visible_tutor', $request->visible);
        }
        
        $reportes = $query->orderBy('fecha_generacion', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->get();
        
        $estudiantes = Estudiante::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
        $docentes = Docente::with('persona')->whereHas('persona', function($query) {
            $query->where('estado', 'Activo');
        })->get();
        
          $periodos = Periodo::join('gestions', 'periodos.gestion_id', '=', 'gestions.id')
        ->select('periodos.*', 'gestions.año as gestion_año') // opcional para mostrar el año
        ->orderBy('gestions.año', 'desc')
        ->orderBy('periodos.numero', 'desc')
        ->get();
        $gestiones = Gestion::orderBy('año', 'desc')->get();
        
        return view('admin.reportes.index', compact('reportes', 'estudiantes', 'docentes', 'periodos', 'gestiones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.reportes.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'docente_id_create' => 'required|exists:docentes,id',
            'periodo_id_create' => 'required|exists:periodos,id',
            'gestion_id_create' => 'required|exists:gestions,id',
            'tipo_create' => 'required|in:Bimestral,Trimestral,Anual',
            'promedio_general_create' => 'nullable|numeric|min:0|max:20',
            'porcentaje_asistencia_create' => 'nullable|numeric|min:0|max:100',
            'comentario_final_create' => 'nullable|string|max:2000',
            'archivo_pdf_create' => 'nullable|file|mimes:pdf|max:5120',
            'visible_tutor_create' => 'nullable|boolean',
        ], [
            'estudiante_id_create.required' => 'El estudiante es obligatorio.',
            'docente_id_create.required' => 'El docente es obligatorio.',
            'periodo_id_create.required' => 'El periodo es obligatorio.',
            'gestion_id_create.required' => 'La gestión es obligatoria.',
            'tipo_create.required' => 'El tipo es obligatorio.',
            'promedio_general_create.numeric' => 'El promedio debe ser un número.',
            'promedio_general_create.max' => 'El promedio no puede ser mayor a 20.',
            'porcentaje_asistencia_create.numeric' => 'El porcentaje debe ser un número.',
            'porcentaje_asistencia_create.max' => 'El porcentaje no puede ser mayor a 100.',
            'archivo_pdf_create.mimes' => 'El archivo debe ser un PDF.',
            'archivo_pdf_create.max' => 'El archivo no puede superar los 5MB.',
        ]);
        
        try {
            DB::beginTransaction();
            
            $reporte = new Reporte();
            $reporte->estudiante_id = $request->estudiante_id_create;
            $reporte->docente_id = $request->docente_id_create;
            $reporte->periodo_id = $request->periodo_id_create;
            $reporte->gestion_id = $request->gestion_id_create;
            $reporte->tipo = $request->tipo_create;
            $reporte->promedio_general = $request->promedio_general_create;
            $reporte->porcentaje_asistencia = $request->porcentaje_asistencia_create;
            $reporte->comentario_final = $request->comentario_final_create;
            $reporte->visible_tutor = $request->has('visible_tutor_create') ? true : false;
            $reporte->fecha_generacion = now();
            
            // Si marca visible, publicar automáticamente
            if ($reporte->visible_tutor) {
                $reporte->fecha_publicacion = now();
            }
            
            // Manejar archivo PDF
            if ($request->hasFile('archivo_pdf_create')) {
                $file = $request->file('archivo_pdf_create');
                $filename = 'reporte_' . time() . '_' . $reporte->estudiante_id . '.pdf';
                $path = $file->storeAs('reportes', $filename, 'public');
                $reporte->archivo_pdf = $path;
            }
            
            $reporte->save();
            
            DB::commit();
            
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Reporte registrado correctamente')
                ->with('icono', 'success');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Error al registrar el reporte: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reporte = Reporte::with(['estudiante.persona', 'estudiante.grado', 'docente.persona', 'periodo', 'gestion'])->findOrFail($id);
        
        // Obtener notas del periodo
        $notas = \App\Models\Nota::whereHas('matricula', function($query) use ($reporte) {
            $query->where('estudiante_id', $reporte->estudiante_id);
        })
        ->where('periodo_id', $reporte->periodo_id)
        ->with('matricula.curso')
        ->get();
        
        // Obtener asistencias del periodo
        $asistencias = \App\Models\Asistencia::where('estudiante_id', $reporte->estudiante_id)
            ->get();
        
        // Obtener comportamientos del periodo
        $comportamientos = \App\Models\Comportamiento::where('estudiante_id', $reporte->estudiante_id)
            ->whereBetween('fecha', [
                $reporte->periodo->fecha_inicio ?? now()->startOfYear(),
                $reporte->periodo->fecha_fin ?? now()->endOfYear()
            ])
            ->orderBy('fecha', 'desc')
            ->get();
        
        return view('admin.reportes.show', compact('reporte', 'notas', 'asistencias', 'comportamientos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('admin.reportes.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $reporte = Reporte::findOrFail($id);

        $validate = Validator::make($request->all(), [
            'estudiante_id' => 'required|exists:estudiantes,id',
            'docente_id' => 'required|exists:docentes,id',
            'periodo_id' => 'required|exists:periodos,id',
            'gestion_id' => 'required|exists:gestions,id',
            'tipo' => 'required|in:Bimestral,Trimestral,Anual',
            'promedio_general' => 'nullable|numeric|min:0|max:20',
            'porcentaje_asistencia' => 'nullable|numeric|min:0|max:100',
            'comentario_final' => 'nullable|string|max:2000',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'visible_tutor' => 'nullable|boolean',
        ], [
            'estudiante_id.required' => 'El estudiante es obligatorio.',
            'docente_id.required' => 'El docente es obligatorio.',
            'periodo_id.required' => 'El periodo es obligatorio.',
            'gestion_id.required' => 'La gestión es obligatoria.',
            'tipo.required' => 'El tipo es obligatorio.',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput()
                ->with('modal_id', $id);
        }

        try {
            DB::beginTransaction();
            
            $reporte->estudiante_id = $request->estudiante_id;
            $reporte->docente_id = $request->docente_id;
            $reporte->periodo_id = $request->periodo_id;
            $reporte->gestion_id = $request->gestion_id;
            $reporte->tipo = $request->tipo;
            $reporte->promedio_general = $request->promedio_general;
            $reporte->porcentaje_asistencia = $request->porcentaje_asistencia;
            $reporte->comentario_final = $request->comentario_final;
            
            $visibleAntes = $reporte->visible_tutor;
            $reporte->visible_tutor = $request->has('visible_tutor') ? true : false;
            
            // Si marca visible y antes no lo estaba, publicar
            if ($reporte->visible_tutor && !$visibleAntes) {
                $reporte->fecha_publicacion = now();
            }
            
            // Si desmarca visible, despublicar
            if (!$reporte->visible_tutor && $visibleAntes) {
                $reporte->fecha_publicacion = null;
            }
            
            // Manejar archivo PDF
            if ($request->hasFile('archivo_pdf')) {
                // Eliminar archivo anterior si existe
                if ($reporte->archivo_pdf && Storage::disk('public')->exists($reporte->archivo_pdf)) {
                    Storage::disk('public')->delete($reporte->archivo_pdf);
                }
                
                $file = $request->file('archivo_pdf');
                $filename = 'reporte_' . time() . '_' . $reporte->estudiante_id . '.pdf';
                $path = $file->storeAs('reportes', $filename, 'public');
                $reporte->archivo_pdf = $path;
            }
            
            $reporte->save();
            
            DB::commit();

            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Reporte actualizado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Error al actualizar el reporte: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $reporte = Reporte::findOrFail($id);
            
            // Eliminar archivo PDF si existe
            if ($reporte->archivo_pdf && Storage::disk('public')->exists($reporte->archivo_pdf)) {
                Storage::disk('public')->delete($reporte->archivo_pdf);
            }
            
            $reporte->delete();
            
            DB::commit();

            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Reporte eliminado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Error al eliminar el reporte: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Publicar reporte
     */
    public function publicar($id)
    {
        try {
            $reporte = Reporte::findOrFail($id);
            $reporte->publicar();

            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Reporte publicado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Error al publicar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Despublicar reporte
     */
    public function despublicar($id)
    {
        try {
            $reporte = Reporte::findOrFail($id);
            $reporte->despublicar();

            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Reporte despublicado correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Error al despublicar: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Calcular datos automáticamente
     */
    public function calcularDatos($id)
    {
        try {
            $reporte = Reporte::findOrFail($id);
            $reporte->calcularDatos();

            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Datos calculados correctamente')
                ->with('icono', 'success');

        } catch (\Exception $e) {
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Error al calcular datos: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }

    /**
     * Descargar PDF
     */
    public function descargarPdf($id)
    {
        try {
            $reporte = Reporte::findOrFail($id);
            
            if (!$reporte->tienePdf()) {
                return redirect()->route('admin.reportes.index')
                    ->with('mensaje', 'El reporte no tiene un archivo PDF')
                    ->with('icono', 'warning');
            }
            
           

        } catch (\Exception $e) {
            return redirect()->route('admin.reportes.index')
                ->with('mensaje', 'Error al descargar PDF: ' . $e->getMessage())
                ->with('icono', 'error');
        }
    }
}
