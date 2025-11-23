<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\DocenteCurso;
use Illuminate\Support\Facades\Auth;

class MisCursosController extends Controller
{
    /**
     * Mostrar los cursos asignados al docente con sus grados
     */
    public function index()
    {
        // Verificar que el usuario tenga perfil de docente
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'Tu perfil de docente no está completo')
                ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        // ✅ CORRECTO: Obtener asignaciones (curso + grado) del docente
        $asignaciones = DocenteCurso::where('docente_id', $docente->id)
            ->with(['curso', 'grado', 'gestion'])
            ->orderBy('curso_id')
            ->orderBy('grado_id')
            ->get();

        // Agrupar por curso para mejor visualización
        $cursosPorId = $asignaciones->groupBy('curso_id');

        return view('docente.mis-cursos', compact('asignaciones', 'cursosPorId'));
    }

    /**
     * Mostrar detalle de una asignación específica
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

        // Buscar la asignación específica
        $asignacion = DocenteCurso::where('id', $id)
            ->where('docente_id', $docente->id)
            ->with(['curso', 'grado', 'gestion'])
            ->firstOrFail();

        // Obtener estudiantes matriculados en este curso y grado
        $estudiantes = \App\Models\Estudiante::whereHas('matriculas', function ($query) use ($asignacion) {
            $query->where('curso_id', $asignacion->curso_id)
                  ->where('grado_id', $asignacion->grado_id)
                  ->where('estado', 'Activa');
        })->with('persona')->get();

        // Obtener horarios de esta asignación
        $horarios = \App\Models\Horario::where('curso_id', $asignacion->curso_id)
            ->where('grado_id', $asignacion->grado_id)
            ->where('docente_id', $docente->id)
            ->orderBy('dia_semana')
            ->orderBy('hora_inicio')
            ->get();

        return view('docente.curso-detalle', compact('asignacion', 'estudiantes', 'horarios'));
    }
}