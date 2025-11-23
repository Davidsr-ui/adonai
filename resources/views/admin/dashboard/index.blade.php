@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1><b><i class="fas fa-tachometer-alt"></i> Dashboard del Sistema</b></h1>
        </div>
        <div class="col-md-4 text-right">
            @if($gestionActiva)
                <span class="badge badge-success badge-lg">
                    <i class="fas fa-calendar"></i> {{ $gestionActiva->nombre }}
                </span>
            @endif
            @if($periodoActivo)
                <span class="badge badge-info badge-lg">
                    <i class="fas fa-clock"></i> {{ $periodoActivo->nombre }}
                </span>
            @endif
        </div>
    </div>
    <hr>
@stop

@section('content')
    <!-- Estadísticas Principales -->
    <div class="row">
        <!-- Usuarios -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['usuarios']['total'] }}</h3>
                    <p>Usuarios del Sistema</p>
                    <small>Activos: {{ $estadisticas['usuarios']['activos'] }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.usuarios.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Estudiantes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['estudiantes']['total'] }}</h3>
                    <p>Estudiantes</p>
                    <small>Activos: {{ $estadisticas['estudiantes']['activos'] }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('admin.estudiantes.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Docentes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['docentes']['total'] }}</h3>
                    <p>Docentes</p>
                    <small>Activos: {{ $estadisticas['docentes']['activos'] }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('admin.docentes.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Tutores -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $estadisticas['tutores']['total'] ?? 0 }}</h3>
                    <p>Tutores</p>
                    <small>Activos: {{ $estadisticas['tutores']['activos'] ?? 0 }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <a href="{{ route('admin.tutores.index') }}" class="small-box-footer">
                    Ver más <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas Académicas -->
    <div class="row">
        <div class="col-md-3">
            <div class="info-box bg-primary">
                <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Grados Activos</span>
                    <span class="info-box-number">{{ $estadisticas['academico']['grados'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box bg-secondary">
                <span class="info-box-icon"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Cursos Totales</span>
                    <span class="info-box-number">{{ $estadisticas['academico']['cursos'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Matrículas Activas</span>
                    <span class="info-box-number">{{ $estadisticas['academico']['matriculas_activas'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Comportamientos</span>
                    <span class="info-box-number">{{ $estadisticas['registros']['comportamientos'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Principales -->
    <div class="row">
        <!-- Gráfico: Estudiantes por Nivel -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Estudiantes por Nivel</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($graficos['estudiantes_por_nivel']->count() > 0)
                        <canvas id="chartEstudiantesNivel" height="200"></canvas>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No hay datos de estudiantes por nivel.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Gráfico: Estudiantes por Grado -->
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Top 10 Grados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($graficos['estudiantes_por_grado']->count() > 0)
                        <canvas id="chartEstudiantesGrado" height="200"></canvas>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No hay datos de estudiantes por grado.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas y Notificaciones -->
    @if(($alertas['estudiantes_sin_matricula']->count() > 0) || 
        ($alertas['cursos_sin_docente']->count() > 0) || 
        (isset($alertas['estudiantes_bajo_rendimiento']) && $alertas['estudiantes_bajo_rendimiento']->count() > 0) ||
        (isset($alertas['estudiantes_inasistencias']) && $alertas['estudiantes_inasistencias']->count() > 0))
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Alertas del Sistema</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($alertas['estudiantes_sin_matricula']->count() > 0)
                        <div class="col-md-3">
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-user-times"></i> Sin Matrícula</h5>
                                <p><strong>{{ $alertas['estudiantes_sin_matricula']->count() }}</strong> estudiantes sin matrícula en la gestión actual.</p>
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalSinMatricula">
                                    Ver lista
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($alertas['cursos_sin_docente']->count() > 0)
                        <div class="col-md-3">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-chalkboard"></i> Sin Docente</h5>
                                <p><strong>{{ $alertas['cursos_sin_docente']->count() }}</strong> cursos sin docente asignado.</p>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalSinDocente">
                                    Ver lista
                                </button>
                            </div>
                        </div>
                        @endif

                        @if(isset($alertas['estudiantes_bajo_rendimiento']) && $alertas['estudiantes_bajo_rendimiento']->count() > 0)
                        <div class="col-md-3">
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-arrow-down"></i> Bajo Rendimiento</h5>
                                <p><strong>{{ $alertas['estudiantes_bajo_rendimiento']->count() }}</strong> estudiantes con promedio menor a 11.</p>
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalBajoRendimiento">
                                    Ver lista
                                </button>
                            </div>
                        </div>
                        @endif

                        @if(isset($alertas['estudiantes_inasistencias']) && $alertas['estudiantes_inasistencias']->count() > 0)
                        <div class="col-md-3">
                            <div class="alert alert-secondary">
                                <h5><i class="fas fa-calendar-times"></i> Inasistencias</h5>
                                <p><strong>{{ $alertas['estudiantes_inasistencias']->count() }}</strong> estudiantes con 5+ inasistencias (30 días).</p>
                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#modalInasistencias">
                                    Ver lista
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Estadísticas de Comportamientos y Reportes -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-check"></i> Comportamientos Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($graficos['comportamientos_por_tipo']) && $graficos['comportamientos_por_tipo']->count() > 0)
                        <canvas id="chartComportamientos" height="200"></canvas>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No hay comportamientos registrados.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line"></i> Gestión Académica</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center mb-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Matrículas Totales</span>
                                    <span class="info-box-number">{{ $estadisticas['academico']['matriculas'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center mb-3">
                            <div class="info-box bg-primary">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Matrículas Activas</span>
                                    <span class="info-box-number">{{ $estadisticas['academico']['matriculas_activas'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Comportamientos</span>
                                    <span class="info-box-number">{{ $estadisticas['registros']['comportamientos'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-file-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Reportes</span>
                                    <span class="info-box-number">{{ $estadisticas['registros']['reportes'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rankings -->
    @if(isset($rankings['mejores_estudiantes']) && $rankings['mejores_estudiantes']->count() > 0)
    <div class="row">
        <!-- Mejores Estudiantes -->
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-trophy"></i> Top 10 Mejores Estudiantes</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th class="text-center">Promedio</th>
                                <th class="text-center">Notas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rankings['mejores_estudiantes'] as $index => $estudiante)
                            <tr>
                                <td>
                                    @if($index < 3)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-medal"></i> {{ $index + 1 }}
                                        </span>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td>{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success">
                                        {{ number_format($estudiante->promedio, 2) }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $estudiante->total_notas }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cursos Más Populares -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-fire"></i> Cursos Más Populares</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(isset($rankings['cursos_mas_matriculas']) && $rankings['cursos_mas_matriculas']->count() > 0)
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Curso</th>
                                <th class="text-center">Matrículas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rankings['cursos_mas_matriculas'] as $index => $curso)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $curso->nombre }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $curso->total }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="p-3">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> No hay matrículas registradas.
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Actividad Reciente -->
    @if(isset($actividadReciente))
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-history"></i> Actividad Reciente</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Últimas Matrículas -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-clipboard-list"></i> Últimas Matrículas</h5>
                            @if(isset($actividadReciente['ultimas_matriculas']) && $actividadReciente['ultimas_matriculas']->count() > 0)
                            <ul class="list-group">
                                @foreach($actividadReciente['ultimas_matriculas'] as $matricula)
                                <li class="list-group-item">
                                    <strong>{{ $matricula->estudiante->persona->nombres }} {{ $matricula->estudiante->persona->apellidos }}</strong>
                                    <br>
                                    <small>
                                        {{ $matricula->curso->nombre }}
                                        @if($matricula->grado)
                                            - {{ $matricula->grado->nombre }}
                                        @endif
                                        <span class="float-right text-muted">{{ $matricula->created_at->diffForHumans() }}</span>
                                    </small>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <div class="alert alert-info">No hay matrículas recientes.</div>
                            @endif
                        </div>

                        <!-- Últimos Comportamientos -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-check"></i> Últimos Comportamientos</h5>
                            @if(isset($actividadReciente['ultimos_comportamientos']) && $actividadReciente['ultimos_comportamientos']->count() > 0)
                            <ul class="list-group">
                                @foreach($actividadReciente['ultimos_comportamientos'] as $comp)
                                <li class="list-group-item">
                                    <span class="badge badge-{{ $comp->tipo == 'Positivo' ? 'success' : ($comp->tipo == 'Negativo' ? 'danger' : 'secondary') }}">
                                        {{ $comp->tipo }}
                                    </span>
                                    <strong>{{ $comp->estudiante->persona->nombres }} {{ $comp->estudiante->persona->apellidos }}</strong>
                                    <br>
                                    <small>
                                        {{ \Str::limit($comp->descripcion, 50) }}
                                        <span class="float-right text-muted">{{ \Carbon\Carbon::parse($comp->fecha)->diffForHumans() }}</span>
                                    </small>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <div class="alert alert-info">No hay comportamientos recientes.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODALES DE ALERTAS -->
    
    <!-- Modal: Estudiantes Sin Matrícula -->
    <div class="modal fade" id="modalSinMatricula" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-user-times"></i> Estudiantes Sin Matrícula</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    @if($alertas['estudiantes_sin_matricula']->count() > 0)
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Estudiante</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alertas['estudiantes_sin_matricula'] as $est)
                            <tr>
                                <td>{{ $est->persona->dni }}</td>
                                <td>{{ $est->persona->nombres }} {{ $est->persona->apellidos }}</td>
                                <td><span class="badge badge-{{ $est->persona->estado == 'Activo' ? 'success' : 'danger' }}">{{ $est->persona->estado }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Cursos Sin Docente -->
    <div class="modal fade" id="modalSinDocente" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><i class="fas fa-chalkboard"></i> Cursos Sin Docente</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    @if($alertas['cursos_sin_docente']->count() > 0)
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alertas['cursos_sin_docente'] as $curso)
                            <tr>
                                <td><strong>{{ $curso->nombre }}</strong></td>
                                <td>{{ $curso->descripcion }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Bajo Rendimiento -->
    @if(isset($alertas['estudiantes_bajo_rendimiento']) && $alertas['estudiantes_bajo_rendimiento']->count() > 0)
    <div class="modal fade" id="modalBajoRendimiento" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-arrow-down"></i> Estudiantes con Bajo Rendimiento</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th class="text-center">Promedio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alertas['estudiantes_bajo_rendimiento'] as $est)
                            <tr>
                                <td>{{ $est->nombres }} {{ $est->apellidos }}</td>
                                <td class="text-center">
                                    <span class="badge badge-danger">{{ number_format($est->promedio, 2) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal: Inasistencias -->
    @if(isset($alertas['estudiantes_inasistencias']) && $alertas['estudiantes_inasistencias']->count() > 0)
    <div class="modal fade" id="modalInasistencias" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title"><i class="fas fa-calendar-times"></i> Estudiantes con Inasistencias</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Estudiantes con 5 o más ausencias en los últimos 30 días.</p>
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Estudiante</th>
                                <th class="text-center">Ausencias</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alertas['estudiantes_inasistencias'] as $est)
                            <tr>
                                <td>{{ $est->nombres }} {{ $est->apellidos }}</td>
                                <td class="text-center">
                                    <span class="badge badge-danger">{{ $est->total_ausencias }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }
        .small-box {
            border-radius: 0.25rem;
        }
        .info-box {
            min-height: 80px;
        }
        .bg-purple {
            background-color: #6f42c1 !important;
            color: white;
        }
        .bg-purple .small-box-footer {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        $(document).ready(function() {
            
            // Gráfico: Estudiantes por Nivel
            @if(isset($graficos['estudiantes_por_nivel']) && $graficos['estudiantes_por_nivel']->count() > 0)
            var ctxNivel = document.getElementById('chartEstudiantesNivel').getContext('2d');
            new Chart(ctxNivel, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($graficos['estudiantes_por_nivel']->pluck('nombre')) !!},
                    datasets: [{
                        label: 'Estudiantes',
                        data: {!! json_encode($graficos['estudiantes_por_nivel']->pluck('total')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
            @endif

            // Gráfico: Estudiantes por Grado
            @if(isset($graficos['estudiantes_por_grado']) && $graficos['estudiantes_por_grado']->count() > 0)
            var ctxGrado = document.getElementById('chartEstudiantesGrado').getContext('2d');
            new Chart(ctxGrado, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($graficos['estudiantes_por_grado']->pluck('nombre')) !!},
                    datasets: [{
                        data: {!! json_encode($graficos['estudiantes_por_grado']->pluck('total')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)',
                            'rgba(199, 199, 199, 0.5)',
                            'rgba(83, 102, 255, 0.5)',
                            'rgba(255, 99, 255, 0.5)',
                            'rgba(99, 255, 132, 0.5)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
            @endif

            // Gráfico: Comportamientos
            @if(isset($graficos['comportamientos_por_tipo']) && $graficos['comportamientos_por_tipo']->count() > 0)
            var ctxComp = document.getElementById('chartComportamientos').getContext('2d');
            new Chart(ctxComp, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($graficos['comportamientos_por_tipo']->pluck('tipo')) !!},
                    datasets: [{
                        data: {!! json_encode($graficos['comportamientos_por_tipo']->pluck('total')) !!},
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(201, 203, 207, 0.5)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
            @endif

        });
    </script>
@stop