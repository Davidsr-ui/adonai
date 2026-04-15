@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-edit text-success"></i>
        <span class="fw-bold fs-4">Edición de turno</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-pencil-alt text-success me-2"></i>
                    Modifique los datos del turno
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ url('/admin/turnos/'.$turno->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="nombre" class="mb-1">
                            <i class="fas fa-clock text-secondary me-1"></i> Nombre del turno <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <input type="text" id="nombre" name="nombre" class="form-control"
                                value="{{ old('nombre', $turno->nombre) }}" placeholder="Ej: Mañana, Tarde, Noche" required>
                        </div>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="form-group mb-3 col-md-6">
                            <label for="hora_inicio" class="mb-1">
                                <i class="fas fa-hourglass-start text-secondary me-1"></i> Hora inicio <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                <input type="time" id="hora_inicio" name="hora_inicio" class="form-control"
                                    value="{{ old('hora_inicio', substr($turno->hora_inicio, 0, 5)) }}" required>
                            </div>
                            @error('hora_inicio')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3 col-md-6">
                            <label for="hora_fin" class="mb-1">
                                <i class="fas fa-hourglass-end text-secondary me-1"></i> Hora fin <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                <input type="time" id="hora_fin" name="hora_fin" class="form-control"
                                    value="{{ old('hora_fin', substr($turno->hora_fin, 0, 5)) }}" required>
                            </div>
                            @error('hora_fin')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="descripcion" class="mb-1">
                            <i class="fas fa-align-left text-secondary me-1"></i> Descripción
                        </label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="3"
                            placeholder="Descripción opcional del turno">{{ old('descripcion', $turno->descripcion) }}</textarea>
                        @error('descripcion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="estado" class="mb-1">
                            <i class="fas fa-toggle-on text-secondary me-1"></i> Estado <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                            <select id="estado" name="estado" class="form-control" required>
                                <option value="activo" {{ old('estado', $turno->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $turno->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        @error('estado')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr class="my-3">

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ url('/admin/turnos') }}" class="btn btn-light px-4">
                            <i class="fas fa-arrow-left me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-sync-alt me-2"></i> Actualizar
                        </button>
                    </div>
                </form>
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
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop