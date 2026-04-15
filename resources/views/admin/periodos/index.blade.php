@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-calendar-alt text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Periodos Académicos</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Periodos registrados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalCreatePeriodo">
                        <i class="fas fa-plus me-1"></i> Crear nuevo periodo
                    </button>

                    <!-- Modal Create -->
                    <div class="modal fade" id="ModalCreatePeriodo" tabindex="-1" aria-labelledby="ModalCreatePeriodoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="ModalCreatePeriodoLabel">
                                        <i class="fas fa-calendar-plus me-1"></i> Registro de un nuevo periodo
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.periodos.store') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="gestion_id_create">Gestión</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                        <select class="form-control" name="gestion_id_create" id="gestion_id_create" required>
                                                            <option value="">Seleccione una gestión</option>
                                                            @foreach ($gestiones as $gestion)
                                                                <option value="{{ $gestion->id }}" {{ old('gestion_id_create') == $gestion->id ? 'selected' : '' }}>
                                                                    {{ $gestion->nombre }} - {{ $gestion->año }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('gestion_id_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="numero_create">Número de Periodo</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                        <input type="number" class="form-control" name="numero_create" 
                                                            value="{{ old('numero_create') }}" placeholder="Ej: 1, 2, 3..." 
                                                            min="1" required>
                                                    </div>
                                                    @error('numero_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="nombre_create">Nombre del periodo</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                        <input type="text" class="form-control" name="nombre_create" 
                                                            value="{{ old('nombre_create') }}" 
                                                            placeholder="Ej: Primer Trimestre" required>
                                                    </div>
                                                    @error('nombre_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fecha_inicio_create">Fecha de Inicio</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                        <input type="date" class="form-control" name="fecha_inicio_create" 
                                                            value="{{ old('fecha_inicio_create') }}" required>
                                                    </div>
                                                    @error('fecha_inicio_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="fecha_fin_create">Fecha de Fin</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                                        <input type="date" class="form-control" name="fecha_fin_create" 
                                                            value="{{ old('fecha_fin_create') }}" required>
                                                    </div>
                                                    @error('fecha_fin_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="estado_create">Estado</label> <b>(*)</b>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                                        <select class="form-control" name="estado_create" required>
                                                            <option value="">Seleccione un estado</option>
                                                            <option value="Planificado" {{ old('estado_create') == 'Planificado' ? 'selected' : '' }}>Planificado</option>
                                                            <option value="Activo" {{ old('estado_create') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                            <option value="Finalizado" {{ old('estado_create') == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                                        </select>
                                                    </div>
                                                    @error('estado_create')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-md-12 text-end">
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
                    <!-- Eliminada la clase table-striped para evitar rayas blancas en oscuro -->
                    <table id="example" class="table table-bordered table-hover table-sm align-middle">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 50px;">Nro</th>
                                <th>Gestión</th>
                                <th>Periodos</th>
                                <th style="text-align: center; width: 200px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gestiones as $gestion)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $gestion->nombre }}</strong>
                                        @if($gestion->año)
                                            <br><small class="text-muted">Año: {{ $gestion->año }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gestion->periodos->count() > 0)
                                            @foreach ($gestion->periodos->sortBy('numero') as $periodo)
                                                <div class="mb-2 pb-1 border-bottom">
                                                    <span class="badge bg-light text-dark me-1" style="font-size: 13px; padding: 8px 12px;">
                                                        <strong>{{ $periodo->numero }}.</strong> {{ $periodo->nombre }}
                                                    </span>
                                                    
                                                    @if($periodo->estado == 'Activo')
                                                        <span class="badge bg-success">{{ $periodo->estado }}</span>
                                                    @elseif($periodo->estado == 'Finalizado')
                                                        <span class="badge bg-secondary">{{ $periodo->estado }}</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">{{ $periodo->estado }}</span>
                                                    @endif
                                                    
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar"></i> 
                                                            {{ $periodo->fecha_inicio?->format('d/m/Y') }} - 
                                                            {{ $periodo->fecha_fin?->format('d/m/Y') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Sin periodos registrados</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gestion->periodos->count() > 0)
                                            @foreach ($gestion->periodos->sortBy('numero') as $periodo)
                                            <div class="d-flex justify-content-center gap-2 mb-2">
                                                <button type="button" class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" 
                                                    data-bs-target="#ModalUpdatePeriodo{{ $periodo->id }}" title="Editar">
                                                    <i class="fas fa-pencil-alt me-1"></i> Editar
                                                </button>

                                                <form action="{{ url('/admin/periodos/'.$periodo->id) }}" 
                                                    method="POST" id="miFormularioPeriodo{{ $periodo->id }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" 
                                                        onclick="preguntar{{ $periodo->id }}(event)" title="Eliminar">
                                                        <i class="fas fa-trash me-1"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>

                                            <script>
                                                function preguntar{{ $periodo->id }}(event) {
                                                    event.preventDefault();
                                                    Swal.fire({
                                                        title: '¿Deseas eliminar este periodo?',
                                                        text: "Esta acción no se puede deshacer",
                                                        icon: 'question',
                                                        showCancelButton: true,
                                                        confirmButtonText: 'Sí, eliminar',
                                                        confirmButtonColor: '#a5161d',
                                                        cancelButtonText: 'Cancelar',
                                                        cancelButtonColor: '#6c757d'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            document.getElementById('miFormularioPeriodo{{ $periodo->id }}').submit();
                                                        }
                                                    });
                                                }
                                            </script>

                                            <!-- Modal Update -->
                                            <div class="modal fade" id="ModalUpdatePeriodo{{ $periodo->id }}" tabindex="-1"
                                                 aria-labelledby="ModalUpdatePeriodoLabel{{ $periodo->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title" id="ModalUpdatePeriodoLabel{{ $periodo->id }}">
                                                                <i class="fas fa-edit me-1"></i> Actualizar periodo
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <form action="{{ url('/admin/periodos/'.$periodo->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="gestion_id">Gestión</label> <b>(*)</b>
                                                                            <div class="input-group mb-3">
                                                                                <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                                                <select class="form-control" name="gestion_id" required>
                                                                                    <option value="">Seleccione una gestión</option>
                                                                                    @foreach ($gestiones as $gest)
                                                                                        <option value="{{ $gest->id }}"
                                                                                            {{ old('gestion_id', $periodo->gestion_id) == $gest->id ? 'selected' : '' }}>
                                                                                            {{ $gest->nombre }} - {{ $gest->año }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            @error('gestion_id')
                                                                                <small class="text-danger">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="numero">Número de Periodo</label> <b>(*)</b>
                                                                            <div class="input-group mb-3">
                                                                                <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                                                                                <input type="number" class="form-control" name="numero" 
                                                                                    value="{{ old('numero', $periodo->numero) }}" 
                                                                                    placeholder="Ej: 1, 2, 3..." min="1" required>
                                                                            </div>
                                                                            @error('numero')
                                                                                <small class="text-danger">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Nombre del periodo</label> <b>(*)</b>
                                                                    <div class="input-group mb-3">
                                                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                        <input type="text" class="form-control" name="nombre" 
                                                                            value="{{ old('nombre', $periodo->nombre) }}" 
                                                                            placeholder="Ej: Primer Trimestre" required>
                                                                    </div>
                                                                    @error('nombre')
                                                                        <small class="text-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="fecha_inicio">Fecha de Inicio</label> <b>(*)</b>
                                                                            <div class="input-group mb-3">
                                                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                                                <input type="date" class="form-control" name="fecha_inicio" 
                                                                                    value="{{ old('fecha_inicio', $periodo->fecha_inicio?->format('Y-m-d')) }}" required>
                                                                            </div>
                                                                            @error('fecha_inicio')
                                                                                <small class="text-danger">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="fecha_fin">Fecha de Fin</label> <b>(*)</b>
                                                                            <div class="input-group mb-3">
                                                                                <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                                                                <input type="date" class="form-control" name="fecha_fin" 
                                                                                    value="{{ old('fecha_fin', $periodo->fecha_fin?->format('Y-m-d')) }}" required>
                                                                            </div>
                                                                            @error('fecha_fin')
                                                                                <small class="text-danger">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="estado">Estado</label> <b>(*)</b>
                                                                    <div class="input-group mb-3">
                                                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                                                        <select class="form-control" name="estado" required>
                                                                            <option value="">Seleccione un estado</option>
                                                                            <option value="Planificado" {{ old('estado', $periodo->estado) == 'Planificado' ? 'selected' : '' }}>Planificado</option>
                                                                            <option value="Activo" {{ old('estado', $periodo->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                                            <option value="Finalizado" {{ old('estado', $periodo->estado) == 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                                                        </select>
                                                                    </div>
                                                                    @error('estado')
                                                                        <small class="text-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>

                                                                <hr>

                                                                <div class="d-flex justify-content-end">
                                                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                                                        <i class="fas fa-times me-1"></i> Cancelar
                                                                    </button>
                                                                    <button type="submit" class="btn btn-success">
                                                                        <i class="fas fa-save me-1"></i> Actualizar
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
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
    /* Estilos base */
    .gap-2 {
        gap: 0.5rem;
    }
    .rounded-pill {
        border-radius: 50rem !important;
        padding-left: 0.9rem;
        padding-right: 0.9rem;
    }
    .border-bottom:last-child {
        border-bottom: none !important;
    }

    /* ===== MODO OSCURO ===== */
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
    body[data-bs-theme="dark"] .dataTables_wrapper table.dataTable tbody tr,
    body[data-bs-theme="dark"] .dataTables_wrapper table.dataTable tbody td {
        background-color: #1a1e2c !important;
    }

    /* Inputs, selects, badges */
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
    body[data-bs-theme="dark"] .badge.bg-light {
        background-color: #2d3448 !important;
        color: #f0f0f0 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-warning {
        background-color: #d39e00 !important;
        color: #1a1e2c !important;
    }
    body[data-bs-theme="dark"] .text-muted {
        color: #a8b3cf !important;
    }
    body[data-bs-theme="dark"] hr {
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
</style>
@stop

@section('js')
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

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (session('modal_id'))
                    var modalElement = document.getElementById("ModalUpdatePeriodo{{ session('modal_id') }}");
                    if (modalElement) {
                        var myModal = new bootstrap.Modal(modalElement);
                        myModal.show();
                    }
                @elseif(session('modal_open') == 'create')
                    var modalElement = document.getElementById("ModalCreatePeriodo");
                    if (modalElement) {
                        var myModal = new bootstrap.Modal(modalElement);
                        myModal.show();
                    }
                @endif
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "order": [[0, "asc"]]
            });
        });
    </script>
@stop