@extends('adminlte::page')

@section('title', 'Editar Publicación')

@section('content')

<div class="pagetitle">
    <h1>Editar Publicación</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Inicio</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.blog.index') }}">Blog</a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
</div>

<section class="section">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title m-0">Actualizar Publicación</h5>
        </div>

        <div class="card-body">

            {{-- ✅ CORRECTO: POST con @method('PUT') --}}
            <form action="{{ route('admin.blog.update', $post->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control"
                               value="{{ old('titulo', $post->titulo) }}" required>
                        @error('titulo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            <option value="Premios" {{ old('categoria', $post->categoria) == 'Premios' ? 'selected' : '' }}>Premios</option>
                            <option value="Concursos" {{ old('categoria', $post->categoria) == 'Concursos' ? 'selected' : '' }}>Concursos</option>
                            <option value="Académico" {{ old('categoria', $post->categoria) == 'Académico' ? 'selected' : '' }}>Académico</option>
                            <option value="Eventos" {{ old('categoria', $post->categoria) == 'Eventos' ? 'selected' : '' }}>Eventos</option>
                            <option value="Comunidad" {{ old('categoria', $post->categoria) == 'Comunidad' ? 'selected' : '' }}>Comunidad</option>
                        </select>
                        @error('categoria')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control"
                               value="{{ old('fecha', $post->fecha->format('Y-m-d')) }}" required>
                        @error('fecha')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Autor</label>
                        <input type="text" name="autor" class="form-control"
                               value="{{ old('autor', $post->autor) }}">
                        @error('autor')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción Corta</label>
                    <textarea name="descripcion_corta" rows="3" class="form-control" required>{{ old('descripcion_corta', $post->descripcion_corta) }}</textarea>
                    @error('descripcion_corta')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Contenido Completo</label>
                    <textarea name="contenido" rows="7" class="form-control" required>{{ old('contenido', $post->contenido) }}</textarea>
                    @error('contenido')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen Actual</label><br>
                    @if ($post->portada)
                        <img src="{{ asset('storage/' . $post->portada) }}"
                             width="120"
                             class="rounded mb-2">
                    @else
                        <span class="text-muted">Sin imagen</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Cambiar Imagen</label>
                    <input type="file" name="portada" class="form-control">
                    <small class="text-muted">Formatos: JPG, JPEG, PNG - Máx: 2MB</small>
                    @error('portada')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>

            </form>

        </div>
    </div>

</section>

@endsection