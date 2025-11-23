@extends('adminlte::page')

@section('content_header')
    <h1><b>Relación Tutor-Estudiante</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Relaciones Registradas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createRelacionModal">
                            <i class="fas fa-plus"></i> Nueva Relación
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="relacionesTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 22%">Tutor</th>
                                <th style="width: 22%">Estudiante</th>
                                <th style="width: 13%">Relación</th>
                                <th style="width: 10%">Tipo</th>
                                <th style="width: 10%">Autoriza Recojo</th>
                                <th style="width: 8%">Estado</th>
                                <th style="width: 10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($relaciones as $relacion)
                            <tr>
                                <td class="text-center">{{ $relacion->id }}</td>
                                <td>
                                    <i class="fas fa-user"></i> 
                                    <strong>{{ $relacion->tutor->persona->apellidos }}, {{ $relacion->tutor->persona->nombres }}</strong>
                                    <br>
                                    <small class="text-muted">DNI: {{ $relacion->tutor->persona->dni }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-user-graduate"></i> 
                                    <strong>{{ $relacion->estudiante->persona->apellidos }}, {{ $relacion->estudiante->persona->nombres }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $relacion->estudiante->codigo_estudiante }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $relacion->relacion_familiar }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $relacion->tipo_badge }}">
                                        {{ $relacion->tipo }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($relacion->autorizacion_recojo)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Sí
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> No
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $relacion->estado_badge }}">
                                        {{ $relacion->estado }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.tutor-estudiante.show', $relacion->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editRelacionModal{{ $relacion->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteRelacionModal{{ $relacion->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editRelacionModal{{ $relacion->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Relación Tutor-Estudiante
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.tutor-estudiante.update', $relacion->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tutor_id">Tutor <span class="text-danger">*</span></label>
                                                            <select name="tutor_id" 
                                                                    class="form-control select2 @error('tutor_id') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione un tutor --</option>
                                                                @foreach($tutores as $tutor)
                                                                    <option value="{{ $tutor->id }}" 
                                                                        {{ old('tutor_id', $relacion->tutor_id) == $tutor->id ? 'selected' : '' }}>
                                                                        {{ $tutor->persona->apellidos }}, {{ $tutor->persona->nombres }} - {{ $tutor->persona->dni }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('tutor_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="estudiante_id">Estudiante <span class="text-danger">*</span></label>
                                                            <select name="estudiante_id" 
                                                                    class="form-control select2 @error('estudiante_id') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione un estudiante --</option>
                                                                @foreach($estudiantes as $estudiante)
                                                                    <option value="{{ $estudiante->id }}" 
                                                                        {{ old('estudiante_id', $relacion->estudiante_id) == $estudiante->id ? 'selected' : '' }}>
                                                                        {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }} - {{ $estudiante->codigo_estudiante }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('estudiante_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="relacion_familiar">Relación Familiar <span class="text-danger">*</span></label>
                                                            <select name="relacion_familiar" 
                                                                    class="form-control @error('relacion_familiar') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione --</option>
                                                                <option value="Padre" {{ old('relacion_familiar', $relacion->relacion_familiar) == 'Padre' ? 'selected' : '' }}>Padre</option>
                                                                <option value="Madre" {{ old('relacion_familiar', $relacion->relacion_familiar) == 'Madre' ? 'selected' : '' }}>Madre</option>
                                                                <option value="Tutor Legal" {{ old('relacion_familiar', $relacion->relacion_familiar) == 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal</option>
                                                                <option value="Abuelo/a" {{ old('relacion_familiar', $relacion->relacion_familiar) == 'Abuelo/a' ? 'selected' : '' }}>Abuelo/a</option>
                                                                <option value="Tío/a" {{ old('relacion_familiar', $relacion->relacion_familiar) == 'Tío/a' ? 'selected' : '' }}>Tío/a</option>
                                                                <option value="Hermano/a" {{ old('relacion_familiar', $relacion->relacion_familiar) == 'Hermano/a' ? 'selected' : '' }}>Hermano/a</option>
                                                                <option value="Otro" {{ old('relacion_familiar', $relacion->relacion_familiar) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                                            </select>
                                                            @error('relacion_familiar')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="tipo">Tipo <span class="text-danger">*</span></label>
                                                            <select name="tipo" 
                                                                    class="form-control @error('tipo') is-invalid @enderror" 
                                                                    required>
                                                                <option value="Principal" {{ old('tipo', $relacion->tipo) == 'Principal' ? 'selected' : '' }}>Principal</option>
                                                                <option value="Secundario" {{ old('tipo', $relacion->tipo) == 'Secundario' ? 'selected' : '' }}>Secundario</option>
                                                            </select>
                                                            @error('tipo')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                            <small class="form-text text-muted">Solo puede haber un tutor principal activo por estudiante</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="estado">Estado <span class="text-danger">*</span></label>
                                                            <select name="estado" 
                                                                    class="form-control @error('estado') is-invalid @enderror" 
                                                                    required>
                                                                <option value="Activo" {{ old('estado', $relacion->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                                <option value="Inactivo" {{ old('estado', $relacion->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                            </select>
                                                            @error('estado')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" 
                                                                       class="custom-control-input" 
                                                                       id="autorizacion_recojo{{ $relacion->id }}" 
                                                                       name="autorizacion_recojo" 
                                                                       value="1"
                                                                       {{ old('autorizacion_recojo', $relacion->autorizacion_recojo) ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="autorizacion_recojo{{ $relacion->id }}">
                                                                    <strong>Autorización de Recojo</strong>
                                                                    <br>
                                                                    <small class="text-muted">Puede recoger al estudiante</small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-save"></i> Actualizar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Eliminar -->
                            <div class="modal fade" id="deleteRelacionModal{{ $relacion->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title">
                                                <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.tutor-estudiante.destroy', $relacion->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar esta relación?</p>
                                                <div class="alert alert-info">
                                                    <strong>Tutor:</strong> {{ $relacion->tutor->persona->nombres }} {{ $relacion->tutor->persona->apellidos }}<br>
                                                    <strong>Estudiante:</strong> {{ $relacion->estudiante->persona->nombres }} {{ $relacion->estudiante->persona->apellidos }}<br>
                                                    <strong>Relación:</strong> {{ $relacion->relacion_familiar }}<br>
                                                    <strong>Tipo:</strong> {{ $relacion->tipo }}
                                                </div>
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    Esta acción no se puede deshacer.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="createRelacionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Nueva Relación Tutor-Estudiante
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.tutor-estudiante.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tutor_id_create">Tutor <span class="text-danger">*</span></label>
                                    <select name="tutor_id_create" 
                                            class="form-control select2 @error('tutor_id_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione un tutor --</option>
                                        @foreach($tutores as $tutor)
                                            <option value="{{ $tutor->id }}" {{ old('tutor_id_create') == $tutor->id ? 'selected' : '' }}>
                                                {{ $tutor->persona->apellidos }}, {{ $tutor->persona->nombres }} - {{ $tutor->persona->dni }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tutor_id_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estudiante_id_create">Estudiante <span class="text-danger">*</span></label>
                                    <select name="estudiante_id_create" 
                                            class="form-control select2 @error('estudiante_id_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione un estudiante --</option>
                                        @foreach($estudiantes as $estudiante)
                                            <option value="{{ $estudiante->id }}" {{ old('estudiante_id_create') == $estudiante->id ? 'selected' : '' }}>
                                                {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }} - {{ $estudiante->codigo_estudiante }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estudiante_id_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="relacion_familiar_create">Relación Familiar <span class="text-danger">*</span></label>
                                    <select name="relacion_familiar_create" 
                                            class="form-control @error('relacion_familiar_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione --</option>
                                        <option value="Padre" {{ old('relacion_familiar_create') == 'Padre' ? 'selected' : '' }}>Padre</option>
                                        <option value="Madre" {{ old('relacion_familiar_create') == 'Madre' ? 'selected' : '' }}>Madre</option>
                                        <option value="Tutor Legal" {{ old('relacion_familiar_create') == 'Tutor Legal' ? 'selected' : '' }}>Tutor Legal</option>
                                        <option value="Abuelo/a" {{ old('relacion_familiar_create') == 'Abuelo/a' ? 'selected' : '' }}>Abuelo/a</option>
                                        <option value="Tío/a" {{ old('relacion_familiar_create') == 'Tío/a' ? 'selected' : '' }}>Tío/a</option>
                                        <option value="Hermano/a" {{ old('relacion_familiar_create') == 'Hermano/a' ? 'selected' : '' }}>Hermano/a</option>
                                        <option value="Otro" {{ old('relacion_familiar_create') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('relacion_familiar_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo_create">Tipo <span class="text-danger">*</span></label>
                                    <select name="tipo_create" 
                                            class="form-control @error('tipo_create') is-invalid @enderror" 
                                            required>
                                        <option value="Principal" {{ old('tipo_create', 'Principal') == 'Principal' ? 'selected' : '' }}>Principal</option>
                                        <option value="Secundario" {{ old('tipo_create') == 'Secundario' ? 'selected' : '' }}>Secundario</option>
                                    </select>
                                    @error('tipo_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Solo puede haber un tutor principal activo por estudiante</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado_create">Estado <span class="text-danger">*</span></label>
                                    <select name="estado_create" 
                                            class="form-control @error('estado_create') is-invalid @enderror" 
                                            required>
                                        <option value="Activo" {{ old('estado_create', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    @error('estado_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="autorizacion_recojo_create" 
                                               name="autorizacion_recojo_create" 
                                               value="1"
                                               {{ old('autorizacion_recojo_create', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="autorizacion_recojo_create">
                                            <strong>Autorización de Recojo</strong>
                                            <br>
                                            <small class="text-muted">Puede recoger al estudiante</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#relacionesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[2, 'asc'], [4, 'asc']]
            });

            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Seleccione una opción',
                allowClear: true
            });

            @if(session('mensaje'))
                Swal.fire({
                    icon: '{{ session('icono') }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            @endif

            @if($errors->any() && session('modal_id'))
                $('#editRelacionModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('tutor_id_create') || $errors->has('estudiante_id_create'))
                $('#createRelacionModal').modal('show');
            @endif
        });
    </script>
@stop