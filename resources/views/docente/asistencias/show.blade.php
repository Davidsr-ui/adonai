@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle de Asistencia</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información de la Asistencia</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $asistencia->estado_badge }} badge-lg">
                            {{ $asistencia->estado }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información del Estudiante -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-graduate"></i> Datos del Estudiante</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Nombre Completo</label>
                                        <p><strong>{{ $asistencia->estudiante->persona->nombres }} {{ $asistencia->estudiante->persona->apellidos }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">DNI</label>
                                        <p>{{ $asistencia->estudiante->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código Estudiante</label>
                                        <p><strong>{{ $asistencia->estudiante->codigo_estudiante }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Grado</label>
                                        <p>
                                            @if($asistencia->estudiante->grado)
                                                <span class="badge badge-info">
                                                    {{ $asistencia->estudiante->grado->nombre_completo }}
                                                </span>
                                            @else
                                                <span class="text-muted">No asignado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Estado del Estudiante</label>
                                        <p>
                                            @if($asistencia->estudiante->persona->estado == 'Activo')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Curso -->
                        <div class="col-md-6">
                            <h5><i class="fas fa-book"></i> Datos del Curso</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Curso</label>
                                        <p><strong>{{ $asistencia->curso->nombre }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Código del Curso</label>
                                        <p>{{ $asistencia->curso->codigo ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Área Curricular</label>
                                        <p>{{ $asistencia->curso->area_curricular ?? 'No especificada' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Docente</label>
                                        <p>
                                            @if($asistencia->docente)
                                                <strong>{{ $asistencia->docente->persona->nombres }} {{ $asistencia->docente->persona->apellidos }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $asistencia->docente->codigo_docente }}</small>
                                            @else
                                                <span class="text-muted">No asignado</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de la Asistencia -->
                    <h5 class="mt-4"><i class="fas fa-calendar-check"></i> Detalles de la Asistencia</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha</label>
                                <p>
                                    <strong>{{ $asistencia->fecha_formateada }}</strong>
                                    <br>
                                    <span class="badge badge-secondary">{{ $asistencia->dia_semana }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Estado</label>
                                <p>
                                    <span class="badge badge-{{ $asistencia->estado_badge }} badge-lg">
                                        @if($asistencia->estado == 'Presente')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($asistencia->estado == 'Ausente')
                                            <i class="fas fa-times-circle"></i>
                                        @elseif($asistencia->estado == 'Tardanza')
                                            <i class="fas fa-clock"></i>
                                        @else
                                            <i class="fas fa-file-alt"></i>
                                        @endif
                                        {{ $asistencia->estado }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Observaciones</label>
                                <p>{{ $asistencia->observaciones ?? 'Sin observaciones' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estadísticas del Estudiante -->
                    <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Estadísticas de Asistencia</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $asistencia->estudiante->asistencias()->count() }}</h3>
                                    <p>Total Registros</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $asistencia->estudiante->asistencias()->where('estado', 'Presente')->count() }}</h3>
                                    <p>Presentes</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $asistencia->estudiante->asistencias()->where('estado', 'Ausente')->count() }}</h3>
                                    <p>Ausencias</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $asistencia->estudiante->asistencias()->where('estado', 'Tardanza')->count() }}</h3>
                                    <p>Tardanzas</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Porcentaje de Asistencia -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Porcentaje de Asistencia General</span>
                                    <span class="info-box-number">
                                        {{ \App\Models\Asistencia::calcularPorcentajeAsistencia($asistencia->estudiante_id) }}%
                                    </span>
                                    <div class="progress">
                                        <div class="progress-bar" 
                                             style="width: {{ \App\Models\Asistencia::calcularPorcentajeAsistencia($asistencia->estudiante_id) }}%"></div>
                                    </div>
                                    <span class="progress-description">
                                        Del total de registros del estudiante
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fecha de Registro</label>
                                <p>{{ \Carbon\Carbon::parse($asistencia->created_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Última Actualización</label>
                                <p>{{ \Carbon\Carbon::parse($asistencia->updated_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('docente.asistencias.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        @if(session('mensaje'))
            Swal.fire({
                icon: '{{ session('icono') }}',
                title: '{{ session('mensaje') }}',
                showConfirmButton: true,
                timer: 3000
            });
        @endif
    </script>
@stop