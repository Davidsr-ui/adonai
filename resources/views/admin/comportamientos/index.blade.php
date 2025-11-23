@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Comportamientos</b></h1>
    <hr>
@stop

@section('content')

@section('content')

    @php
        // Detectar si es admin o docente y usar prefijo correcto
        $routePrefix = Auth::user()->hasRole('Administrador') ? 'admin' : 'docente';
    @endphp
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
            <form action="{{ route($routePrefix . '.comportamientos.index') }}" method="GET">
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
                            <label>Tipo</label>
                            <select name="tipo" class="form-control">
                                <option value="">Todos</option>
                                <option value="Positivo" {{ request('tipo') == 'Positivo' ? 'selected' : '' }}>Positivo</option>
                                <option value="Negativo" {{ request('tipo') == 'Negativo' ? 'selected' : '' }}>Negativo</option>
                                <option value="Neutro" {{ request('tipo') == 'Neutro' ? 'selected' : '' }}>Neutro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Notificado</label>
                            <select name="notificado" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('notificado') === '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ request('notificado') === '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route($routePrefix . '.comportamientos.index') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Comportamientos Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createComportamientoModal">
                            <i class="fas fa-plus"></i> Registrar Comportamiento
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="comportamientosTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 4%">ID</th>
                                <th style="width: 10%">Fecha</th>
                                <th style="width: 20%">Estudiante</th>
                                <th style="width: 15%">Docente</th>
                                <th style="width: 25%">Descripción</th>
                                <th style="width: 8%">Tipo</th>
                                <th style="width: 6%">Sanción</th>
                                <th style="width: 6%">Notif.</th>
                                <th style="width: 12%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comportamientos as $comportamiento)
                            <tr>
                                <td class="text-center">{{ $comportamiento->id }}</td>
                                <td>
                                    <strong>{{ $comportamiento->fecha_formateada }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $comportamiento->dia_semana }}</small>
                                </td>
                                <td>
                                    <i class="fas fa-user-graduate"></i> 
                                    <strong>{{ $comportamiento->estudiante->persona->apellidos }}, {{ $comportamiento->estudiante->persona->nombres }}</strong>
                                </td>
                                <td>
                                    @if($comportamiento->docente)
                                        {{ $comportamiento->docente->persona->apellidos }}, {{ $comportamiento->docente->persona->nombres }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>{{ \Str::limit($comportamiento->descripcion, 50) }}</td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $comportamiento->tipo_badge }}">
                                        <i class="fas {{ $comportamiento->tipo_icon }}"></i>
                                        {{ $comportamiento->tipo }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($comportamiento->sancion)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($comportamiento->notificado_tutor)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{route($routePrefix . '.comportamientos.show', $comportamiento->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editComportamientoModal{{ $comportamiento->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteComportamientoModal{{ $comportamiento->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editComportamientoModal{{ $comportamiento->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Comportamiento
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route($routePrefix . '.comportamientos.update', $comportamiento->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Estudiante <span class="text-danger">*</span></label>
                                                            <select name="estudiante_id" class="form-control select2" required>
                                                                <option value="">-- Seleccione --</option>
                                                                @foreach($estudiantes as $estudiante)
                                                                    <option value="{{ $estudiante->id }}" {{ old('estudiante_id', $comportamiento->estudiante_id) == $estudiante->id ? 'selected' : '' }}>
                                                                        {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Docente</label>
                                                            <select name="docente_id" class="form-control select2">
                                                                <option value="">-- Seleccione (Opcional) --</option>
                                                                @foreach($docentes as $docente)
                                                                    <option value="{{ $docente->id }}" {{ old('docente_id', $comportamiento->docente_id) == $docente->id ? 'selected' : '' }}>
                                                                        {{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Fecha <span class="text-danger">*</span></label>
                                                            <input type="date" name="fecha" class="form-control" 
                                                                   value="{{ old('fecha', $comportamiento->fecha->format('Y-m-d')) }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Tipo <span class="text-danger">*</span></label>
                                                            <select name="tipo" class="form-control" required>
                                                                <option value="Positivo" {{ old('tipo', $comportamiento->tipo) == 'Positivo' ? 'selected' : '' }}>Positivo</option>
                                                                <option value="Negativo" {{ old('tipo', $comportamiento->tipo) == 'Negativo' ? 'selected' : '' }}>Negativo</option>
                                                                <option value="Neutro" {{ old('tipo', $comportamiento->tipo) == 'Neutro' ? 'selected' : '' }}>Neutro</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Descripción <span class="text-danger">*</span></label>
                                                            <textarea name="descripcion" class="form-control" rows="3" 
                                                                      maxlength="1000" required>{{ old('descripcion', $comportamiento->descripcion) }}</textarea>
                                                            <small class="text-muted">Máximo 1000 caracteres</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Sanción</label>
                                                            <input type="text" name="sancion" class="form-control" 
                                                                   value="{{ old('sancion', $comportamiento->sancion) }}" 
                                                                   maxlength="255">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" 
                                                                       id="notificado_tutor{{ $comportamiento->id }}" 
                                                                       name="notificado_tutor" value="1"
                                                                       {{ old('notificado_tutor', $comportamiento->notificado_tutor) ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="notificado_tutor{{ $comportamiento->id }}">
                                                                    <strong>Notificar a Tutor</strong>
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
                            <div class="modal fade" id="deleteComportamientoModal{{ $comportamiento->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route($routePrefix . '.comportamientos.destroy', $comportamiento->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar este registro de comportamiento?</p>
                                                <div class="alert alert-info">
                                                    <strong>Estudiante:</strong> {{ $comportamiento->estudiante->persona->nombres }} {{ $comportamiento->estudiante->persona->apellidos }}<br>
                                                    <strong>Fecha:</strong> {{ $comportamiento->fecha_formateada }}<br>
                                                    <strong>Tipo:</strong> {{ $comportamiento->tipo }}<br>
                                                    <strong>Descripción:</strong> {{ \Str::limit($comportamiento->descripcion, 100) }}
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
    <div class="modal fade" id="createComportamientoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Registrar Nuevo Comportamiento
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route($routePrefix . '.comportamientos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estudiante <span class="text-danger">*</span></label>
                                    <select name="estudiante_id_create" class="form-control select2" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($estudiantes as $estudiante)
                                            <option value="{{ $estudiante->id }}" {{ old('estudiante_id_create') == $estudiante->id ? 'selected' : '' }}>
                                                {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Docente</label>
                                    <select name="docente_id_create" class="form-control select2">
                                        <option value="">-- Seleccione (Opcional) --</option>
                                        @foreach($docentes as $docente)
                                            <option value="{{ $docente->id }}" {{ old('docente_id_create') == $docente->id ? 'selected' : '' }}>
                                                {{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_create" class="form-control" 
                                           value="{{ old('fecha_create', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo <span class="text-danger">*</span></label>
                                    <select name="tipo_create" class="form-control" required>
                                        <option value="Positivo" {{ old('tipo_create', 'Neutro') == 'Positivo' ? 'selected' : '' }}>Positivo</option>
                                        <option value="Negativo" {{ old('tipo_create') == 'Negativo' ? 'selected' : '' }}>Negativo</option>
                                        <option value="Neutro" {{ old('tipo_create', 'Neutro') == 'Neutro' ? 'selected' : '' }}>Neutro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción <span class="text-danger">*</span></label>
                                    <textarea name="descripcion_create" class="form-control" rows="4" 
                                              maxlength="1000" required 
                                              placeholder="Describa el comportamiento observado...">{{ old('descripcion_create') }}</textarea>
                                    <small class="text-muted">Máximo 1000 caracteres</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Sanción (Opcional)</label>
                                    <input type="text" name="sancion_create" class="form-control" 
                                           value="{{ old('sancion_create') }}" 
                                           placeholder="Ej: Suspensión por 3 días" 
                                           maxlength="255">
                                    <small class="text-muted">Solo si aplica</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="notificado_tutor_create" 
                                               name="notificado_tutor_create" value="1"
                                               {{ old('notificado_tutor_create') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="notificado_tutor_create">
                                            <strong>Notificar a Tutor</strong>
                                            <br>
                                            <small class="text-muted">Enviar notificación</small>
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
            $('#comportamientosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[1, 'desc']]
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
                $('#editComportamientoModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('estudiante_id_create') || $errors->has('descripcion_create'))
                $('#createComportamientoModal').modal('show');
            @endif
        });
    </script>
@stop