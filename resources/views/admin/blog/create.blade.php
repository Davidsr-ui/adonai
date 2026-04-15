@extends('layouts.admin')

@section('title', 'Crear Publicación')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 pt-3 pb-1">
        <h5 class="card-title fw-bold mb-0">
            <i class="fas fa-plus-circle text-primary me-2"></i>Nueva Publicación
        </h5>
    </div>
    <div class="card-body">
        {{-- Mostrar errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
                    <select name="categoria" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="Premios" {{ old('categoria') == 'Premios' ? 'selected' : '' }}>Premios</option>
                        <option value="Concursos" {{ old('categoria') == 'Concursos' ? 'selected' : '' }}>Concursos</option>
                        <option value="Académico" {{ old('categoria') == 'Académico' ? 'selected' : '' }}>Académico</option>
                        <option value="Eventos" {{ old('categoria') == 'Eventos' ? 'selected' : '' }}>Eventos</option>
                        <option value="Comunidad" {{ old('categoria') == 'Comunidad' ? 'selected' : '' }}>Comunidad</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha <span class="text-danger">*</span></label>
                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Autor</label>
                    <input type="text" name="autor" class="form-control" value="{{ old('autor') }}" placeholder="Dirección Académica">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Descripción Corta <span class="text-danger">*</span></label>
                    <textarea name="descripcion_corta" rows="3" class="form-control" required>{{ old('descripcion_corta') }}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Contenido (Texto Completo) <span class="text-danger">*</span></label>
                    <textarea name="contenido" rows="7" class="form-control" required>{{ old('contenido') }}</textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Imagen de Portada</label>
                    <input type="file" name="portada" class="form-control">
                    <small class="text-muted">Formatos: JPG, JPEG, PNG — Máx. 2MB</small>
                </div>
            </div>

            <hr class="my-3">

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary rounded-pill">Cancelar</a>
                <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-save me-1"></i>Guardar</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
<style>
    .gap-2 { gap: 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; padding-left: 0.9rem; padding-right: 0.9rem; }

    body[data-bs-theme="dark"] .card,
    body[data-bs-theme="dark"] .modal-content {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .card-header.bg-white {
        background-color: #171c2c !important;
        border-bottom-color: #2a3446 !important;
        color: #f8f9fa;
    }
    body[data-bs-theme="dark"] .form-control,
    body[data-bs-theme="dark"] .form-select {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
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
</style>
@stop