@extends('adminlte::page')

@section('content_header')
    <h1><b>Tutores</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tutores registrados</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createTutorModal">
                            <i class="fa fa-plus"></i> Crear nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>DNI</th>
                                <th>Apellidos y Nombres</th>
                                <th>Código</th>
                                <th>Ocupación</th>
                                <th>Teléfono</th>
                                <th>Estudiantes</th>
                                <th>Estado</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = 1;
                            @endphp
                            @foreach ($tutores as $tutor)
                                @if ($tutor->persona)
                                    <tr>
                                        <td style="text-align: center">{{ $contador++ }}</td>
                                        <td>{{ $tutor->persona->dni ?? 'N/A' }}</td>
                                        <td>{{ $tutor->persona->apellidos ?? '' }} {{ $tutor->persona->nombres ?? 'N/A' }}
                                        </td>
                                        <td>{{ $tutor->codigo_tutor ?? '-' }}</td>
                                        <td>{{ $tutor->ocupacion ?? '-' }}</td>
                                        <td>{{ $tutor->persona->telefono ?? '-' }}</td>
                                        <td style="text-align: center">
                                            <span class="badge badge-info">{{ $tutor->estudiantes->count() }}</span>
                                        </td>
                                        <td>
                                            @if ($tutor->persona->estado == 'Activo')
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('admin.tutores.show', $tutor->id) }}"
                                                    class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#editTutorModal{{ $tutor->id }}"><i
                                                        class="fa fa-pencil"></i></button>
                                                <form action="{{ route('admin.tutores.destroy', $tutor->id) }}"
                                                    method="post" onclick="preguntar{{ $tutor->id }}(event)"
                                                    id="miFormulario{{ $tutor->id }}">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        style="border-radius:0px 5px 5px 0px"><i
                                                            class="fa fa-trash"></i></button>
                                                </form>
                                                <script>
                                                    function preguntar{{ $tutor->id }}(event) {
                                                        event.preventDefault();
                                                        Swal.fire({
                                                            title: "¿Seguro que quiere eliminar este registro?",
                                                            icon: "warning",
                                                            showCancelButton: true,
                                                            confirmButtonText: "Si, Eliminar!",
                                                            cancelButtonText: "Cancelar",
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                Swal.fire({
                                                                    title: "Eliminado!",
                                                                    text: "El registro se elimino.",
                                                                    icon: "success"
                                                                });
                                                                var form = document.getElementById('miFormulario{{ $tutor->id }}');
                                                                form.submit();
                                                            }
                                                        });
                                                        return false;
                                                    }
                                                </script>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal CREATE-->
    <div class="modal fade" id="createTutorModal" tabindex="-1" aria-labelledby="createTutorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.tutores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createTutorModalLabel">Crear Nuevo Tutor</h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">
                                    <li>{{ $error }}</li>
                                </div>
                            @endforeach
                        @endif

                        <h5>Datos Personales</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">DNI <b>*</b></label>
                                    <input type="text" name="dni_create" value="{{ old('dni_create') }}"
                                        class="form-control" required maxlength="20">
                                    <small style="color:red">
                                        @error('dni_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Nombres <b>*</b></label>
                                    <input type="text" name="nombres_create" value="{{ old('nombres_create') }}"
                                        class="form-control" required maxlength="100">
                                    <small style="color:red">
                                        @error('nombres_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Apellidos <b>*</b></label>
                                    <input type="text" name="apellidos_create" value="{{ old('apellidos_create') }}"
                                        class="form-control" required maxlength="100">
                                    <small style="color:red">
                                        @error('apellidos_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Fecha de Nacimiento <b>*</b></label>
                                    <input type="date" name="fecha_nacimiento_create"
                                        value="{{ old('fecha_nacimiento_create') }}" class="form-control" required>
                                    <small style="color:red">
                                        @error('fecha_nacimiento_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Género <b>*</b></label>
                                    <select name="genero_create" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="M" {{ old('genero_create') == 'M' ? 'selected' : '' }}>
                                            Masculino</option>
                                        <option value="F" {{ old('genero_create') == 'F' ? 'selected' : '' }}>
                                            Femenino</option>
                                        <option value="Otro" {{ old('genero_create') == 'Otro' ? 'selected' : '' }}>Otro
                                        </option>
                                    </select>
                                    <small style="color:red">
                                        @error('genero_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Estado <b>*</b></label>
                                    <select name="estado_create" class="form-select" required>
                                        <option value="Activo" {{ old('estado_create') == 'Activo' ? 'selected' : '' }}>
                                            Activo</option>
                                        <option value="Inactivo"
                                            {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    <small style="color:red">
                                        @error('estado_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Dirección</label>
                                    <input type="text" name="direccion_create" value="{{ old('direccion_create') }}"
                                        class="form-control" maxlength="255">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Teléfono</label>
                                    <input type="text" name="telefono_create" value="{{ old('telefono_create') }}"
                                        class="form-control" maxlength="20">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Teléfono Emergencia</label>
                                    <input type="text" name="telefono_emergencia_create"
                                        value="{{ old('telefono_emergencia_create') }}" class="form-control"
                                        maxlength="20">
                                </div>
                            </div>
                        </div>

                        {{-- FOTO DE PERFIL — FILA NUEVA INDEPENDIENTE --}}
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Foto de Perfil</label>
                                    <input type="file" name="foto_perfil" class="form-control">
                                    <small class="text-muted">Formatos permitidos: JPG, JPEG, PNG — Máx: 2MB</small>
                                </div>
                            </div>
                        </div>


                        <h5 class="mt-3">Datos del Tutor</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Código Tutor</label>
                                    <input type="text" name="codigo_tutor_create"
                                        value="{{ old('codigo_tutor_create') }}" class="form-control" maxlength="50">
                                    <small style="color:red">
                                        @error('codigo_tutor_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ocupación</label>
                                    <input type="text" name="ocupacion_create" value="{{ old('ocupacion_create') }}"
                                        class="form-control" maxlength="100">
                                    <small style="color:red">
                                        @error('ocupacion_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal EDIT-->
    @foreach ($tutores as $tutor)
        @if ($tutor->persona)
            <div class="modal fade" id="editTutorModal{{ $tutor->id }}" tabindex="-1"
                aria-labelledby="editTutorModalLabel{{ $tutor->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('admin.tutores.update', $tutor->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editTutorModalLabel{{ $tutor->id }}">Editar Tutor
                                </h1>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if (session('modal_id') == $tutor->id && $errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger">
                                            <li>{{ $error }}</li>
                                        </div>
                                    @endforeach
                                @endif

                                <h5>Datos Personales</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">DNI <b>*</b></label>
                                            <input type="text" name="dni"
                                                value="{{ $tutor->persona->dni ?? '' }}" class="form-control" required
                                                maxlength="20">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Nombres <b>*</b></label>
                                            <input type="text" name="nombres"
                                                value="{{ $tutor->persona->nombres ?? '' }}" class="form-control"
                                                required maxlength="100">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Apellidos <b>*</b></label>
                                            <input type="text" name="apellidos"
                                                value="{{ $tutor->persona->apellidos ?? '' }}" class="form-control"
                                                required maxlength="100">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Fecha de Nacimiento <b>*</b></label>
                                            <input type="date" name="fecha_nacimiento"
                                                value="{{ $tutor->persona->fecha_nacimiento ?? '' }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Género <b>*</b></label>
                                            <select name="genero" class="form-select" required>
                                                <option value="">Seleccione...</option>
                                                <option value="M"
                                                    {{ ($tutor->persona->genero ?? '') == 'M' ? 'selected' : '' }}>
                                                    Masculino</option>
                                                <option value="F"
                                                    {{ ($tutor->persona->genero ?? '') == 'F' ? 'selected' : '' }}>Femenino
                                                </option>
                                                <option value="Otro"
                                                    {{ ($tutor->persona->genero ?? '') == 'Otro' ? 'selected' : '' }}>Otro
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Estado <b>*</b></label>
                                            <select name="estado" class="form-select" required>
                                                <option value="Activo"
                                                    {{ ($tutor->persona->estado ?? 'Activo') == 'Activo' ? 'selected' : '' }}>
                                                    Activo</option>
                                                <option value="Inactivo"
                                                    {{ ($tutor->persona->estado ?? '') == 'Inactivo' ? 'selected' : '' }}>
                                                    Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Dirección</label>
                                            <input type="text" name="direccion"
                                                value="{{ $tutor->persona->direccion ?? '' }}" class="form-control"
                                                maxlength="255">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Teléfono</label>
                                            <input type="text" name="telefono"
                                                value="{{ $tutor->persona->telefono ?? '' }}" class="form-control"
                                                maxlength="20">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Teléfono Emergencia</label>
                                            <input type="text" name="telefono_emergencia"
                                                value="{{ $tutor->persona->telefono_emergencia ?? '' }}"
                                                class="form-control" maxlength="20">
                                        </div>
                                    </div>
                                </div>

                                {{-- FOTO PERFIL --}}
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <label>Foto de Perfil</label>
                                        <input type="file" name="foto_perfil" class="form-control">

                                        @if ($tutor->persona && $tutor->persona->foto_perfil)
                                            <p class="mt-2">Foto actual:</p>
                                            <img src="{{ asset('storage/' . $tutor->persona->foto_perfil) }}"
                                                class="img-thumbnail"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                            <p class="text-muted mt-2">Sin foto actual</p>
                                        @endif
                                    </div>
                                </div>

                                <h5 class="mt-3">Datos del Tutor</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Código Tutor</label>
                                            <input type="text" name="codigo_tutor"
                                                value="{{ $tutor->codigo_tutor ?? '' }}" class="form-control"
                                                maxlength="50">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Ocupación</label>
                                            <input type="text" name="ocupacion" value="{{ $tutor->ocupacion ?? '' }}"
                                                class="form-control" maxlength="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if (session('modal_id') == $tutor->id && $errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var modal = new bootstrap.Modal(document.getElementById('editTutorModal{{ $tutor->id }}'));
                        modal.show();
                    });
                </script>
            @endif
        @endif
    @endforeach

    <script>
        $(function() {
            $("#example1").DataTable({
                "pageLength": 10,
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "language": {
                    "emptyTable": "No hay información",
                    "decimal": "",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Tutores",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Tutores",
                    "infoFiltered": "(Filtrado de _MAX_ total Tutores)",
                    "thousands": ".",
                    "lengthMenu": "Mostrar _MENU_ Tutores",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Ultimo",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@stop

@section('css')
    <style>
        .modal-lg {
            max-width: 900px;
        }
    </style>
@stop

@section('js')
    @if (session('mensaje'))
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
