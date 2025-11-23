<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Gestion;
use App\Models\Periodo;
use App\Models\Curso;
use App\Models\Grado;
use App\Models\Estudiante;
use App\Models\Asistencia;
use App\Models\Nota;
use App\Models\Comportamiento;
use App\Models\Reporte;
use App\Models\DocenteCurso;
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
            return view('docente.dashboard-sin-persona');
        }
        
        // Si tiene persona pero no es docente, mostrar mensaje
        $docente = $persona->docente;
        if (!$docente) {
            return view('docente.dashboard-sin-docente');
        }

        // Obtener gestiones
        $gestiones = Gestion::orderBy('año', 'desc')->get();
        $gestionActual = Gestion::where('estado', 'Activo')->first();

        // Obtener periodos
        $periodos = Periodo::orderBy('numero')->get();
        $periodoActual = Periodo::where('estado', 'Activo')->first();

        // ✅ FORMA CORRECTA: Obtener asignaciones del docente con grados
        $asignaciones = DocenteCurso::where('docente_id', $docente->id)
            ->with(['curso', 'grado', 'gestion'])
            ->get();

        // Obtener cursos únicos (sin repetir)
        $cursos = $asignaciones->pluck('curso')->unique('id');
        $cursosIds = $cursos->pluck('id');

        // Obtener grados donde enseña
        $gradosIds = $asignaciones->pluck('grado_id')->unique();

        // Obtener estudiantes matriculados en los cursos del docente
        $estudiantes = Estudiante::whereHas('matriculas', function ($query) use ($cursosIds) {
            $query->whereIn('curso_id', $cursosIds)
                  ->where('estado', 'Activa');
        })->with('persona')->get();

        // Estadísticas de Asistencias (últimos 7 días)
        $asistenciasRecientes = Asistencia::where('docente_id', $docente->id)
            ->where('fecha', '>=', Carbon::now()->subDays(7))
            ->get();

        $totalAsistencias = $asistenciasRecientes->count();
        $presentes = $asistenciasRecientes->where('estado', 'Presente')->count();
        $ausentes = $asistenciasRecientes->where('estado', 'Ausente')->count();
        $tardanzas = $asistenciasRecientes->where('estado', 'Tardanza')->count();

        // Estadísticas de Notas
        $notasRegistradas = Nota::where('docente_id', $docente->id)->count();
        $notasPublicadas = Nota::where('docente_id', $docente->id)
            ->where('visible_tutor', true)
            ->count();

        // Estadísticas de Comportamientos
        $comportamientos = Comportamiento::where('docente_id', $docente->id)->get();
        $comportamientosPositivos = $comportamientos->where('tipo', 'Positivo')->count();
        $comportamientosNegativos = $comportamientos->where('tipo', 'Negativo')->count();
        $comportamientosNotificados = $comportamientos->where('notificado_tutor', true)->count();

        // Estadísticas de Reportes
        $reportes = Reporte::where('docente_id', $docente->id)->get();
        $reportesPublicados = $reportes->where('visible_tutor', true)->count();

        // Últimas actividades (últimas 5 asistencias registradas)
        $ultimasAsistencias = Asistencia::where('docente_id', $docente->id)
            ->with(['estudiante.persona', 'curso'])
            ->orderBy('fecha', 'desc')
            ->limit(5)
            ->get();

        // Últimas notas registradas
        $ultimasNotas = Nota::where('docente_id', $docente->id)
            ->with(['matricula.estudiante.persona', 'matricula.curso'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('docente.dashboard', compact(
            'gestiones',
            'gestionActual',
            'periodos',
            'periodoActual',
            'asignaciones',  // ← Ahora pasamos asignaciones en lugar de cursos
            'cursos',
            'estudiantes',
            'totalAsistencias',
            'presentes',
            'ausentes',
            'tardanzas',
            'notasRegistradas',
            'notasPublicadas',
            'comportamientosPositivos',
            'comportamientosNegativos',
            'comportamientosNotificados',
            'reportes',
            'reportesPublicados',
            'ultimasAsistencias',
            'ultimasNotas'
        ));
    }
}