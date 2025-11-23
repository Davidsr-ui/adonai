@extends('adminlte::page')

@section('content_header')
    <h1><b>Control de Asistencias</b></h1>
    <hr>
@stop

@section('content')
    <!-- Filtros -->
    <div class="card card-outline card-info collapsed-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('docente.asistencias.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Estudiante</label>
                            <select name="estudiante_id" class="form-control select2">
                                <option value="">Todos</option>
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}" {{ request('estudiante_id') == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Curso</label>
                            <select name="curso_id" class="form-control select2">
                                <option value="">Todos</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado" class="form-control">
                                <option value="">Todos</option>
                                <option value="Presente" {{ request('estado') == 'Presente' ? 'selected' : '' }}>Presente</option>
                                <option value="Ausente" {{ request('estado') == 'Ausente' ? 'selected' : '' }}>Ausente</option>
                                <option value="Tardanza" {{ request('estado') == 'Tardanza' ? 'selected' : '' }}>Tardanza</option>
                                <option value="Justificado" {{ request('estado') == 'Justificado' ? 'selected' : '' }}>Justificado</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('docente.asistencias.index') }}" class="btn btn-secondary">
                            <i class="fas fa-eraser"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Asistencias Registradas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createAsistenciaModal">
                            <i class="fas fa-plus"></i> Registrar Asistencia
                        </button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#registroMasivoModal">
                            <i class="fas fa-users"></i> Registro Masivo
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="asistenciasTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 10%">Fecha</th>
                                <th style="width: 25%">Estudiante</th>
                                <th style="width: 20%">Curso</th>
                                <th style="width: 20%">Docente</th>
                                <th style="width: 10%">Estado</th>
                                <th style="width: 10%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asistencias as $asistencia)
                            <tr>
                                <td class="text-center">{{ $asistencia->id }}</td>
                                <td>
                                    <strong>{{ $asistencia->fecha_formateada }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $asistencia->dia_semana }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-user-graduate"></i> 
                                    <strong>{{ $asistencia->estudiante->persona->apellidos }}, {{ $asistencia->estudiante->persona->nombres }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $asistencia->estudiante->codigo_estudiante }}</small>
                                </td>
                                <td>{{ $asistencia->curso->nombre }}</td>
                                <td>
                                    @if($asistencia->docente)
                                        {{ $asistencia->docente->persona->apellidos }}, {{ $asistencia->docente->persona->nombres }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $asistencia->estado_badge }}">
                                        @if($asistencia->estado == 'Presente')
                                            <i class="fas fa-check"></i>
                                        @elseif($asistencia->estado == 'Ausente')
                                            <i class="fas fa-times"></i>
                                        @elseif($asistencia->estado == 'Tardanza')
                                            <i class="fas fa-clock"></i>
                                        @else
                                            <i class="fas fa-file-alt"></i>
                                        @endif
                                        {{ $asistencia->estado }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('docente.asistencias.show', $asistencia->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editAsistenciaModal{{ $asistencia->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteAsistenciaModal{{ $asistencia->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editAsistenciaModal{{ $asistencia->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Asistencia
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('docente.asistencias.update', $asistencia->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="estudiante_id">Estudiante <span class="text-danger">*</span></label>
                                                            <select name="estudiante_id" 
                                                                    class="form-control select2 @error('estudiante_id') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione --</option>
                                                                @foreach($estudiantes as $estudiante)
                                                                    <option value="{{ $estudiante->id }}" 
                                                                        {{ old('estudiante_id', $asistencia->estudiante_id) == $estudiante->id ? 'selected' : '' }}>
                                                                        {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('estudiante_id')
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
                                                                <option value="">-- Seleccione --</option>
                                                                @foreach($cursos as $curso)
                                                                    <option value="{{ $curso->id }}" 
                                                                        {{ old('curso_id', $asistencia->curso_id) == $curso->id ? 'selected' : '' }}>
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
                                                            <label for="fecha">Fecha <span class="text-danger">*</span></label>
                                                            <input type="date" 
                                                                   name="fecha" 
                                                                   class="form-control @error('fecha') is-invalid @enderror" 
                                                                   value="{{ old('fecha', $asistencia->fecha->format('Y-m-d')) }}"
                                                                   required>
                                                            @error('fecha')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="estado">Estado <span class="text-danger">*</span></label>
                                                            <select name="estado" 
                                                                    class="form-control @error('estado') is-invalid @enderror" 
                                                                    required>
                                                                <option value="Presente" {{ old('estado', $asistencia->estado) == 'Presente' ? 'selected' : '' }}>Presente</option>
                                                                <option value="Ausente" {{ old('estado', $asistencia->estado) == 'Ausente' ? 'selected' : '' }}>Ausente</option>
                                                                <option value="Tardanza" {{ old('estado', $asistencia->estado) == 'Tardanza' ? 'selected' : '' }}>Tardanza</option>
                                                                <option value="Justificado" {{ old('estado', $asistencia->estado) == 'Justificado' ? 'selected' : '' }}>Justificado</option>
                                                            </select>
                                                            @error('estado')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="observaciones">Observaciones</label>
                                                            <textarea name="observaciones" 
                                                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                                                      rows="3"
                                                                      maxlength="500">{{ old('observaciones', $asistencia->observaciones) }}</textarea>
                                                            @error('observaciones')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
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
                            <div class="modal fade" id="deleteAsistenciaModal{{ $asistencia->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('docente.asistencias.destroy', $asistencia->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar este registro de asistencia?</p>
                                                <div class="alert alert-info">
                                                    <strong>Estudiante:</strong> {{ $asistencia->estudiante->persona->nombres }} {{ $asistencia->estudiante->persona->apellidos }}<br>
                                                    <strong>Curso:</strong> {{ $asistencia->curso->nombre }}<br>
                                                    <strong>Fecha:</strong> {{ $asistencia->fecha_formateada }}<br>
                                                    <strong>Estado:</strong> {{ $asistencia->estado }}
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
    <div class="modal fade" id="createAsistenciaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Registrar Asistencia
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('docente.asistencias.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estudiante_id_create">Estudiante <span class="text-danger">*</span></label>
                                    <select name="estudiante_id_create" 
                                            class="form-control select2 @error('estudiante_id_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($estudiantes as $estudiante)
                                            <option value="{{ $estudiante->id }}" {{ old('estudiante_id_create') == $estudiante->id ? 'selected' : '' }}>
                                                {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('estudiante_id_create')
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
                                        <option value="">-- Seleccione --</option>
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
                                    <label for="fecha_create">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           name="fecha_create" 
                                           class="form-control @error('fecha_create') is-invalid @enderror" 
                                           value="{{ old('fecha_create', date('Y-m-d')) }}"
                                           required>
                                    @error('fecha_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado_create">Estado <span class="text-danger">*</span></label>
                                    <select name="estado_create" 
                                            class="form-control @error('estado_create') is-invalid @enderror" 
                                            required>
                                        <option value="Presente" {{ old('estado_create', 'Presente') == 'Presente' ? 'selected' : '' }}>Presente</option>
                                        <option value="Ausente" {{ old('estado_create') == 'Ausente' ? 'selected' : '' }}>Ausente</option>
                                        <option value="Tardanza" {{ old('estado_create') == 'Tardanza' ? 'selected' : '' }}>Tardanza</option>
                                        <option value="Justificado" {{ old('estado_create') == 'Justificado' ? 'selected' : '' }}>Justificado</option>
                                    </select>
                                    @error('estado_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones_create">Observaciones</label>
                                    <textarea name="observaciones_create" 
                                              class="form-control @error('observaciones_create') is-invalid @enderror" 
                                              rows="3"
                                              maxlength="500">{{ old('observaciones_create') }}</textarea>
                                    @error('observaciones_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
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

    <!-- Modal Registro Masivo -->
    <div class="modal fade" id="registroMasivoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        <i class="fas fa-users"></i> Registro Masivo de Asistencias
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('docente.asistencias.registro-masivo') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Registro masivo:</strong> Se registrarán todos los estudiantes matriculados en el curso seleccionado como <strong>Presentes</strong> en la fecha indicada.
                        </div>
                        <div class="form-group">
                            <label for="curso_id">Curso <span class="text-danger">*</span></label>
                            <select name="curso_id" class="form-control" required>
                                <option value="">-- Seleccione un curso --</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Registrar Masivo
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
            $('#asistenciasTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[1, 'desc'], [2, 'asc']]
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
                $('#editAsistenciaModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('estudiante_id_create') || $errors->has('curso_id_create') || $errors->has('fecha_create'))
                $('#createAsistenciaModal').modal('show');
            @endif
        });
    </script>
@stop