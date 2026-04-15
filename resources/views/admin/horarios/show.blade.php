@extends('layouts.admin')

@section('title', 'Detalle del Horario')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-calendar-alt text-primary"></i>
        <span class="fw-bold fs-4">Detalle del Horario</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información del Horario
                </h5>
                <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary btn-sm rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <!-- Información Principal -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small-box bg-light p-3 rounded border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-calendar fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-muted">Gestión</h6>
                                    <p class="mb-0 fw-semibold">{{ $horario->gestion->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-light p-3 rounded border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-book fa-2x text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-muted">Curso</h6>
                                    <p class="mb-0 fw-semibold">{{ $horario->curso->nombre }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-light p-3 rounded border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users fa-2x text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-muted">Grado y Sección</h6>
                                    <p class="mb-0 fw-semibold">{{ $horario->grado->nombre }} {{ $horario->grado->seccion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="small-box bg-light p-3 rounded border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 text-muted">Docente</h6>
                                    <p class="mb-0 fw-semibold">
                                        {{ $horario->docente ? $horario->docente->persona->apellidos . ' ' . $horario->docente->persona->nombres : 'Sin asignar' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horario Detallado -->
                <div class="card mt-4 border">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Horario de Clase</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="fw-semibold"><i class="fas fa-calendar-day me-1"></i> Día de la Semana</label>
                                <p class="mt-1">
                                    <span class="badge bg-primary">{{ $horario->dia_semana }}</span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-semibold"><i class="fas fa-clock me-1"></i> Hora de Inicio</label>
                                <p class="mt-1"><strong>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-semibold"><i class="fas fa-clock me-1"></i> Hora de Fin</label>
                                <p class="mt-1"><strong>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-semibold"><i class="fas fa-hourglass-half me-1"></i> Duración</label>
                                <p class="mt-1">
                                    @php
                                        $inicio = \Carbon\Carbon::parse($horario->hora_inicio);
                                        $fin = \Carbon\Carbon::parse($horario->hora_fin);
                                        $duracion = $inicio->diff($fin);
                                        $horas = $duracion->h;
                                        $minutos = $duracion->i;
                                    @endphp
                                    <span class="badge bg-info">
                                        @if($horas > 0)
                                            {{ $horas }} {{ $horas == 1 ? 'hora' : 'horas' }}
                                        @endif
                                        @if($minutos > 0)
                                            {{ $minutos }} {{ $minutos == 1 ? 'minuto' : 'minutos' }}
                                        @endif
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="fw-semibold"><i class="fas fa-door-open me-1"></i> Aula Asignada</label>
                                <p class="mt-1">
                                    @if($horario->aula)
                                        <span class="badge bg-success">Aula {{ $horario->aula }}</span>
                                    @else
                                        <span class="badge bg-secondary">No asignada</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold"><i class="fas fa-info-circle me-1"></i> Estado</label>
                                <p class="mt-1"><span class="badge bg-success">Activo</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="card mt-4 border">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Información Adicional</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Fecha de Registro</label>
                                <p>{{ \Carbon\Carbon::parse($horario->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Última Actualización</label>
                                <p>{{ \Carbon\Carbon::parse($horario->updated_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if($horario->docente)
                        <div class="mt-3 p-3 bg-light rounded border">
                            <h6><i class="fas fa-user-tie me-2"></i> Datos del Docente</h6>
                            <p class="mb-1"><strong>Código:</strong> {{ $horario->docente->codigo_docente }}</p>
                            <p class="mb-1"><strong>Especialidad:</strong> {{ $horario->docente->especialidad ?? 'No especificada' }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $horario->docente->persona->user->email ?? 'No disponible' }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-4 d-flex gap-2 justify-content-start">
                    <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary rounded-pill">
                        <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                    </a>
                    <button type="button" class="btn btn-outline-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editHorarioModal">
                        <i class="fas fa-edit me-1"></i> Editar Horario
                    </button>
                    <button type="button" class="btn btn-outline-danger rounded-pill" onclick="confirmarEliminacion()">
                        <i class="fas fa-trash me-1"></i> Eliminar Horario
                    </button>
                    <form id="formEliminar" action="{{ route('admin.horarios.destroy', $horario->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Editar (simplificado) --}}
<div class="modal fade" id="editHorarioModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Horario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.horarios.update', $horario->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Día <span class="text-danger">*</span></label>
                            <select name="dia_semana" class="form-select" required>
                                <option value="Lunes" {{ $horario->dia_semana == 'Lunes' ? 'selected' : '' }}>Lunes</option>
                                <option value="Martes" {{ $horario->dia_semana == 'Martes' ? 'selected' : '' }}>Martes</option>
                                <option value="Miércoles" {{ $horario->dia_semana == 'Miércoles' ? 'selected' : '' }}>Miércoles</option>
                                <option value="Jueves" {{ $horario->dia_semana == 'Jueves' ? 'selected' : '' }}>Jueves</option>
                                <option value="Viernes" {{ $horario->dia_semana == 'Viernes' ? 'selected' : '' }}>Viernes</option>
                                <option value="Sábado" {{ $horario->dia_semana == 'Sábado' ? 'selected' : '' }}>Sábado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Aula</label>
                            <input type="text" name="aula" value="{{ $horario->aula }}" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                            <input type="time" name="hora_inicio" value="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                            <input type="time" name="hora_fin" value="{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .gap-2 { gap: 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; padding-left: 0.9rem; padding-right: 0.9rem; }

    /* Modo oscuro */
    body[data-bs-theme="dark"] .card,
    body[data-bs-theme="dark"] .modal-content {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .card-header.bg-light {
        background-color: #171c2c !important;
        border-bottom-color: #2a3446 !important;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .small-box.bg-light {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .text-muted {
        color: #a8b3cf !important;
    }
    body[data-bs-theme="dark"] .border {
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .btn-outline-success {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-success:hover {
        background-color: #6fcf97;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-danger {
        color: #e74c5c;
        border-color: #e74c5c;
    }
    body[data-bs-theme="dark"] .btn-outline-danger:hover {
        background-color: #e74c5c;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-secondary {
        background-color: #2a3446;
        border-color: #3a4458;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .form-control,
    body[data-bs-theme="dark"] .form-select {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .bg-light {
        background-color: #171c2c !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarEliminacion() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará el horario permanentemente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formEliminar').submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>
@stop