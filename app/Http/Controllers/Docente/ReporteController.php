<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Reporte;
use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Periodo;
use App\Models\Gestion;
use App\Models\Comportamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Obtener docente autenticado
     */
    private function getDocente()
    {
        if (!Auth::user()->persona || !Auth::user()->persona->docente) {
            abort(403, 'Tu perfil de docente no está completo');
        }
        return Auth::user()->persona->docente;
    }

    /**
     * Mostrar listado de reportes del docente
     */
    public function index(Request $request)
    {
        $docente = $this->getDocente();

        // ✅ CORRECCIÓN: Obtener IDs de cursos sin ambigüedad
        $cursosIds = DB::table('docente_curso')
            ->where('docente_id', $docente->id)
            ->pluck('curso_id');

        // Obtener SOLO estudiantes matriculados en los cursos del docente
        $estudiantes = Estudiante::whereHas('matriculas', function ($query) use ($cursosIds) {
            $query->whereIn('curso_id', $cursosIds)
                  ->where('estado', 'Matriculado');
        })->with('persona')->get();

        $periodos = Periodo::orderBy('numero')->get();
        $gestiones = Gestion::orderBy('año', 'desc')->get();

        // SOLO el docente autenticado
        $docentes = Docente::where('id', $docente->id)->with('persona')->get();

        // Filtrar SOLO reportes del docente
        $query = Reporte::where('docente_id', $docente->id)
            ->with(['estudiante.persona', 'periodo', 'gestion', 'docente.persona']);

        // Aplicar filtros
        if ($request->filled('estudiante_id')) {
            $query->where('estudiante_id', $request->estudiante_id);
        }

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        if ($request->filled('gestion_id')) {
            $query->where('gestion_id', $request->gestion_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('visible')) {
            $query->where('visible_tutor', $request->visible === '1');
        }

        $reportes = $query->orderBy('created_at', 'desc')->get();

        // ✅ USAR VISTA DE DOCENTE
        return view('docente.reportes.index', compact(
            'reportes',
            'estudiantes',
            'periodos',
            'gestiones',
            'docentes'
        ));
    }

    /**
     * ✅ GUARDAR NUEVO REPORTE - CON CÁLCULO AUTOMÁTICO
     */
    public function store(Request $request)
    {
        $docente = $this->getDocente();

        // Validación
        $request->validate([
            'estudiante_id_create' => 'required|exists:estudiantes,id',
            'tipo_create' => 'required|in:Bimestral,Trimestral,Anual',
            'periodo_id_create' => 'required|exists:periodos,id',
            'gestion_id_create' => 'required|exists:gestions,id',
            'comentario_final_create' => 'nullable|string|max:2000',
            'archivo_pdf_create' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // ✅ CORRECCIÓN: Obtener cursos sin ambigüedad
        $cursosDocente = DB::table('docente_curso')
            ->where('docente_id', $docente->id)
            ->pluck('curso_id');
        
        // Obtener matrículas del estudiante SOLO en cursos del docente
        $matriculasIds = DB::table('matriculas')
            ->where('estudiante_id', $request->estudiante_id_create)
            ->where('gestion_id', $request->gestion_id_create)
            ->whereIn('curso_id', $cursosDocente)
            ->pluck('id');

        // ✅ CALCULAR PROMEDIO (solo de los cursos del docente)
        $promedioNotas = DB::table('notas')
            ->where('periodo_id', $request->periodo_id_create)
            ->whereIn('matricula_id', $matriculasIds)
            ->avg('nota_final');

        // ✅ CALCULAR ASISTENCIA (solo de los cursos del docente)
        $totalAsistencias = DB::table('asistencias')
            ->where('estudiante_id', $request->estudiante_id_create)
            ->whereIn('curso_id', $cursosDocente)
            ->count();

        $asistenciasPresentes = DB::table('asistencias')
            ->where('estudiante_id', $request->estudiante_id_create)
            ->where('estado', 'Presente')
            ->whereIn('curso_id', $cursosDocente)
            ->count();

        $porcentajeAsistencia = $totalAsistencias > 0 
            ? round(($asistenciasPresentes / $totalAsistencias) * 100, 2) 
            : 0;

        // Preparar datos
        $data = [
            'estudiante_id' => $request->estudiante_id_create,
            'docente_id' => $docente->id,
            'tipo' => $request->tipo_create,
            'periodo_id' => $request->periodo_id_create,
            'gestion_id' => $request->gestion_id_create,
            'promedio_general' => $promedioNotas ? round($promedioNotas, 2) : null,
            'porcentaje_asistencia' => $porcentajeAsistencia,
            'comentario_final' => $request->comentario_final_create,
            'visible_tutor' => $request->has('visible_tutor_create'),
            'fecha_generacion' => now(),
        ];

        // Manejar archivo PDF
        if ($request->hasFile('archivo_pdf_create')) {
            $archivo = $request->file('archivo_pdf_create');
            $nombreArchivo = 'reporte_' . time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('reportes', $nombreArchivo, 'public');
            $data['archivo_pdf'] = $ruta;
        }

        Reporte::create($data);

        return redirect()->route('docente.reportes.index')
            ->with('mensaje', 'Reporte creado correctamente con cálculos automáticos')
            ->with('icono', 'success');
    }

    /**
     * ✅ ACTUALIZAR REPORTE
     */
    public function update(Request $request, $id)
    {
        $reporte = Reporte::findOrFail($id);
        $docente = $this->getDocente();

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes modificar este reporte')
                        ->with('icono', 'error');
        }

        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'tipo' => 'required|in:Bimestral,Trimestral,Anual',
            'periodo_id' => 'required|exists:periodos,id',
            'gestion_id' => 'required|exists:gestions,id',
            'promedio_general' => 'nullable|numeric|min:0|max:20',
            'porcentaje_asistencia' => 'nullable|numeric|min:0|max:100',
            'comentario_final' => 'nullable|string|max:2000',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = [
            'estudiante_id' => $request->estudiante_id,
            'tipo' => $request->tipo,
            'periodo_id' => $request->periodo_id,
            'gestion_id' => $request->gestion_id,
            'promedio_general' => $request->promedio_general,
            'porcentaje_asistencia' => $request->porcentaje_asistencia,
            'comentario_final' => $request->comentario_final,
            'visible_tutor' => $request->has('visible_tutor'),
        ];

        // Manejar archivo PDF
        if ($request->hasFile('archivo_pdf')) {
            if ($reporte->archivo_pdf && Storage::disk('public')->exists($reporte->archivo_pdf)) {
                Storage::disk('public')->delete($reporte->archivo_pdf);
            }

            $archivo = $request->file('archivo_pdf');
            $nombreArchivo = 'reporte_' . time() . '_' . $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('reportes', $nombreArchivo, 'public');
            $data['archivo_pdf'] = $ruta;
        }

        $reporte->update($data);

        return redirect()->route('docente.reportes.index')
            ->with('mensaje', 'Reporte actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar reporte
     */
    public function destroy($id)
    {
        $reporte = Reporte::findOrFail($id);
        $docente = $this->getDocente();

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes eliminar este reporte')
                        ->with('icono', 'error');
        }

        if ($reporte->archivo_pdf && Storage::disk('public')->exists($reporte->archivo_pdf)) {
            Storage::disk('public')->delete($reporte->archivo_pdf);
        }

        $reporte->delete();

        return redirect()->route('docente.reportes.index')
            ->with('mensaje', 'Reporte eliminado correctamente')
            ->with('icono', 'success');
    }

    /**
     * ✅ VER DETALLE - SOLO NOTAS/ASISTENCIAS/COMPORTAMIENTOS DE SUS CURSOS
     */
    public function show($id)
    {
        $reporte = Reporte::with([
            'estudiante.persona',
            'estudiante.grado.nivel',
            'periodo',
            'gestion',
            'docente.persona'
        ])->findOrFail($id);

        $docente = $this->getDocente();

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes ver este reporte')
                        ->with('icono', 'error');
        }

        // ✅ CORRECCIÓN: Obtener cursos sin ambigüedad
        $cursosDocente = DB::table('docente_curso')
            ->where('docente_id', $docente->id)
            ->pluck('curso_id');

        // Obtener matrículas del estudiante SOLO en cursos del docente
        $matriculasIds = DB::table('matriculas')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->where('gestion_id', $reporte->gestion_id)
            ->whereIn('curso_id', $cursosDocente)
            ->pluck('id');

        // ✅ NOTAS: Solo de los cursos del docente
        $notas = DB::table('notas as n')
            ->join('matriculas as m', 'n.matricula_id', '=', 'm.id')
            ->join('cursos as c', 'm.curso_id', '=', 'c.id')
            ->where('n.periodo_id', $reporte->periodo_id)
            ->whereIn('n.matricula_id', $matriculasIds)
            ->select(
                'n.*',
                'c.nombre as curso_nombre',
                'm.curso_id'
            )
            ->get()
            ->map(function($nota) {
                $nota->tipo_evaluacion_badge = match($nota->tipo_evaluacion) {
                    'Parcial' => 'info',
                    'Final' => 'primary',
                    'Práctica' => 'success',
                    'Oral' => 'warning',
                    'Trabajo' => 'secondary',
                    default => 'secondary'
                };
                
                $nota->estado_nota_badge = $nota->nota_final >= 11 ? 'success' : 'danger';
                $nota->estado_nota_texto = $nota->nota_final >= 11 ? 'Aprobado' : 'Desaprobado';
                
                $nota->matricula = (object)[
                    'curso' => (object)['nombre' => $nota->curso_nombre]
                ];
                
                return $nota;
            });

        // ✅ ASISTENCIAS: Solo de los cursos del docente
        $asistencias = DB::table('asistencias')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->whereIn('curso_id', $cursosDocente)
            ->get();

        // ✅ COMPORTAMIENTOS: Solo registrados por ESTE docente
        $comportamientos = Comportamiento::where('estudiante_id', $reporte->estudiante_id)
            ->where('docente_id', $docente->id)
            ->whereBetween('fecha', [
                $reporte->periodo->fecha_inicio,
                $reporte->periodo->fecha_fin
            ])
            ->get()
            ->map(function($comp) {
                $comp->tipo_badge = match($comp->tipo) {
                    'Positivo' => 'success',
                    'Negativo' => 'danger',
                    'Neutro' => 'secondary',
                    default => 'secondary'
                };
                
                $comp->tipo_icon = match($comp->tipo) {
                    'Positivo' => 'fa-thumbs-up',
                    'Negativo' => 'fa-thumbs-down',
                    'Neutro' => 'fa-meh',
                    default => 'fa-circle'
                };
                
                $comp->fecha_formateada = $comp->fecha->format('d/m/Y');
                
                return $comp;
            });

        return view('docente.reportes.show', compact('reporte', 'notas', 'asistencias', 'comportamientos'));
    }

    /**
     * Publicar reporte
     */
    public function publicar($id)
    {
        $reporte = Reporte::findOrFail($id);
        $docente = $this->getDocente();

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes publicar este reporte')
                        ->with('icono', 'error');
        }

        $reporte->update([
            'visible_tutor' => true,
            'fecha_publicacion' => now(),
        ]);

        return back()->with('mensaje', 'Reporte publicado para tutores')
                    ->with('icono', 'success');
    }

    /**
     * Despublicar reporte
     */
    public function despublicar($id)
    {
        $reporte = Reporte::findOrFail($id);
        $docente = $this->getDocente();

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes despublicar este reporte')
                        ->with('icono', 'error');
        }

        $reporte->update([
            'visible_tutor' => false,
            'fecha_publicacion' => null,
        ]);

        return back()->with('mensaje', 'Reporte despublicado')
                    ->with('icono', 'success');
    }

    /**
     * Descargar PDF del reporte
     */
    public function descargarPdf($id)
    {
        $reporte = Reporte::findOrFail($id);
        $docente = $this->getDocente();

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes descargar este reporte')
                        ->with('icono', 'error');
        }

        if (!$reporte->archivo_pdf || !Storage::disk('public')->exists($reporte->archivo_pdf)) {
            return back()->with('mensaje', 'El archivo PDF no existe')
                        ->with('icono', 'error');
        }

        return Storage::disk('public')->download($reporte->archivo_pdf);
    }

    /**
     * ✅ RECALCULAR DATOS - Solo de sus cursos
     */
    public function calcularDatos($id)
    {
        $reporte = Reporte::findOrFail($id);
        $docente = $this->getDocente();

        if ($reporte->docente_id != $docente->id) {
            return back()->with('mensaje', 'No puedes calcular datos de este reporte')
                        ->with('icono', 'error');
        }

        // ✅ CORRECCIÓN: Obtener cursos sin ambigüedad
        $cursosDocente = DB::table('docente_curso')
            ->where('docente_id', $docente->id)
            ->pluck('curso_id');

        // Calcular promedio de notas (solo cursos del docente)
        $matriculasIds = DB::table('matriculas')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->where('gestion_id', $reporte->gestion_id)
            ->whereIn('curso_id', $cursosDocente)
            ->pluck('id');

        $promedioNotas = DB::table('notas')
            ->where('periodo_id', $reporte->periodo_id)
            ->whereIn('matricula_id', $matriculasIds)
            ->avg('nota_final');

        // Calcular porcentaje de asistencia (solo cursos del docente)
        $totalAsistencias = DB::table('asistencias')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->whereIn('curso_id', $cursosDocente)
            ->count();

        $asistenciasPresentes = DB::table('asistencias')
            ->where('estudiante_id', $reporte->estudiante_id)
            ->where('estado', 'Presente')
            ->whereIn('curso_id', $cursosDocente)
            ->count();

        $porcentajeAsistencia = $totalAsistencias > 0 
            ? round(($asistenciasPresentes / $totalAsistencias) * 100, 2) 
            : 0;

        $reporte->update([
            'promedio_general' => $promedioNotas ? round($promedioNotas, 2) : null,
            'porcentaje_asistencia' => $porcentajeAsistencia,
        ]);

        return back()->with('mensaje', 'Datos recalculados correctamente (solo de tus cursos)')
                    ->with('icono', 'success');
    }
}