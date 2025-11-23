@extends('adminlte::page')

@section('content_header')
    <h1><b>Ficha del Alumno</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <!-- Columna izquierda - Datos del estudiante -->
        <div class="col-md-8">
            <!-- Información Personal -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user"></i> Información Personal</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>DNI:</strong> {{ $estudiante->persona->dni ?? 'N/A' }}</p>
                            <p><strong>Nombres:</strong> {{ $estudiante->persona->nombres ?? 'N/A' }}</p>
                            <p><strong>Apellidos:</strong> {{ $estudiante->persona->apellidos ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Código:</strong> {{ $estudiante->codigo_estudiante ?? 'N/A' }}</p>
                            <p><strong>Grado:</strong> {{ $estudiante->grado->nombre ?? 'N/A' }}</p>
                            <p><strong>Nivel:</strong> {{ $estudiante->grado->nivel->nombre ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Fecha de Nacimiento:</strong> 
                                @if($estudiante->persona && $estudiante->persona->fecha_nacimiento)
                                    {{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}
                                @else
                                    N/A
                                @endif
                            </p>
                            <p><strong>Género:</strong> 
                                @if($estudiante->persona)
                                    @if($estudiante->persona->genero == 'M') Masculino
                                    @elseif($estudiante->persona->genero == 'F') Femenino
                                    @else Otro @endif
                                @else N/A @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Año de Ingreso:</strong> {{ $estudiante->año_ingreso ?? 'N/A' }}</p>
                            <p><strong>Condición:</strong> 
                                <span class="badge badge-{{ $estudiante->condicion == 'Regular' ? 'success' : 'warning' }}">
                                    {{ $estudiante->condicion ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Asistencia -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-check"></i> Resumen de Asistencia</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Porcentaje de Asistencia</span>
                                    <span class="info-box-number">{{ $porcentajeAsistencia }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Últimas Asistencias</span>
                                    <span class="info-box-number">{{ $asistencias->count() }} registros</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($asistencias->count() > 0)
                        <h6 class="mt-3"><strong>Últimas 10 asistencias:</strong></h6>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Curso</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistencias as $asistencia)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ $asistencia->curso->nombre ?? 'N/A' }}</td>
                                        <td>
                                            @if($asistencia->estado == 'Presente')
                                                <span class="badge badge-success">Presente</span>
                                            @elseif($asistencia->estado == 'Falta')
                                                <span class="badge badge-danger">Falta</span>
                                            @elseif($asistencia->estado == 'Tardanza')
                                                <span class="badge badge-warning">Tardanza</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $asistencia->estado }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

            <!-- Comportamiento -->
            @if($comportamientos->count() > 0)
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exclamation-circle"></i> Observaciones de Comportamiento</h3>
                    </div>
                    <div class="card-body">
                        @foreach($comportamientos as $comp)
                            <div class="alert alert-{{ $comp->tipo == 'Positivo' ? 'success' : 'warning' }}">
                                <strong>{{ \Carbon\Carbon::parse($comp->fecha)->format('d/m/Y') }}</strong> - 
                                <span class="badge badge-{{ $comp->tipo == 'Positivo' ? 'success' : 'warning' }}">
                                    {{ $comp->tipo }}
                                </span><br>
                                {{ $comp->descripcion }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Notas -->
            @if(isset($notas) && $notas->count() > 0)
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line"></i> Notas</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Periodo</th>
                                    <th>Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notas as $nota)
                                    <tr>
                                        <td>{{ $nota->curso->nombre ?? 'N/A' }}</td>
                                        <td>{{ $nota->periodo->nombre ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $nota->nota >= 14 ? 'success' : 'danger' }}">
                                                {{ $nota->nota }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Columna derecha - Tutores -->
        <div class="col-md-4">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> Tutores / Apoderados</h3>
                </div>
                <div class="card-body">
                    @if($tutores->count() > 0)
                        @foreach($tutores as $tutor)
                            <div class="card mb-2">
                                <div class="card-body p-2">
                                    <h6 class="card-title">
                                        <i class="fas fa-user"></i>
                                        {{ $tutor->nombres }} {{ $tutor->apellidos }}
                                        @if($tutor->tipo == 'Principal')
                                            <span class="badge badge-primary">Principal</span>
                                        @else
                                            <span class="badge badge-secondary">Secundario</span>
                                        @endif
                                    </h6>
                                    <p class="card-text mb-1">
                                        <strong>Relación:</strong> {{ $tutor->relacion_familiar }}<br>
                                        <strong>Teléfono:</strong> {{ $tutor->telefono ?? 'N/A' }}<br>
                                        @if($tutor->telefono_emergencia)
                                            <strong>Emergencia:</strong> {{ $tutor->telefono_emergencia }}
                                        @endif
                                    </p>
                                    @if($tutor->user_id)
                                        <a href="{{ route('docente.mensajeria') }}?destinatario_user_id={{ $tutor->user_id }}&estudiante_id={{ $estudiante->id }}" 
                                           class="btn btn-sm btn-primary btn-block">
                                            <i class="fas fa-envelope"></i> Enviar Mensaje
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No hay tutores registrados.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Acciones -->
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('docente.mis-alumnos') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Volver a la Lista
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop