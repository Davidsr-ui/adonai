<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\DocenteCurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MisEstudiantesController extends Controller
{
    /**
     * Mostrar los estudiantes de los cursos del docente
     */
    public function index(Request $request)
    {
        // Verificar que el usuario tenga perfil de docente
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'Tu perfil de docente no está completo')
                ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        // ✅ CORRECTO: Obtener asignaciones del docente
        $asignaciones = DocenteCurso::where('docente_id', $docente->id)
            ->with(['curso', 'grado'])
            ->get();

        // Obtener cursos y grados únicos para filtros
        $cursos = $asignaciones->pluck('curso')->unique('id');
        $grados = $asignaciones->pluck('grado')->unique('id');
        
        $cursosIds = $cursos->pluck('id');

        // Obtener SOLO estudiantes matriculados en los cursos del docente
        $query = Estudiante::whereHas('matriculas', function ($q) use ($cursosIds) {
            $q->whereIn('curso_id', $cursosIds)
              ->where('estado', 'Activa');
        })->with(['persona', 'grado', 'tutor.persona']);

        // Aplicar filtros
        if ($request->filled('curso_id')) {
            $query->whereHas('matriculas', function ($q) use ($request) {
                $q->where('curso_id', $request->curso_id)
                  ->where('estado', 'Activa');
            });
        }

        if ($request->filled('grado_id')) {
            $query->where('grado_id', $request->grado_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('persona', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $estudiantes = $query->orderBy('grado_id')
                            ->get();

        // ✅ CORREGIDO: Usar la ruta correcta de la vista
        return view('docente.estudiantes.index', compact('estudiantes', 'cursos', 'grados', 'asignaciones'));
    }

    /**
     * Mostrar detalle de un estudiante
     */
    public function show($id)
    {
        // Verificar que el usuario tenga perfil de docente
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'Tu perfil de docente no está completo')
                ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        // Obtener cursos del docente
        $cursosIds = $docente->cursos->pluck('id');

        // Buscar estudiante
        $estudiante = Estudiante::with([
            'persona',
            'grado',
            'tutor.persona',
            'matriculas' => function ($query) use ($cursosIds) {
                $query->whereIn('curso_id', $cursosIds)->where('estado', 'Activa');
            },
            'matriculas.curso'
        ])->findOrFail($id);

        // Verificar que el estudiante esté en alguno de los cursos del docente
        if ($estudiante->matriculas->isEmpty()) {
            return redirect()->route('docente.estudiantes.index')
                ->with('mensaje', 'Este estudiante no está en tus cursos')
                ->with('icono', 'error');
        }

        // Obtener asistencias del estudiante en los cursos del docente
        $asistencias = \App\Models\Asistencia::where('estudiante_id', $estudiante->id)
            ->whereIn('curso_id', $cursosIds)
            ->where('docente_id', $docente->id)
            ->with('curso')
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        // Obtener notas del estudiante en los cursos del docente
        $notas = \App\Models\Nota::where('docente_id', $docente->id)
            ->whereHas('matricula', function ($query) use ($estudiante) {
                $query->where('estudiante_id', $estudiante->id);
            })
            ->with(['matricula.curso', 'periodo'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener comportamientos del estudiante registrados por el docente
        $comportamientos = \App\Models\Comportamiento::where('estudiante_id', $estudiante->id)
            ->where('docente_id', $docente->id)
            ->with('curso')
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        // Estadísticas de asistencia
        $totalAsistencias = $asistencias->count();
        $presentes = $asistencias->where('estado', 'Presente')->count();
        $ausentes = $asistencias->where('estado', 'Ausente')->count();
        $tardanzas = $asistencias->where('estado', 'Tardanza')->count();
        $porcentajeAsistencia = $totalAsistencias > 0 
            ? round(($presentes / $totalAsistencias) * 100, 2) 
            : 0;

        // Promedio de notas
        $promedioNotas = $notas->avg('nota_final');

        return view('docente.estudiante-detalle', compact(
            'estudiante',
            'asistencias',
            'notas',
            'comportamientos',
            'totalAsistencias',
            'presentes',
            'ausentes',
            'tardanzas',
            'porcentajeAsistencia',
            'promedioNotas'
        ));
    }
}