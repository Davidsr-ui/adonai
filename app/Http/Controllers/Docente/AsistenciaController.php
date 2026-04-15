<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    /**
     * Mostrar selector de curso para registrar asistencias.
     */
    public function create(Request $request)
    {
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'Tu perfil de docente no está completo')
                ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;
        $cursos = $docente->cursos()->get();

        $cursoId = $request->input('curso_id');
        $fecha = $request->input('fecha', date('Y-m-d'));
        $estudiantes = collect();

        if ($cursoId) {
            if (!$cursos->contains('id', $cursoId)) {
                return redirect()->route('docente.asistencias.create')
                    ->with('mensaje', 'Curso no válido')
                    ->with('icono', 'error');
            }

            $estudiantes = Estudiante::whereHas('matriculas', function ($q) use ($cursoId) {
                $q->where('curso_id', $cursoId)->where('estado', 'Matriculado');
            })->with('persona')->get();

            foreach ($estudiantes as $e) {
                $asistenciaExistente = Asistencia::where('estudiante_id', $e->id)
                    ->where('curso_id', $cursoId)
                    ->where('fecha', $fecha)
                    ->first();
                $e->asistencia_estado = $asistenciaExistente ? $asistenciaExistente->estado : null;
                $e->asistencia_id = $asistenciaExistente ? $asistenciaExistente->id : null;
                $e->asistencia_observaciones = $asistenciaExistente ? $asistenciaExistente->observaciones : null;
            }
        }

        return view('docente.asistencias.create', compact('cursos', 'cursoId', 'fecha', 'estudiantes'));
    }

    /**
     * Guardar asistencias (múltiples a la vez).
     */
    public function store(Request $request)
    {
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*.estudiante_id' => 'required|exists:estudiantes,id',
            'asistencias.*.estado' => 'required|in:Presente,Ausente,Tardanza,Justificado',
            'asistencias.*.observaciones' => 'nullable|string|max:500',
        ]);

        $cursoId = $request->curso_id;
        $fecha = $request->fecha;

        $cursoIds = $docente->cursos->pluck('id')->toArray();
        if (!in_array($cursoId, $cursoIds)) {
            return back()->with('mensaje', 'No puedes registrar asistencias en este curso')->with('icono', 'error');
        }

        DB::beginTransaction();
        try {
            $registrados = 0;
            foreach ($request->asistencias as $item) {
                $asistencia = Asistencia::where('estudiante_id', $item['estudiante_id'])
                    ->where('curso_id', $cursoId)
                    ->where('fecha', $fecha)
                    ->first();

                if ($asistencia) {
                    $asistencia->update([
                        'estado' => $item['estado'],
                        'observaciones' => $item['observaciones'] ?? null,
                    ]);
                } else {
                    Asistencia::create([
                        'estudiante_id' => $item['estudiante_id'],
                        'curso_id' => $cursoId,
                        'docente_id' => $docente->id,
                        'fecha' => $fecha,
                        'estado' => $item['estado'],
                        'observaciones' => $item['observaciones'] ?? null,
                    ]);
                }
                $registrados++;
            }

            DB::commit();

            return redirect()->route('docente.asistencias.index')
                ->with('mensaje', "Se registraron/actualizaron {$registrados} asistencias correctamente")
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('mensaje', 'Error al guardar asistencias: ' . $e->getMessage())
                        ->with('icono', 'error');
        }
    }

    /**
     * Mostrar listado de asistencias (histórico).
     */
    public function index(Request $request)
    {
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return redirect()->route('docente.dashboard')
                ->with('mensaje', 'Tu perfil de docente no está completo')
                ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;
        $cursos = $docente->cursos()->get();
        $cursosIds = $cursos->pluck('id');

        $estudiantes = Estudiante::whereHas('matriculas', function ($query) use ($cursosIds) {
            $query->whereIn('curso_id', $cursosIds)->where('estado', 'Matriculado');
        })->with('persona')->get();

        $query = Asistencia::whereIn('curso_id', $cursosIds)
            ->with(['estudiante.persona', 'curso', 'docente.persona']);

        if ($request->filled('fecha')) $query->whereDate('fecha', $request->fecha);
        if ($request->filled('estudiante_id')) $query->where('estudiante_id', $request->estudiante_id);
        if ($request->filled('curso_id')) $query->where('curso_id', $request->curso_id);
        if ($request->filled('estado')) $query->where('estado', $request->estado);

        $asistencias = $query->orderBy('fecha', 'desc')->get();

        return view('docente.asistencias.index', compact('asistencias', 'estudiantes', 'cursos'));
    }

    /**
     * Ver detalle de una asistencia.
     */
    public function show($id)
    {
        $asistencia = Asistencia::with(['estudiante.persona', 'curso', 'docente.persona'])->findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }
        $docente = Auth::user()->persona->docente;
        $cursoIds = $docente->cursos->pluck('id')->toArray();
        if (!in_array($asistencia->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes ver esta asistencia')->with('icono', 'error');
        }

        return view('docente.asistencias.show', compact('asistencia'));
    }

    /**
     * Obtener datos de una asistencia en formato JSON (para editar vía AJAX)
     */
    public function edit($id)
    {
        $asistencia = Asistencia::with(['estudiante.persona', 'curso'])->findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return response()->json(['error' => 'No tienes permisos'], 403);
        }

        $docente = Auth::user()->persona->docente;
        $cursoIds = $docente->cursos->pluck('id')->toArray();

        if (!in_array($asistencia->curso_id, $cursoIds)) {
            return response()->json(['error' => 'No puedes editar esta asistencia'], 403);
        }

        return response()->json($asistencia);
    }

    /**
     * Actualizar una asistencia individual.
     */
    public function update(Request $request, $id)
    {
        $asistencia = Asistencia::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }
        $docente = Auth::user()->persona->docente;
        $cursoIds = $docente->cursos->pluck('id')->toArray();
        if (!in_array($asistencia->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes modificar esta asistencia')->with('icono', 'error');
        }

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'curso_id' => 'required|exists:cursos,id',
            'fecha' => 'required|date',
            'estado' => 'required|in:Presente,Ausente,Tardanza,Justificado',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $asistencia->update($request->only(['estudiante_id', 'curso_id', 'fecha', 'estado', 'observaciones']));

        return redirect()->route('docente.asistencias.index')
            ->with('mensaje', 'Asistencia actualizada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar asistencia.
     */
    public function destroy($id)
    {
        $asistencia = Asistencia::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }
        $docente = Auth::user()->persona->docente;
        $cursoIds = $docente->cursos->pluck('id')->toArray();
        if (!in_array($asistencia->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes eliminar esta asistencia')->with('icono', 'error');
        }

        $asistencia->delete();

        return redirect()->route('docente.asistencias.index')
            ->with('mensaje', 'Asistencia eliminada correctamente')
            ->with('icono', 'success');
    }
}