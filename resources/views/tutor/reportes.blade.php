@extends('adminlte::page')

@section('title', 'Reportes de Mis Estudiantes')

@section('content_header')
    <h1><b>Reportes Académicos de Mis Estudiantes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fas fa-file-alt"></i> Reportes Publicados</h3>
                </div>
                <div class="card-body">
                    @if(Auth::user()->persona && Auth::user()->persona->tutor)
                        @php
                            // Obtener los estudiantes del tutor
                            $estudiantes = Auth::user()->persona->tutor->estudiantes;
                            $estudiantesIds = $estudiantes->pluck('id');
                            
                            // ✅ CORREGIDO: Sin ->curso, solo campos que existen
                            $reportes = \App\Models\Reporte::whereIn('estudiante_id', $estudiantesIds)
                                ->where('visible_tutor', true)
                                ->with(['estudiante.persona', 'estudiante.grado', 'periodo', 'gestion', 'docente.persona'])
                                ->orderBy('fecha_generacion', 'desc')
                                ->get();
                        @endphp
                        
                        @if($reportes->count() > 0)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Puedes ver los reportes académicos que los docentes han publicado de tus estudiantes.
                            </div>

                            <div class="row">
                                @foreach($reportes as $reporte)
                                    <div class="col-md-6">
                                        <div class="card card-outline card-primary">
                                            <div class="card-header">
                                                <h5 class="card-title">
                                                    <i class="fas fa-file-alt"></i>
                                                    Reporte {{ $reporte->tipo }} - {{ $reporte->periodo->nombre }}
                                                </h5>
                                                <div class="card-tools">
                                                    <span class="badge badge-{{ $reporte->tipo_badge }}">
                                                        {{ $reporte->tipo }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p>
                                                    <strong><i class="fas fa-user-graduate"></i> Estudiante:</strong><br>
                                                    {{ $reporte->estudiante->persona->apellidos }}, 
                                                    {{ $reporte->estudiante->persona->nombres }}
                                                </p>
                                                <p>
                                                    <strong><i class="fas fa-users"></i> Grado:</strong> 
                                                    <span class="badge badge-info">
                                                        {{ $reporte->estudiante->grado->nombre_completo ?? 'Sin grado' }}
                                                    </span>
                                                </p>
                                                <p>
                                                    <strong><i class="fas fa-calendar"></i> Periodo:</strong> 
                                                    <span class="badge badge-secondary">{{ $reporte->periodo->nombre }}</span>
                                                </p>
                                                <p>
                                                    <strong><i class="fas fa-calendar-alt"></i> Gestión:</strong> 
                                                    {{ $reporte->gestion->año }}
                                                </p>
                                                <p>
                                                    <strong><i class="fas fa-chalkboard-teacher"></i> Docente:</strong> 
                                                    {{ $reporte->docente->persona->apellidos }}, {{ $reporte->docente->persona->nombres }}
                                                </p>
                                                <hr>
                                                
                                                <!-- Métricas -->
                                                <div class="row">
                                                    @if($reporte->promedio_general)
                                                    <div class="col-6">
                                                        <div class="info-box bg-{{ $reporte->estado_promedio_badge }}">
                                                            <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                                            <div class="info-box-content">
                                                                <span class="info-box-text">Promedio</span>
                                                                <span class="info-box-number">{{ number_format($reporte->promedio_general, 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    
                                                    @if($reporte->porcentaje_asistencia)
                                                    <div class="col-6">
                                                        <div class="info-box bg-{{ $reporte->estado_asistencia_badge }}">
                                                            <span class="info-box-icon"><i class="fas fa-clipboard-check"></i></span>
                                                            <div class="info-box-content">
                                                                <span class="info-box-text">Asistencia</span>
                                                                <span class="info-box-number">{{ number_format($reporte->porcentaje_asistencia, 1) }}%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>

                                                @if($reporte->comentario_final)
                                                <div class="mt-2">
                                                    <p><strong>Comentario del Docente:</strong></p>
                                                    <div class="alert alert-light" style="max-height: 150px; overflow-y: auto;">
                                                        {{ \Str::limit($reporte->comentario_final, 200) }}
                                                    </div>
                                                </div>
                                                @endif

                                                <button type="button" 
                                                        class="btn btn-primary btn-block" 
                                                        data-toggle="modal" 
                                                        data-target="#verReporteModal{{ $reporte->id }}">
                                                    <i class="fas fa-eye"></i> Ver Reporte Completo
                                                </button>

                                                @if($reporte->tienePdf())
                                                <a href="{{ route('tutor.reportes.descargar-pdf', $reporte->id) }}" 
                                                   class="btn btn-danger btn-block mt-2">
                                                    <i class="fas fa-file-pdf"></i> Descargar PDF
                                                </a>
                                                @endif
                                            </div>
                                            <div class="card-footer text-muted">
                                                <small>
                                                    <i class="fas fa-clock"></i> 
                                                    Generado: {{ $reporte->fecha_generacion_formateada }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Ver Completo -->
                                    <div class="modal fade" id="verReporteModal{{ $reporte->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-file-alt"></i> 
                                                        Reporte {{ $reporte->tipo }} - {{ $reporte->periodo->nombre }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Información del Estudiante -->
                                                    <div class="card card-outline card-info">
                                                        <div class="card-header">
                                                            <h5 class="card-title"><i class="fas fa-user"></i> Información del Estudiante</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p><strong>Estudiante:</strong><br>
                                                                    {{ $reporte->estudiante->persona->apellidos }}, 
                                                                    {{ $reporte->estudiante->persona->nombres }}</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <p><strong>DNI:</strong><br>
                                                                    {{ $reporte->estudiante->persona->dni }}</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <p><strong>Código:</strong><br>
                                                                    {{ $reporte->estudiante->codigo_estudiante }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <p><strong>Grado:</strong><br>
                                                                    <span class="badge badge-info badge-lg">
                                                                        {{ $reporte->estudiante->grado->nombre_completo ?? 'Sin grado' }}
                                                                    </span>
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <p><strong>Periodo:</strong><br>
                                                                    <span class="badge badge-secondary badge-lg">{{ $reporte->periodo->nombre }}</span>
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <p><strong>Gestión:</strong><br>
                                                                    <span class="badge badge-dark badge-lg">{{ $reporte->gestion->año }}</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Métricas de Rendimiento -->
                                                    <div class="row">
                                                        @if($reporte->promedio_general)
                                                        <div class="col-md-6">
                                                            <div class="info-box bg-{{ $reporte->estado_promedio_badge }}">
                                                                <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text">Promedio General</span>
                                                                    <span class="info-box-number">{{ number_format($reporte->promedio_general, 2) }}</span>
                                                                    <span class="progress-description">
                                                                        Estado: <strong>{{ $reporte->estado_promedio_texto }}</strong>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        
                                                        @if($reporte->porcentaje_asistencia)
                                                        <div class="col-md-6">
                                                            <div class="info-box bg-{{ $reporte->estado_asistencia_badge }}">
                                                                <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text">Porcentaje de Asistencia</span>
                                                                    <span class="info-box-number">{{ number_format($reporte->porcentaje_asistencia, 1) }}%</span>
                                                                    <span class="progress-description">
                                                                        @if($reporte->porcentaje_asistencia >= 90)
                                                                            Excelente asistencia
                                                                        @elseif($reporte->porcentaje_asistencia >= 75)
                                                                            Buena asistencia
                                                                        @elseif($reporte->porcentaje_asistencia >= 60)
                                                                            Asistencia regular
                                                                        @else
                                                                            Necesita mejorar
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <!-- Comentario del Docente -->
                                                    @if($reporte->comentario_final)
                                                    <div class="card card-outline card-success">
                                                        <div class="card-header">
                                                            <h5 class="card-title"><i class="fas fa-comment"></i> Comentario del Docente</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <p><strong>Docente:</strong> {{ $reporte->docente->persona->apellidos }}, {{ $reporte->docente->persona->nombres }}</p>
                                                            <div class="alert alert-light" style="white-space: pre-line;">
                                                                {{ $reporte->comentario_final }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    <!-- Información Adicional -->
                                                    <div class="card card-outline card-secondary">
                                                        <div class="card-header">
                                                            <h5 class="card-title"><i class="fas fa-info-circle"></i> Información del Reporte</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <p><strong>Tipo de Reporte:</strong><br>
                                                                    <span class="badge badge-{{ $reporte->tipo_badge }} badge-lg">{{ $reporte->tipo }}</span>
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <p><strong>Fecha de Generación:</strong><br>
                                                                    {{ $reporte->fecha_generacion_formateada }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <p><strong>Publicado:</strong><br>
                                                                    {{ $reporte->fecha_publicacion_formateada }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    @if($reporte->tienePdf())
                                                    <a href="{{ route('tutor.reportes.descargar-pdf', $reporte->id) }}" 
                                                       class="btn btn-danger">
                                                        <i class="fas fa-file-pdf"></i> Descargar PDF
                                                    </a>
                                                    @endif
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Cerrar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Estadísticas -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5><b>Resumen de Reportes</b></h5>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3>{{ $reportes->count() }}</h3>
                                            <p>Total Reportes</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $reportes->where('tipo', 'Bimestral')->count() }}</h3>
                                            <p>Bimestrales</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $reportes->where('tipo', 'Trimestral')->count() }}</h3>
                                            <p>Trimestrales</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $reportes->where('tipo', 'Anual')->count() }}</h3>
                                            <p>Anuales</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay reportes publicados para tus estudiantes en este momento.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Tu perfil de tutor no está completo. Por favor contacta al administrador.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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