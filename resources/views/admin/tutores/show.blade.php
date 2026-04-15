@extends('layouts.admin')

@section('title', 'Detalle del Tutor')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-user-tie text-primary"></i>
        <span class="fw-bold fs-4">Detalle del Tutor</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información del Tutor
                </h5>
                <a href="{{ route('admin.tutores.index') }}" class="btn btn-secondary btn-sm rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5><i class="fas fa-id-card me-2"></i>Datos Personales</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="fw-semibold">DNI</label>
                                <p>{{ $tutor->persona->dni ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Nombres</label>
                                <p>{{ $tutor->persona->nombres ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Apellidos</label>
                                <p>{{ $tutor->persona->apellidos ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="fw-semibold">Fecha de Nacimiento</label>
                                <p>
                                    @if ($tutor->persona && $tutor->persona->fecha_nacimiento)
                                        {{ \Carbon\Carbon::parse($tutor->persona->fecha_nacimiento)->format('d/m/Y') }}
                                    @else N/A @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Edad</label>
                                <p>
                                    @if ($tutor->persona && $tutor->persona->fecha_nacimiento)
                                        {{ \Carbon\Carbon::parse($tutor->persona->fecha_nacimiento)->age }} años
                                    @else N/A @endif
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Género</label>
                                <p>
                                    @if ($tutor->persona)
                                        @if ($tutor->persona->genero == 'M') Masculino
                                        @elseif($tutor->persona->genero == 'F') Femenino
                                        @else Otro @endif
                                    @else N/A @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Dirección</label>
                                <p>{{ $tutor->persona->direccion ?? 'No especificada' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Teléfono</label>
                                <p>{{ $tutor->persona->telefono ?? 'No especificado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Teléfono Emergencia</label>
                                <p>{{ $tutor->persona->telefono_emergencia ?? 'No especificado' }}</p>
                            </div>
                        </div>

                        <h5 class="mt-4"><i class="fas fa-briefcase me-2"></i>Información del Tutor</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="fw-semibold">Código Tutor</label>
                                <p><strong>{{ $tutor->codigo_tutor ?? 'No asignado' }}</strong></p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Ocupación</label>
                                <p>{{ $tutor->ocupacion ?? 'No especificada' }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Estado</label>
                                <p>
                                    @if ($tutor->persona && $tutor->persona->estado == 'Activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            @if ($tutor->persona && $tutor->persona->foto_perfil)
                                <img src="{{ asset('storage/' . $tutor->persona->foto_perfil) }}" class="img-thumbnail" style="max-width: 200px;">
                            @else
                                <div class="p-5 bg-light rounded">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                    <p class="text-muted mt-2">Sin foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Estudiantes a cargo --}}
                @if ($tutor->estudiantes->count() > 0)
                    <h5 class="mt-4"><i class="fas fa-users me-2"></i>Estudiantes a Cargo</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Estudiante</th>
                                    <th>Grado</th>
                                    <th>Relación</th>
                                    <th>Tipo</th>
                                    <th>Autorizado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tutor->estudiantes as $estudiante)
                                <tr>
                                    <td>{{ $estudiante->codigo_estudiante ?? 'N/A' }}</td>
                                    <td>
                                        @if ($estudiante->persona)
                                            {{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}
                                        @else Sin datos @endif
                                    </td>
                                    <td>{{ $estudiante->grado->nombre ?? '-' }}</td>
                                    <td>{{ $estudiante->pivot->relacion_familiar ?? 'N/A' }}</td>
                                    <td>
                                        @if ($estudiante->pivot->tipo == 'Principal')
                                            <span class="badge bg-primary">Principal</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $estudiante->pivot->tipo }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($estudiante->pivot->autorizacion_recojo)
                                            <span class="badge bg-success">Sí</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($estudiante->pivot->estado == 'Activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-danger">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i> No tiene estudiantes asignados.
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="d-flex gap-2 justify-content-start">
                            <a href="{{ route('admin.tutores.index') }}" class="btn btn-secondary rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
                            <button type="button" class="btn btn-outline-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editTutorModal{{ $tutor->id }}">
                                <i class="fas fa-edit me-1"></i> Editar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Editar (simplificado y corregido) --}}
<div class="modal fade" id="editTutorModal{{ $tutor->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Tutor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tutores.update', $tutor->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <h6 class="fw-bold text-muted text-uppercase mb-3"><i class="fas fa-id-card me-1"></i> Datos Personales</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">DNI <span class="text-danger">*</span></label>
                            <input type="text" name="dni" value="{{ $tutor->persona->dni ?? '' }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" value="{{ $tutor->persona->nombres ?? '' }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" value="{{ $tutor->persona->apellidos ?? '' }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_nacimiento" value="{{ $tutor->persona->fecha_nacimiento ?? '' }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Género <span class="text-danger">*</span></label>
                            <select name="genero" class="form-select" required>
                                <option value="M" {{ ($tutor->persona->genero ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ ($tutor->persona->genero ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ ($tutor->persona->genero ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select" required>
                                <option value="Activo" {{ ($tutor->persona->estado ?? 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ ($tutor->persona->estado ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" value="{{ $tutor->persona->direccion ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" value="{{ $tutor->persona->telefono ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono Emergencia</label>
                            <input type="text" name="telefono_emergencia" value="{{ $tutor->persona->telefono_emergencia ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Foto de Perfil</label>
                            <input type="file" name="foto_perfil" class="form-control">
                            @if ($tutor->persona->foto_perfil ?? false)
                                <img src="{{ asset('storage/' . $tutor->persona->foto_perfil) }}" class="img-thumbnail mt-2" width="80">
                            @else
                                <p class="text-muted mt-1 small">Sin foto actual</p>
                            @endif
                        </div>
                    </div>

                    <h6 class="fw-bold text-muted text-uppercase mt-4 mb-3"><i class="fas fa-briefcase me-1"></i> Datos del Tutor</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Código Tutor</label>
                            <input type="text" name="codigo_tutor" value="{{ $tutor->codigo_tutor ?? '' }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ocupación</label>
                            <input type="text" name="ocupacion" value="{{ $tutor->ocupacion ?? '' }}" class="form-control">
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
    .img-thumbnail { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.25rem; max-width: 100%; height: auto; }

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
    body[data-bs-theme="dark"] .bg-light {
        background-color: #171c2c !important;
    }
    body[data-bs-theme="dark"] .form-control,
    body[data-bs-theme="dark"] .form-select {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
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
    body[data-bs-theme="dark"] .img-thumbnail {
        background-color: #0f1220;
        border-color: #2a3446;
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