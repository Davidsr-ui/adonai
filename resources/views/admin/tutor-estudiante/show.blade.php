@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-user-friends text-primary"></i>
        <span class="fw-bold fs-4">Detalle de Relación Tutor-Estudiante</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información de la Relación
                </h5>
                <div>
                    @php
                        $tipoBadge = $relacion->tipo == 'Principal' ? 'primary' : 'info text-dark';
                        $estadoBadge = $relacion->estado == 'Activo' ? 'success' : 'danger';
                    @endphp
                    <span class="badge bg-{{ $estadoBadge }} me-1">{{ $relacion->estado }}</span>
                    <span class="badge bg-{{ explode(' ', $tipoBadge)[0] }}">{{ $relacion->tipo }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Datos del Tutor -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-user me-2"></i>Datos del Tutor</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Nombre Completo</label>
                                <p><strong>{{ $relacion->tutor->persona->nombres }} {{ $relacion->tutor->persona->apellidos }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">DNI</label>
                                <p>{{ $relacion->tutor->persona->dni }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Celular</label>
                                <p>{{ $relacion->tutor->persona->telefono ?? 'No registrado' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Dirección</label>
                                <p>{{ $relacion->tutor->persona->direccion ?? 'No registrada' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Ocupación</label>
                                <p>{{ $relacion->tutor->ocupacion ?? 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Estudiante -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-user-graduate me-2"></i>Datos del Estudiante</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Nombre Completo</label>
                                <p><strong>{{ $relacion->estudiante->persona->nombres }} {{ $relacion->estudiante->persona->apellidos }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">DNI</label>
                                <p>{{ $relacion->estudiante->persona->dni }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Código Estudiante</label>
                                <p><strong>{{ $relacion->estudiante->codigo_estudiante }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Fecha de Nacimiento</label>
                                <p>{{ \Carbon\Carbon::parse($relacion->estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Edad</label>
                                <p>{{ \Carbon\Carbon::parse($relacion->estudiante->persona->fecha_nacimiento)->age }} años</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Grado Actual</label>
                                <p>
                                    @if($relacion->estudiante->grado)
                                        <span class="badge bg-info">{{ $relacion->estudiante->grado->nombre_completo }}</span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Estado del Estudiante</label>
                                <p>
                                    @if($relacion->estudiante->persona->estado == 'Activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de la Relación -->
                <h5 class="mt-4"><i class="fas fa-link me-2"></i>Información de la Relación</h5>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label class="fw-semibold">Relación Familiar</label>
                        <p><span class="badge bg-secondary">{{ $relacion->relacion_familiar }}</span></p>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-semibold">Tipo de Relación</label>
                        <p>
                            <span class="badge bg-{{ $relacion->tipo == 'Principal' ? 'primary' : 'info text-dark' }}">
                                @if($relacion->tipo == 'Principal')<i class="fas fa-star me-1"></i>@endif
                                {{ $relacion->tipo }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-semibold">Autorización de Recojo</label>
                        <p>
                            @if($relacion->autorizacion_recojo)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Autorizado</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> No Autorizado</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-semibold">Estado</label>
                        <p>
                            <span class="badge bg-{{ $relacion->estado == 'Activo' ? 'success' : 'danger' }}">{{ $relacion->estado }}</span>
                        </p>
                    </div>
                </div>

                <!-- Alertas informativas -->
                @if($relacion->tipo == 'Principal')
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Tutor Principal:</strong> Esta persona es el contacto principal del estudiante y tiene todas las responsabilidades y derechos sobre su educación.
                </div>
                @endif

                @if($relacion->autorizacion_recojo)
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Autorización de Recojo:</strong> Esta persona está autorizada para recoger al estudiante de la institución.
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Sin Autorización:</strong> Esta persona NO está autorizada para recoger al estudiante de la institución.
                </div>
                @endif

                <!-- Estadísticas -->
                <h5 class="mt-4"><i class="fas fa-chart-bar me-2"></i>Información Adicional</h5>
                <hr>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="small-box bg-info p-3 rounded text-center">
                            <h3>{{ $relacion->estudiante->tutorEstudiantes()->count() }}</h3>
                            <p class="mb-0">Total Tutores del Estudiante</p>
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-success p-3 rounded text-center">
                            <h3>{{ $relacion->tutor->tutorEstudiantes()->count() }}</h3>
                            <p class="mb-0">Total Estudiantes del Tutor</p>
                            <i class="fas fa-user-graduate"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-warning p-3 rounded text-center">
                            <h3>{{ $relacion->estudiante->tutorEstudiantes()->where('autorizacion_recojo', true)->count() }}</h3>
                            <p class="mb-0">Personas Autorizadas (Recojo)</p>
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="fw-semibold">Fecha de Registro</label>
                        <p>{{ \Carbon\Carbon::parse($relacion->created_at)->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-semibold">Última Actualización</label>
                        <p>{{ \Carbon\Carbon::parse($relacion->updated_at)->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="d-flex gap-2 justify-content-start">
                            <a href="{{ route('admin.tutor-estudiante.index') }}" class="btn btn-secondary rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                            </a>
                            <a href="{{ route('admin.tutores.show', $relacion->tutor->id) }}" class="btn btn-outline-info rounded-pill">
                                <i class="fas fa-user me-1"></i> Ver Perfil del Tutor
                            </a>
                            <a href="{{ route('admin.estudiantes.show', $relacion->estudiante->id) }}" class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-user-graduate me-1"></i> Ver Perfil del Estudiante
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
    body[data-bs-theme="dark"] .btn-secondary {
        background-color: #2a3446;
        border-color: #3a4458;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .text-muted {
        color: #a8b3cf !important;
    }
    body[data-bs-theme="dark"] .alert-info {
        background-color: #1a1e2c;
        border-color: #2a3446;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .alert-success {
        background-color: #1a2c1a;
        border-color: #2d6a2d;
        color: #a3d9a3;
    }
    body[data-bs-theme="dark"] .alert-warning {
        background-color: #2a1e0c;
        border-color: #664d00;
        color: #ffd966;
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