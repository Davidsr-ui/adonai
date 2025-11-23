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

class NotaController extends Controller
{
    /**
     * Mostrar listado de notas del docente
     * SOLO muestra notas de los cursos asignados al docente
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

        // Obtener todos los periodos ordenados por número
        $periodos = Periodo::orderBy('numero')->get();

        // Obtener SOLO matrículas de los cursos del docente
        $matriculas = Matricula::whereIn('curso_id', $cursosIds)
            ->where('estado', 'Matriculado')
            ->with(['estudiante.persona', 'curso'])
            ->get();

        // Obtener SOLO docente autenticado
        $docentes = Docente::where('id', $docente->id)->with('persona')->get();

        // Filtrar notas SOLO del docente autenticado
        $query = Nota::where('docente_id', $docente->id)
            ->with(['matricula.estudiante.persona', 'matricula.curso', 'periodo', 'docente.persona']);

        // Aplicar filtros adicionales
        if ($request->filled('estudiante_id')) {
            $query->whereHas('matricula', function ($q) use ($request) {
                $q->where('estudiante_id', $request->estudiante_id);
            });
        }

        if ($request->filled('curso_id')) {
            $query->whereHas('matricula', function ($q) use ($request) {
                $q->where('curso_id', $request->curso_id);
            });
        }

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        if ($request->filled('tipo_evaluacion')) {
            $query->where('tipo_evaluacion', $request->tipo_evaluacion);
        }

        $notas = $query->orderBy('created_at', 'desc')->get();

        // Reutilizar la vista de admin pero con datos filtrados
        return view('docente.notas.index', compact(
            'notas',
            'estudiantes',
            'cursos',
            'periodos',
            'matriculas',
            'docentes'
        ));
    }

    /**
     * Guardar nueva nota
     */
    public function store(Request $request)
    {
        // Verificar que el usuario tenga perfil de docente
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
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

        // Verificar que la matrícula pertenezca a un curso del docente
        $matricula = Matricula::findOrFail($request->matricula_id_create);
        $cursoIds = $docente->cursos->pluck('id')->toArray();

        if (!in_array($matricula->curso_id, $cursoIds)) {
            return back()->with('mensaje', 'No puedes registrar notas en este curso')
                        ->with('icono', 'error');
        }

        // Crear la nota con el docente autenticado
        Nota::create([
            'matricula_id' => $request->matricula_id_create,
            'periodo_id' => $request->periodo_id_create,
            'docente_id' => $docente->id, // Automáticamente el docente autenticado
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
     * Actualizar nota
     */
    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        // Verificar que el docente pueda modificar esta nota
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes modificar esta nota')
                        ->with('icono', 'error');
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

        // Verificar permisos
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes eliminar esta nota')
                        ->with('icono', 'error');
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

        // Verificar permisos
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes ver esta nota')
                        ->with('icono', 'error');
        }

        // Reutilizar la vista de admin
        return view('docente.notas.show', compact('nota'));
    }

    /**
     * Publicar nota para tutores
     */
    public function publicar($id)
    {
        $nota = Nota::findOrFail($id);

        // Verificar permisos
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes publicar esta nota')
                        ->with('icono', 'error');
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

        // Verificar permisos
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($nota->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes despublicar esta nota')
                        ->with('icono', 'error');
        }

        $nota->update([
            'visible_tutor' => false,
        ]);

        return back()->with('mensaje', 'Nota despublicada')
                    ->with('icono', 'success');
    }
}