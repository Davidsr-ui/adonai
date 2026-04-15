@extends('layouts.admin')

@section('title', 'Gestión de Matrículas')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-file-signature text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Matrículas</span>
    </div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Matrículas Registradas</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createMatriculaModal">
                <i class="fas fa-plus me-1"></i> Nueva Matrícula
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="matriculasTable" class="table table-bordered table-hover table-sm align-middle">
                <thead>
                    <tr>
                        <th class="w-1">ID</th>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Grado</th>
                        <th>Nivel</th>
                        <th>Gestión</th>
                        <th>Estado</th>
                        <th class="w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($matriculas as $matricula)
                    <tr>
                        <td class="text-muted">{{ $matricula->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $matricula->estudiante->persona->apellidos }}, {{ $matricula->estudiante->persona->nombres }}</div>
                            <div class="text-muted small">{{ $matricula->estudiante->codigo_estudiante }}</div>
                        </td>
                        <td class="text-muted">{{ $matricula->curso->nombre }}</td>
                        <td class="text-muted">{{ $matricula->grado->nombre_completo }}</td>
                        <td><span class="badge bg-cyan text-white">{{ $matricula->grado->nivel->nombre ?? 'N/A' }}</span></td>
                        <td><span class="badge bg-secondary">{{ $matricula->gestion->nombre }}</span></td>
                        <td>
                            @php
                                $estadoClase = match($matricula->estado) {
                                    'Matriculado' => 'bg-primary',
                                    'Aprobado' => 'bg-success',
                                    'Retirado' => 'bg-warning text-dark',
                                    'Desaprobado' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $estadoClase }}">{{ $matricula->estado }}</span>
                         </div>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.matriculas.show', $matricula->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Ver">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                                <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editMatriculaModal{{ $matricula->id }}" title="Editar">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>
                                <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteMatriculaModal{{ $matricula->id }}" title="Eliminar">
                                    <i class="fas fa-trash me-1"></i> Eliminar
                                </button>
                            </div>
                         </div>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="editMatriculaModal{{ $matricula->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Matrícula</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.matriculas.update', $matricula->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                                                <select name="estudiante_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($estudiantes as $estudiante)
                                                        <option value="{{ $estudiante->id }}" {{ old('estudiante_id', $matricula->estudiante_id) == $estudiante->id ? 'selected' : '' }}>
                                                            {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }} - {{ $estudiante->codigo_estudiante }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Curso <span class="text-danger">*</span></label>
                                                <select name="curso_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($cursos as $curso)
                                                        <option value="{{ $curso->id }}" {{ old('curso_id', $matricula->curso_id) == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Grado <span class="text-danger">*</span></label>
                                                <select name="grado_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($grados as $grado)
                                                        <option value="{{ $grado->id }}" {{ old('grado_id', $matricula->grado_id) == $grado->id ? 'selected' : '' }}>
                                                            {{ $grado->nivel->nombre }} - {{ $grado->nombre_completo }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Gestión <span class="text-danger">*</span></label>
                                                <select name="gestion_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($gestiones as $gestion)
                                                        <option value="{{ $gestion->id }}" {{ old('gestion_id', $matricula->gestion_id) == $gestion->id ? 'selected' : '' }}>{{ $gestion->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                <select name="estado" class="form-select" required>
                                                    @foreach(['Matriculado','Retirado','Aprobado','Desaprobado'] as $est)
                                                        <option value="{{ $est }}" {{ old('estado', $matricula->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                                    @endforeach
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

                    {{-- Modal Eliminar --}}
                    <div class="modal fade" id="deleteMatriculaModal{{ $matricula->id }}" tabindex="-1">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.matriculas.destroy', $matricula->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <strong>{{ $matricula->estudiante->persona->nombres }} {{ $matricula->estudiante->persona->apellidos }}</strong><br>
                                            <small>{{ $matricula->curso->nombre }} — {{ $matricula->grado->nombre_completo }}</small><br>
                                            <small>{{ $matricula->gestion->nombre }}</small>
                                        </div>
                                        <div class="alert alert-warning"><i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer. Si tiene notas no podrá eliminarse.</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger w-50"><i class="fas fa-trash me-1"></i>Eliminar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Crear --}}
<div class="modal fade" id="createMatriculaModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nueva Matrícula</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.matriculas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                            <select name="estudiante_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}" {{ old('estudiante_id_create') == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }} - {{ $estudiante->codigo_estudiante }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Curso <span class="text-danger">*</span></label>
                            <select name="curso_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ old('curso_id_create') == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Grado <span class="text-danger">*</span></label>
                            <select name="grado_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id }}" {{ old('grado_id_create') == $grado->id ? 'selected' : '' }}>
                                        {{ $grado->nivel->nombre }} - {{ $grado->nombre_completo }} ({{ $grado->estudiantes->count() }}/{{ $grado->capacidad_maxima }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gestión <span class="text-danger">*</span></label>
                            <select name="gestion_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($gestiones as $gestion)
                                    <option value="{{ $gestion->id }}" {{ old('gestion_id_create') == $gestion->id ? 'selected' : '' }}>{{ $gestion->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado_create" class="form-select" required>
                                @foreach(['Matriculado','Retirado','Aprobado','Desaprobado'] as $est)
                                    <option value="{{ $est }}" {{ old('estado_create', 'Matriculado') == $est ? 'selected' : '' }}>{{ $est }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    .gap-2 { gap: 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; padding-left: 0.9rem; padding-right: 0.9rem; }

    /* Modo oscuro */
    body[data-bs-theme="dark"] .card,
    body[data-bs-theme="dark"] .modal-content {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .card-header {
        background-color: #171c2c !important;
        border-bottom-color: #2a3446 !important;
        color: #f8f9fa;
    }
    body[data-bs-theme="dark"] .table,
    body[data-bs-theme="dark"] .table-bordered {
        background-color: #1a1e2c !important;
        color: #e9ecef !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .table td,
    body[data-bs-theme="dark"] .table th,
    body[data-bs-theme="dark"] .table-bordered th,
    body[data-bs-theme="dark"] .table-bordered td {
        border-color: #2a3446 !important;
        color: #e9ecef !important;
        background-color: #1a1e2c !important;
    }
    body[data-bs-theme="dark"] .table thead th {
        background-color: #0f1220 !important;
        color: #f8f9fa !important;
        border-bottom-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .table-hover > tbody > tr:hover > * {
        background-color: #2c3145 !important;
    }
    body[data-bs-theme="dark"] .form-control,
    body[data-bs-theme="dark"] .form-select,
    body[data-bs-theme="dark"] .input-group-text {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .btn-outline-info {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-info:hover {
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
    body[data-bs-theme="dark"] .text-muted {
        color: #a8b3cf !important;
    }
    body[data-bs-theme="dark"] .alert-info {
        background-color: #1a1e2c;
        border-color: #2a3446;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .alert-warning {
        background-color: #2a1e0c;
        border-color: #664d00;
        color: #ffd966;
    }
    body[data-bs-theme="dark"] .badge.bg-cyan {
        background-color: #17a2b8 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-secondary {
        background-color: #3a4458 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-primary {
        background-color: #0d6efd !important;
    }
    body[data-bs-theme="dark"] .badge.bg-success {
        background-color: #198754 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-warning {
        background-color: #d39e00 !important;
        color: #1a1e2c !important;
    }
    body[data-bs-theme="dark"] .badge.bg-danger {
        background-color: #dc3545 !important;
    }
    /* DataTables oscuro */
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_length,
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_filter,
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_info,
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_paginate {
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #e9ecef !important;
        background: #1a1e2c !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4e73df !important;
        color: white !important;
    }
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_filter input {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#matriculasTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            responsive: true,
            autoWidth: false,
            order: [[5, 'desc'], [3, 'asc']]
        });

        @if(session('mensaje'))
            Swal.fire({
                icon: '{{ session('icono') }}',
                title: '{{ session('mensaje') }}',
                showConfirmButton: false,
                timer: 2500
            });
        @endif

        @if($errors->any() && session('modal_id'))
            var modal = new bootstrap.Modal(document.getElementById('editMatriculaModal{{ session('modal_id') }}'));
            modal.show();
        @endif

        @if($errors->has('estudiante_id_create') || $errors->has('curso_id_create') || $errors->has('grado_id_create') || $errors->has('gestion_id_create'))
            var modalCreate = new bootstrap.Modal(document.getElementById('createMatriculaModal'));
            modalCreate.show();
        @endif
    });
</script>
@stop