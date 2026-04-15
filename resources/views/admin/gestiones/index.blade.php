@extends('layouts.admin')

@section('title', 'Gestión de Gestiones')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-calendar-alt text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Gestiones Educativas</span>
    </div>
@stop

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ url('/admin/gestiones/create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Crear nueva gestión
        </a>
    </div>
</div>

<div class="row">
    @foreach ($gestiones as $gestion)
    <div class="col-md-4 col-lg-3 col-sm-6 col-12">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-body p-0">
                <div class="text-center py-3 bg-light rounded-top">
                    <img src="{{ asset('img/calendario.gif') }}" width="60px" alt="" class="mb-2">
                    <h5 class="fw-bold mb-1">{{ $gestion->nombre }}</h5>
                    
                    @if($gestion->año)
                        <h2 class="fw-bold mb-2" style="color: #1a56db;">{{ $gestion->año }}</h2>
                    @endif
                    
                    <div class="mb-2">
                        @if($gestion->estado == 'Activo')
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> {{ $gestion->estado }}
                            </span>
                        @elseif($gestion->estado == 'Finalizado')
                            <span class="badge bg-secondary px-3 py-2">
                                <i class="fas fa-check-double me-1"></i> {{ $gestion->estado }}
                            </span>
                        @else
                            <span class="badge bg-warning text-dark px-3 py-2">
                                <i class="fas fa-clock me-1"></i> {{ $gestion->estado }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-3">
                    <div class="d-flex align-items-center text-muted mb-3">
                        <i class="fas fa-calendar-week me-2"></i>
                        <small>
                            <strong>Inicio:</strong> {{ $gestion->fecha_inicio?->format('d/m/Y') }}
                        </small>
                    </div>
                    <div class="d-flex align-items-center text-muted mb-3">
                        <i class="fas fa-calendar-check me-2"></i>
                        <small>
                            <strong>Fin:</strong> {{ $gestion->fecha_fin?->format('d/m/Y') }}
                        </small>
                    </div>
                    
                    <hr class="my-2">
                    
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ url('/admin/gestiones/'.$gestion->id.'/edit') }}" 
                           class="btn btn-outline-success btn-sm flex-grow-1 rounded-pill">
                            <i class="fas fa-pencil-alt me-1"></i> Editar
                        </a>
                        
                        <form action="{{ url('/admin/gestiones/'.$gestion->id) }}" 
                              method="POST" 
                              id="deleteForm{{ $gestion->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm rounded-pill" 
                                    onclick="confirmarEliminacion({{ $gestion->id }})">
                                <i class="fas fa-trash me-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@stop

@section('css')
<style>
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .bg-light {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%) !important;
    }
    
    .btn-outline-success:hover {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    .gap-2 {
        gap: 0.5rem;
    }
    
    .badge {
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .rounded-pill {
        border-radius: 50rem !important;
        padding-left: 0.9rem;
        padding-right: 0.9rem;
    }

    /* Modo oscuro */
    body[data-bs-theme="dark"] .card {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .bg-light {
        background: #171c2c !important;
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
    body[data-bs-theme="dark"] .badge.bg-warning {
        background-color: #d39e00 !important;
        color: #1a1e2c !important;
    }
</style>
@stop

@section('js')
<script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Deseas eliminar esta gestión educativa?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Eliminar',
            confirmButtonColor: '#dc3545',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>

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