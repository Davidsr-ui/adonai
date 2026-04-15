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
     * Lista de estudiantes del docente
     * Si llegan curso_id y grado_id, muestra solo los de ese curso (vista por curso)
     * Si no, muestra todos con filtros (vista mis-alumnos)
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
        $asignaciones = DocenteCurso::where('docente_id', $docente->id)->with(['curso', 'grado'])->get();
        $cursosIds = $asignaciones->pluck('curso_id')->unique();

        // Verificar si se está filtrando por curso y grado específicos (desde mis-cursos)
        $cursoId = $request->input('curso_id');
        $gradoId = $request->input('grado_id');

        if ($cursoId && $gradoId) {
            // Modo "estudiantes por curso": solo estudiantes de ese curso y grado
            $estudiantes = Estudiante::whereHas('matriculas', function ($q) use ($cursoId, $gradoId) {
                $q->where('curso_id', $cursoId)
                  ->where('grado_id', $gradoId)
                  ->where('estado', 'Matriculado');
            })->with(['persona', 'grado'])->get();

            // Obtener información del curso para los botones de asistencias y notas
            $curso = \App\Models\Curso::find($cursoId);
            $grado = \App\Models\Grado::find($gradoId);

            return view('docente.estudiantes.index', compact('estudiantes', 'curso', 'grado'));
        }

        // Modo "mis alumnos": todos los estudiantes del docente con filtros
        $cursos = $asignaciones->pluck('curso')->unique('id');
        $grados = $asignaciones->pluck('grado')->unique('id');

        $query = Estudiante::whereHas('matriculas', function ($q) use ($cursosIds) {
            $q->whereIn('curso_id', $cursosIds)->where('estado', 'Matriculado');
        })->with(['persona', 'grado']);

        // Aplicar filtros
        if ($request->filled('curso_id')) {
            $query->whereHas('matriculas', function ($q) use ($request) {
                $q->where('curso_id', $request->curso_id)->where('estado', 'Matriculado');
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

        $estudiantes = $query->orderBy('grado_id')->get();

        return view('docente.mis-alumnos', compact('estudiantes', 'cursos', 'grados', 'asignaciones'));
    }

    /**
     * Mostrar ficha detallada de un estudiante
     */
    public function show($id)
    {
        // Verificar perfil de docente
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'Tu perfil de docente no está completo')
                ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;
        $cursosIds = DocenteCurso::where('docente_id', $docente->id)->pluck('curso_id')->unique();

        $estudiante = Estudiante::with([
            'persona',
            'grado',
            'tutores.persona',
            'matriculas' => function ($query) use ($cursosIds) {
                $query->whereIn('curso_id', $cursosIds)->where('estado', 'Matriculado');
            },
            'matriculas.curso'
        ])->findOrFail($id);

        if ($estudiante->matriculas->isEmpty()) {
            return redirect()->route('docente.mis-alumnos')
                ->with('mensaje', 'Este estudiante no está en tus cursos')
                ->with('icono', 'error');
        }

        // Asistencias, notas, comportamientos (igual que antes)
        $asistencias = \App\Models\Asistencia::where('estudiante_id', $estudiante->id)
            ->whereIn('curso_id', $cursosIds)
            ->where('docente_id', $docente->id)
            ->with('curso')
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        $notas = \App\Models\Nota::where('docente_id', $docente->id)
            ->whereHas('matricula', function ($query) use ($estudiante) {
                $query->where('estudiante_id', $estudiante->id);
            })
            ->with(['matricula.curso', 'periodo'])
            ->orderBy('created_at', 'desc')
            ->get();

        $comportamientos = \App\Models\Comportamiento::where('estudiante_id', $estudiante->id)
            ->where('docente_id', $docente->id)
            ->with('curso')
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        $totalAsistencias = $asistencias->count();
        $presentes = $asistencias->where('estado', 'Presente')->count();
        $ausentes = $asistencias->where('estado', 'Ausente')->count();
        $tardanzas = $asistencias->where('estado', 'Tardanza')->count();
        $porcentajeAsistencia = $totalAsistencias > 0 ? round(($presentes / $totalAsistencias) * 100, 2) : 0;
        $promedioNotas = $notas->avg('nota_final');

        return view('docente.estudiante-detalle', compact(
            'estudiante', 'asistencias', 'notas', 'comportamientos',
            'totalAsistencias', 'presentes', 'ausentes', 'tardanzas',
            'porcentajeAsistencia', 'promedioNotas'
        ));
    }
}