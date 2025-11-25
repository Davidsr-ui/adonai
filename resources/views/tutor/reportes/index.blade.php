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
                    <div class="card-tools">
                        @if($tutor->estudiantes->count() > 1)
                            <select id="estudianteSelect" class="form-control form-control-sm" onchange="cambiarEstudiante()">
                                @foreach($tutor->estudiantes as $est)
                                    <option value="{{ $est->id }}" {{ $estudiante && $estudiante->id == $est->id ? 'selected' : '' }}>
                                        {{ $est->persona->apellidos }} {{ $est->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($estudiante)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-user"></i> Estudiante:</strong> {{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}<br>
                            <strong><i class="fas fa-graduation-cap"></i> Grado:</strong> {{ $estudiante->grado->nombre ?? 'N/A' }}<br>
                            <strong><i class="fas fa-layer-group"></i> Nivel:</strong> {{ $estudiante->grado->nivel->nombre ?? 'N/A' }}
                        </div>

                        @if($reportes->count() > 0)
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

                                                <!-- ✅ BOTÓN QUE VA AL SHOW COMPLETO -->
                                                <a href="{{ route('tutor.reportes.show', $reporte->id) }}" 
                                                   class="btn btn-primary btn-block">
                                                    <i class="fas fa-eye"></i> Ver Reporte Completo
                                                </a>

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
                                            <h3>{{ $totalReportes }}</h3>
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
                                            <h3>{{ $bimestrales }}</h3>
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
                                            <h3>{{ $trimestrales }}</h3>
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
                                            <h3>{{ $anuales }}</h3>
                                            <p>Anuales</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('tutor.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No hay reportes publicados para este estudiante.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No tienes estudiantes asignados.
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
        function cambiarEstudiante() {
            const estudianteId = document.getElementById('estudianteSelect').value;
            window.location.href = '{{ route("tutor.reportes.index") }}?estudiante_id=' + estudianteId;
        }

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