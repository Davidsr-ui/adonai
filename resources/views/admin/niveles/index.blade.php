@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de niveles</b></h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-10">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Niveles registrados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                        <i class="fas fa-plus"></i> Crear nuevo nivel
                    </button>

                    <!-- Modal Create -->
                    <div class="modal fade" id="ModalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #007bff; color: white;">
                                    <h5 class="modal-title" id="exampleModalLabel">Registro de un nuevo nivel</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('/admin/niveles/create') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Nombre del nivel</label> <span class="text-danger">*</span>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" name="nombre_create" value="{{ old('nombre_create') }}" placeholder="Ej: Nivel 1" required>
                                                    </div>
                                                    @error('nombre_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Orden</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                                        </div>
                                                        <input type="number" class="form-control" name="orden_create" value="{{ old('orden_create', 0) }}" min="0">
                                                    </div>
                                                    @error('orden_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Estado</label> <span class="text-danger">*</span>
                                                    <select name="estado_create" class="form-control" required>
                                                        <option value="Activo" {{ old('estado_create') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                        <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                    </select>
                                                    @error('estado_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Descripción</label>
                                                    <textarea class="form-control" name="descripcion_create" rows="3" placeholder="Descripción opcional del nivel">{{ old('descripcion_create') }}</textarea>
                                                    @error('descripcion_create')
                                                        <small style="color: red;">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-md-12 d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Guardar
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
                <table id="example" class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Nro</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th style="width: 80px;">Orden</th>
                            <th style="width: 100px;">Estado</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($niveles as $nivel)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td>{{ $nivel->nombre }}</td>
                                <td>{{ Str::limit($nivel->descripcion, 50) ?? 'Sin descripción' }}</td>
                                <td style="text-align: center">{{ $nivel->orden }}</td>
                                <td style="text-align: center">
                                    @if($nivel->estado == 'Activo')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#ModalUpdate{{ $nivel->id }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        
                                        {{-- ✅ FORMULARIO DE ELIMINACIÓN CORREGIDO --}}
                                        <form action="{{ url('/admin/niveles/'.$nivel->id) }}" 
                                              method="POST" 
                                              id="deleteFormNivel{{ $nivel->id }}" 
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="confirmarEliminacionNivel({{ $nivel->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal Update -->
                                    <div class="modal fade" id="ModalUpdate{{ $nivel->id }}" tabindex="-1" aria-labelledby="ModalUpdateLabel{{ $nivel->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #28a745; color: white;">
                                                    <h5 class="modal-title" id="ModalUpdateLabel{{ $nivel->id }}">Actualizar nivel</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ url('/admin/niveles/'.$nivel->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        
                                                        {{-- ✅ CAMPO OCULTO PARA IDENTIFICAR EL MODAL --}}
                                                        <input type="hidden" name="modal_id" value="{{ $nivel->id }}">
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Nombre del nivel</label> <span class="text-danger">*</span>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                                                        </div>
                                                                        <input type="text" class="form-control" name="nombre" value="{{ old('nombre', $nivel->nombre) }}" placeholder="Ej: Nivel 1" required>
                                                                    </div>
                                                                    @error('nombre')
                                                                        <small style="color: red;">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Orden</label>
                                                                    <div class="input-group mb-3">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                                                        </div>
                                                                        <input type="number" class="form-control" name="orden" value="{{ old('orden', $nivel->orden) }}" min="0">
                                                                    </div>
                                                                    @error('orden')
                                                                        <small style="color: red;">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label>Estado</label> <span class="text-danger">*</span>
                                                                    <select name="estado" class="form-control" required>
                                                                        <option value="Activo" {{ old('estado', $nivel->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                                        <option value="Inactivo" {{ old('estado', $nivel->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                                    </select>
                                                                    @error('estado')
                                                                        <small style="color: red;">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Descripción</label>
                                                                    <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción opcional del nivel">{{ old('descripcion', $nivel->descripcion) }}</textarea>
                                                                    @error('descripcion')
                                                                        <small style="color: red;">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr>

                                                        <div class="d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fas fa-sync-alt"></i> Actualizar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    <style>
        .btn-group .btn {
            margin: 0;
        }
    </style>
@stop

@section('js')
{{-- ✅ SCRIPT DE ELIMINACIÓN CORREGIDO - UNA SOLA FUNCIÓN --}}
<script>
    function confirmarEliminacionNivel(id) {
        Swal.fire({
            title: '¿Deseas eliminar este nivel?',
            text: "Esta acción no se puede deshacer",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            confirmButtonColor: '#a5161d',
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteFormNivel' + id).submit();
            }
        });
    }
</script>

{{-- ✅ SCRIPT DE MODALES CORREGIDO --}}
@if($errors->any())
<script>
    $(document).ready(function(){
        @if (session('modal_id'))
            // Si hay modal_id, abrir el modal de actualización específico
            $("#ModalUpdate{{ session('modal_id') }}").modal("show");
        @elseif(old('modal_id'))
            // Si hay old('modal_id'), abrir ese modal de actualización
            $("#ModalUpdate{{ old('modal_id') }}").modal("show");
        @else
            // Si no hay ninguno, abrir el modal de crear
            $("#ModalCreate").modal("show");
        @endif
    });
</script>
@endif

{{-- Mensajes de éxito/error --}}
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