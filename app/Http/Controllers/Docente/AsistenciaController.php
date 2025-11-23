<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends Controller
{
    /**
     * Mostrar listado de asistencias del docente
     * SOLO muestra asistencias de los cursos asignados al docente
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

        // ✅ CORREGIDO: Obtener SOLO los cursos del docente autenticado (sin with grado)
        $cursos = $docente->cursos()->get();
        $cursosIds = $cursos->pluck('id');

        // Obtener SOLO estudiantes matriculados en los cursos del docente
        $estudiantes = Estudiante::whereHas('matriculas', function ($query) use ($cursosIds) {
            $query->whereIn('curso_id', $cursosIds)
                  ->where('estado', 'Matriculado');
        })->with('persona')->get();

        // Obtener SOLO docente autenticado (para el formulario)
        $docentes = Docente::where('id', $docente->id)->with('persona')->get();

        // Filtrar asistencias SOLO de los cursos del docente
        $query = Asistencia::whereIn('curso_id', $cursosIds)
            ->with(['estudiante.persona', 'curso', 'docente.persona']);

        // Aplicar filtros adicionales si existen
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('estudiante_id')) {
            $query->where('estudiante_id', $request->estudiante_id);
        }

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $asistencias = $query->orderBy('fecha', 'desc')->get();

        // Reutilizar la vista de admin pero con datos filtrados
        return view('docente.asistencias.index', compact(
            'asistencias',
            'estudiantes',
            'cursos',
            'docentes'
        ));
    }

    /**
     * Guardar nueva asistencia
     */
    public function store(Request $request)
    {
        // Verificar que el usuario tenga perfil de docente
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        // Validar que el curso pertenezca al docente
        $cursoIds = $docente->cursos->pluck('id')->toArray();
        $cursoId = $request->input('curso_id_create');

        if (!in_array($cursoId, $cursoIds)) {
            return back()->with('mensaje', 'No puedes registrar asistencias en este curso')
                        ->with('icono', 'error');
        }

        $request->validate([
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'curso_id_create' => 'required|exists:cursos,id',
            'fecha_create' => 'required|date',
            'estado_create' => 'required|in:Presente,Ausente,Tardanza,Justificado',
            'observaciones_create' => 'nullable|string|max:500',
        ]);

        // Verificar que no exista duplicado
        $existe = Asistencia::where('estudiante_id', $request->estudiante_id_create)
            ->where('curso_id', $request->curso_id_create)
            ->where('fecha', $request->fecha_create)
            ->exists();

        if ($existe) {
            return back()->with('mensaje', 'Ya existe un registro de asistencia para este estudiante en esta fecha')
                        ->with('icono', 'warning');
        }

        // Crear la asistencia con el docente autenticado
        Asistencia::create([
            'estudiante_id' => $request->estudiante_id_create,
            'curso_id' => $request->curso_id_create,
            'docente_id' => $docente->id, // Automáticamente el docente autenticado
            'fecha' => $request->fecha_create,
            'estado' => $request->estado_create,
            'observaciones' => $request->observaciones_create,
        ]);

        return redirect()->route('docente.asistencias.index')
            ->with('mensaje', 'Asistencia registrada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Actualizar asistencia
     */
    public function update(Request $request, $id)
    {
        $asistencia = Asistencia::findOrFail($id);

        // Verificar que el docente pueda modificar esta asistencia
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;
        $cursoIds = $docente->cursos->pluck('id')->toArray();

        if (!in_array($asistencia->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes modificar esta asistencia')
                        ->with('icono', 'error');
        }

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'curso_id' => 'required|exists:cursos,id',
            'fecha' => 'required|date',
            'estado' => 'required|in:Presente,Ausente,Tardanza,Justificado',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $asistencia->update([
            'estudiante_id' => $request->estudiante_id,
            'curso_id' => $request->curso_id,
            'fecha' => $request->fecha,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('docente.asistencias.index')
            ->with('mensaje', 'Asistencia actualizada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar asistencia
     */
    public function destroy($id)
    {
        $asistencia = Asistencia::findOrFail($id);

        // Verificar permisos
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;
        $cursoIds = $docente->cursos->pluck('id')->toArray();

        if (!in_array($asistencia->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes eliminar esta asistencia')
                        ->with('icono', 'error');
        }

        $asistencia->delete();

        return redirect()->route('docente.asistencias.index')
            ->with('mensaje', 'Asistencia eliminada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Ver detalle de una asistencia
     */
    public function show($id)
    {
        $asistencia = Asistencia::with([
            'estudiante.persona',
            'curso',
            'docente.persona'
        ])->findOrFail($id);

        // Verificar permisos
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;
        $cursoIds = $docente->cursos->pluck('id')->toArray();

        if (!in_array($asistencia->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes ver esta asistencia')
                        ->with('icono', 'error');
        }

        // Reutilizar la vista de admin
        return view('docente.asistencias.show', compact('asistencia'));
    }

    /**
     * Registro masivo de asistencias
     */
    public function registroMasivo(Request $request)
    {
        // Verificar permisos
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'fecha' => 'required|date',
            'docente_id' => 'nullable|exists:docentes,id',
        ]);

        // Verificar que el curso pertenezca al docente
        $cursoIds = $docente->cursos->pluck('id')->toArray();
        if (!in_array($request->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes registrar asistencias en este curso')
                        ->with('icono', 'error');
        }

        // Obtener estudiantes matriculados en el curso
        $matriculas = \App\Models\Matricula::where('curso_id', $request->curso_id)
            ->where('estado', 'Matriculado')
            ->get();

        $registrados = 0;
        foreach ($matriculas as $matricula) {
            // Verificar que no exista duplicado
            $existe = Asistencia::where('estudiante_id', $matricula->estudiante_id)
                ->where('curso_id', $request->curso_id)
                ->where('fecha', $request->fecha)
                ->exists();

            if (!$existe) {
                Asistencia::create([
                    'estudiante_id' => $matricula->estudiante_id,
                    'curso_id' => $request->curso_id,
                    'docente_id' => $docente->id,
                    'fecha' => $request->fecha,
                    'estado' => 'Presente',
                    'observaciones' => 'Registro masivo',
                ]);
                $registrados++;
            }
        }

        return redirect()->route('docente.asistencias.index')
            ->with('mensaje', "Se registraron {$registrados} asistencias correctamente")
            ->with('icono', 'success');
    }
}