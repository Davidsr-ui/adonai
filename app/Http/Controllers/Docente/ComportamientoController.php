<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Comportamiento;
use App\Models\Estudiante;
use App\Models\Curso;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComportamientoController extends Controller
{
    /**
     * Mostrar listado de comportamientos del docente
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

        // Obtener cursos del docente
        $cursos = $docente->cursos()->get();
        $cursosIds = $cursos->pluck('id');

        // Obtener estudiantes matriculados en los cursos del docente
        $estudiantes = Estudiante::whereHas('matriculas', function ($query) use ($cursosIds) {
            $query->whereIn('curso_id', $cursosIds)
                  ->where('estado', 'Matriculado');
        })->with('persona')->get();

        // Obtener solo docente autenticado
        $docentes = Docente::where('id', $docente->id)->with('persona')->get();

        // Filtrar comportamientos del docente
        $query = Comportamiento::where('docente_id', $docente->id)
            ->with(['estudiante.persona', 'curso', 'docente.persona']);

        // Aplicar filtros
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        if ($request->filled('estudiante_id')) {
            $query->where('estudiante_id', $request->estudiante_id);
        }

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('notificado')) {
            $query->where('notificado_tutor', $request->notificado === '1');
        }

        $comportamientos = $query->orderBy('fecha', 'desc')->get();

        // Reutilizar la vista de admin (ya adaptada con $routePrefix)
        return view('admin.comportamientos.index', compact(
            'comportamientos',
            'estudiantes',
            'cursos',
            'docentes'
        ));
    }

    /**
     * Guardar nuevo comportamiento
     */
    public function store(Request $request)
    {
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        $request->validate([
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'curso_id_create' => 'nullable|exists:cursos,id',
            'fecha_create' => 'required|date',
            'tipo_create' => 'required|in:Positivo,Negativo,Neutro',
            'descripcion_create' => 'required|string|max:1000',
            'sancion_create' => 'nullable|string|max:500',
        ]);

        // Si no se especifica curso, usar el primer curso del docente
        $cursoId = $request->curso_id_create;
        if (!$cursoId) {
            $cursoId = $docente->cursos->first()->id ?? null;
        }

        // Verificar que el curso pertenezca al docente (si se especificó)
        if ($cursoId) {
            $cursoIds = $docente->cursos->pluck('id')->toArray();
            if (!in_array($cursoId, $cursoIds)) {
                return back()->with('mensaje', 'No puedes registrar comportamientos en este curso')
                            ->with('icono', 'error');
            }
        }

        Comportamiento::create([
            'estudiante_id' => $request->estudiante_id_create,
            'curso_id' => $cursoId,
            'docente_id' => $docente->id,
            'fecha' => $request->fecha_create,
            'tipo' => $request->tipo_create,
            'descripcion' => $request->descripcion_create,
            'sancion' => $request->sancion_create,
            'notificado_tutor' => $request->has('notificado_tutor_create'),
            'fecha_notificacion' => $request->has('notificado_tutor_create') ? now() : null,
        ]);

        return redirect()->route('docente.comportamientos.index')
            ->with('mensaje', 'Comportamiento registrado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Actualizar comportamiento - CORREGIDO
     */
    public function update(Request $request, $id)
    {
        $comportamiento = Comportamiento::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($comportamiento->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes modificar este comportamiento')
                        ->with('icono', 'error');
        }

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'fecha' => 'required|date',
            'tipo' => 'required|in:Positivo,Negativo,Neutro',
            'descripcion' => 'required|string|max:1000',
            'sancion' => 'nullable|string|max:500',
        ]);

        // Preparar datos para actualizar
        $data = [
            'estudiante_id' => $request->estudiante_id,
            'fecha' => $request->fecha,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'sancion' => $request->sancion,
        ];

        // Manejar notificación al tutor
        if ($request->has('notificado_tutor')) {
            $data['notificado_tutor'] = true;
            // Solo actualizar fecha si no estaba notificado antes
            if (!$comportamiento->notificado_tutor) {
                $data['fecha_notificacion'] = now();
            }
        } else {
            $data['notificado_tutor'] = false;
            $data['fecha_notificacion'] = null;
        }

        $comportamiento->update($data);

        return redirect()->route('docente.comportamientos.index')
            ->with('mensaje', 'Comportamiento actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar comportamiento
     */
    public function destroy($id)
    {
        $comportamiento = Comportamiento::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($comportamiento->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes eliminar este comportamiento')
                        ->with('icono', 'error');
        }

        $comportamiento->delete();

        return redirect()->route('docente.comportamientos.index')
            ->with('mensaje', 'Comportamiento eliminado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Ver detalle - USA VISTA DE DOCENTE
     */
    public function show($id)
    {
        $comportamiento = Comportamiento::with([
            'estudiante.persona',
            'estudiante.grado',
            'curso',
            'docente.persona'
        ])->findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($comportamiento->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes ver este comportamiento')
                        ->with('icono', 'error');
        }

        // Resumen de comportamientos del estudiante
        $resumen = [
            'total' => Comportamiento::where('estudiante_id', $comportamiento->estudiante_id)->count(),
            'positivos' => Comportamiento::where('estudiante_id', $comportamiento->estudiante_id)->where('tipo', 'Positivo')->count(),
            'negativos' => Comportamiento::where('estudiante_id', $comportamiento->estudiante_id)->where('tipo', 'Negativo')->count(),
            'neutros' => Comportamiento::where('estudiante_id', $comportamiento->estudiante_id)->where('tipo', 'Neutro')->count(),
            'con_sancion' => Comportamiento::where('estudiante_id', $comportamiento->estudiante_id)->whereNotNull('sancion')->count(),
            'notificados' => Comportamiento::where('estudiante_id', $comportamiento->estudiante_id)->where('notificado_tutor', true)->count(),
        ];

        // Últimos comportamientos del estudiante
        $ultimosComportamientos = Comportamiento::where('estudiante_id', $comportamiento->estudiante_id)
            ->with(['docente.persona'])
            ->orderBy('fecha', 'desc')
            ->limit(10)
            ->get();

        // ✅ USA VISTA DE DOCENTE
        return view('docente.comportamientos.show', compact('comportamiento', 'resumen', 'ultimosComportamientos'));
    }

    /**
     * Notificar a tutor
     */
    public function notificar($id)
    {
        $comportamiento = Comportamiento::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($comportamiento->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes notificar este comportamiento')
                        ->with('icono', 'error');
        }

        $comportamiento->update([
            'notificado_tutor' => true,
            'fecha_notificacion' => now(),
        ]);

        return back()->with('mensaje', 'Notificación enviada al tutor')
                    ->with('icono', 'success');
    }

    /**
     * Cancelar notificación
     */
    public function cancelarNotificacion($id)
    {
        $comportamiento = Comportamiento::findOrFail($id);

        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            return back()->with('mensaje', 'No tienes permisos')
                        ->with('icono', 'error');
        }

        $docente = Auth::user()->persona->docente;

        if ($comportamiento->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes cancelar esta notificación')
                        ->with('icono', 'error');
        }

        $comportamiento->update([
            'notificado_tutor' => false,
            'fecha_notificacion' => null,
        ]);

        return back()->with('mensaje', 'Notificación cancelada')
                    ->with('icono', 'success');
    }
}