@extends('adminlte::page')

@section('title', 'Dashboard Tutor')

@section('content_header')
    <h1><b>Panel del Tutor</b></h1>
    <hr>
@stop

@section('content')
    <!-- Mensaje de Bienvenida -->
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-success">
                <h5><i class="fas fa-info"></i> Bienvenido, {{ Auth::user()->nombre_completo }}</h5>
                Desde este panel puedes ver el progreso académico y asistencias de los estudiantes bajo tu tutoría.
            </div>
        </div>
    </div>

    @if(!Auth::user()->persona || !Auth::user()->persona->tutor)
        <!-- Alerta si no tiene perfil de tutor -->
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <h4><i class="fas fa-exclamation-triangle"></i> Perfil Incompleto</h4>
                    <p>Tu perfil de tutor no está completo o no está asociado correctamente.</p>
                    <p>Por favor, contacta al administrador para completar tu perfil.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Estadísticas Principales -->
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $estudiantes->count() }}</h3>
                        <p>Estudiantes a mi Cargo</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('tutor.mis-estudiantes') }}" class="small-box-footer">
                        Ver estudiantes <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalReportes }}</h3>
                        <p>Reportes Disponibles</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <a href="{{ route('tutor.reportes.index') }}" class="small-box-footer">
                        Ver reportes <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Alertas Importantes -->
        @if(count($alertas) > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Alertas Importantes</h3>
                        <div class="card-tools">
                            <span class="badge badge-warning">{{ count($alertas) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($alertas as $alerta)
                        <div class="alert alert-{{ $alerta['tipo'] }}">
                            <i class="{{ $alerta['icono'] }}"></i>
                            {{ $alerta['mensaje'] }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Mis Estudiantes -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-users"></i> Mis Estudiantes</h3>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $estudiantes->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($estudiantes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>DNI</th>
                                            <th>Grado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($estudiantes as $estudiante)
                                            <tr>
                                                <td>
                                                    <strong>{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</strong>
                                                </td>
                                                <td>{{ $estudiante->persona->dni }}</td>
                                                <td>
                                                    @if($estudiante->grado)
                                                        <span class="badge badge-info">
                                                            {{ $estudiante->grado->nombre }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No tienes estudiantes asignados aún.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('tutor.mis-estudiantes') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> Ver Todos Mis Estudiantes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bolt"></i> Accesos Rápidos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <a href="{{ route('tutor.mis-estudiantes') }}" class="btn btn-info btn-block btn-lg">
                                    <i class="fas fa-users fa-2x"></i>
                                    <br>
                                    <small>Mis Estudiantes</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('tutor.notas') }}" class="btn btn-warning btn-block btn-lg">
                                    <i class="fas fa-star fa-2x"></i>
                                    <br>
                                    <small>Ver Notas</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('tutor.asistencias') }}" class="btn btn-danger btn-block btn-lg">
                                    <i class="fas fa-clipboard-check fa-2x"></i>
                                    <br>
                                    <small>Ver Asistencias</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('tutor.comportamientos') }}" class="btn btn-purple btn-block btn-lg">
                                    <i class="fas fa-user-check fa-2x"></i>
                                    <br>
                                    <small>Comportamientos</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('tutor.reportes.index') }}" class="btn btn-secondary btn-block btn-lg">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                    <br>
                                    <small>Reportes</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('tutor.horarios') }}" class="btn btn-primary btn-block btn-lg">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                    <br>
                                    <small>Horarios</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notificaciones Recientes -->
        <div class="row">
            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bell"></i> Últimos Comportamientos Notificados</h3>
                        <div class="card-tools">
                            <span class="badge badge-warning">{{ $ultimosComportamientos->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($ultimosComportamientos->count() > 0)
                            <div class="timeline">
                                @foreach($ultimosComportamientos as $comp)
                                <div>
                                    <i class="fas {{ $comp->tipo_icon }} bg-{{ $comp->tipo_badge }}"></i>
                                    <div class="timeline-item">
                                        <span class="time">
                                            <i class="fas fa-clock"></i> {{ $comp->fecha_formateada }}
                                        </span>
                                        <h3 class="timeline-header">
                                            <strong>{{ $comp->estudiante->persona->apellidos }}, {{ $comp->estudiante->persona->nombres }}</strong>
                                        </h3>
                                        <div class="timeline-body">
                                            <span class="badge badge-{{ $comp->tipo_badge }}">{{ $comp->tipo }}</span>
                                            <p>{{ \Str::limit($comp->descripcion, 100) }}</p>
                                            @if($comp->docente)
                                                <small class="text-muted">
                                                    <i class="fas fa-chalkboard-teacher"></i>
                                                    {{ $comp->docente->persona->apellidos }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No hay comportamientos notificados recientes.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('tutor.comportamientos') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-eye"></i> Ver Todos los Comportamientos
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-alt"></i> Últimos Reportes Académicos</h3>
                        <div class="card-tools">
                            <span class="badge badge-light">{{ $ultimosReportes->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($ultimosReportes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Periodo</th>
                                            <th>Tipo</th>
                                            <th>Promedio</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ultimosReportes as $reporte)
                                        <tr>
                                            <td>
                                                <strong>{{ $reporte->estudiante->persona->apellidos }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $reporte->periodo->nombre }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $reporte->tipo_badge }}">
                                                    {{ $reporte->tipo }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($reporte->promedio_general)
                                                    <span class="badge badge-{{ $reporte->estado_promedio_badge }}">
                                                        {{ number_format($reporte->promedio_general, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('tutor.reportes.show', $reporte->id) }}" 
                                                   class="btn btn-xs btn-primary" 
                                                   title="Ver reporte">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No hay reportes disponibles aún.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('tutor.reportes.index') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> Ver Todos los Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <style>
        .small-box-footer {
            text-decoration: none;
        }
        .btn-purple {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }
        .btn-purple:hover {
            background-color: #5a32a3;
            border-color: #5a32a3;
            color: white;
        }
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #dee2e6;
            left: 31px;
            margin: 0;
        }
        .timeline > div {
            margin-bottom: 15px;
            position: relative;
        }
        .timeline > div > .timeline-item {
            margin-left: 60px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 10px;
        }
        .timeline > div > .fas {
            width: 30px;
            height: 30px;
            font-size: 15px;
            line-height: 30px;
            position: absolute;
            color: #fff;
            background: #6c757d;
            border-radius: 50%;
            text-align: center;
            left: 18px;
            top: 0;
        }
        .timeline > div > .timeline-item > .time {
            color: #999;
            float: right;
            padding: 10px;
            font-size: 12px;
        }
        .timeline > div > .timeline-item > .timeline-header {
            margin: 0;
            color: #555;
            border-bottom: 1px solid #f4f4f4;
            padding: 10px;
            font-size: 16px;
            line-height: 1.1;
        }
        .timeline > div > .timeline-item > .timeline-body {
            padding: 10px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            @if(session('mensaje'))
                Swal.fire({
                    icon: '{{ session('icono') }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            @endif
        });
    </script>
@stop