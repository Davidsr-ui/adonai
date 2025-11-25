<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use App\Models\Tutor;
use App\Models\Persona;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReporteController extends Controller
{
    /**
     * Obtener tutor autenticado
     */
    private function getTutor()
    {
        $user = Auth::user();
        $persona = Persona::where('user_id', $user->id)->first();
        
        if (!$persona || !$persona->tutor) {
            abort(403, 'Tu perfil de tutor no está completo');
        }
        
        return $persona->tutor;
    }

    /**
     * Listar reportes con filtro por estudiante
     */
    public function index(Request $request)
    {
        $tutor = $this->getTutor();
        
        // Cargar estudiantes del tutor
        $tutor->load(['estudiantes.persona', 'estudiantes.grado.nivel']);
        
        // Obtener estudiante seleccionado (o el primero por defecto)
        $estudiante = $tutor->estudiantes->first();
        $estudiante_id = $request->get('estudiante_id', $estudiante?->id);
        
        if ($estudiante_id) {
            $estudiante = Estudiante::with(['grado.nivel', 'persona'])->find($estudiante_id);
        }
        
        // Obtener reportes del estudiante seleccionado
        $reportes = collect();
        $bimestrales = 0;
        $trimestrales = 0;
        $anuales = 0;
        $totalReportes = 0;
        
        if ($estudiante) {
            $reportes = Reporte::where('estudiante_id', $estudiante->id)
                ->where('visible_tutor', true)
                ->with(['estudiante.persona', 'estudiante.grado', 'periodo', 'gestion', 'docente.persona'])
                ->orderBy('fecha_generacion', 'desc')
                ->get();
            
            // Calcular estadísticas
            $totalReportes = $reportes->count();
            $bimestrales = $reportes->where('tipo', 'Bimestral')->count();
            $trimestrales = $reportes->where('tipo', 'Trimestral')->count();
            $anuales = $reportes->where('tipo', 'Anual')->count();
        }
        
        return view('tutor.reportes.index', compact(
            'tutor',
            'estudiante',
            'reportes',
            'bimestrales',
            'trimestrales',
            'anuales',
            'totalReportes'
        ));
    }

    /**
     * ✅ VER DETALLE COMPLETO DEL REPORTE
     * Muestra: Notas, Asistencias, Comportamientos (TODO)
     */
    public function show($id)
    {
        $tutor = $this->getTutor();
        
        // Obtener reporte con relaciones
        $reporte = Reporte::with([
            'estudiante.persona',
            'estudiante.grado.nivel',
            'periodo',
            'gestion',
            'docente.persona'
        ])->findOrFail($id);

        // ✅ CORRECCIÓN: Sin paréntesis (colección)
        $estudiantesIds = $tutor->estudiantes->pluck('id');
        
        if (!$estudiantesIds->contains($reporte->estudiante_id)) {
            abort(403, 'No tienes permiso para ver este reporte');
        }

        // Verificar que el reporte esté publicado
        if (!$reporte->visible_tutor) {
            abort(403, 'Este reporte no está disponible');
        }

        // ✅ OBTENER TODAS LAS MATRÍCULAS DEL ESTUDIANTE EN LA GESTIÓN
        $matriculasIds = DB::table('matriculas')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->where('gestion_id', $reporte->gestion_id)
            ->pluck('id');

        // ✅ OBTENER TODAS LAS NOTAS DEL PERIODO (sin filtrar por docente)
        $notas = DB::table('notas as n')
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
                // Agregar propiedades computadas
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
                
                // Crear objeto matricula para compatibilidad
                $nota->matricula = (object)[
                    'curso' => (object)['nombre' => $nota->curso_nombre]
                ];
                
                return $nota;
            });

        // ✅ OBTENER TODAS LAS ASISTENCIAS (sin filtrar por docente)
        $asistencias = DB::table('asistencias')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->whereIn('curso_id', function($query) use ($matriculasIds) {
                $query->select('curso_id')
                      ->from('matriculas')
                      ->whereIn('id', $matriculasIds);
            })
            ->get();

        // ✅ OBTENER TODOS LOS COMPORTAMIENTOS DEL PERIODO (sin filtrar por docente)
        $comportamientos = DB::table('comportamientos')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->whereBetween('fecha', [
                $reporte->periodo->fecha_inicio,
                $reporte->periodo->fecha_fin
            ])
            ->get()
            ->map(function($comp) {
                // Agregar propiedades computadas
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

        return view('tutor.reportes.show', compact('reporte', 'notas', 'asistencias', 'comportamientos'));
    }

    /**
     * Descargar PDF del reporte
     */
    public function descargarPdf($id)
    {
        $tutor = $this->getTutor();
        
        $reporte = Reporte::findOrFail($id);

        // ✅ CORRECCIÓN: Sin paréntesis (colección)
        $estudiantesIds = $tutor->estudiantes->pluck('id');
        
        if (!$estudiantesIds->contains($reporte->estudiante_id)) {
            abort(403, 'No tienes permiso para descargar este reporte');
        }

        if (!$reporte->visible_tutor) {
            abort(403, 'Este reporte no está disponible');
        }

        if (!$reporte->archivo_pdf || !Storage::disk('public')->exists($reporte->archivo_pdf)) {
            return back()->with('mensaje', 'El archivo PDF no existe')
                        ->with('icono', 'error');
        }

        return Storage::disk('public')->download($reporte->archivo_pdf);
    }
}