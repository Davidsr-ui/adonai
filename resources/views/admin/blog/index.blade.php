@extends('adminlte::page')

@section('title', 'Blog - Publicaciones')

@section('content')

<div class="pagetitle">
    <h1>Gestión del Blog</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Blog</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0">Lista de Publicaciones</h5>

            {{-- BOTÓN NUEVA PUBLICACIÓN --}}
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Nueva Publicación
            </a>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Portada</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Fecha</th>
                        <th>Autor</th>
                        <th style="width: 130px;">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($posts as $post)
                        <tr>

                            {{-- PORTADA --}}
                            <td>
                                @if($post->portada)
                                    <img src="{{ asset('storage/' . $post->portada) }}"
                                         width="70"
                                         class="rounded shadow-sm">
                                @else
                                    <span class="text-muted">Sin imagen</span>
                                @endif
                            </td>

                            {{-- TÍTULO --}}
                            <td>{{ $post->titulo }}</td>

                            {{-- CATEGORÍA --}}
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $post->categoria }}
                                </span>
                            </td>

                            {{-- FECHA --}}
                            <td>{{ $post->fecha->format('d/m/Y') }}</td>

                            {{-- AUTOR --}}
                            <td>{{ $post->autor ?? '—' }}</td>

                            {{-- ACCIONES --}}
                            <td class="text-center">

                                {{-- EDITAR --}}
                                <a href="{{ route('admin.blog.edit', $post->id) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>

                                {{-- ELIMINAR --}}
                                <form action="{{ route('admin.blog.destroy', $post->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar esta publicación?');">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No hay publicaciones registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</section>

@endsection
