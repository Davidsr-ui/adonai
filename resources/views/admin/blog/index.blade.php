@extends('layouts.admin')

@section('title', 'Blog - Publicaciones')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-newspaper text-primary"></i>
        <span class="fw-bold fs-4">Gestión del Blog</span>
    </div>
@stop

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Lista de Publicaciones</h3>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nueva Publicación
            </a>
        </div>

        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="blogTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Portada</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th>Autor</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td>
                                @if($post->portada)
                                    <img src="{{ asset('storage/' . $post->portada) }}"
                                         class="rounded"
                                         style="width:50px; height:50px; object-fit:cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                         style="width:50px; height:50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $post->titulo }}</div>
                            </td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $post->categoria }}
                                </span>
                            </td>

                            <td class="text-muted small">
                                {{ $post->fecha->format('d/m/Y') }}
                            </td>

                            <td class="text-muted">
                                {{ $post->autor ?? '—' }}
                            </td>

                            <td class="text-end">
                                <a href="{{ route('admin.blog.edit', $post->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.blog.destroy', $post->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar publicación?');">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<style>
    #blogTable {
        border-radius: 10px;
        overflow: hidden;
    }

    #blogTable thead {
        background-color: #f8f9fa;
    }

    #blogTable tbody tr:hover {
        background-color: #f1f3f5;
        transition: 0.2s;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px;
        padding: 5px 10px;
        border: 1px solid #ddd;
    }
</style>
@stop

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#blogTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
            emptyTable: `
                <div style="padding:40px; text-align:center;">
                    <i class="fas fa-newspaper" style="font-size:40px; opacity:0.3;"></i>
                    <div style="margin-top:10px; font-weight:600;">
                        No hay publicaciones
                    </div>
                    <div style="font-size:13px; color:gray;">
                        Empieza creando una 
                    </div>
                </div>
            `
        },
        responsive: true,
        autoWidth: false,
        order: [[3, 'desc']]
    });
});
</script>
@stop