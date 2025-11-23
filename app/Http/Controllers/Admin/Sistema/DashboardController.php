<?php

namespace App\Http\Controllers\Admin\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Persona;
use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Tutor;
use App\Models\Administrador;
use App\Models\Gestion;
use App\Models\Periodo;
use App\Models\Grado;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\Comportamiento;
use App\Models\Reporte;
use App\Models\Mensaje;
use App\Models\Notificacion;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener gestión y periodo activos
        $gestionActiva = Gestion::where('estado', 'Activo')->first();
        $periodoActivo = Periodo::where('estado', 'Activo')->first();
        
        // Estadísticas Generales
        $estadisticas = [
            'usuarios' => $this->obtenerEstadisticasUsuarios(),
            'personas' => $this->obtenerEstadisticasPersonas(),
            'estudiantes' => $this->obtenerEstadisticasEstudiantes(),
            'docentes' => $this->obtenerEstadisticasDocentes(),
            'academico' => $this->obtenerEstadisticasAcademicas($gestionActiva),
            'registros' => $this->obtenerEstadisticasRegistros($gestionActiva, $periodoActivo),
            'mensajeria' => $this->obtenerEstadisticasMensajeria(),
        ];
        
        // Gráficos
        $graficos = [
            'estudiantes_por_nivel' => $this->obtenerEstudiantesPorNivel($gestionActiva),
            'estudiantes_por_grado' => $this->obtenerEstudiantesPorGrado($gestionActiva),
            'matriculas_por_mes' => $this->obtenerMatriculasPorMes($gestionActiva),
            'asistencias_ultimos_7_dias' => $this->obtenerAsistenciasUltimos7Dias(),
            'notas_distribucion' => $this->obtenerDistribucionNotas($periodoActivo),
            'comportamientos_por_tipo' => $this->obtenerComportamientosPorTipo($gestionActiva),
        ];
        
        // Actividad Reciente
        $actividadReciente = [
            'ultimas_matriculas' => $this->obtenerUltimasMatriculas(5),
            'ultimos_mensajes' => $this->obtenerUltimosMensajes(5),
            'ultimas_notas' => $this->obtenerUltimasNotas(5),
            'ultimos_comportamientos' => $this->obtenerUltimosComportamientos(5),
        ];
        
        // Alertas y Notificaciones
        $alertas = [
            'estudiantes_sin_matricula' => $this->obtenerEstudiantesSinMatricula($gestionActiva),
            'cursos_sin_docente' => $this->obtenerCursosSinDocente($gestionActiva),
            'estudiantes_bajo_rendimiento' => $this->obtenerEstudiantesBajoRendimiento($periodoActivo),
            'estudiantes_inasistencias' => $this->obtenerEstudiantesConInasistencias(),
        ];
        
        // Top Rankings
        $rankings = [
            'mejores_estudiantes' => $this->obtenerMejoresEstudiantes($periodoActivo, 10),
            'cursos_mas_matriculas' => $this->obtenerCursosConMasMatriculas($gestionActiva, 5),
            'docentes_mas_cursos' => $this->obtenerDocentesConMasCursos($gestionActiva, 5),
        ];
        
        return view('admin.dashboard.index', compact(
            'estadisticas',
            'graficos',
            'actividadReciente',
            'alertas',
            'rankings',
            'gestionActiva',
            'periodoActivo'
        ));
    }

    /**
     * Estadísticas de Usuarios
     */
    private function obtenerEstadisticasUsuarios()
    {
        return [
            'total' => User::count(),
            'activos' => User::whereHas('persona', function($q) {
                $q->where('estado', 'Activo');
            })->count(),
            'verificados' => User::whereNotNull('email_verified_at')->count(),
            'con_persona' => User::has('persona')->count(),
        ];
    }

    /**
     * Estadísticas de Personas
     */
    private function obtenerEstadisticasPersonas()
    {
        return [
            'total' => Persona::count(),
            'activas' => Persona::where('estado', 'Activo')->count(),
            'con_usuario' => Persona::whereNotNull('user_id')->count(),
            'sin_usuario' => Persona::whereNull('user_id')->count(),
        ];
    }

    /**
     * Estadísticas de Estudiantes
     */
    private function obtenerEstadisticasEstudiantes()
    {
        return [
            'total' => Estudiante::count(),
            'activos' => Estudiante::whereHas('persona', function($q) {
                $q->where('estado', 'Activo');
            })->count(),
            'con_tutor' => DB::table('tutor_estudiante')->distinct('estudiante_id')->count(),
            'sin_tutor' => Estudiante::whereDoesntHave('tutores')->count(),
        ];
    }

    /**
     * Estadísticas de Docentes
     */
    private function obtenerEstadisticasDocentes()
    {
        return [
            'total' => Docente::count(),
            'activos' => Docente::whereHas('persona', function($q) {
                $q->where('estado', 'Activo');
            })->count(),
            'nombrados' => Docente::where('tipo_contrato', 'Nombrado')->count(),
            'contratados' => Docente::where('tipo_contrato', 'Contratado')->count(),
        ];
    }

    /**
     * Estadísticas Académicas
     */
    private function obtenerEstadisticasAcademicas($gestion)
    {
        return [
            'gestiones' => Gestion::count(),
            'periodos' => Periodo::count(),
            'grados' => Grado::count(),
            'cursos' => Curso::count(),
            'matriculas' => $gestion ? Matricula::where('gestion_id', $gestion->id)->count() : 0,
            'matriculas_activas' => $gestion ? Matricula::where('gestion_id', $gestion->id)
                ->where('estado', 'Matriculado')->count() : 0,
        ];
    }

    /**
     * Estadísticas de Registros
     */
    private function obtenerEstadisticasRegistros($gestion, $periodo)
    {
        $notas = $periodo ? Nota::where('periodo_id', $periodo->id)->count() : 0;
        $asistencias = Asistencia::whereDate('fecha', Carbon::today())->count();
        $comportamientos = $gestion ? Comportamiento::whereYear('fecha', $gestion->año)->count() : 0;
        $reportes = $gestion ? Reporte::where('gestion_id', $gestion->id)->count() : 0;

        return [
            'notas' => $notas,
            'asistencias_hoy' => $asistencias,
            'comportamientos' => $comportamientos,
            'reportes' => $reportes,
        ];
    }

    /**
     * Estadísticas de Mensajería
     */
    private function obtenerEstadisticasMensajeria()
    {
        return [
            'mensajes_total' => Mensaje::count(),
            'mensajes_hoy' => Mensaje::whereDate('created_at', Carbon::today())->count(),
            'notificaciones' => DB::table('notificaciones')->count(),
            'notificaciones_no_leidas' => DB::table('notificaciones')->where('leido', false)->count(),
        ];
    }

    /**
     * Estudiantes por Nivel
     */
    private function obtenerEstudiantesPorNivel($gestion)
    {
        if (!$gestion) return collect([]);

        return DB::table('matriculas')
            ->join('grados', 'matriculas.grado_id', '=', 'grados.id')
            ->join('nivels', 'grados.nivel_id', '=', 'nivels.id')
            ->where('matriculas.gestion_id', $gestion->id)
            ->select('nivels.nombre', DB::raw('count(distinct matriculas.estudiante_id) as total'))
            ->groupBy('nivels.nombre', 'nivels.orden')
            ->orderBy('nivels.orden')
            ->get();
    }

    /**
     * Estudiantes por Grado
     */
    private function obtenerEstudiantesPorGrado($gestion)
    {
        if (!$gestion) return collect([]);

        return DB::table('matriculas')
            ->join('grados', 'matriculas.grado_id', '=', 'grados.id')
            ->where('matriculas.gestion_id', $gestion->id)
            ->select('grados.nombre', DB::raw('count(distinct matriculas.estudiante_id) as total'))
            ->groupBy('grados.id', 'grados.nombre')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    /**
     * Matrículas por Mes
     */
    private function obtenerMatriculasPorMes($gestion)
    {
        if (!$gestion) return collect([]);

        return Matricula::where('gestion_id', $gestion->id)
            ->select(
                DB::raw('MONTH(created_at) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                return [
                    'mes' => $meses[$item->mes - 1],
                    'total' => $item->total
                ];
            });
    }

    /**
     * Asistencias últimos 7 días
     */
    private function obtenerAsistenciasUltimos7Dias()
    {
        $fechaInicio = Carbon::now()->subDays(6);
        
        return DB::table('asistencias')
            ->where('fecha', '>=', $fechaInicio)
            ->select(
                'fecha',
                DB::raw('SUM(CASE WHEN estado = "Presente" THEN 1 ELSE 0 END) as presentes'),
                DB::raw('SUM(CASE WHEN estado = "Ausente" THEN 1 ELSE 0 END) as ausentes'),
                DB::raw('SUM(CASE WHEN estado = "Tardanza" THEN 1 ELSE 0 END) as tardanzas')
            )
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->map(function($item) {
                return [
                    'fecha' => Carbon::parse($item->fecha)->format('d/m'),
                    'presentes' => $item->presentes,
                    'ausentes' => $item->ausentes,
                    'tardanzas' => $item->tardanzas,
                ];
            });
    }

    /**
     * Distribución de Notas
     */
    private function obtenerDistribucionNotas($periodo)
    {
        if (!$periodo) return collect([]);

        return DB::table('notas')
            ->where('periodo_id', $periodo->id)
            ->select(
                DB::raw('CASE 
                    WHEN nota_final >= 18 THEN "Excelente (18-20)"
                    WHEN nota_final >= 14 THEN "Bueno (14-17)"
                    WHEN nota_final >= 11 THEN "Regular (11-13)"
                    ELSE "Deficiente (0-10)"
                END as rango'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->groupBy('rango')
            ->get();
    }

    /**
     * Comportamientos por Tipo
     */
    private function obtenerComportamientosPorTipo($gestion)
    {
        if (!$gestion) return collect([]);

        return Comportamiento::whereYear('fecha', $gestion->año)
            ->select('tipo', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo')
            ->get();
    }

    /**
     * Últimas Matrículas
     */
    private function obtenerUltimasMatriculas($limit = 5)
    {
        return Matricula::with(['estudiante.persona', 'curso', 'grado'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Últimos Mensajes
     */
    private function obtenerUltimosMensajes($limit = 5)
    {
        return Mensaje::with(['remitente.persona'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Últimas Notas Publicadas
     */
    private function obtenerUltimasNotas($limit = 5)
    {
        return Nota::with(['matricula.estudiante.persona', 'docente.persona'])
            ->whereNotNull('fecha_publicacion')
            ->orderBy('fecha_publicacion', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Últimos Comportamientos
     */
    private function obtenerUltimosComportamientos($limit = 5)
    {
        return Comportamiento::with(['estudiante.persona', 'docente.persona'])
            ->orderBy('fecha', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Estudiantes sin Matrícula
     */
    private function obtenerEstudiantesSinMatricula($gestion)
    {
        if (!$gestion) return collect();

        return Estudiante::with('persona')
            ->whereHas('persona', function($q) {
                $q->where('estado', 'Activo');
            })
            ->whereDoesntHave('matriculas', function($q) use ($gestion) {
                $q->where('gestion_id', $gestion->id);
            })
            ->limit(10)
            ->get();
    }

    /**
     * Cursos sin Docente Asignado
     */
    private function obtenerCursosSinDocente($gestion)
    {
        if (!$gestion) return collect();

        return Curso::whereDoesntHave('asignaciones', function($q) use ($gestion) {
            $q->where('gestion_id', $gestion->id);
        })
        ->limit(10)
        ->get();
    }

    /**
     * Estudiantes con Bajo Rendimiento
     */
    private function obtenerEstudiantesBajoRendimiento($periodo)
    {
        if (!$periodo) return collect();

        return DB::table('notas')
            ->join('matriculas', 'notas.matricula_id', '=', 'matriculas.id')
            ->join('estudiantes', 'matriculas.estudiante_id', '=', 'estudiantes.id')
            ->join('personas', 'estudiantes.persona_id', '=', 'personas.id')
            ->where('notas.periodo_id', $periodo->id)
            ->select(
                'estudiantes.id',
                'personas.nombres',
                'personas.apellidos',
                DB::raw('AVG(notas.nota_final) as promedio')
            )
            ->groupBy('estudiantes.id', 'personas.nombres', 'personas.apellidos')
            ->having('promedio', '<', 11)
            ->orderBy('promedio', 'asc')
            ->limit(10)
            ->get();
    }

    /**
     * Estudiantes con Inasistencias
     */
    private function obtenerEstudiantesConInasistencias()
    {
        $fechaInicio = Carbon::now()->subDays(30);

        return DB::table('asistencias')
            ->join('estudiantes', 'asistencias.estudiante_id', '=', 'estudiantes.id')
            ->join('personas', 'estudiantes.persona_id', '=', 'personas.id')
            ->where('asistencias.fecha', '>=', $fechaInicio)
            ->where('asistencias.estado', 'Ausente')
            ->select(
                'estudiantes.id',
                'personas.nombres',
                'personas.apellidos',
                DB::raw('COUNT(*) as total_ausencias')
            )
            ->groupBy('estudiantes.id', 'personas.nombres', 'personas.apellidos')
            ->having('total_ausencias', '>=', 5)
            ->orderByDesc('total_ausencias')
            ->limit(10)
            ->get();
    }

    /**
     * Mejores Estudiantes
     */
    private function obtenerMejoresEstudiantes($periodo, $limit = 10)
    {
        if (!$periodo) return collect();

        return DB::table('notas')
            ->join('matriculas', 'notas.matricula_id', '=', 'matriculas.id')
            ->join('estudiantes', 'matriculas.estudiante_id', '=', 'estudiantes.id')
            ->join('personas', 'estudiantes.persona_id', '=', 'personas.id')
            ->where('notas.periodo_id', $periodo->id)
            ->select(
                'estudiantes.id',
                'personas.nombres',
                'personas.apellidos',
                'personas.foto_perfil',
                DB::raw('AVG(notas.nota_final) as promedio'),
                DB::raw('COUNT(notas.id) as total_notas')
            )
            ->groupBy('estudiantes.id', 'personas.nombres', 'personas.apellidos', 'personas.foto_perfil')
            ->having('total_notas', '>=', 3)
            ->orderByDesc('promedio')
            ->limit($limit)
            ->get();
    }

    /**
     * Cursos con Más Matrículas
     */
    private function obtenerCursosConMasMatriculas($gestion, $limit = 5)
    {
        if (!$gestion) return collect();

        return DB::table('matriculas')
            ->join('cursos', 'matriculas.curso_id', '=', 'cursos.id')
            ->where('matriculas.gestion_id', $gestion->id)
            ->select('cursos.nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('cursos.id', 'cursos.nombre')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Docentes con Más Cursos
     */
    private function obtenerDocentesConMasCursos($gestion, $limit = 5)
    {
        if (!$gestion) return collect();

        return DB::table('docente_curso')
            ->join('docentes', 'docente_curso.docente_id', '=', 'docentes.id')
            ->join('personas', 'docentes.persona_id', '=', 'personas.id')
            ->where('docente_curso.gestion_id', $gestion->id)
            ->select(
                'personas.nombres',
                'personas.apellidos',
                DB::raw('COUNT(DISTINCT docente_curso.curso_id) as total_cursos')
            )
            ->groupBy('docentes.id', 'personas.nombres', 'personas.apellidos')
            ->orderByDesc('total_cursos')
            ->limit($limit)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.dashboard.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.dashboard.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.dashboard.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('admin.dashboard.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('admin.dashboard.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('admin.dashboard.index');
    }
}