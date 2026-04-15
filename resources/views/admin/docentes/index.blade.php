@extends('layouts.admin')

@section('title', 'Docentes')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-chalkboard-teacher text-primary"></i>
        <span class="fw-bold fs-4">Docentes</span>
    </div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Docentes Registrados</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createDocenteModal">
                <i class="fas fa-plus me-1"></i> Crear nuevo
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="docentesTable" class="table table-bordered table-hover table-sm align-middle">
                <thead>
                    <tr>
                        <th class="w-1">Nro</th>
                        <th>DNI</th>
                        <th>Apellidos y Nombres</th>
                        <th>Código</th>
                        <th>Especialidad</th>
                        <th>Tipo Contrato</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th class="w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $contador = 1; @endphp
                    @foreach($docentes as $docente)
                    <tr>
                        <td class="text-muted text-center">{{ $contador++ }}</td>
                        <td class="text-muted">{{ $docente->persona->dni ?? '—' }}</td>
                        <td>
                            <div class="fw-semibold">{{ $docente->persona->apellidos }} {{ $docente->persona->nombres }}</div>
                        </td>
                        <td class="text-muted">{{ $docente->codigo_docente }}</td>
                        <td class="text-muted">{{ $docente->especialidad ?? '-' }}</td>
                        <td>
                            @if($docente->tipo_contrato == 'Nombrado')
                                <span class="badge bg-success">{{ $docente->tipo_contrato }}</span>
                            @elseif($docente->tipo_contrato == 'Contratado')
                                <span class="badge bg-cyan text-white">{{ $docente->tipo_contrato }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ $docente->tipo_contrato }}</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $docente->persona->telefono ?? '-' }}</td>
                        <td>
                            @if($docente->persona->estado == 'Activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.docentes.show', $docente->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Ver">
                                    <i class="fas fa-eye me-1"></i> Ver
                                </a>
                                <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editDocenteModal{{ $docente->id }}" title="Editar">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </button>
                                <form action="{{ route('admin.docentes.destroy', $docente->id) }}" method="POST" id="formDelete{{ $docente->id }}" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" onclick="confirmarEliminar({{ $docente->id }})" title="Eliminar">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                         </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Crear --}}
<div class="modal fade" id="createDocenteModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Crear Nuevo Docente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.docentes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif

                    <h6 class="fw-bold text-muted text-uppercase mb-3"><i class="fas fa-id-card me-1"></i> Datos Personales</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">DNI <span class="text-danger">*</span></label>
                            <input type="text" name="dni_create" value="{{ old('dni_create') }}" class="form-control" required maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres_create" value="{{ old('nombres_create') }}" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos_create" value="{{ old('apellidos_create') }}" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_nacimiento_create" value="{{ old('fecha_nacimiento_create') }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Género <span class="text-danger">*</span></label>
                            <select name="genero_create" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="M" {{ old('genero_create') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('genero_create') == 'F' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('genero_create') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado_create" class="form-select" required>
                                <option value="Activo" {{ old('estado_create', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion_create" value="{{ old('direccion_create') }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono_create" value="{{ old('telefono_create') }}" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono Emergencia</label>
                            <input type="text" name="telefono_emergencia_create" value="{{ old('telefono_emergencia_create') }}" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Foto de Perfil</label>
                            <input type="file" name="foto_perfil" class="form-control">
                            <small class="text-muted">JPG, JPEG, PNG — Máx: 2MB</small>
                        </div>
                    </div>

                    <h6 class="fw-bold text-muted text-uppercase mt-4 mb-3"><i class="fas fa-briefcase me-1"></i> Datos Laborales</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Código Docente <span class="text-danger">*</span></label>
                            <input type="text" name="codigo_docente_create" value="{{ old('codigo_docente_create') }}" class="form-control" required maxlength="50">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="especialidad_create" value="{{ old('especialidad_create') }}" class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha Contratación <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_contratacion_create" value="{{ old('fecha_contratacion_create') }}" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Contrato <span class="text-danger">*</span></label>
                            <select name="tipo_contrato_create" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="Nombrado" {{ old('tipo_contrato_create') == 'Nombrado' ? 'selected' : '' }}>Nombrado</option>
                                <option value="Contratado" {{ old('tipo_contrato_create') == 'Contratado' ? 'selected' : '' }}>Contratado</option>
                                <option value="Temporal" {{ old('tipo_contrato_create') == 'Temporal' ? 'selected' : '' }}>Temporal</option>
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

{{-- Modales Editar --}}
@foreach($docentes as $docente)
<div class="modal fade" id="editDocenteModal{{ $docente->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Docente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.docentes.update', $docente->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    @if(session('modal_id') == $docente->id && $errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif

                    <h6 class="fw-bold text-muted text-uppercase mb-3"><i class="fas fa-id-card me-1"></i> Datos Personales</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">DNI <span class="text-danger">*</span></label>
                            <input type="text" name="dni" value="{{ $docente->persona->dni ?? '—' }}" class="form-control" required maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="nombres" value="{{ $docente->persona->nombres }}" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellidos" value="{{ $docente->persona->apellidos }}" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_nacimiento" value="{{ $docente->persona->fecha_nacimiento }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Género <span class="text-danger">*</span></label>
                            <select name="genero" class="form-select" required>
                                <option value="M" {{ $docente->persona->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ $docente->persona->genero == 'F' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ $docente->persona->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select" required>
                                <option value="Activo" {{ $docente->persona->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ $docente->persona->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" value="{{ $docente->persona->direccion }}" class="form-control" maxlength="255">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" value="{{ $docente->persona->telefono }}" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono Emergencia</label>
                            <input type="text" name="telefono_emergencia" value="{{ $docente->persona->telefono_emergencia }}" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Foto de Perfil</label>
                            <input type="file" name="foto_perfil" class="form-control">
                            @if($docente->persona->foto_perfil)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $docente->persona->foto_perfil) }}" class="avatar avatar-xl rounded" width="80">
                                </div>
                            @else
                                <small class="text-muted">Sin foto actual</small>
                            @endif
                        </div>
                    </div>

                    <h6 class="fw-bold text-muted text-uppercase mt-4 mb-3"><i class="fas fa-briefcase me-1"></i> Datos Laborales</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Código Docente <span class="text-danger">*</span></label>
                            <input type="text" name="codigo_docente" value="{{ $docente->codigo_docente }}" class="form-control" required maxlength="50">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="especialidad" value="{{ $docente->especialidad }}" class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha Contratación <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_contratacion" value="{{ $docente->fecha_contratacion }}" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Contrato <span class="text-danger">*</span></label>
                            <select name="tipo_contrato" class="form-select" required>
                                <option value="Nombrado" {{ $docente->tipo_contrato == 'Nombrado' ? 'selected' : '' }}>Nombrado</option>
                                <option value="Contratado" {{ $docente->tipo_contrato == 'Contratado' ? 'selected' : '' }}>Contratado</option>
                                <option value="Temporal" {{ $docente->tipo_contrato == 'Temporal' ? 'selected' : '' }}>Temporal</option>
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
@endforeach
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    .gap-2 { gap: 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; padding-left: 0.9rem; padding-right: 0.9rem; }
    .avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }

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
    body[data-bs-theme="dark"] .badge.bg-cyan {
        background-color: #17a2b8 !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#docentesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            responsive: true,
            autoWidth: false,
            order: [[2, 'asc']]
        });
    });

    function confirmarEliminar(id) {
        Swal.fire({
            title: '¿Seguro que quiere eliminar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d63939'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('formDelete' + id).submit();
        });
    }

    @if(session('mensaje'))
        Swal.fire({ icon: '{{ session('icono') }}', title: '{{ session('mensaje') }}', timer: 2500, showConfirmButton: false });
    @endif

    @if($errors->any() && session('modal_id'))
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('editDocenteModal{{ session('modal_id') }}'));
            modal.show();
        });
    @endif
</script>
@stop