@extends('adminlte::page')


@section('title', 'Crear Publicación')

@section('content')

<div class="pagetitle">
    <h1>Nueva Publicación</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blog</a></li>
            <li class="breadcrumb-item active">Crear</li>
        </ol>
    </nav>
</div>

<section class="section">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title m-0">Registrar Publicación</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="Premios">Premios</option>
                            <option value="Concursos">Concursos</option>
                            <option value="Académico">Académico</option>
                            <option value="Eventos">Eventos</option>
                            <option value="Comunidad">Comunidad</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Autor</label>
                        <input type="text" name="autor" class="form-control" placeholder="Dirección Académica">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción Corta</label>
                    <textarea name="descripcion_corta" rows="3" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contenido (Texto Completo)</label>
                    <textarea name="contenido" rows="7" class="form-control" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen de Portada</label>
                    <input type="file" name="portada" class="form-control">
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </form>

        </div>
    </div>

</section>

@endsection
