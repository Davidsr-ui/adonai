@extends('layouts.admin')

@section('title', 'Gestión de Turnos')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-clock text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Turnos</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Turnos registrados</h3>
                <a href="{{ url('/admin/turnos/create') }}" class="btn btn-primary btn-sm rounded-pill">
                    <i class="fas fa-plus me-1"></i> Crear nuevo turno
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="turnosTable" class="table table-bordered table-hover table-sm align-middle">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">N°</th>
                                <th>Nombre</th>
                                <th style="width: 120px;">Hora inicio</th>
                                <th style="width: 120px;">Hora fin</th>
                                <th style="width: 100px; text-align: center;">Estado</th>
                                <th>Descripción</th>
                                <th style="width: 120px; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($turnos as $turno)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td><strong>{{ $turno->nombre }}</strong></td>
                                    <td style="text-align: center">
                                        <span class="badge bg-info">
                                            {{ \Carbon\Carbon::parse($turno->hora_inicio)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td style="text-align: center">
                                        <span class="badge bg-info">
                                            {{ \Carbon\Carbon::parse($turno->hora_fin)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td style="text-align: center">
                                        @if($turno->estado === 'activo')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Activo
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times-circle me-1"></i> Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($turno->descripcion, 50) ?? '—' }}</td>
                                    <td style="text-align: center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ url('/admin/turnos/'.$turno->id.'/edit') }}" 
                                               class="btn btn-outline-success btn-sm rounded-pill" 
                                               title="Editar">
                                                <i class="fas fa-pencil-alt me-1"></i> Editar
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm rounded-pill" 
                                                    onclick="confirmDelete({{ $turno->id }})" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash me-1"></i> Eliminar
                                            </button>
                                        </div>
                                        
                                        <form id="delete-form-{{ $turno->id }}" 
                                              action="{{ url('/admin/turnos/'.$turno->id) }}" 
                                              method="POST" 
                                              style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        <p class="mb-0">No hay turnos registrados.</p>
                                    </td>
                                </tr>
                            @endforelse
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
    /* Tabla fondo oscuro sin rayas */
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
    /* Badges en modo oscuro */
    body[data-bs-theme="dark"] .badge.bg-info {
        background-color: #0d6efd !important;
    }
    body[data-bs-theme="dark"] .badge.bg-success {
        background-color: #198754 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-secondary {
        background-color: #3a4458 !important;
    }
    /* Botones outline */
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
    body[data-bs-theme="dark"] .dataTables_wrapper table.dataTable tbody tr,
    body[data-bs-theme="dark"] .dataTables_wrapper table.dataTable tbody td {
        background-color: #1a1e2c !important;
    }
    /* Textos auxiliares */
    body[data-bs-theme="dark"] .text-muted {
        color: #a8b3cf !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('mensaje'))
<script>
    Swal.fire({
        icon: '{{ session('icono') ?? "success" }}',
        title: '¡Éxito!',
        text: '{{ session('mensaje') }}',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#3085d6',
        timer: 3000
    });
</script>
@endif

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '⚠️ ¿Está seguro?',
            text: "Esta acción eliminará el turno de forma permanente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    $(document).ready(function() {
        $('#turnosTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "order": [[0, "asc"]]
        });
    });
</script>
@stop