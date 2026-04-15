@extends('layouts.admin')

@section('title', 'Asignación de Docentes')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-chalkboard-teacher text-primary"></i>
        <span class="fw-bold fs-4">Asignación de Docentes</span>
    </div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Asignaciones Registradas</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createAsignacionModal">
                <i class="fas fa-plus me-1"></i> Nueva Asignación
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="asignacionesTable" class="table table-bordered table-hover table-sm align-middle">
                <thead>
                    <tr>
                        <th class="w-1">ID</th>
                        <th>Docente</th>
                        <th>Curso</th>
                        <th>Grado</th>
                        <th>Nivel</th>
                        <th>Gestión</th>
                        <th>Tutor Aula</th>
                        <th class="w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asignaciones as $asignacion)
                    <tr>
                        <td class="text-muted">{{ $asignacion->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $asignacion->docente->persona->apellidos }}, {{ $asignacion->docente->persona->nombres }}</div>
                            <div class="text-muted small">{{ $asignacion->docente->codigo_docente }}</div>
                        </td>
                        <td class="text-muted">{{ $asignacion->curso->nombre }}</td>
                        <td class="text-muted">{{ $asignacion->grado->nombre_completo }}</td>
                        <td><span class="badge bg-cyan text-white">{{ $asignacion->grado->nivel->nombre ?? 'N/A' }}</span></td>
                        <td><span class="badge bg-secondary">{{ $asignacion->gestion->nombre }}</span></td>
                        <td>
                            @if($asignacion->es_tutor_aula)
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.asignaciones.show', $asignacion->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Ver">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                                <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editAsignacionModal{{ $asignacion->id }}" title="Editar">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>
                                <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteAsignacionModal{{ $asignacion->id }}" title="Eliminar">
                                    <i class="fas fa-trash me-1"></i> Eliminar
                                </button>
                            </div>
                         </div>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="editAsignacionModal{{ $asignacion->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Asignación</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.asignaciones.update', $asignacion->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Docente <span class="text-danger">*</span></label>
                                                <select name="docente_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($docentes as $docente)
                                                        <option value="{{ $docente->id }}" {{ old('docente_id', $asignacion->docente_id) == $docente->id ? 'selected' : '' }}>
                                                            {{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }} — {{ $docente->codigo_docente }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Curso <span class="text-danger">*</span></label>
                                                <select name="curso_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($cursos as $curso)
                                                        <option value="{{ $curso->id }}" {{ old('curso_id', $asignacion->curso_id) == $curso->id ? 'selected' : '' }}>
                                                            {{ $curso->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Grado <span class="text-danger">*</span></label>
                                                <select name="grado_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($grados as $grado)
                                                        <option value="{{ $grado->id }}" {{ old('grado_id', $asignacion->grado_id) == $grado->id ? 'selected' : '' }}>
                                                            {{ $grado->nivel->nombre }} — {{ $grado->nombre_completo }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Gestión <span class="text-danger">*</span></label>
                                                <select name="gestion_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($gestiones as $gestion)
                                                        <option value="{{ $gestion->id }}" {{ old('gestion_id', $asignacion->gestion_id) == $gestion->id ? 'selected' : '' }}>
                                                            {{ $gestion->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="es_tutor_aula{{ $asignacion->id }}"
                                                        name="es_tutor_aula" value="1"
                                                        {{ old('es_tutor_aula', $asignacion->es_tutor_aula) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="es_tutor_aula{{ $asignacion->id }}">
                                                        <strong>Es Tutor de Aula</strong>
                                                        <span class="text-muted d-block small">Solo puede haber un tutor por grado y gestión</span>
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
                    <div class="modal fade" id="deleteAsignacionModal{{ $asignacion->id }}" tabindex="-1">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.asignaciones.destroy', $asignacion->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <strong>{{ $asignacion->docente->persona->apellidos }}, {{ $asignacion->docente->persona->nombres }}</strong><br>
                                            <small>{{ $asignacion->curso->nombre }} — {{ $asignacion->grado->nombre_completo }}</small><br>
                                            <small>{{ $asignacion->gestion->nombre }}</small>
                                        </div>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer.
                                        </div>
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
<div class="modal fade" id="createAsignacionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nueva Asignación de Docente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.asignaciones.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Docente <span class="text-danger">*</span></label>
                            <select name="docente_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($docentes as $docente)
                                    <option value="{{ $docente->id }}" {{ old('docente_id_create') == $docente->id ? 'selected' : '' }}>
                                        {{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }} — {{ $docente->codigo_docente }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Curso <span class="text-danger">*</span></label>
                            <select name="curso_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ old('curso_id_create') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Grado <span class="text-danger">*</span></label>
                            <select name="grado_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id }}" {{ old('grado_id_create') == $grado->id ? 'selected' : '' }}>
                                        {{ $grado->nivel->nombre }} — {{ $grado->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gestión <span class="text-danger">*</span></label>
                            <select name="gestion_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($gestiones as $gestion)
                                    <option value="{{ $gestion->id }}" {{ old('gestion_id_create') == $gestion->id ? 'selected' : '' }}>
                                        {{ $gestion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="es_tutor_aula_create"
                                    name="es_tutor_aula_create" value="1"
                                    {{ old('es_tutor_aula_create') ? 'checked' : '' }}>
                                <label class="form-check-label" for="es_tutor_aula_create">
                                    <strong>Es Tutor de Aula</strong>
                                    <span class="text-muted d-block small">Solo puede haber un tutor por grado y gestión</span>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
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
    /* Select2 en modo oscuro */
    body[data-bs-theme="dark"] .select2-container--default .select2-selection--single {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .select2-dropdown {
        background-color: #1a1e2c !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .select2-container--default .select2-results__option {
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #2c3145 !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#asignacionesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            responsive: true,
            autoWidth: false,
            order: [[5, 'desc'], [3, 'asc']]
        });

        $('.select2').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true
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
            var modal = new bootstrap.Modal(document.getElementById('editAsignacionModal{{ session('modal_id') }}'));
            modal.show();
        @endif

        @if($errors->has('docente_id_create') || $errors->has('curso_id_create') || $errors->has('grado_id_create') || $errors->has('gestion_id_create'))
            var modalCreate = new bootstrap.Modal(document.getElementById('createAsignacionModal'));
            modalCreate.show();
        @endif
    });
</script>
@stop