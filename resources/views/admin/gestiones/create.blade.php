@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-calendar-alt text-primary"></i>
        <span class="fw-bold fs-4">Creación de una nueva gestión educativa</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-pencil-alt text-primary me-2"></i>
                    Llene los datos del formulario
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/gestiones') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="año" class="mb-1">
                                    <i class="fas fa-calendar-alt text-secondary me-1"></i> Año
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="año" 
                                           value="{{ old('año', date('Y')) }}" 
                                           placeholder="Ej: 2024" 
                                           min="2000" 
                                           max="2100">
                                </div>
                                @error('año')
                                    <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="nombre" class="mb-1">
                                    <i class="fas fa-university text-secondary me-1"></i> 
                                    Nombre de la Gestión <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                    <input type="text" 
                                           class="form-control" 
                                           name="nombre" 
                                           value="{{ old('nombre') }}" 
                                           placeholder="Ej: Gestión 2024" 
                                           required>
                                </div>
                                @error('nombre')
                                    <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="fecha_inicio" class="mb-1">
                                    <i class="fas fa-calendar text-secondary me-1"></i> 
                                    Fecha de Inicio <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="date" 
                                           class="form-control" 
                                           name="fecha_inicio" 
                                           value="{{ old('fecha_inicio') }}" 
                                           required>
                                </div>
                                @error('fecha_inicio')
                                    <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="fecha_fin" class="mb-1">
                                    <i class="fas fa-calendar-check text-secondary me-1"></i> 
                                    Fecha de Fin <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                    <input type="date" 
                                           class="form-control" 
                                           name="fecha_fin" 
                                           value="{{ old('fecha_fin') }}" 
                                           required>
                                </div>
                                @error('fecha_fin')
                                    <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="estado" class="mb-1">
                                    <i class="fas fa-toggle-on text-secondary me-1"></i> 
                                    Estado <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                    <select class="form-control" name="estado" required>
                                        <option value="">Seleccione un estado</option>
                                        <option value="Planificado" {{ old('estado') == 'Planificado' ? 'selected' : '' }}>
                                            Planificado
                                        </option>
                                        <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>
                                            Activo
                                        </option>
                                        <option value="Finalizado" {{ old('estado') == 'Finalizado' ? 'selected' : '' }}>
                                            Finalizado
                                        </option>
                                    </select>
                                </div>
                                @error('estado')
                                    <small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="mb-1">&nbsp;</label>
                                <div class="alert alert-light border py-2 mb-0">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    <small>Campos con <span class="text-danger">*</span> son obligatorios</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">
                    
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ url('/admin/gestiones') }}" class="btn btn-light px-4">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-4">
                <img src="{{ asset('img/calendario.gif') }}" width="80px" alt="Calendario">
                <h5 class="fw-bold text-primary mt-3 mb-2">Información</h5>
                <p class="text-muted mb-3 small">
                    <i class="fas fa-info-circle me-2"></i>
                    Complete los datos para crear una nueva gestión educativa.
                </p>
                <hr class="my-2">
                <small class="text-muted">
                    <i class="fas fa-lightbulb me-1"></i>
                    Las fechas deben ser coherentes con el periodo académico
                </small>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .form-control, .input-group-text {
        border-radius: 6px;
    }
    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }
    .card {
        border-radius: 10px;
    }
    .btn {
        border-radius: 6px;
        font-weight: 500;
    }
    .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    .btn-light:hover {
        background-color: #e9ecef;
    }
    .gap-2 {
        gap: 0.5rem;
    }
    .alert-light {
        background-color: #f8f9fa;
        border-radius: 6px;
    }

    /* Modo oscuro */
    body[data-bs-theme="dark"] .card,
    body[data-bs-theme="dark"] .card-header {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .card-header {
        background-color: #171c2c !important;
    }
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
    body[data-bs-theme="dark"] .alert-light {
        background-color: #171c2c !important;
        border-color: #2a3446 !important;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .btn-light {
        background-color: #2a3446;
        border-color: #3a4458;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .btn-light:hover {
        background-color: #3a4458;
    }
    body[data-bs-theme="dark"] hr {
        border-color: #2a3446;
    }
    body[data-bs-theme="dark"] .text-muted {
        color: #a8b3cf !important;
    }
</style>
@stop