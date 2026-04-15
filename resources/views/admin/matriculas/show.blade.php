@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-file-signature text-primary"></i>
        <span class="fw-bold fs-4">Detalle de la Matrícula</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información de la Matrícula
                </h5>
                <div>
                    <span class="badge bg-{{ $matricula->estado_badge }}">
                        {{ $matricula->estado }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Datos del Estudiante -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-user-graduate me-2"></i>Datos del Estudiante</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Nombre Completo</label>
                                <p><strong>{{ $matricula->estudiante->persona->nombres }} {{ $matricula->estudiante->persona->apellidos }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">DNI</label>
                                <p>{{ $matricula->estudiante->persona->dni }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Código Estudiante</label>
                                <p><strong>{{ $matricula->estudiante->codigo_estudiante }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Fecha de Nacimiento</label>
                                <p>{{ \Carbon\Carbon::parse($matricula->estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Edad</label>
                                <p>{{ \Carbon\Carbon::parse($matricula->estudiante->persona->fecha_nacimiento)->age }} años</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Estado del Estudiante</label>
                                <p>
                                    @if($matricula->estudiante->persona->estado == 'Activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Curso -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-book me-2"></i>Datos del Curso</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Curso</label>
                                <p><strong>{{ $matricula->curso->nombre }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Código del Curso</label>
                                <p>{{ $matricula->curso->codigo ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Área Curricular</label>
                                <p>{{ $matricula->curso->area_curricular ?? 'No especificada' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Horas Semanales</label>
                                <p><span class="badge bg-warning text-dark">{{ $matricula->curso->horas_semanales }} horas</span></p>
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
                                <p><span class="badge bg-cyan text-white">{{ $matricula->grado->nivel->nombre ?? 'N/A' }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Grado</label>
                                <p><strong>{{ $matricula->grado->nombre_completo }}</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Capacidad</label>
                                <p>{{ $matricula->grado->estudiantes->count() }} / {{ $matricula->grado->capacidad_maxima }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Turno</label>
                                <p>
                                    @if($matricula->grado->turno)
                                        <span class="badge bg-secondary">{{ $matricula->grado->turno->nombre }}</span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión y Estado -->
                    <div class="col-md-6">
                        <h5><i class="fas fa-calendar-alt me-2"></i>Gestión y Estado</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Gestión</label>
                                <p><span class="badge bg-secondary">{{ $matricula->gestion->nombre }}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Año</label>
                                <p>{{ $matricula->gestion->año }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Estado Gestión</label>
                                <p>
                                    @php
                                        $gestionClase = match($matricula->gestion->estado) {
                                            'Activo' => 'bg-success',
                                            'Finalizado' => 'bg-secondary',
                                            default => 'bg-info',
                                        };
                                    @endphp
                                    <span class="badge {{ $gestionClase }}">{{ $matricula->gestion->estado }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Estado de la Matrícula</label>
                                <p>
                                    @php
                                        $estadoBadge = match($matricula->estado) {
                                            'Matriculado' => 'primary',
                                            'Aprobado' => 'success',
                                            'Retirado' => 'warning text-dark',
                                            'Desaprobado' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ explode(' ', $estadoBadge)[0] }} {{ str_contains($estadoBadge, 'text-dark') ? 'text-dark' : 'text-white' }}">
                                        @if($matricula->estado == 'Matriculado')
                                            <i class="fas fa-check-circle me-1"></i>
                                        @elseif($matricula->estado == 'Aprobado')
                                            <i class="fas fa-trophy me-1"></i>
                                        @elseif($matricula->estado == 'Desaprobado')
                                            <i class="fas fa-times-circle me-1"></i>
                                        @else
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                        @endif
                                        {{ $matricula->estado }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notas -->
                <h5 class="mt-4"><i class="fas fa-clipboard-list me-2"></i>Registro de Notas</h5>
                <hr>
                @if($matricula->notas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Periodo</th>
                                    <th>Tipo Evaluación</th>
                                    <th class="text-center">Nota Práctica</th>
                                    <th class="text-center">Nota Teoría</th>
                                    <th class="text-center">Nota Final</th>
                                    <th>Fecha Evaluación</th>
                                    <th>Docente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matricula->notas as $nota)
                                <tr>
                                    <td>{{ $nota->periodo->nombre ?? 'N/A' }}</td>
                                    <td>{{ $nota->tipo_evaluacion }}</td>
                                    <td class="text-center">{{ $nota->nota_practica ?? '-' }}</td>
                                    <td class="text-center">{{ $nota->nota_teoria ?? '-' }}</td>
                                    <td class="text-center"><strong>{{ $nota->nota_final }}</strong></td>
                                    <td>{{ $nota->fecha_evaluacion ? \Carbon\Carbon::parse($nota->fecha_evaluacion)->format('d/m/Y') : 'No registrada' }}</td>
                                    <td>{{ $nota->docente->persona->apellidos ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <td colspan="4" class="text-end"><strong>Promedio:</strong></td>
                                    <td class="text-center"><strong>{{ number_format($matricula->calcularPromedio() ?? 0, 2) }}</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No hay notas registradas para esta matrícula.
                    </div>
                @endif

                <!-- Estadísticas -->
                <h5 class="mt-4"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                <hr>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="small-box bg-info p-3 rounded text-center">
                            <h3>{{ $matricula->notas->count() }}</h3>
                            <p class="mb-0">Notas Registradas</p>
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-success p-3 rounded text-center">
                            <h3>{{ number_format($matricula->calcularPromedio() ?? 0, 2) }}</h3>
                            <p class="mb-0">Promedio General</p>
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-danger p-3 rounded text-center">
                            <h3>{{ $matricula->curso->horas_semanales }}</h3>
                            <p class="mb-0">Horas Semanales</p>
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="fw-semibold">Fecha de Matrícula</label>
                        <p>{{ \Carbon\Carbon::parse($matricula->created_at)->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-semibold">Última Actualización</label>
                        <p>{{ \Carbon\Carbon::parse($matricula->updated_at)->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="d-flex gap-2 justify-content-start">
                            <a href="{{ route('admin.matriculas.index') }}" class="btn btn-secondary rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                            </a>
                            <a href="{{ route('admin.estudiantes.show', $matricula->estudiante->id) }}" class="btn btn-outline-info rounded-pill">
                                <i class="fas fa-user-graduate me-1"></i> Ver Perfil del Estudiante
                            </a>
                            <a href="{{ route('admin.cursos.show', $matricula->curso->id) }}" class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-book me-1"></i> Ver Detalles del Curso
                            </a>
                            <a href="{{ route('admin.grados.show', $matricula->grado->id) }}" class="btn btn-outline-success rounded-pill">
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
    body[data-bs-theme="dark"] .badge.bg-secondary {
        background-color: #3a4458 !important;
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
    body[data-bs-theme="dark"] .alert-info {
        background-color: #1a1e2c;
        border-color: #2a3446;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .table {
        background-color: #1a1e2c !important;
        color: #e9ecef !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .table td,
    body[data-bs-theme="dark"] .table th {
        border-color: #2a3446 !important;
        background-color: #1a1e2c !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .table thead th {
        background-color: #0f1220 !important;
        color: #f8f9fa !important;
    }
    body[data-bs-theme="dark"] .table-hover > tbody > tr:hover > * {
        background-color: #2c3145 !important;
    }
    body[data-bs-theme="dark"] .bg-light {
        background-color: #171c2c !important;
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