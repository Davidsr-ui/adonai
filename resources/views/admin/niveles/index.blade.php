@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-layer-group text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Niveles</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Niveles registrados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalCreate">
                        <i class="fas fa-plus me-1"></i> Crear nuevo nivel
                    </button>

                    <!-- Modal Create -->
                    <div class="modal fade" id="ModalCreate" tabindex="-1" aria-labelledby="ModalCreateLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="ModalCreateLabel">
                                        <i class="fas fa-layer-group me-1"></i> Registro de un nuevo nivel
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('/admin/niveles/create') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="nombre_create">Nombre del nivel</label> <span class="text-danger">*</span>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                        <input type="text" class="form-control" name="nombre_create" value="{{ old('nombre_create') }}" placeholder="Ej: Nivel Inicial" required>
                                                    </div>
                                                    @error('nombre_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="orden_create">Orden</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                                        <input type="number" class="form-control" name="orden_create" value="{{ old('orden_create', 0) }}" min="0">
                                                    </div>
                                                    @error('orden_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="estado_create">Estado</label> <span class="text-danger">*</span>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                                        <select name="estado_create" class="form-control" required>
                                                            <option value="Activo" {{ old('estado_create') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                            <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                        </select>
                                                    </div>
                                                    @error('estado_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="descripcion_create">Descripción</label>
                                                    <textarea class="form-control" name="descripcion_create" rows="3" placeholder="Descripción opcional del nivel">{{ old('descripcion_create') }}</textarea>
                                                    @error('descripcion_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-md-12 d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i> Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i> Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-hover table-sm align-middle">
                        <!-- Eliminamos la clase table-striped para evitar el fondo gris en claro y blanco en oscuro -->
                        <thead>
                            <tr>
                                <th style="width: 50px;">Nro</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th style="width: 80px;">Orden</th>
                                <th style="width: 100px;">Estado</th>
                                <th style="width: 180px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($niveles as $nivel)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ $nivel->nombre }}</td>
                                    <td>{{ Str::limit($nivel->descripcion, 50) ?? 'Sin descripción' }}</td>
                                    <td style="text-align: center">{{ $nivel->orden }}</td>
                                    <td style="text-align: center">
                                        @if($nivel->estado == 'Activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#ModalUpdate{{ $nivel->id }}">
                                                <i class="fas fa-pencil-alt me-1"></i> Editar
                                            </button>
                                            
                                            <form action="{{ url('/admin/niveles/'.$nivel->id) }}" 
                                                  method="POST" 
                                                  id="deleteFormNivel{{ $nivel->id }}" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-outline-danger btn-sm rounded-pill" 
                                                        onclick="confirmarEliminacionNivel({{ $nivel->id }})">
                                                    <i class="fas fa-trash me-1"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal Update -->
                                        <div class="modal fade" id="ModalUpdate{{ $nivel->id }}" tabindex="-1" aria-labelledby="ModalUpdateLabel{{ $nivel->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title" id="ModalUpdateLabel{{ $nivel->id }}">
                                                            <i class="fas fa-edit me-1"></i> Actualizar nivel
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ url('/admin/niveles/'.$nivel->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            
                                                            <input type="hidden" name="modal_id" value="{{ $nivel->id }}">
                                                            
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3">
                                                                        <label>Nombre del nivel</label> <span class="text-danger">*</span>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                                            <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $nivel->nombre) }}" placeholder="Ej: Nivel Inicial" required>
                                                                        </div>
                                                                        @error('nombre')
                                                                            <small class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label>Orden</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                                                            <input type="number" class="form-control" name="orden" value="{{ old('orden', $nivel->orden) }}" min="0">
                                                                        </div>
                                                                        @error('orden')
                                                                            <small class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-3">
                                                                        <label>Estado</label> <span class="text-danger">*</span>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                                                            <select name="estado" class="form-control" required>
                                                                                <option value="Activo" {{ old('estado', $nivel->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                                                <option value="Inactivo" {{ old('estado', $nivel->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                                            </select>
                                                                        </div>
                                                                        @error('estado')
                                                                            <small class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group mb-3">
                                                                        <label>Descripción</label>
                                                                        <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción opcional del nivel">{{ old('descripcion', $nivel->descripcion) }}</textarea>
                                                                        @error('descripcion')
                                                                            <small class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <hr>

                                                            <div class="d-flex justify-content-end gap-2">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="fas fa-times me-1"></i> Cancelar
                                                                </button>
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-sync-alt me-1"></i> Actualizar
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .gap-2 {
        gap: 0.5rem;
    }
    .rounded-pill {
        border-radius: 50rem !important;
        padding-left: 0.9rem;
        padding-right: 0.9rem;
    }

    /* ===== MODO OSCURO COMPLETO ===== */
    body[data-bs-theme="dark"] .card,
    body[data-bs-theme="dark"] .modal-content {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .card-header {
        background-color: #171c2c !important;
        border-bottom-color: #2a3446 !important;
    }
    
    /* Tabla: fondo unificado oscuro, sin rayas */
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
        background-color: #1a1e2c !important; /* Fijo para todas las celdas */
    }
    body[data-bs-theme="dark"] .table thead th {
        background-color: #0f1220 !important;
        color: #f8f9fa !important;
        border-bottom-color: #2a3446 !important;
    }
    /* Hover efecto */
    body[data-bs-theme="dark"] .table-hover > tbody > tr:hover > * {
        background-color: #2c3145 !important;
    }
    
    /* DataTables: forzar fondo consistente */
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
    body[data-bs-theme="dark"] .dataTables_wrapper table.dataTable tbody tr {
        background-color: #1a1e2c !important;
    }
    body[data-bs-theme="dark"] .dataTables_wrapper table.dataTable tbody td {
        background-color: #1a1e2c !important;
    }
    
    /* Formularios */
    body[data-bs-theme="dark"] .form-control,
    body[data-bs-theme="dark"] .input-group-text,
    body[data-bs-theme="dark"] select.form-control {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .input-group-text {
        background-color: #1a1e2c !important;
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
    body[data-bs-theme="dark"] .modal-header {
        border-bottom-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] hr {
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-success {
        background-color: #198754 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-secondary {
        background-color: #3a4458 !important;
    }
</style>
@stop

@section('js')
<script>
    function confirmarEliminacionNivel(id) {
        Swal.fire({
            title: '¿Deseas eliminar este nivel?',
            text: "Esta acción no se puede deshacer",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#a5161d',
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteFormNivel' + id).submit();
            }
        });
    }
</script>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('modal_id'))
            var modalElement = document.getElementById("ModalUpdate{{ session('modal_id') }}");
            if (modalElement) {
                var myModal = new bootstrap.Modal(modalElement);
                myModal.show();
            }
        @elseif(old('modal_id'))
            var modalElement = document.getElementById("ModalUpdate{{ old('modal_id') }}");
            if (modalElement) {
                var myModal = new bootstrap.Modal(modalElement);
                myModal.show();
            }
        @else
            var modalElement = document.getElementById("ModalCreate");
            if (modalElement) {
                var myModal = new bootstrap.Modal(modalElement);
                myModal.show();
            }
        @endif
    });
</script>
@endif

@if(session('mensaje'))
<script>
    Swal.fire({
        icon: '{{ session('icono') }}',
        title: '{{ session('mensaje') }}',
        showConfirmButton: false,
        timer: 2500
    });
</script>
@endif
@stop