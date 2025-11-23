<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Periodo;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    /**
     * Mostrar listado de reportes del docente
     */
    public function index(Request $request)
    {
        // Verificar perfil de docente
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'Tu perfil de docente no está completo')
                ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        // Obtener cursos del docente
        $cursos = $docente->cursos()->get();
        $cursosIds = $cursos->pluck('id');

        // Obtener estudiantes matriculados en los cursos del docente
        $estudiantes = Estudiante::whereHas('matriculas', function ($query) use ($cursosIds) {
            $query->whereIn('curso_id', $cursosIds)
                  ->where('estado', 'Matriculado');
        })->with('persona')->get();

        // Obtener periodos
        $periodos = Periodo::orderBy('numero')->get();

        // Obtener gestiones
        $gestiones = Gestion::orderBy('año', 'desc')->get();

        // Obtener solo docente autenticado
        $docentes = Docente::where('id', $docente->id)->with('persona')->get();

        // Filtrar reportes del docente
        $query = Reporte::where('docente_id', $docente->id)
            ->with(['estudiante.persona', 'periodo', 'gestion', 'docente.persona']);

        // Aplicar filtros
        if ($request->filled('estudiante_id')) {
            $query->where('estudiante_id', $request->estudiante_id);
        }

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        if ($request->filled('gestion_id')) {
            $query->where('gestion_id', $request->gestion_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('visible')) {
            $query->where('visible_tutor', $request->visible === '1');
        }

        $reportes = $query->orderBy('created_at', 'desc')->get();

        // Reutilizar la vista de admin (ya adaptada con $routePrefix)
        return view('admin.reportes.index', compact(
            'reportes',
            'estudiantes',
            'periodos',
            'gestiones',
            'docentes'
        ));
    }

    /**
     * Guardar nuevo reporte
     */
    public function store(Request $request)
    {
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        // Validación con los campos REALES de la vista
        $request->validate([
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'docente_id_create' => 'required|exists:docentes,id',
            'tipo_create' => 'required|in:Bimestral,Trimestral,Anual',
            'periodo_id_create' => 'required|exists:periodos,id',
            'gestion_id_create' => 'required|exists:gestions,id',
            'promedio_general_create' => 'nullable|numeric|min:0|max:20',
            'porcentaje_asistencia_create' => 'nullable|numeric|min:0|max:100',
            'comentario_final_create' => 'nullable|string|max:2000',
            'archivo_pdf_create' => 'nullable|file|mimes:pdf|max:5120', // 5MB
        ]);

        // Verificar que el docente seleccionado sea el mismo autenticado
        if ($request->docente_id_create != $docente->id) {
            return back()->with('mensaje', 'No puedes crear reportes para otro docente')
                        ->with('icono', 'error');
        }

        // Preparar datos
        $data = [
            'estudiante_id' => $request->estudiante_id_create,
            'docente_id' => $request->docente_id_create,
            'tipo' => $request->tipo_create,
            'periodo_id' => $request->periodo_id_create,
            'gestion_id' => $request->gestion_id_create,
            'promedio_general' => $request->promedio_general_create,
            'porcentaje_asistencia' => $request->porcentaje_asistencia_create,
            'comentario_final' => $request->comentario_final_create,
            'visible_tutor' => $request->has('visible_tutor_create'),
            'fecha_generacion' => now(),
        ];

        // Manejar archivo PDF si se sube
        if ($request->hasFile('archivo_pdf_create')) {
            $archivo = $request->file('archivo_pdf_create');
            $nombreArchivo = 'reporte_' . time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('reportes', $nombreArchivo, 'public');
            $data['archivo_pdf'] = $ruta;
        }

        Reporte::create($data);

        return redirect()->route('docente.reportes.index')
            ->with('mensaje', 'Reporte creado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Actualizar reporte
     */
    public function update(Request $request, $id)
    {
        $reporte = Reporte::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes modificar este reporte')
                        ->with('icono', 'error');
        }

        // Validación con los campos REALES de la vista (SIN sufijo _create)
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'docente_id' => 'required|exists:docentes,id',
            'tipo' => 'required|in:Bimestral,Trimestral,Anual',
            'periodo_id' => 'required|exists:periodos,id',
            'gestion_id' => 'required|exists:gestions,id',
            'promedio_general' => 'nullable|numeric|min:0|max:20',
            'porcentaje_asistencia' => 'nullable|numeric|min:0|max:100',
            'comentario_final' => 'nullable|string|max:2000',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // Preparar datos
        $data = [
            'estudiante_id' => $request->estudiante_id,
            'docente_id' => $request->docente_id,
            'tipo' => $request->tipo,
            'periodo_id' => $request->periodo_id,
            'gestion_id' => $request->gestion_id,
            'promedio_general' => $request->promedio_general,
            'porcentaje_asistencia' => $request->porcentaje_asistencia,
            'comentario_final' => $request->comentario_final,
            'visible_tutor' => $request->has('visible_tutor'),
        ];

        // Manejar archivo PDF si se sube uno nuevo
        if ($request->hasFile('archivo_pdf')) {
            // Eliminar archivo anterior si existe
            if ($reporte->archivo_pdf && Storage::disk('public')->exists($reporte->archivo_pdf)) {
                Storage::disk('public')->delete($reporte->archivo_pdf);
            }

            // Guardar nuevo archivo
            $archivo = $request->file('archivo_pdf');
            $nombreArchivo = 'reporte_' . time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('reportes', $nombreArchivo, 'public');
            $data['archivo_pdf'] = $ruta;
        }

        $reporte->update($data);

        return redirect()->route('docente.reportes.index')
            ->with('mensaje', 'Reporte actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar reporte
     */
    public function destroy($id)
    {
        $reporte = Reporte::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes eliminar este reporte')
                        ->with('icono', 'error');
        }

        // Eliminar archivo PDF si existe
        if ($reporte->archivo_pdf && Storage::disk('public')->exists($reporte->archivo_pdf)) {
            Storage::disk('public')->delete($reporte->archivo_pdf);
        }

        $reporte->delete();

        return redirect()->route('docente.reportes.index')
            ->with('mensaje', 'Reporte eliminado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Ver detalle - CORREGIDO: Usa vista de docente
     */
    public function show($id)
    {
        $reporte = Reporte::with([
            'estudiante.persona',
            'estudiante.grado.nivel',
            'periodo',
            'gestion',
            'docente.persona'
        ])->findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes ver este reporte')
                        ->with('icono', 'error');
        }

        // Obtener IDs de matrículas del estudiante en el periodo del reporte
        $matriculasIds = \DB::table('matriculas')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->where('gestion_id', $reporte->gestion_id)
            ->pluck('id');

        // Obtener notas del periodo
        $notas = \DB::table('notas as n')
            ->join('matriculas as m', 'n.matricula_id', '=', 'm.id')
            ->join('cursos as c', 'm.curso_id', '=', 'c.id')
            ->where('n.periodo_id', $reporte->periodo_id)
            ->whereIn('n.matricula_id', $matriculasIds)
            ->select(
                'n.*',
                'c.nombre as curso_nombre',
                'm.curso_id'
            )
            ->get()
            ->map(function($nota) {
                // Agregar propiedades computadas que la vista espera
                $nota->tipo_evaluacion_badge = match($nota->tipo_evaluacion) {
                    'Parcial' => 'info',
                    'Final' => 'primary',
                    'Práctica' => 'success',
                    'Oral' => 'warning',
                    'Trabajo' => 'secondary',
                    default => 'secondary'
                };
                
                $nota->estado_nota_badge = $nota->nota_final >= 11 ? 'success' : 'danger';
                $nota->estado_nota_texto = $nota->nota_final >= 11 ? 'Aprobado' : 'Desaprobado';
                
                // Crear objeto matricula para compatibilidad con la vista
                $nota->matricula = (object)[
                    'curso' => (object)['nombre' => $nota->curso_nombre]
                ];
                
                return $nota;
            });

        // Obtener asistencias del estudiante en el periodo
        $asistencias = \DB::table('asistencias')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->whereIn('curso_id', function($query) use ($matriculasIds) {
                $query->select('curso_id')
                      ->from('matriculas')
                      ->whereIn('id', $matriculasIds);
            })
            ->get();

        // Obtener comportamientos del periodo
        $comportamientos = \DB::table('comportamientos')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->where('docente_id', $docente->id)
            ->whereBetween('fecha', [
                $reporte->periodo->fecha_inicio,
                $reporte->periodo->fecha_fin
            ])
            ->get()
            ->map(function($comp) {
                // Agregar propiedades computadas que la vista espera
                $comp->tipo_badge = match($comp->tipo) {
                    'Positivo' => 'success',
                    'Negativo' => 'danger',
                    'Neutro' => 'secondary',
                    default => 'secondary'
                };
                
                $comp->tipo_icon = match($comp->tipo) {
                    'Positivo' => 'fa-thumbs-up',
                    'Negativo' => 'fa-thumbs-down',
                    'Neutro' => 'fa-meh',
                    default => 'fa-circle'
                };
                
                $comp->fecha_formateada = \Carbon\Carbon::parse($comp->fecha)->format('d/m/Y');
                
                return $comp;
            });

        // ✅ CAMBIO: Usar vista de docente en lugar de admin
        return view('docente.reportes.show', compact('reporte', 'notas', 'asistencias', 'comportamientos'));
    }

    /**
     * Publicar reporte
     */
    public function publicar($id)
    {
        $reporte = Reporte::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes publicar este reporte')
                        ->with('icono', 'error');
        }

        $reporte->update([
            'visible_tutor' => true,
            'fecha_generacion' => now(),
        ]);

        return back()->with('mensaje', 'Reporte publicado para tutores')
                    ->with('icono', 'success');
    }

    /**
     * Despublicar reporte
     */
    public function despublicar($id)
    {
        $reporte = Reporte::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes despublicar este reporte')
                        ->with('icono', 'error');
        }

        $reporte->update([
            'visible_tutor' => false,
        ]);

        return back()->with('mensaje', 'Reporte despublicado')
                    ->with('icono', 'success');
    }

    /**
     * Descargar PDF del reporte
     */
    public function descargarPdf($id)
    {
        $reporte = Reporte::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes descargar este reporte')
                        ->with('icono', 'error');
        }

        if (!$reporte->archivo_pdf || !Storage::disk('public')->exists($reporte->archivo_pdf)) {
            return back()->with('mensaje', 'El archivo PDF no existe')
                        ->with('icono', 'error');
        }

        return Storage::disk('public')->download($reporte->archivo_pdf);
    }

    /**
     * Calcular datos automáticamente
     */
    public function calcularDatos($id)
    {
        $reporte = Reporte::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes calcular datos de este reporte')
                        ->with('icono', 'error');
        }

        // Calcular promedio de notas
        $matriculasIds = \DB::table('matriculas')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->where('gestion_id', $reporte->gestion_id)
            ->pluck('id');

        $promedioNotas = \DB::table('notas')
            ->where('periodo_id', $reporte->periodo_id)
            ->whereIn('matricula_id', $matriculasIds)
            ->avg('nota_final');

        // Calcular porcentaje de asistencia
        $totalAsistencias = \DB::table('asistencias')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->whereIn('curso_id', function($query) use ($matriculasIds) {
                $query->select('curso_id')
                      ->from('matriculas')
                      ->whereIn('id', $matriculasIds);
            })
            ->count();

        $asistenciasPresentes = \DB::table('asistencias')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->where('estado', 'Presente')
            ->whereIn('curso_id', function($query) use ($matriculasIds) {
                $query->select('curso_id')
                      ->from('matriculas')
                      ->whereIn('id', $matriculasIds);
            })
            ->count();

        $porcentajeAsistencia = $totalAsistencias > 0 
            ? round(($asistenciasPresentes / $totalAsistencias) * 100, 2) 
            : 0;

        $reporte->update([
            'promedio_general' => round($promedioNotas, 2),
            'porcentaje_asistencia' => $porcentajeAsistencia,
        ]);

        return back()->with('mensaje', 'Datos calculados correctamente')
                    ->with('icono', 'success');
    }
}