@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Reporte Académico</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Información del Reporte</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $reporte->tipo_badge }} badge-lg">
                            {{ $reporte->tipo }}
                        </span>
                        @if($reporte->promedio_general)
                        <span class="badge badge-{{ $reporte->estado_promedio_badge }} badge-lg">
                            Promedio: {{ number_format($reporte->promedio_general, 2) }}
                        </span>
                        @endif
                        @if($reporte->visible_tutor)
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-eye"></i> Visible Tutores
                        </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información General -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-user-graduate"></i> Datos del Estudiante</h5>
                            <hr>
                            <div class="form-group">
                                <label>Nombre Completo</label>
                                <p><strong>{{ $reporte->estudiante->persona->nombres }} {{ $reporte->estudiante->persona->apellidos }}</strong></p>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DNI</label>
                                        <p>{{ $reporte->estudiante->persona->dni }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Código</label>
                                        <p><strong>{{ $reporte->estudiante->codigo_estudiante }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Grado</label>
                                <p>
                                    @if($reporte->estudiante->grado)
                                        <span class="badge badge-info">
                                            {{ $reporte->estudiante->grado->nombre_completo }}
                                        </span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5><i class="fas fa-clipboard-list"></i> Datos Académicos</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Periodo</label>
                                        <p>
                                            <span class="badge badge-secondary badge-lg">
                                                {{ $reporte->periodo->nombre }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Gestión</label>
                                        <p><strong>{{ $reporte->gestion->año }}</strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Docente Generador</label>
                                <p><strong>{{ $reporte->docente->persona->nombres }} {{ $reporte->docente->persona->apellidos }}</strong></p>
                            </div>
                            <div class="form-group">
                                <label>Tipo de Reporte</label>
                                <p>
                                    <span class="badge badge-{{ $reporte->tipo_badge }} badge-lg">
                                        {{ $reporte->tipo }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Calificaciones y Asistencia -->
                    <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Calificaciones y Asistencia</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-{{ $reporte->estado_promedio_badge ?? 'secondary' }}">
                                <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Promedio General</span>
                                    <span class="info-box-number" style="font-size: 2.5rem;">
                                        <strong>{{ $reporte->promedio_general ? number_format($reporte->promedio_general, 2) : 'N/A' }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-{{ $reporte->estado_asistencia_badge ?? 'secondary' }}">
                                <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Asistencia</span>
                                    <span class="info-box-number" style="font-size: 2.5rem;">
                                        <strong>{{ $reporte->porcentaje_asistencia ? number_format($reporte->porcentaje_asistencia, 1) . '%' : 'N/A' }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-graduation-cap"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Estado Académico</span>
                                    <span class="info-box-number" style="font-size: 1.5rem;">
                                        @if($reporte->promedio_general)
                                            {{ $reporte->estado_promedio_texto }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-gradient-danger">
                                <span class="info-box-icon"><i class="fas fa-file-pdf"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Archivo PDF</span>
                                    <span class="info-box-number" style="font-size: 1.5rem;">
                                        @if($reporte->tienePdf())
                                            <a href="{{ route('admin.reportes.descargar-pdf', $reporte->id) }}" class="text-white">
                                                <i class="fas fa-download"></i> Descargar
                                            </a>
                                        @else
                                            No disponible
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($reporte->promedio_general)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-{{ $reporte->estado_promedio_badge }}">
                                <h5><i class="fas fa-info-circle"></i> Estado Académico</h5>
                                <p>
                                    El estudiante obtuvo un promedio de <strong>{{ number_format($reporte->promedio_general, 2) }}</strong> puntos,
                                    lo cual es considerado como <strong>{{ $reporte->estado_promedio_texto }}</strong>.
                                    @if($reporte->estaAprobado())
                                        El estudiante ha <strong>APROBADO</strong> este periodo.
                                    @else
                                        El estudiante está <strong>DESAPROBADO</strong> en este periodo.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Notas del Periodo -->
                    <h5 class="mt-4"><i class="fas fa-clipboard-list"></i> Notas del Periodo</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Curso</th>
                                    <th>Tipo Evaluación</th>
                                    <th class="text-center">N. Práctica</th>
                                    <th class="text-center">N. Teoría</th>
                                    <th class="text-center">N. Final</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notas as $nota)
                                <tr>
                                    <td>{{ $nota->matricula->curso->nombre }}</td>
                                    <td>
                                        <span class="badge badge-{{ $nota->tipo_evaluacion_badge }}">
                                            {{ $nota->tipo_evaluacion }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $nota->nota_practica ?? '-' }}</td>
                                    <td class="text-center">{{ $nota->nota_teoria ?? '-' }}</td>
                                    <td class="text-center">
                                        <strong class="badge badge-{{ $nota->estado_nota_badge }}">
                                            {{ $nota->nota_final }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $nota->estado_nota_badge }}">
                                            {{ $nota->estado_nota_texto }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No hay notas registradas en este periodo</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Registro de Asistencias -->
                    <h5 class="mt-4"><i class="fas fa-calendar-check"></i> Registro de Asistencias</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $asistencias->count() }}</h3>
                                    <p>Total Registros</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $asistencias->where('estado', 'Presente')->count() }}</h3>
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
                                    <h3>{{ $asistencias->where('estado', 'Ausente')->count() }}</h3>
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
                                    <h3>{{ $asistencias->where('estado', 'Tardanza')->count() }}</h3>
                                    <p>Tardanzas</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comportamientos del Periodo -->
                    <h5 class="mt-4"><i class="fas fa-user-check"></i> Comportamientos del Periodo</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Sanción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comportamientos as $comportamiento)
                                <tr>
                                    <td>{{ $comportamiento->fecha_formateada }}</td>
                                    <td>
                                        <span class="badge badge-{{ $comportamiento->tipo_badge }}">
                                            <i class="fas {{ $comportamiento->tipo_icon }}"></i>
                                            {{ $comportamiento->tipo }}
                                        </span>
                                    </td>
                                    <td>{{ \Str::limit($comportamiento->descripcion, 100) }}</td>
                                    <td>
                                        @if($comportamiento->sancion)
                                            <span class="badge badge-warning">{{ $comportamiento->sancion }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay comportamientos registrados en este periodo</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Comentario Final -->
                    @if($reporte->comentario_final)
                    <h5 class="mt-4"><i class="fas fa-comment-alt"></i> Comentario Final del Docente</h5>
                    <hr>
                    <div class="callout callout-info">
                        <p style="white-space: pre-line;">{{ $reporte->comentario_final }}</p>
                    </div>
                    @endif

                    <!-- Información del Sistema -->
                    <h5 class="mt-4"><i class="fas fa-info-circle"></i> Información del Sistema</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha de Generación</label>
                                <p>{{ $reporte->fecha_generacion_formateada }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha de Publicación</label>
                                <p>{{ $reporte->fecha_publicacion_formateada }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fecha de Registro</label>
                                <p>{{ \Carbon\Carbon::parse($reporte->created_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Última Actualización</label>
                                <p>{{ \Carbon\Carbon::parse($reporte->updated_at)->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ route('admin.reportes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <a href="{{ route('admin.estudiantes.show', $reporte->estudiante->id) }}" class="btn btn-info">
                                    <i class="fas fa-user-graduate"></i> Ver Perfil del Estudiante
                                </a>
                                <a href="{{ route('admin.docentes.show', $reporte->docente->id) }}" class="btn btn-success">
                                    <i class="fas fa-chalkboard-teacher"></i> Ver Perfil del Docente
                                </a>
                                @if($reporte->tienePdf())
                                <a href="{{ route('admin.reportes.descargar-pdf', $reporte->id) }}" class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Descargar PDF
                                </a>
                                @endif
                                @if(!$reporte->visible_tutor)
                                <form action="{{ route('admin.reportes.publicar', $reporte->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-eye"></i> Publicar para Tutores
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.reportes.despublicar', $reporte->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fas fa-eye-slash"></i> Despublicar
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.reportes.calcular-datos', $reporte->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-calculator"></i> Calcular Datos Automáticamente
                                    </button>
                                </form>
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