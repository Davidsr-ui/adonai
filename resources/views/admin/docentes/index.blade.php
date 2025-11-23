@extends('adminlte::page')

@section('content_header')
    <h1><b>Docentes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Docentes registrados</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createDocenteModal">
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
                                <th>Especialidad</th>
                                <th>Tipo Contrato</th>
                                <th>Teléfono</th>
                                <th>Estado</th>
                                <th style="text-align: center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contador = 1;
                            @endphp
                            @foreach ($docentes as $docente)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td>{{ $docente->persona->dni ?? '—' }}</td>
                                    <td>{{ $docente->persona->apellidos }} {{ $docente->persona->nombres }}</td>
                                    <td>{{ $docente->codigo_docente }}</td>
                                    <td>{{ $docente->especialidad ?? '-' }}</td>
                                    <td>
                                        @if ($docente->tipo_contrato == 'Nombrado')
                                            <span class="badge badge-success">{{ $docente->tipo_contrato }}</span>
                                        @elseif($docente->tipo_contrato == 'Contratado')
                                            <span class="badge badge-info">{{ $docente->tipo_contrato }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $docente->tipo_contrato }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $docente->persona->telefono ?? '-' }}</td>
                                    <td>
                                        @if ($docente->persona->estado == 'Activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('admin.docentes.show', $docente->id) }}"
                                                class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#editDocenteModal{{ $docente->id }}"><i
                                                    class="fa fa-pencil"></i></button>
                                            <form action="{{ route('admin.docentes.destroy', $docente->id) }}"
                                                method="post" onclick="preguntar{{ $docente->id }}(event)"
                                                id="miFormulario{{ $docente->id }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    style="border-radius:0px 5px 5px 0px"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                            <script>
                                                function preguntar{{ $docente->id }}(event) {
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
                                                            var form = document.getElementById('miFormulario{{ $docente->id }}');
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
    <div class="modal fade" id="createDocenteModal" tabindex="-1" aria-labelledby="createDocenteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- FORMULARIO CON enctype PARA SUBIR IMÁGENES -->
                <form action="{{ route('admin.docentes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createDocenteModalLabel">Crear Nuevo Docente</h1>
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

                        <h5 class="mt-3">Datos Laborales</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Código Docente <b>*</b></label>
                                    <input type="text" name="codigo_docente_create"
                                        value="{{ old('codigo_docente_create') }}" class="form-control" required
                                        maxlength="50">
                                    <small style="color:red">
                                        @error('codigo_docente_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Especialidad</label>
                                    <input type="text" name="especialidad_create"
                                        value="{{ old('especialidad_create') }}" class="form-control" maxlength="100">
                                    <small style="color:red">
                                        @error('especialidad_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Fecha Contratación <b>*</b></label>
                                    <input type="date" name="fecha_contratacion_create"
                                        value="{{ old('fecha_contratacion_create') }}" class="form-control" required>
                                    <small style="color:red">
                                        @error('fecha_contratacion_create')
                                            {{ $message }}
                                        @enderror
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Tipo de Contrato <b>*</b></label>
                                    <select name="tipo_contrato_create" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Nombrado"
                                            {{ old('tipo_contrato_create') == 'Nombrado' ? 'selected' : '' }}>Nombrado
                                        </option>
                                        <option value="Contratado"
                                            {{ old('tipo_contrato_create') == 'Contratado' ? 'selected' : '' }}>Contratado
                                        </option>
                                        <option value="Temporal"
                                            {{ old('tipo_contrato_create') == 'Temporal' ? 'selected' : '' }}>Temporal
                                        </option>
                                    </select>
                                    <small style="color:red">
                                        @error('tipo_contrato_create')
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
    @foreach ($docentes as $docente)
        <div class="modal fade" id="editDocenteModal{{ $docente->id }}" tabindex="-1"
            aria-labelledby="editDocenteModalLabel{{ $docente->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- AGREGADO: enctype PARA SUBIR IMÁGENES -->
                    <form action="{{ route('admin.docentes.update', $docente->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editDocenteModalLabel{{ $docente->id }}">
                                Editar Docente
                            </h1>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            @if (session('modal_id') == $docente->id && $errors->any())
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
                                        <input type="text" name="dni" value="{{ $docente->persona->dni ?? '—' }}"
                                            class="form-control" required maxlength="20">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Nombres <b>*</b></label>
                                        <input type="text" name="nombres" value="{{ $docente->persona->nombres }}"
                                            class="form-control" required maxlength="100">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Apellidos <b>*</b></label>
                                        <input type="text" name="apellidos"
                                            value="{{ $docente->persona->apellidos }}" class="form-control" required
                                            maxlength="100">
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Fecha de Nacimiento <b>*</b></label>
                                        <input type="date" name="fecha_nacimiento"
                                            value="{{ $docente->persona->fecha_nacimiento }}" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Género <b>*</b></label>
                                        <select name="genero" class="form-select" required>
                                            <option value="M"
                                                {{ $docente->persona->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                                            <option value="F"
                                                {{ $docente->persona->genero == 'F' ? 'selected' : '' }}>Femenino</option>
                                            <option value="Otro"
                                                {{ $docente->persona->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Estado <b>*</b></label>
                                        <select name="estado" class="form-select" required>
                                            <option value="Activo"
                                                {{ $docente->persona->estado == 'Activo' ? 'selected' : '' }}>Activo
                                            </option>
                                            <option value="Inactivo"
                                                {{ $docente->persona->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo
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
                                            value="{{ $docente->persona->direccion }}" class="form-control"
                                            maxlength="255">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Teléfono</label>
                                        <input type="text" name="telefono" value="{{ $docente->persona->telefono }}"
                                            class="form-control" maxlength="20">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Teléfono Emergencia</label>
                                        <input type="text" name="telefono_emergencia"
                                            value="{{ $docente->persona->telefono_emergencia }}" class="form-control"
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

                                        @if ($docente->persona->foto_perfil)
                                            <img src="{{ asset('storage/' . $docente->persona->foto_perfil) }}"
                                                class="img-thumbnail mt-2" width="120">
                                        @else
                                            <p class="text-muted mt-2">Sin foto actual</p>
                                        @endif

                                        <small class="text-muted">Formatos permitidos: JPG, JPEG, PNG — Máx. 2MB</small>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-3">Datos Laborales</h5>
                            <hr>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Código Docente <b>*</b></label>
                                        <input type="text" name="codigo_docente"
                                            value="{{ $docente->codigo_docente }}" class="form-control" required
                                            maxlength="50">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Especialidad</label>
                                        <input type="text" name="especialidad" value="{{ $docente->especialidad }}"
                                            class="form-control" maxlength="100">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Fecha Contratación <b>*</b></label>
                                        <input type="date" name="fecha_contratacion"
                                            value="{{ $docente->fecha_contratacion }}" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Tipo de Contrato <b>*</b></label>
                                        <select name="tipo_contrato" class="form-select" required>
                                            <option value="Nombrado"
                                                {{ $docente->tipo_contrato == 'Nombrado' ? 'selected' : '' }}>Nombrado
                                            </option>
                                            <option value="Contratado"
                                                {{ $docente->tipo_contrato == 'Contratado' ? 'selected' : '' }}>Contratado
                                            </option>
                                            <option value="Temporal"
                                                {{ $docente->tipo_contrato == 'Temporal' ? 'selected' : '' }}>Temporal
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

        @if (session('modal_id') == $docente->id && $errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('editDocenteModal{{ $docente->id }}'));
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
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Docentes",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Docentes",
                    "infoFiltered": "(Filtrado de _MAX_ total Docentes)",
                    "thousands": ".",
                    "lengthMenu": "Mostrar _MENU_ Docentes",
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
