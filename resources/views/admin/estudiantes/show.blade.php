@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-user-graduate text-primary"></i>
        <span class="fw-bold fs-4">Detalle del Estudiante</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información del Estudiante
                </h5>
                <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary btn-sm rounded-pill">
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
                                <p>{{ $estudiante->persona->dni }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Nombres</label>
                                <p>{{ $estudiante->persona->nombres }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Apellidos</label>
                                <p>{{ $estudiante->persona->apellidos }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="fw-semibold">Fecha de Nacimiento</label>
                                <p>{{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Edad</label>
                                <p>{{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->age }} años</p>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-semibold">Género</label>
                                <p>
                                    @if ($estudiante->persona->genero == 'M') Masculino
                                    @elseif($estudiante->persona->genero == 'F') Femenino
                                    @else Otro @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Dirección</label>
                                <p>{{ $estudiante->persona->direccion ?? 'No especificada' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Teléfono</label>
                                <p>{{ $estudiante->persona->telefono ?? 'No especificado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-semibold">Teléfono de Emergencia</label>
                                <p>{{ $estudiante->persona->telefono_emergencia ?? 'No especificado' }}</p>
                            </div>
                        </div>

                        <h5 class="mt-4"><i class="fas fa-graduation-cap me-2"></i>Información Académica</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="fw-semibold">Código Estudiante</label>
                                <p><strong>{{ $estudiante->codigo_estudiante }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-semibold">Año de Ingreso</label>
                                <p>{{ $estudiante->año_ingreso }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-semibold">Años en la Institución</label>
                                <p>{{ date('Y') - $estudiante->año_ingreso }} años</p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-semibold">Condición</label>
                                <p>
                                    @php
                                        $condClase = match($estudiante->condicion) {
                                            'Regular'   => 'bg-success',
                                            'Irregular' => 'bg-warning text-dark',
                                            'Retirado'  => 'bg-danger',
                                            default     => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $condClase }}">{{ $estudiante->condicion }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fw-semibold">Grado Actual</label>
                                <p>{{ $estudiante->grado ? $estudiante->grado->nombre_completo : 'Sin asignar' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-semibold">Nivel</label>
                                <p>{{ $estudiante->grado && $estudiante->grado->nivel ? $estudiante->grado->nivel->nombre : '-' }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="fw-semibold">Turno</label>
                                <p>{{ $estudiante->grado && $estudiante->grado->turno ? $estudiante->grado->turno->nombre : '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            @if ($estudiante->persona->foto_perfil)
                                <img src="{{ asset('storage/' . $estudiante->persona->foto_perfil) }}" alt="Foto de {{ $estudiante->persona->nombres }}" class="img-thumbnail" style="max-width: 200px;">
                            @else
                                <div class="bg-light p-5 rounded">
                                    <i class="fas fa-user-graduate fa-5x text-muted"></i>
                                    <p class="text-muted mt-2">Sin foto</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <label class="fw-semibold">Estado</label>
                            <p>
                                @if ($estudiante->persona->estado == 'Activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if ($estudiante->tutores->count() > 0)
                    <h5 class="mt-4"><i class="fas fa-users me-2"></i>Tutores</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>DNI</th>
                                    <th>Tutor</th>
                                    <th>Relación</th>
                                    <th>Tipo</th>
                                    <th>Teléfono</th>
                                    <th>Autorizado Recojo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudiante->tutores as $tutor)
                                    <tr>
                                        <td>{{ $tutor->persona->dni }}</td>
                                        <td>{{ $tutor->persona->apellidos }} {{ $tutor->persona->nombres }}</td>
                                        <td>{{ $tutor->pivot->relacion_familiar }}</td>
                                        <td>
                                            @if ($tutor->pivot->tipo == 'Principal')
                                                <span class="badge bg-primary">{{ $tutor->pivot->tipo }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $tutor->pivot->tipo }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $tutor->persona->telefono ?? '-' }}</td>
                                        <td>
                                            @if ($tutor->pivot->autorizacion_recojo)
                                                <span class="badge bg-success">Sí</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($tutor->pivot->estado == 'Activo')
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
                @endif

                <div class="row mt-4">
                    <div class="col-md-6">
                        @if ($estudiante->asistencias && $estudiante->asistencias->count() > 0)
                            <h5><i class="fas fa-calendar-check me-2"></i>Resumen de Asistencias</h5>
                            <hr>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="small-box bg-success p-3 rounded text-center">
                                        <h4>{{ $estudiante->asistencias->where('estado', 'Presente')->count() }}</h4>
                                        <p class="mb-0">Presente</p>
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-danger p-3 rounded text-center">
                                        <h4>{{ $estudiante->asistencias->where('estado', 'Ausente')->count() }}</h4>
                                        <p class="mb-0">Ausente</p>
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-warning p-3 rounded text-center">
                                        <h4>{{ $estudiante->asistencias->where('estado', 'Tardanza')->count() }}</h4>
                                        <p class="mb-0">Tardanza</p>
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small-box bg-info p-3 rounded text-center">
                                        <h4>{{ $estudiante->asistencias->where('estado', 'Justificado')->count() }}</h4>
                                        <p class="mb-0">Justificado</p>
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if ($estudiante->comportamientos && $estudiante->comportamientos->count() > 0)
                            <h5><i class="fas fa-smile me-2"></i>Registro de Comportamiento</h5>
                            <hr>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="small-box bg-success p-3 rounded text-center">
                                        <h4>{{ $estudiante->comportamientos->where('tipo', 'Positivo')->count() }}</h4>
                                        <p class="mb-0">Positivo</p>
                                        <i class="fas fa-smile"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small-box bg-danger p-3 rounded text-center">
                                        <h4>{{ $estudiante->comportamientos->where('tipo', 'Negativo')->count() }}</h4>
                                        <p class="mb-0">Negativo</p>
                                        <i class="fas fa-frown"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small-box bg-secondary p-3 rounded text-center">
                                        <h4>{{ $estudiante->comportamientos->where('tipo', 'Neutro')->count() }}</h4>
                                        <p class="mb-0">Neutro</p>
                                        <i class="fas fa-meh"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex gap-2 justify-content-start">
                            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary rounded-pill">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
                            <button type="button" class="btn btn-outline-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editEstudianteModal{{ $estudiante->id }}">
                                <i class="fas fa-edit me-1"></i> Editar
                            </button>
                            <a href="{{ route('admin.tutor-estudiante.index') }}" class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-users me-1"></i> Asignar Tutor
                            </a>
                            <a href="{{ route('admin.matriculas.index', ['estudiante_id' => $estudiante->id]) }}" class="btn btn-outline-info rounded-pill">
                                <i class="fas fa-graduation-cap me-1"></i> Ver Matrículas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Editar (versión simplificada pero completa) --}}
<div class="modal fade" id="editEstudianteModal{{ $estudiante->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Estudiante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.estudiantes.update', $estudiante->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <h6 class="text-muted fw-bold mb-3"><i class="fas fa-id-card me-1"></i> Datos Personales</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">DNI *</label>
                            <input type="text" name="dni" value="{{ $estudiante->persona->dni }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombres *</label>
                            <input type="text" name="nombres" value="{{ $estudiante->persona->nombres }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellidos *</label>
                            <input type="text" name="apellidos" value="{{ $estudiante->persona->apellidos }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" value="{{ $estudiante->persona->fecha_nacimiento }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Género *</label>
                            <select name="genero" class="form-select" required>
                                <option value="M" {{ $estudiante->persona->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ $estudiante->persona->genero == 'F' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ $estudiante->persona->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado *</label>
                            <select name="estado" class="form-select" required>
                                <option value="Activo" {{ $estudiante->persona->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ $estudiante->persona->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" value="{{ $estudiante->persona->direccion }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" value="{{ $estudiante->persona->telefono }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono Emergencia</label>
                            <input type="text" name="telefono_emergencia" value="{{ $estudiante->persona->telefono_emergencia }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Foto de Perfil</label>
                            <input type="file" name="foto_perfil" class="form-control">
                            @if ($estudiante->persona->foto_perfil)
                                <img src="{{ asset('storage/' . $estudiante->persona->foto_perfil) }}" class="img-thumbnail mt-2" width="80">
                            @else
                                <p class="text-muted mt-1 small">Sin foto actual</p>
                            @endif
                        </div>
                    </div>

                    <h6 class="text-muted fw-bold mt-4 mb-3"><i class="fas fa-graduation-cap me-1"></i> Datos Académicos</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Código Estudiante</label>
                            <input type="text" name="codigo_estudiante" value="{{ $estudiante->codigo_estudiante }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Año de Ingreso</label>
                            <input type="number" name="año_ingreso" value="{{ $estudiante->año_ingreso }}" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Condición</label>
                            <select name="condicion" class="form-select">
                                <option value="Regular" {{ $estudiante->condicion == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Irregular" {{ $estudiante->condicion == 'Irregular' ? 'selected' : '' }}>Irregular</option>
                                <option value="Retirado" {{ $estudiante->condicion == 'Retirado' ? 'selected' : '' }}>Retirado</option>
                            </select>
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
    body[data-bs-theme="dark"] .btn-outline-primary {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-primary:hover {
        background-color: #6fcf97;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-info {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-info:hover {
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
    body[data-bs-theme="dark"] .small-box {
        background-color: #0f1220;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .small-box i {
        opacity: 0.6;
    }
    body[data-bs-theme="dark"] .bg-light {
        background-color: #171c2c !important;
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