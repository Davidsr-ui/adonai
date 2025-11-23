@extends('adminlte::page')

@section('content_header')
    <h1><b>Asignación de Docentes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Asignaciones Registradas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createAsignacionModal">
                            <i class="fas fa-plus"></i> Nueva Asignación
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="asignacionesTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 20%">Docente</th>
                                <th style="width: 15%">Curso</th>
                                <th style="width: 15%">Grado</th>
                                <th style="width: 10%">Nivel</th>
                                <th style="width: 12%">Gestión</th>
                                <th style="width: 10%">Tutor Aula</th>
                                <th style="width: 13%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignaciones as $asignacion)
                            <tr>
                                <td class="text-center">{{ $asignacion->id }}</td>
                                <td>
                                    <i class="fas fa-user-tie"></i> 
                                    <strong>{{ $asignacion->docente->persona->apellidos }}, {{ $asignacion->docente->persona->nombres }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $asignacion->docente->codigo_docente }}</small>
                                </td>
                                <td>{{ $asignacion->curso->nombre }}</td>
                                <td>{{ $asignacion->grado->nombre_completo }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $asignacion->grado->nivel->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $asignacion->gestion->nombre }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($asignacion->es_tutor_aula)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Sí
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.asignaciones.show', $asignacion->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editAsignacionModal{{ $asignacion->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteAsignacionModal{{ $asignacion->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editAsignacionModal{{ $asignacion->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Asignación
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.asignaciones.update', $asignacion->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="docente_id">Docente <span class="text-danger">*</span></label>
                                                            <select name="docente_id" 
                                                                    class="form-control select2 @error('docente_id') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione un docente --</option>
                                                                @foreach($docentes as $docente)
                                                                    <option value="{{ $docente->id }}" 
                                                                        {{ old('docente_id', $asignacion->docente_id) == $docente->id ? 'selected' : '' }}>
                                                                        {{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }} - {{ $docente->codigo_docente }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('docente_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="curso_id">Curso <span class="text-danger">*</span></label>
                                                            <select name="curso_id" 
                                                                    class="form-control select2 @error('curso_id') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione un curso --</option>
                                                                @foreach($cursos as $curso)
                                                                    <option value="{{ $curso->id }}" 
                                                                        {{ old('curso_id', $asignacion->curso_id) == $curso->id ? 'selected' : '' }}>
                                                                        {{ $curso->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('curso_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="grado_id">Grado <span class="text-danger">*</span></label>
                                                            <select name="grado_id" 
                                                                    class="form-control select2 @error('grado_id') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione un grado --</option>
                                                                @foreach($grados as $grado)
                                                                    <option value="{{ $grado->id }}" 
                                                                        {{ old('grado_id', $asignacion->grado_id) == $grado->id ? 'selected' : '' }}>
                                                                        {{ $grado->nivel->nombre }} - {{ $grado->nombre_completo }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('grado_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gestion_id">Gestión <span class="text-danger">*</span></label>
                                                            <select name="gestion_id" 
                                                                    class="form-control @error('gestion_id') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione una gestión --</option>
                                                                @foreach($gestiones as $gestion)
                                                                    <option value="{{ $gestion->id }}" 
                                                                        {{ old('gestion_id', $asignacion->gestion_id) == $gestion->id ? 'selected' : '' }}>
                                                                        {{ $gestion->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('gestion_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" 
                                                                       class="custom-control-input" 
                                                                       id="es_tutor_aula{{ $asignacion->id }}" 
                                                                       name="es_tutor_aula" 
                                                                       value="1"
                                                                       {{ old('es_tutor_aula', $asignacion->es_tutor_aula) ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="es_tutor_aula{{ $asignacion->id }}">
                                                                    <strong>Es Tutor de Aula</strong>
                                                                    <br>
                                                                    <small class="text-muted">Solo puede haber un tutor por grado y gestión</small>
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
                            <div class="modal fade" id="deleteAsignacionModal{{ $asignacion->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('admin.asignaciones.destroy', $asignacion->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar esta asignación?</p>
                                                <div class="alert alert-info">
                                                    <strong>Docente:</strong> {{ $asignacion->docente->persona->nombres }} {{ $asignacion->docente->persona->apellidos }}<br>
                                                    <strong>Curso:</strong> {{ $asignacion->curso->nombre }}<br>
                                                    <strong>Grado:</strong> {{ $asignacion->grado->nombre_completo }}<br>
                                                    <strong>Gestión:</strong> {{ $asignacion->gestion->nombre }}
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
    <div class="modal fade" id="createAsignacionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Nueva Asignación de Docente
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.asignaciones.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="docente_id_create">Docente <span class="text-danger">*</span></label>
                                    <select name="docente_id_create" 
                                            class="form-control select2 @error('docente_id_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione un docente --</option>
                                        @foreach($docentes as $docente)
                                            <option value="{{ $docente->id }}" {{ old('docente_id_create') == $docente->id ? 'selected' : '' }}>
                                                {{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }} - {{ $docente->codigo_docente }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('docente_id_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="curso_id_create">Curso <span class="text-danger">*</span></label>
                                    <select name="curso_id_create" 
                                            class="form-control select2 @error('curso_id_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione un curso --</option>
                                        @foreach($cursos as $curso)
                                            <option value="{{ $curso->id }}" {{ old('curso_id_create') == $curso->id ? 'selected' : '' }}>
                                                {{ $curso->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('curso_id_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grado_id_create">Grado <span class="text-danger">*</span></label>
                                    <select name="grado_id_create" 
                                            class="form-control select2 @error('grado_id_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione un grado --</option>
                                        @foreach($grados as $grado)
                                            <option value="{{ $grado->id }}" {{ old('grado_id_create') == $grado->id ? 'selected' : '' }}>
                                                {{ $grado->nivel->nombre }} - {{ $grado->nombre_completo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grado_id_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gestion_id_create">Gestión <span class="text-danger">*</span></label>
                                    <select name="gestion_id_create" 
                                            class="form-control @error('gestion_id_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione una gestión --</option>
                                        @foreach($gestiones as $gestion)
                                            <option value="{{ $gestion->id }}" {{ old('gestion_id_create') == $gestion->id ? 'selected' : '' }}>
                                                {{ $gestion->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gestion_id_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="es_tutor_aula_create" 
                                               name="es_tutor_aula_create" 
                                               value="1"
                                               {{ old('es_tutor_aula_create') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="es_tutor_aula_create">
                                            <strong>Es Tutor de Aula</strong>
                                            <br>
                                            <small class="text-muted">Solo puede haber un tutor por grado y gestión</small>
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
            // Inicializar DataTable
            $('#asignacionesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[5, 'desc'], [3, 'asc']] // Ordenar por gestión y grado
            });

            // Inicializar Select2
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
                $('#editAsignacionModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('docente_id_create') || $errors->has('curso_id_create') || $errors->has('grado_id_create') || $errors->has('gestion_id_create'))
                $('#createAsignacionModal').modal('show');
            @endif
        });
    </script>
@stop