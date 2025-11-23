@extends('adminlte::page')

@section('content_header')
    <h1><b>Estudiantes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Estudiantes registrados</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createEstudianteModal">
                            <i class="fa fa-plus"></i> Crear nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Código</th>
                                <th>DNI</th>
                                <th>Apellidos y Nombres</th>
                                <th>Grado</th>
                                <th>Tutor Principal</th>
                                <th>Año Ingreso</th>
                                <th>Condición</th>
                                <th>Estado</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = 1;
                            @endphp
                            @foreach ($estudiantes as $estudiante)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td>{{ $estudiante->codigo_estudiante }}</td>
                                    <td>{{ $estudiante->persona->dni }}</td>
                                    <td>{{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}</td>
                                    <td>{{ $estudiante->grado ? $estudiante->grado->nombre_completo : 'Sin asignar' }}</td>
                                    <td>{{ $estudiante->tutor_principal ? $estudiante->tutor_principal->nombre_completo : 'Sin tutor' }}
                                    </td>
                                    <td style="text-align: center">{{ $estudiante->año_ingreso }}</td>
                                    <td>
                                        @if ($estudiante->condicion == 'Regular')
                                            <span class="badge badge-success">{{ $estudiante->condicion }}</span>
                                        @elseif($estudiante->condicion == 'Irregular')
                                            <span class="badge badge-warning">{{ $estudiante->condicion }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $estudiante->condicion }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($estudiante->persona->estado == 'Activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('admin.estudiantes.show', $estudiante->id) }}"
                                                class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#editEstudianteModal{{ $estudiante->id }}"><i
                                                    class="fa fa-pencil"></i></button>
                                            <form action="{{ route('admin.estudiantes.destroy', $estudiante->id) }}"
                                                method="post" onclick="preguntar{{ $estudiante->id }}(event)"
                                                id="miFormulario{{ $estudiante->id }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    style="border-radius:0px 5px 5px 0px"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                            <script>
                                                function preguntar{{ $estudiante->id }}(event) {
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
                                                            var form = document.getElementById('miFormulario{{ $estudiante->id }}');
                                                            form.submit();
                                                        }
                                                    });
                                                    return false;
                                                }
                                            </script>
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

    <!-- Modal CREATE-->
    <div class="modal fade" id="createEstudianteModal" tabindex="-1" aria-labelledby="createEstudianteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.estudiantes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createEstudianteModalLabel">Crear Nuevo Estudiante</h1>
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
                                    <small style="color:red">
                                        @error('direccion_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Teléfono</label>
                                    <input type="text" name="telefono_create" value="{{ old('telefono_create') }}"
                                        class="form-control" maxlength="20">
                                    <small style="color:red">
                                        @error('telefono_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Teléfono Emergencia</label>
                                    <input type="text" name="telefono_emergencia_create"
                                        value="{{ old('telefono_emergencia_create') }}" class="form-control"
                                        maxlength="20">
                                    <small style="color:red">
                                        @error('telefono_emergencia_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                        </div>

                        {{-- FOTO DE PERFIL --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Foto de Perfil</label>
                                    <input type="file" name="foto_perfil" class="form-control">
                                    <small class="text-muted">Formatos permitidos: JPG, JPEG, PNG — Máx: 2MB</small>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-3">Datos Académicos</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Código Estudiante <b>*</b></label>
                                    <input type="text" name="codigo_estudiante_create"
                                        value="{{ old('codigo_estudiante_create') }}" class="form-control" required
                                        maxlength="50">
                                    <small style="color:red">
                                        @error('codigo_estudiante_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Grado</label>
                                    <select name="grado_id_create" class="form-select">
                                        <option value="">Sin asignar...</option>
                                        @foreach ($grados as $grado)
                                            <option value="{{ $grado->id }}"
                                                {{ old('grado_id_create') == $grado->id ? 'selected' : '' }}>
                                                {{ $grado->nombre_completo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small style="color:red">
                                        @error('grado_id_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Año de Ingreso <b>*</b></label>
                                    <input type="number" name="año_ingreso_create"
                                        value="{{ old('año_ingreso_create', date('Y')) }}" class="form-control" required
                                        min="1900" max="{{ date('Y') }}">
                                    <small style="color:red">
                                        @error('año_ingreso_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Condición <b>*</b></label>
                                    <select name="condicion_create" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Regular"
                                            {{ old('condicion_create') == 'Regular' ? 'selected' : '' }}>Regular</option>
                                        <option value="Irregular"
                                            {{ old('condicion_create') == 'Irregular' ? 'selected' : '' }}>Irregular
                                        </option>
                                        <option value="Retirado"
                                            {{ old('condicion_create') == 'Retirado' ? 'selected' : '' }}>Retirado</option>
                                    </select>
                                    <small style="color:red">
                                        @error('condicion_create')
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
    @foreach ($estudiantes as $estudiante)
        <div class="modal fade" id="editEstudianteModal{{ $estudiante->id }}" tabindex="-1"
            aria-labelledby="editEstudianteModalLabel{{ $estudiante->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('admin.estudiantes.update', $estudiante->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editEstudianteModalLabel{{ $estudiante->id }}">Editar
                                Estudiante</h1>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if (session('modal_id') == $estudiante->id && $errors->any())
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
                                        <input type="text" name="dni" value="{{ $estudiante->persona->dni }}"
                                            class="form-control" required maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Nombres <b>*</b></label>
                                        <input type="text" name="nombres" value="{{ $estudiante->persona->nombres }}"
                                            class="form-control" required maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Apellidos <b>*</b></label>
                                        <input type="text" name="apellidos"
                                            value="{{ $estudiante->persona->apellidos }}" class="form-control" required
                                            maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fecha de Nacimiento <b>*</b></label>
                                        <input type="date" name="fecha_nacimiento"
                                            value="{{ $estudiante->persona->fecha_nacimiento }}" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Género <b>*</b></label>
                                        <select name="genero" class="form-select" required>
                                            <option value="">Seleccione...</option>
                                            <option value="M"
                                                {{ $estudiante->persona->genero == 'M' ? 'selected' : '' }}>Masculino
                                            </option>
                                            <option value="F"
                                                {{ $estudiante->persona->genero == 'F' ? 'selected' : '' }}>Femenino
                                            </option>
                                            <option value="Otro"
                                                {{ $estudiante->persona->genero == 'Otro' ? 'selected' : '' }}>Otro
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Estado <b>*</b></label>
                                        <select name="estado" class="form-select" required>
                                            <option value="Activo"
                                                {{ $estudiante->persona->estado == 'Activo' ? 'selected' : '' }}>Activo
                                            </option>
                                            <option value="Inactivo"
                                                {{ $estudiante->persona->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Dirección</label>
                                        <input type="text" name="direccion"
                                            value="{{ $estudiante->persona->direccion }}" class="form-control"
                                            maxlength="255">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Teléfono</label>
                                        <input type="text" name="telefono"
                                            value="{{ $estudiante->persona->telefono }}" class="form-control"
                                            maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Teléfono Emergencia</label>
                                        <input type="text" name="telefono_emergencia"
                                            value="{{ $estudiante->persona->telefono_emergencia }}" class="form-control"
                                            maxlength="20">
                                    </div>
                                </div>
                            </div>

                            <!-- FOTO DE PERFIL -->
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Foto de Perfil</label>
                                        <input type="file" name="foto_perfil" class="form-control">

                                        @if ($estudiante->persona->foto_perfil)
                                            <img src="{{ asset('storage/' . $estudiante->persona->foto_perfil) }}"
                                                class="img-thumbnail mt-2" width="120">
                                        @else
                                            <p class="text-muted mt-2">Sin foto actual</p>
                                        @endif

                                        <small class="text-muted">Formatos permitidos: JPG, JPEG, PNG — Máx. 2MB</small>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-3">Datos Académicos</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Código Estudiante <b>*</b></label>
                                        <input type="text" name="codigo_estudiante"
                                            value="{{ $estudiante->codigo_estudiante }}" class="form-control" required
                                            maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Grado</label>
                                        <select name="grado_id" class="form-select">
                                            <option value="">Sin asignar...</option>
                                            @foreach ($grados as $grado)
                                                <option value="{{ $grado->id }}"
                                                    {{ $estudiante->grado_id == $grado->id ? 'selected' : '' }}>
                                                    {{ $grado->nombre_completo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Año de Ingreso <b>*</b></label>
                                        <input type="number" name="año_ingreso" value="{{ $estudiante->año_ingreso }}"
                                            class="form-control" required min="1900" max="{{ date('Y') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Condición <b>*</b></label>
                                        <select name="condicion" class="form-select" required>
                                            <option value="">Seleccione...</option>
                                            <option value="Regular"
                                                {{ $estudiante->condicion == 'Regular' ? 'selected' : '' }}>Regular
                                            </option>
                                            <option value="Irregular"
                                                {{ $estudiante->condicion == 'Irregular' ? 'selected' : '' }}>Irregular
                                            </option>
                                            <option value="Retirado"
                                                {{ $estudiante->condicion == 'Retirado' ? 'selected' : '' }}>Retirado
                                            </option>
                                        </select>
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
        @if (session('modal_id') == $estudiante->id && $errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('editEstudianteModal{{ $estudiante->id }}'));
                    modal.show();
                });
            </script>
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
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Estudiantes",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Estudiantes",
                    "infoFiltered": "(Filtrado de _MAX_ total Estudiantes)",
                    "thousands": ".",
                    "lengthMenu": "Mostrar _MENU_ Estudiantes",
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
