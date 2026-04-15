@extends('layouts.admin')

@section('title', 'Gestión de Grados')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-layer-group text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Grados</span>
    </div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Grados Registrados</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createGradoModal">
                <i class="fas fa-plus me-1"></i> Nuevo Grado
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="gradosTable" class="table table-bordered table-hover table-sm align-middle">
                <thead>
                    <tr>
                        <th class="w-1">ID</th>
                        <th>Nivel</th>
                        <th>Nombre</th>
                        <th>Sección</th>
                        <th>Turno</th>
                        <th>Capacidad</th>
                        <th>Ocupación</th>
                        <th>Estado</th>
                        <th class="w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grados as $grado)
                    <tr>
                        <td class="text-muted">{{ $grado->id }}</td>
                        <td>
                            <span class="badge bg-cyan text-white">{{ $grado->nivel->nombre ?? 'Sin nivel' }}</span>
                        </td>
                        <td><div class="fw-semibold">{{ $grado->nombre }}</div></td>
                        <td>
                            @if($grado->seccion)
                                <span class="badge bg-secondary">{{ $grado->seccion }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($grado->turno)
                                <span class="badge bg-warning text-dark">{{ $grado->turno->nombre }}</span>
                            @else
                                <span class="text-muted">No asignado</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $grado->estudiantes->count() }} / {{ $grado->capacidad_maxima }}</td>
                        <td class="text-center">
                            @php
                                $porcentaje = $grado->porcentaje_ocupacion;
                                $clase = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 70 ? 'warning' : 'success');
                            @endphp
                            <span class="badge bg-{{ $clase }} text-white">{{ $porcentaje }}%</span>
                        </td>
                        <td>
                            @if($grado->estado == 'Activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.grados.show', $grado->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Ver">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                                <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editGradoModal{{ $grado->id }}" title="Editar">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>
                                <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteGradoModal{{ $grado->id }}" title="Eliminar">
                                    <i class="fas fa-trash me-1"></i> Eliminar
                                </button>
                            </div>
                         </div>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="editGradoModal{{ $grado->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Grado</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.grados.update', $grado->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Nivel <span class="text-danger">*</span></label>
                                                <select name="nivel_id" class="form-select" required>
                                                    <option value="">-- Seleccione --</option>
                                                    @foreach($niveles as $nivel)
                                                        <option value="{{ $nivel->id }}" {{ old('nivel_id', $grado->nivel_id) == $nivel->id ? 'selected' : '' }}>{{ $nivel->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Turno</label>
                                                <select name="turno_id" class="form-select">
                                                    <option value="">-- Sin turno --</option>
                                                    @foreach($turnos as $turno)
                                                        <option value="{{ $turno->id }}" {{ old('turno_id', $grado->turno_id) == $turno->id ? 'selected' : '' }}>{{ $turno->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nombre del Grado <span class="text-danger">*</span></label>
                                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $grado->nombre) }}" placeholder="Ej: 1er Grado" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Sección</label>
                                                <input type="text" name="seccion" class="form-control" value="{{ old('seccion', $grado->seccion) }}" placeholder="Ej: A, B, C">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Capacidad Máxima <span class="text-danger">*</span></label>
                                                <input type="number" name="capacidad_maxima" class="form-control" value="{{ old('capacidad_maxima', $grado->capacidad_maxima) }}" min="1" max="100" required>
                                                <small class="text-muted">Actuales: {{ $grado->estudiantes->count() }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                <select name="estado" class="form-select" required>
                                                    <option value="Activo" {{ old('estado', $grado->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                    <option value="Inactivo" {{ old('estado', $grado->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
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
                    <div class="modal fade" id="deleteGradoModal{{ $grado->id }}" tabindex="-1">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.grados.destroy', $grado->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <strong>{{ $grado->nombre_completo }}</strong><br>
                                            <small>Nivel: {{ $grado->nivel->nombre ?? 'N/A' }}</small>
                                        </div>
                                        @if($grado->estudiantes->count() > 0)
                                            <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-1"></i>Este grado tiene {{ $grado->estudiantes->count() }} estudiante(s) y no puede eliminarse.</div>
                                        @else
                                            <div class="alert alert-warning"><i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer.</div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger w-50" {{ $grado->estudiantes->count() > 0 ? 'disabled' : '' }}><i class="fas fa-trash me-1"></i>Eliminar</button>
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
<div class="modal fade" id="createGradoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Crear Nuevo Grado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.grados.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nivel <span class="text-danger">*</span></label>
                            <select name="nivel_id_create" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($niveles as $nivel)
                                    <option value="{{ $nivel->id }}" {{ old('nivel_id_create') == $nivel->id ? 'selected' : '' }}>{{ $nivel->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Turno</label>
                            <select name="turno_id_create" class="form-select">
                                <option value="">-- Sin turno --</option>
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->id }}" {{ old('turno_id_create') == $turno->id ? 'selected' : '' }}>
                                        {{ $turno->nombre }} ({{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Grado <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_create" class="form-control" value="{{ old('nombre_create') }}" placeholder="Ej: 1er Grado" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sección</label>
                            <input type="text" name="seccion_create" class="form-control" value="{{ old('seccion_create') }}" placeholder="Ej: A, B, C">
                            <small class="text-muted">Opcional — para diferenciar paralelos</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacidad Máxima <span class="text-danger">*</span></label>
                            <input type="number" name="capacidad_maxima_create" class="form-control" value="{{ old('capacidad_maxima_create', 30) }}" min="1" max="100" required>
                            <small class="text-muted">Entre 1 y 100 estudiantes</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado_create" class="form-select" required>
                                <option value="Activo" {{ old('estado_create') == 'Activo' ? 'selected' : '' }}>Activo</option>
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
    $(document).ready(function() {
        $('#gradosTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            responsive: true,
            autoWidth: false,
            order: [[1, 'asc'], [2, 'asc']]
        });

        @if(session('mensaje'))
            Swal.fire({
                icon: '{{ session('icono') }}',
                title: '{{ session('mensaje') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if($errors->any() && session('modal_id'))
            var modal = new bootstrap.Modal(document.getElementById('editGradoModal{{ session('modal_id') }}'));
            modal.show();
        @endif

        @if($errors->has('nombre_create') || $errors->has('nivel_id_create') || $errors->has('capacidad_maxima_create'))
            var modalCreate = new bootstrap.Modal(document.getElementById('createGradoModal'));
            modalCreate.show();
        @endif
    });
</script>
@stop