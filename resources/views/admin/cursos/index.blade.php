@extends('layouts.admin')

@section('title', 'Gestión de Cursos')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-book text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Cursos</span>
    </div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Cursos Registrados</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createCursoModal">
                <i class="fas fa-plus me-1"></i> Nuevo Curso
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="cursosTable" class="table table-bordered table-hover table-sm align-middle">
                <thead>
                    <tr>
                        <th class="w-1">ID</th>
                        <th>Grado</th>
                        <th>Nivel</th>
                        <th>Código</th>
                        <th>Nombre del Curso</th>
                        <th>Área Curricular</th>
                        <th>Hrs/Sem</th>
                        <th>Estado</th>
                        <th class="w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursos as $curso)
                    <tr>
                        <td class="text-muted">{{ $curso->id }}</td>
                        <td>
                            <span class="badge bg-primary text-white">
                                {{ $curso->grado->nombre_completo ?? 'Sin grado' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-cyan text-white">{{ $curso->nivel->nombre ?? 'Sin nivel' }}</span>
                        </td>
                        <td class="text-muted small">{{ $curso->codigo ?? '—' }}</td>
                        <td class="fw-semibold">{{ $curso->nombre }}</td>
                        <td class="text-muted">{{ $curso->area_curricular ?? '—' }}</td>
                        <td class="text-center">{{ $curso->horas_semanales }}</td>
                        <td>
                            @if($curso->estado == 'Activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.cursos.show', $curso->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Ver">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                                <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editCursoModal{{ $curso->id }}" title="Editar">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>
                                <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteCursoModal{{ $curso->id }}" title="Eliminar">
                                    <i class="fas fa-trash me-1"></i> Eliminar
                                </button>
                            </div>
                         </div>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="editCursoModal{{ $curso->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Curso</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Grado <span class="text-danger">*</span></label>
                                                <select name="grado_id" class="form-select" required>
                                                    <option value="">-- Seleccione un grado --</option>
                                                    @foreach($grados as $grado)
                                                        <option value="{{ $grado->id }}" {{ old('grado_id', $curso->grado_id) == $grado->id ? 'selected' : '' }}>
                                                            {{ $grado->nombre_completo }} ({{ $grado->nivel->nombre ?? 'Sin nivel' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Código del Curso</label>
                                                <input type="text" name="codigo" class="form-control"
                                                    value="{{ old('codigo', $curso->codigo) }}" placeholder="Ej: MAT-101">
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Nombre del Curso <span class="text-danger">*</span></label>
                                                <input type="text" name="nombre" class="form-control"
                                                    value="{{ old('nombre', $curso->nombre) }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                <select name="estado" class="form-select" required>
                                                    <option value="Activo" {{ old('estado', $curso->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                    <option value="Inactivo" {{ old('estado', $curso->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Área Curricular</label>
                                                <input type="text" name="area_curricular" class="form-control"
                                                    value="{{ old('area_curricular', $curso->area_curricular) }}"
                                                    placeholder="Ej: Ciencias, Humanidades, etc.">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Horas Semanales <span class="text-danger">*</span></label>
                                                <input type="number" name="horas_semanales" class="form-control"
                                                    value="{{ old('horas_semanales', $curso->horas_semanales) }}"
                                                    min="1" max="40" required>
                                                <small class="text-muted">Entre 1 y 40 horas</small>
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
                    <div class="modal fade" id="deleteCursoModal{{ $curso->id }}" tabindex="-1">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <strong>{{ $curso->nombre }}</strong><br>
                                            @if($curso->codigo)
                                                <small>Código: {{ $curso->codigo }}</small><br>
                                            @endif
                                            <small>Grado: {{ $curso->grado->nombre_completo ?? 'N/A' }}</small>
                                        </div>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer. Si el curso tiene docentes o matrículas asignadas, no podrá eliminarse.
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
<div class="modal fade" id="createCursoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nuevo Curso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.cursos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any() && !session('modal_id'))
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger py-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    @endif

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Grado <span class="text-danger">*</span></label>
                            <select name="grado_id_create" class="form-select" required>
                                <option value="">-- Seleccione un grado --</option>
                                @foreach($grados as $grado)
                                    <option value="{{ $grado->id }}" {{ old('grado_id_create') == $grado->id ? 'selected' : '' }}>
                                        {{ $grado->nombre_completo }} ({{ $grado->nivel->nombre ?? 'Sin nivel' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">El nivel se asignará automáticamente según el grado seleccionado.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código del Curso</label>
                            <input type="text" name="codigo_create" class="form-control"
                                value="{{ old('codigo_create') }}" placeholder="Ej: MAT-101">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Curso <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_create" class="form-control"
                                value="{{ old('nombre_create') }}" placeholder="Ej: Matemáticas" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Área Curricular</label>
                            <input type="text" name="area_curricular_create" class="form-control"
                                value="{{ old('area_curricular_create') }}"
                                placeholder="Ej: Ciencias, Humanidades, Comunicación, etc.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Horas Semanales <span class="text-danger">*</span></label>
                            <input type="number" name="horas_semanales_create" class="form-control"
                                value="{{ old('horas_semanales_create', 2) }}" min="1" max="40" required>
                            <small class="text-muted">Entre 1 y 40 horas</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado_create" class="form-select" required>
                                <option value="Activo" {{ old('estado_create', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
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
    body[data-bs-theme="dark"] .alert-danger {
        background-color: #2a1c1c;
        border-color: #8b3c3c;
        color: #f5a3a3;
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
    $(document).ready(function () {
        $('#cursosTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            responsive: true,
            autoWidth: false,
            order: [[4, 'asc']] // Ordenar por nombre del curso
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
            var modal = new bootstrap.Modal(document.getElementById('editCursoModal{{ session('modal_id') }}'));
            modal.show();
        @endif

        @if($errors->has('grado_id_create') || $errors->has('nombre_create') || $errors->has('horas_semanales_create'))
            var modalCreate = new bootstrap.Modal(document.getElementById('createCursoModal'));
            modalCreate.show();
        @endif
    });
</script>
@stop