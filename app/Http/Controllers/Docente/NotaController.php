<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Nota;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Periodo;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotaController extends Controller
{
    /**
     * Mostrar listado de notas del docente (histórico)
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
        $periodos = Periodo::orderBy('numero')->get();

        $estudiantes = Estudiante::whereHas('matriculas', function ($query) use ($cursosIds) {
            $query->whereIn('curso_id', $cursosIds)->where('estado', 'Matriculado');
        })->with('persona')->get();

        $query = Nota::where('docente_id', $docente->id)
            ->with(['matricula.estudiante.persona', 'matricula.curso', 'periodo', 'docente.persona']);

        if ($request->filled('estudiante_id')) {
            $query->whereHas('matricula', fn($q) => $q->where('estudiante_id', $request->estudiante_id));
        }
        if ($request->filled('curso_id')) {
            $query->whereHas('matricula', fn($q) => $q->where('curso_id', $request->curso_id));
        }
        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }
        if ($request->filled('tipo_evaluacion')) {
            $query->where('tipo_evaluacion', $request->tipo_evaluacion);
        }

        $notas = $query->orderBy('created_at', 'desc')->get();

        return view('docente.notas.index', compact('notas', 'estudiantes', 'cursos', 'periodos'));
    }

    /**
     * Mostrar formulario para registrar notas múltiples por curso.
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
        $periodos = Periodo::orderBy('numero')->get();

        $cursoId = $request->input('curso_id');
        $periodoId = $request->input('periodo_id');
        $estudiantes = collect();

        if ($cursoId && $periodoId) {
            if (!$cursos->contains('id', $cursoId)) {
                return redirect()->route('docente.notas.create')
                    ->with('mensaje', 'Curso no válido')
                    ->with('icono', 'error');
            }

            $estudiantes = Estudiante::whereHas('matriculas', function ($q) use ($cursoId) {
                $q->where('curso_id', $cursoId)->where('estado', 'Matriculado');
            })->with('persona')->get();

            foreach ($estudiantes as $e) {
                $matricula = Matricula::where('estudiante_id', $e->id)
                    ->where('curso_id', $cursoId)
                    ->first();

                if ($matricula) {
                    $notaExistente = Nota::where('matricula_id', $matricula->id)
                        ->where('periodo_id', $periodoId)
                        ->first();

                    $e->matricula_id = $matricula->id;
                    $e->nota_practica = $notaExistente->nota_practica ?? null;
                    $e->nota_teoria = $notaExistente->nota_teoria ?? null;
                    $e->nota_final = $notaExistente->nota_final ?? null;
                    $e->nota_id = $notaExistente->id ?? null;
                    $e->tipo_evaluacion = $notaExistente->tipo_evaluacion ?? 'Parcial';
                    $e->descripcion = $notaExistente->descripcion ?? null;
                    $e->observaciones = $notaExistente->observaciones ?? null;
                    $e->fecha_evaluacion = $notaExistente->fecha_evaluacion ?? null; // ← AGREGADO
                    $e->visible_tutor = $notaExistente->visible_tutor ?? false;
                } else {
                    $e->matricula_id = null;
                    $e->nota_practica = null;
                    $e->nota_teoria = null;
                    $e->nota_final = null;
                    $e->nota_id = null;
                    $e->tipo_evaluacion = 'Parcial';
                    $e->fecha_evaluacion = null;
                }
            }
        }

        return view('docente.notas.create', compact('cursos', 'periodos', 'cursoId', 'periodoId', 'estudiantes'));
    }

    /**
     * Guardar múltiples notas a la vez.
     */
    public function storeMultiple(Request $request)
    {
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'periodo_id' => 'required|exists:periodos,id',
            'notas' => 'required|array',
            'notas.*.matricula_id' => 'required|exists:matriculas,id',
            'notas.*.nota_practica' => 'nullable|numeric|min:0|max:20',
            'notas.*.nota_teoria' => 'nullable|numeric|min:0|max:20',
            'notas.*.nota_final' => 'required|numeric|min:0|max:20',
            'notas.*.tipo_evaluacion' => 'required|in:Parcial,Final,Práctica,Oral,Trabajo',
            'notas.*.fecha_evaluacion' => 'nullable|date', // ← AGREGADO
            'notas.*.descripcion' => 'nullable|string|max:500',
            'notas.*.observaciones' => 'nullable|string|max:500',
            'notas.*.visible_tutor' => 'nullable|boolean',
        ]);

        $cursoId = $request->curso_id;
        $periodoId = $request->periodo_id;

        $cursoIds = $docente->cursos->pluck('id')->toArray();
        if (!in_array($cursoId, $cursoIds)) {
            return back()->with('mensaje', 'No puedes registrar notas en este curso')->with('icono', 'error');
        }

        DB::beginTransaction();
        try {
            $registrados = 0;
            foreach ($request->notas as $item) {
                if (isset($item['nota_id']) && $item['nota_id']) {
                    $nota = Nota::find($item['nota_id']);
                    if ($nota) {
                        $nota->update([
                            'nota_practica' => $item['nota_practica'] ?? null,
                            'nota_teoria' => $item['nota_teoria'] ?? null,
                            'nota_final' => $item['nota_final'],
                            'tipo_evaluacion' => $item['tipo_evaluacion'],
                            'fecha_evaluacion' => $item['fecha_evaluacion'] ?? null,
                            'descripcion' => $item['descripcion'] ?? null,
                            'observaciones' => $item['observaciones'] ?? null,
                            'visible_tutor' => isset($item['visible_tutor']),
                            'fecha_publicacion' => isset($item['visible_tutor']) ? now() : null,
                        ]);
                    }
                } else {
                    Nota::create([
                        'matricula_id' => $item['matricula_id'],
                        'periodo_id' => $periodoId,
                        'docente_id' => $docente->id,
                        'nota_practica' => $item['nota_practica'] ?? null,
                        'nota_teoria' => $item['nota_teoria'] ?? null,
                        'nota_final' => $item['nota_final'],
                        'tipo_evaluacion' => $item['tipo_evaluacion'],
                        'fecha_evaluacion' => $item['fecha_evaluacion'] ?? null,
                        'descripcion' => $item['descripcion'] ?? null,
                        'observaciones' => $item['observaciones'] ?? null,
                        'visible_tutor' => isset($item['visible_tutor']),
                        'fecha_publicacion' => isset($item['visible_tutor']) ? now() : null,
                    ]);
                }
                $registrados++;
            }

            DB::commit();

            return redirect()->route('docente.notas.index')
                ->with('mensaje', "Se registraron/actualizaron {$registrados} notas correctamente")
                ->with('icono', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('mensaje', 'Error al guardar notas: ' . $e->getMessage())
                        ->with('icono', 'error');
        }
    }

    /**
     * Guardar una nueva nota individual (desde el modal del index)
     */
    public function store(Request $request)
    {
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        $request->validate([
            'matricula_id_create' => 'required|exists:matriculas,id',
            'periodo_id_create' => 'required|exists:periodos,id',
            'nota_practica_create' => 'nullable|numeric|min:0|max:20',
            'nota_teoria_create' => 'nullable|numeric|min:0|max:20',
            'nota_final_create' => 'required|numeric|min:0|max:20',
            'tipo_evaluacion_create' => 'required|in:Parcial,Final,Práctica,Oral,Trabajo',
            'fecha_evaluacion_create' => 'nullable|date',
            'descripcion_create' => 'nullable|string|max:500',
            'observaciones_create' => 'nullable|string|max:500',
            'visible_tutor_create' => 'nullable|boolean',
        ]);

        $matricula = Matricula::findOrFail($request->matricula_id_create);
        $cursoIds = $docente->cursos->pluck('id')->toArray();

        if (!in_array($matricula->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes registrar notas en este curso')->with('icono', 'error');
        }

        Nota::create([
            'matricula_id' => $request->matricula_id_create,
            'periodo_id' => $request->periodo_id_create,
            'docente_id' => $docente->id,
            'nota_practica' => $request->nota_practica_create,
            'nota_teoria' => $request->nota_teoria_create,
            'nota_final' => $request->nota_final_create,
            'tipo_evaluacion' => $request->tipo_evaluacion_create,
            'fecha_evaluacion' => $request->fecha_evaluacion_create,
            'descripcion' => $request->descripcion_create,
            'observaciones' => $request->observaciones_create,
            'visible_tutor' => $request->has('visible_tutor_create'),
            'fecha_publicacion' => $request->has('visible_tutor_create') ? now() : null,
        ]);

        return redirect()->route('docente.notas.index')
            ->with('mensaje', 'Nota registrada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Obtener datos de una nota en formato JSON (para editar vía AJAX)
     */
    public function edit($id)
    {
        $nota = Nota::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return response()->json(['error' => 'No tienes permisos'], 403);
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return response()->json(['error' => 'No puedes editar esta nota'], 403);
        }

        return response()->json($nota);
    }

    /**
     * Actualizar una nota individual
     */
    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes modificar esta nota')->with('icono', 'error');
        }

        $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
            'periodo_id' => 'required|exists:periodos,id',
            'nota_practica' => 'nullable|numeric|min:0|max:20',
            'nota_teoria' => 'nullable|numeric|min:0|max:20',
            'nota_final' => 'required|numeric|min:0|max:20',
            'tipo_evaluacion' => 'required|in:Parcial,Final,Práctica,Oral,Trabajo',
            'fecha_evaluacion' => 'nullable|date',
            'descripcion' => 'nullable|string|max:500',
            'observaciones' => 'nullable|string|max:500',
            'visible_tutor' => 'nullable|boolean',
        ]);

        $nota->update([
            'matricula_id' => $request->matricula_id,
            'periodo_id' => $request->periodo_id,
            'nota_practica' => $request->nota_practica,
            'nota_teoria' => $request->nota_teoria,
            'nota_final' => $request->nota_final,
            'tipo_evaluacion' => $request->tipo_evaluacion,
            'fecha_evaluacion' => $request->fecha_evaluacion,
            'descripcion' => $request->descripcion,
            'observaciones' => $request->observaciones,
            'visible_tutor' => $request->has('visible_tutor'),
            'fecha_publicacion' => $request->has('visible_tutor') && !$nota->visible_tutor ? now() : $nota->fecha_publicacion,
        ]);

        return redirect()->route('docente.notas.index')
            ->with('mensaje', 'Nota actualizada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar nota
     */
    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes eliminar esta nota')->with('icono', 'error');
        }

        $nota->delete();

        return redirect()->route('docente.notas.index')
            ->with('mensaje', 'Nota eliminada correctamente')
            ->with('icono', 'success');
    }

    /**
     * Ver detalle de una nota
     */
    public function show($id)
    {
        $nota = Nota::with([
            'matricula.estudiante.persona',
            'matricula.curso',
            'matricula.grado',
            'periodo',
            'docente.persona'
        ])->findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes ver esta nota')->with('icono', 'error');
        }

        return view('docente.notas.show', compact('nota'));
    }

    /**
     * Publicar nota para tutores
     */
    public function publicar($id)
    {
        $nota = Nota::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes publicar esta nota')->with('icono', 'error');
        }

        $nota->update([
            'visible_tutor' => true,
            'fecha_publicacion' => now(),
        ]);

        return back()->with('mensaje', 'Nota publicada para tutores')
                    ->with('icono', 'success');
    }

    /**
     * Despublicar nota para tutores
     */
    public function despublicar($id)
    {
        $nota = Nota::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes despublicar esta nota')->with('icono', 'error');
        }

        $nota->update([
            'visible_tutor' => false,
        ]);

        return back()->with('mensaje', 'Nota despublicada')
                    ->with('icono', 'success');
    }
}