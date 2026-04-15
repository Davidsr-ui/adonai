@extends('layouts.admin')

@section('title', 'Relación Tutor-Estudiante')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-user-friends text-primary"></i>
        <span class="fw-bold fs-4">Relación Tutor-Estudiante</span>
    </div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Relaciones Registradas</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createRelacionModal">
                <i class="fas fa-plus me-1"></i> Nueva Relación
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="relacionesTable" class="table table-bordered table-hover table-sm align-middle">
                <thead>
                    <tr>
                        <th class="w-1">ID</th>
                        <th>Tutor</th>
                        <th>Estudiante</th>
                        <th>Relación</th>
                        <th>Tipo</th>
                        <th>Autoriza Recojo</th>
                        <th>Estado</th>
                        <th class="w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($relaciones as $relacion)
                    <tr>
                        <td class="text-muted">{{ $relacion->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $relacion->tutor->persona->apellidos }}, {{ $relacion->tutor->persona->nombres }}</div>
                            <div class="text-muted small"><i class="fas fa-id-card me-1"></i>{{ $relacion->tutor->persona->dni }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $relacion->estudiante->persona->apellidos }}, {{ $relacion->estudiante->persona->nombres }}</div>
                            <div class="text-muted small">{{ $relacion->estudiante->codigo_estudiante }}</div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $relacion->relacion_familiar }}</span></td>
                        <td>
                            @php
                                $tipoClase = $relacion->tipo == 'Principal' ? 'bg-primary' : 'bg-info text-dark';
                            @endphp
                            <span class="badge {{ $tipoClase }}">{{ $relacion->tipo }}</span>
                        </td>
                        <td>
                            @if($relacion->autorizacion_recojo)
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sí</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i>No</span>
                            @endif
                        </td>
                        <td>
                            @if($relacion->estado === 'Activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.tutor-estudiante.show', $relacion->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Ver">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                                <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editRelacionModal{{ $relacion->id }}" title="Editar">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>
                                <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteRelacionModal{{ $relacion->id }}" title="Eliminar">
                                    <i class="fas fa-trash me-1"></i> Eliminar
                                </button>
                            </div>
                         </div>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="editRelacionModal{{ $relacion->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Relación</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.tutor-estudiante.update', $relacion->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Tutor <span class="text-danger">*</span></label>
                                                <select name="tutor_id" class="form-select" required>
                                                    <option value="">-- Seleccione un tutor --</option>
                                                    @foreach($tutores as $tutor)
                                                        <option value="{{ $tutor->id }}" {{ old('tutor_id', $relacion->tutor_id) == $tutor->id ? 'selected' : '' }}>
                                                            {{ $tutor->persona->apellidos }}, {{ $tutor->persona->nombres }} - {{ $tutor->persona->dni }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                                                <select name="estudiante_id" class="form-select" required>
                                                    <option value="">-- Seleccione un estudiante --</option>
                                                    @foreach($estudiantes as $estudiante)
                                                        <option value="{{ $estudiante->id }}" {{ old('estudiante_id', $relacion->estudiante_id) == $estudiante->id ? 'selected' : '' }}>
                                                            {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }} - {{ $estudiante->codigo_estudiante }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Relación Familiar <span class="text-danger">*</span></label>
                                                <select name="relacion_familiar" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach(['Padre','Madre','Tutor Legal','Abuelo/a','Tío/a','Hermano/a','Otro'] as $rel)
                                                        <option value="{{ $rel }}" {{ old('relacion_familiar', $relacion->relacion_familiar) == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tipo <span class="text-danger">*</span></label>
                                                <select name="tipo" class="form-select" required>
                                                    <option value="Principal" {{ old('tipo', $relacion->tipo) == 'Principal' ? 'selected' : '' }}>Principal</option>
                                                    <option value="Secundario" {{ old('tipo', $relacion->tipo) == 'Secundario' ? 'selected' : '' }}>Secundario</option>
                                                </select>
                                                <small class="text-muted">Solo puede haber un tutor principal activo por estudiante</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                <select name="estado" class="form-select" required>
                                                    <option value="Activo" {{ old('estado', $relacion->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                    <option value="Inactivo" {{ old('estado', $relacion->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-end">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="autorizacion_recojo" value="1" id="autorizacion_recojo_edit{{ $relacion->id }}" {{ old('autorizacion_recojo', $relacion->autorizacion_recojo) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="autorizacion_recojo_edit{{ $relacion->id }}">
                                                        <strong>Autorización de Recojo</strong><br>
                                                        <small class="text-muted">Puede recoger al estudiante</small>
                                                    </label>
                                                </div>
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
                    <div class="modal fade" id="deleteRelacionModal{{ $relacion->id }}" tabindex="-1">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.tutor-estudiante.destroy', $relacion->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <strong>Tutor:</strong> {{ $relacion->tutor->persona->nombres }} {{ $relacion->tutor->persona->apellidos }}<br>
                                            <strong>Estudiante:</strong> {{ $relacion->estudiante->persona->nombres }} {{ $relacion->estudiante->persona->apellidos }}<br>
                                            <strong>Relación:</strong> {{ $relacion->relacion_familiar }}
                                        </div>
                                        <div class="alert alert-warning"><i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer.</div>
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
<div class="modal fade" id="createRelacionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nueva Relación Tutor-Estudiante</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tutor-estudiante.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tutor <span class="text-danger">*</span></label>
                            <select name="tutor_id_create" class="form-select" required>
                                <option value="">-- Seleccione un tutor --</option>
                                @foreach($tutores as $tutor)
                                    <option value="{{ $tutor->id }}" {{ old('tutor_id_create') == $tutor->id ? 'selected' : '' }}>
                                        {{ $tutor->persona->apellidos }}, {{ $tutor->persona->nombres }} - {{ $tutor->persona->dni }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                            <select name="estudiante_id_create" class="form-select" required>
                                <option value="">-- Seleccione un estudiante --</option>
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}" {{ old('estudiante_id_create') == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }} - {{ $estudiante->codigo_estudiante }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Relación Familiar <span class="text-danger">*</span></label>
                            <select name="relacion_familiar_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach(['Padre','Madre','Tutor Legal','Abuelo/a','Tío/a','Hermano/a','Otro'] as $rel)
                                    <option value="{{ $rel }}" {{ old('relacion_familiar_create') == $rel ? 'selected' : '' }}>{{ $rel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo <span class="text-danger">*</span></label>
                            <select name="tipo_create" class="form-select" required>
                                <option value="Principal" {{ old('tipo_create') == 'Principal' ? 'selected' : '' }}>Principal</option>
                                <option value="Secundario" {{ old('tipo_create') == 'Secundario' ? 'selected' : '' }}>Secundario</option>
                            </select>
                            <small class="text-muted">Solo puede haber un tutor principal activo por estudiante</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado_create" class="form-select" required>
                                <option value="Activo" {{ old('estado_create', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="autorizacion_recojo_create" value="1" id="autorizacion_recojo_create" {{ old('autorizacion_recojo_create', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="autorizacion_recojo_create">
                                    <strong>Autorización de Recojo</strong><br>
                                    <small class="text-muted">Puede recoger al estudiante</small>
                                </label>
                            </div>
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
    body[data-bs-theme="dark"] .badge.bg-primary {
        background-color: #0d6efd !important;
    }
    body[data-bs-theme="dark"] .badge.bg-info {
        background-color: #17a2b8 !important;
        color: #0f1220 !important;
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
        $('#relacionesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            responsive: true,
            autoWidth: false,
            order: [[2, 'asc'], [4, 'asc']]
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
            var modal = new bootstrap.Modal(document.getElementById('editRelacionModal{{ session('modal_id') }}'));
            modal.show();
        @endif

        @if($errors->has('tutor_id_create') || $errors->has('estudiante_id_create'))
            var modalCreate = new bootstrap.Modal(document.getElementById('createRelacionModal'));
            modalCreate.show();
        @endif
    });
</script>
@stop