@extends('adminlte::page')

@section('title', 'Dashboard Docente')

@section('content_header')
    <h1><b>Panel del Docente</b></h1>
    <hr>
@stop

@section('content')
    <!-- Mensaje de Bienvenida -->
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Bienvenido, {{ Auth::user()->nombre_completo }}</h5>
                Desde este panel puedes gestionar tus cursos, registrar asistencias y calificaciones de tus estudiantes.
            </div>
        </div>
    </div>

    @if(!Auth::user()->persona || !Auth::user()->persona->docente)
        <!-- Alerta si no tiene perfil de docente -->
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    <h4><i class="fas fa-exclamation-triangle"></i> Perfil Incompleto</h4>
                    <p>Tu perfil de docente no está completo o no está asociado correctamente.</p>
                    <p>Por favor, contacta al administrador para completar tu perfil.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Estadísticas Principales -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $asignaciones->count() }}</h3>
                        <p>Asignaciones Totales</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <a href="{{ route('docente.mis-cursos') }}" class="small-box-footer">
                        Ver cursos <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $estudiantes->count() }}</h3>
                        <p>Estudiantes Activos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('docente.mis-alumnos') }}" class="small-box-footer">
                        Ver mis alumnos <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalAsistencias }}</h3>
                        <p>Asistencias (7 días)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <a href="{{ route('docente.asistencias.index') }}" class="small-box-footer">
                        Registrar asistencias <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $notasRegistradas }}</h3>
                        <p>Notas Registradas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <a href="{{ route('docente.notas.index') }}" class="small-box-footer">
                        Registrar notas <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Segunda fila de estadísticas -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Presentes</span>
                        <span class="info-box-number">{{ $presentes }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-times"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ausentes</span>
                        <span class="info-box-number">{{ $ausentes }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-smile"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Comportamientos +</span>
                        <span class="info-box-number">{{ $comportamientosPositivos }}</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-frown"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Comportamientos -</span>
                        <span class="info-box-number">{{ $comportamientosNegativos }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Mis Cursos -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-book"></i> Mis Cursos y Grados Asignados</h3>
                    </div>
                    <div class="card-body">
                        @if($asignaciones->count() > 0)
                            <ul class="list-group">
                                @foreach($asignaciones->take(5) as $asignacion)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $asignacion->curso->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-users"></i> {{ $asignacion->grado->nombre_completo }}
                                            </small>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-primary badge-pill">
                                                {{ $asignacion->curso->creditos }} créditos
                                            </span>
                                            @if($asignacion->es_tutor_aula)
                                                <br>
                                                <span class="badge badge-success mt-1">
                                                    <i class="fas fa-star"></i> Tutor
                                                </span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @if($asignaciones->count() > 5)
                                <small class="text-muted">Mostrando 5 de {{ $asignaciones->count() }} asignaciones</small>
                            @endif
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No tienes cursos asignados aún.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('docente.mis-cursos') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> Ver Todos Mis Cursos
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
                                <a href="{{ route('docente.mis-alumnos') }}" class="btn btn-info btn-block btn-lg">
                                    <i class="fas fa-users fa-2x"></i>
                                    <br>
                                    <small>Mis Alumnos</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('docente.asistencias.index') }}" class="btn btn-warning btn-block btn-lg">
                                    <i class="fas fa-clipboard-check fa-2x"></i>
                                    <br>
                                    <small>Asistencias</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('docente.notas.index') }}" class="btn btn-danger btn-block btn-lg">
                                    <i class="fas fa-star fa-2x"></i>
                                    <br>
                                    <small>Notas</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('docente.comportamientos.index') }}" class="btn btn-purple btn-block btn-lg">
                                    <i class="fas fa-user-check fa-2x"></i>
                                    <br>
                                    <small>Comportamientos</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('docente.reportes.index') }}" class="btn btn-secondary btn-block btn-lg">
                                    <i class="fas fa-file-alt fa-2x"></i>
                                    <br>
                                    <small>Reportes</small>
                                </a>
                            </div>
                            <div class="col-6 mb-3">
                                <a href="{{ route('docente.mensajeria') }}" class="btn btn-primary btn-block btn-lg">
                                    <i class="fas fa-envelope fa-2x"></i>
                                    <br>
                                    <small>Mensajería</small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Adicionales -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar"></i> Estadísticas de Notas</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="info-box bg-primary">
                                    <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total</span>
                                        <span class="info-box-number">{{ $notasRegistradas }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-eye"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Publicadas</span>
                                        <span class="info-box-number">{{ $notasPublicadas }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-check"></i> Comportamientos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center mb-2">
                                <h4 class="text-muted">
                                    Total: <strong>{{ $comportamientosPositivos + $comportamientosNegativos }}</strong>
                                </h4>
                            </div>
                            <div class="col-6 text-center">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-smile"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Positivos</span>
                                        <span class="info-box-number">{{ $comportamientosPositivos }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="info-box bg-danger">
                                    <span class="info-box-icon"><i class="fas fa-frown"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Negativos</span>
                                        <span class="info-box-number">{{ $comportamientosNegativos }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-alt"></i> Reportes Académicos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="info-box bg-secondary">
                                    <span class="info-box-icon"><i class="fas fa-list"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total</span>
                                        <span class="info-box-number">{{ $reportes->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Publicados</span>
                                        <span class="info-box-number">{{ $reportesPublicados }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimas Actividades -->
        <div class="row">
            <!-- Últimas Asistencias -->
            <div class="col-md-6">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Últimas Asistencias Registradas</h3>
                    </div>
                    <div class="card-body">
                        @if($ultimasAsistencias && $ultimasAsistencias->count() > 0)
                            <ul class="list-group">
                                @foreach($ultimasAsistencias as $asistencia)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $asistencia->estudiante->persona->apellidos }}, {{ $asistencia->estudiante->persona->nombres }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $asistencia->curso->nombre }}</small>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-{{ $asistencia->estado_badge }}">
                                                    {{ $asistencia->estado }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $asistencia->fecha_formateada }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No has registrado asistencias recientemente.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('docente.asistencias.index') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-plus"></i> Registrar Asistencias
                        </a>
                    </div>
                </div>
            </div>

            <!-- Últimas Notas -->
            <div class="col-md-6">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-star"></i> Últimas Notas Registradas</h3>
                    </div>
                    <div class="card-body">
                        @if($ultimasNotas && $ultimasNotas->count() > 0)
                            <ul class="list-group">
                                @foreach($ultimasNotas as $nota)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $nota->matricula->estudiante->persona->apellidos }}, {{ $nota->matricula->estudiante->persona->nombres }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $nota->matricula->curso->nombre }}</small>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-{{ $nota->estado_nota_badge }} badge-lg">
                                                    {{ $nota->nota_final }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $nota->tipo_evaluacion }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                No has registrado notas recientemente.
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('docente.notas.index') }}" class="btn btn-danger btn-block">
                            <i class="fas fa-plus"></i> Registrar Notas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Gestión Actual -->
        @if($gestionActual)
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Gestión y Periodo Actual</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>Gestión Actual:</strong></h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> {{ $gestionActual->nombre }}
                                    <span class="badge badge-success">Activo</span>
                                </p>
                            </div>
                            @if($periodoActual)
                            <div class="col-md-6">
                                <h5><strong>Periodo Actual:</strong></h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar-week"></i> {{ $periodoActual->nombre }}
                                    <span class="badge badge-info">Activo</span>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
@stop

@section('css')
    <style>
        .small-box-footer {
            text-decoration: none;
        }
        .badge-lg {
            font-size: 1.2rem;
            padding: 0.5rem 0.8rem;
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