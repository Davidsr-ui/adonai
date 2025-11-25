<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Gestion;
use App\Models\Periodo;
use App\Models\Estudiante;
use App\Models\Asistencia;
use App\Models\Nota;
use App\Models\Comportamiento;
use App\Models\Reporte;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ CORRECCIÓN: Verificar sin redirigir
        $user = Auth::user();
        $persona = $user->persona;
        
        // Si no tiene persona vinculada, mostrar vista con mensaje
        if (!$persona) {
            return view('tutor.dashboard-sin-persona');
        }
        
        // Si tiene persona pero no es tutor, mostrar mensaje
        $tutor = $persona->tutor;
        if (!$tutor) {
            return view('tutor.dashboard-sin-tutor');
        }

        // Obtener gestiones
        $gestiones = Gestion::orderBy('año', 'desc')->get();
        $gestionActual = Gestion::where('estado', 'Activo')->first();

        // Obtener periodos
        $periodos = Periodo::orderBy('numero')->get();
        $periodoActual = Periodo::where('estado', 'Activo')->first();

        // Obtener estudiantes del tutor
        $estudiantes = $tutor->estudiantes()->with(['persona', 'grado'])->get();
        $estudiantesIds = $estudiantes->pluck('id');

        // Estadísticas de Reportes
        $reportes = Reporte::whereIn('estudiante_id', $estudiantesIds)
            ->where('visible_tutor', true)
            ->get();

        $totalReportes = $reportes->count();

        // ✅ Alertas (estudiantes que requieren atención)
        $alertas = [];
        
        foreach ($estudiantes as $estudiante) {
            // Verificar inasistencias
            $inasistenciasEstudiante = Asistencia::where('estudiante_id', $estudiante->id)
                ->where('estado', 'Ausente')
                ->where('fecha', '>=', Carbon::now()->subDays(30))
                ->count();
            
            if ($inasistenciasEstudiante >= 3) {
                $alertas[] = [
                    'tipo' => 'danger',
                    'icono' => 'fas fa-exclamation-triangle',
                    'mensaje' => "{$estudiante->persona->apellidos}, {$estudiante->persona->nombres} tiene {$inasistenciasEstudiante} inasistencias en el último mes"
                ];
            }

            // Verificar notas bajas
            $notasBajas = Nota::whereHas('matricula', function($q) use ($estudiante) {
                    $q->where('estudiante_id', $estudiante->id);
                })
                ->where('visible_tutor', true)
                ->where('nota_final', '<', 11)
                ->count();
            
            if ($notasBajas >= 2) {
                $alertas[] = [
                    'tipo' => 'warning',
                    'icono' => 'fas fa-exclamation-circle',
                    'mensaje' => "{$estudiante->persona->apellidos}, {$estudiante->persona->nombres} tiene {$notasBajas} notas desaprobadas"
                ];
            }

            // Verificar comportamientos negativos
            $comportamientosNegativosEstudiante = Comportamiento::where('estudiante_id', $estudiante->id)
                ->where('tipo', 'Negativo')
                ->where('notificado_tutor', true)
                ->where('fecha', '>=', Carbon::now()->subDays(30))
                ->count();
            
            if ($comportamientosNegativosEstudiante >= 3) {
                $alertas[] = [
                    'tipo' => 'danger',
                    'icono' => 'fas fa-frown',
                    'mensaje' => "{$estudiante->persona->apellidos}, {$estudiante->persona->nombres} tiene {$comportamientosNegativosEstudiante} comportamientos negativos este mes"
                ];
            }
        }

        // Últimas notificaciones
        $ultimosComportamientos = Comportamiento::whereIn('estudiante_id', $estudiantesIds)
            ->where('notificado_tutor', true)
            ->with(['estudiante.persona', 'curso', 'docente.persona'])
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        $ultimosReportes = Reporte::whereIn('estudiante_id', $estudiantesIds)
            ->where('visible_tutor', true)
            ->with(['estudiante.persona', 'curso', 'periodo'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('tutor.dashboard', compact(
            'gestiones',
            'gestionActual',
            'periodos',
            'periodoActual',
            'estudiantes',
            'totalReportes',
            'alertas',
            'ultimosComportamientos',
            'ultimosReportes'
        ));
    }
}