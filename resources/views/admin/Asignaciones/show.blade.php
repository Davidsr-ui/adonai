@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-chalkboard-teacher text-primary"></i>
        <span class="fw-bold fs-4">Detalle de la Asignación</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información de la Asignación
                </h5>
                <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary btn-sm rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Datos del Docente -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-user-tie me-2"></i>Datos del Docente</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Nombre Completo</label>
                                <p><strong>{{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellidos }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">DNI</label>
                                <p>{{ $asignacion->docente->persona->dni }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Código Docente</label>
                                <p><strong>{{ $asignacion->docente->codigo_docente }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Especialidad</label>
                                <p>{{ $asignacion->docente->especialidad ?? 'No especificada' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Tipo de Contrato</label>
                                <p>
                                    @php
                                        $contratoClase = match($asignacion->docente->tipo_contrato) {
                                            'Nombrado' => 'bg-success',
                                            'Contratado' => 'bg-info',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp
                                    <span class="badge {{ $contratoClase }}">{{ $asignacion->docente->tipo_contrato }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Datos Académicos -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-book me-2"></i>Datos Académicos</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Curso</label>
                                <p><strong>{{ $asignacion->curso->nombre }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Código del Curso</label>
                                <p>{{ $asignacion->curso->codigo ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Área Curricular</label>
                                <p>{{ $asignacion->curso->area_curricular ?? 'No especificada' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Créditos</label>
                                <p><span class="badge bg-primary">{{ $asignacion->curso->creditos }} créditos</span></p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Horas Semanales</label>
                                <p><span class="badge bg-warning text-dark">{{ $asignacion->curso->horas_semanales }} horas</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Información del Grado -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-layer-group me-2"></i>Información del Grado</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Nivel</label>
                                <p><span class="badge bg-cyan text-white">{{ $asignacion->grado->nivel->nombre ?? 'N/A' }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Grado</label>
                                <p><strong>{{ $asignacion->grado->nombre_completo }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Capacidad</label>
                                <p>{{ $asignacion->grado->capacidad_maxima }} estudiantes</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Turno</label>
                                <p>
                                    @if($asignacion->grado->turno)
                                        <span class="badge bg-secondary">{{ $asignacion->grado->turno->nombre }}</span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión y Tutoría -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-calendar-alt me-2"></i>Gestión y Tutoría</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Gestión</label>
                                <p><span class="badge bg-secondary">{{ $asignacion->gestion->nombre }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Año</label>
                                <p>{{ $asignacion->gestion->año }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Estado Gestión</label>
                                <p>
                                    @php
                                        $gestionClase = match($asignacion->gestion->estado) {
                                            'Activo' => 'bg-success',
                                            'Finalizado' => 'bg-secondary',
                                            default => 'bg-info',
                                        };
                                    @endphp
                                    <span class="badge {{ $gestionClase }}">{{ $asignacion->gestion->estado }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Tutor de Aula</label>
                                <p>
                                    @if($asignacion->es_tutor_aula)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> SÍ - Es tutor de este grado</span>
                                        <br>
                                        <small class="text-muted">Este docente es el tutor responsable del grado {{ $asignacion->grado->nombre_completo }}</small>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i> NO - Solo docente de curso</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <h5 class="mt-4"><i class="fas fa-chart-bar me-2"></i>Información Adicional</h5>
                <hr>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="small-box bg-info p-3 rounded text-center">
                            <h3>{{ $asignacion->grado->estudiantes->count() }}</h3>
                            <p class="mb-0">Estudiantes en el Grado</p>
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success p-3 rounded text-center">
                            <h3>{{ $asignacion->curso->creditos }}</h3>
                            <p class="mb-0">Créditos del Curso</p>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning p-3 rounded text-center">
                            <h3>{{ $asignacion->curso->horas_semanales }}</h3>
                            <p class="mb-0">Horas Semanales</p>
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-danger p-3 rounded text-center">
                            <h3>{{ $asignacion->docente->docenteCursos->count() }}</h3>
                            <p class="mb-0">Total Asignaciones Docente</p>
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="fw-semibold">Fecha de Asignación</label>
                        <p>{{ \Carbon\Carbon::parse($asignacion->created_at)->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-semibold">Última Actualización</label>
                        <p>{{ \Carbon\Carbon::parse($asignacion->updated_at)->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="d-flex gap-2 justify-content-start">
                            <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                            </a>
                            <a href="{{ route('admin.docentes.show', $asignacion->docente->id) }}" class="btn btn-outline-info rounded-pill">
                                <i class="fas fa-user-tie me-1"></i> Ver Perfil del Docente
                            </a>
                            <a href="{{ route('admin.cursos.show', $asignacion->curso->id) }}" class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-book me-1"></i> Ver Detalles del Curso
                            </a>
                            <a href="{{ route('admin.grados.show', $asignacion->grado->id) }}" class="btn btn-outline-success rounded-pill">
                                <i class="fas fa-layer-group me-1"></i> Ver Detalles del Grado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .gap-2 { gap: 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; padding-left: 0.9rem; padding-right: 0.9rem; }
    .small-box { background: rgba(0,0,0,0.05); transition: all 0.2s; }
    .small-box i { font-size: 1.8rem; margin-top: -0.3rem; opacity: 0.5; }

    /* Modo oscuro */
    body[data-bs-theme="dark"] .card,
    body[data-bs-theme="dark"] .modal-content {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .card-header.bg-white {
        background-color: #171c2c !important;
        border-bottom-color: #2a3446 !important;
        color: #f8f9fa;
    }
    body[data-bs-theme="dark"] .small-box {
        background-color: #0f1220;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .small-box i {
        opacity: 0.6;
    }
    body[data-bs-theme="dark"] .badge.bg-cyan {
        background-color: #17a2b8 !important;
    }
    body[data-bs-theme="dark"] .btn-outline-info {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-info:hover {
        background-color: #6fcf97;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-primary {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-primary:hover {
        background-color: #6fcf97;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-success {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-success:hover {
        background-color: #6fcf97;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-secondary {
        background-color: #2a3446;
        border-color: #3a4458;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .text-muted {
        color: #a8b3cf !important;
    }
</style>
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