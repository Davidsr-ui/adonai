@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Administradores</b></h1>
    <hr>
@stop

@section('content')
    <!-- Estadísticas -->
    <div class="row">
        <div class="col-md-2">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total'] }}</h3>
                    <p>Total</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $estadisticas['directores'] }}</h3>
                    <p>Directores</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['subdirectores'] }}</h3>
                    <p>Subdirectores</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-cog"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $estadisticas['secretarios'] }}</h3>
                    <p>Secretarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-edit"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['activos'] }}</h3>
                    <p>Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $estadisticas['con_area'] }}</h3>
                    <p>Con Área</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>
    </div>

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
            <form action="{{ route('admin.administradores.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Buscar por Nombre/DNI</label>
                            <input type="text" name="buscar" class="form-control" 
                                   value="{{ request('buscar') }}" 
                                   placeholder="Ingrese nombre, apellido o DNI">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Cargo</label>
                            <select name="cargo" class="form-control">
                                <option value="">Todos</option>
                                <option value="Director" {{ request('cargo') == 'Director' ? 'selected' : '' }}>Director</option>
                                <option value="Subdirector" {{ request('cargo') == 'Subdirector' ? 'selected' : '' }}>Subdirector</option>
                                <option value="Secretario" {{ request('cargo') == 'Secretario' ? 'selected' : '' }}>Secretario</option>
                                <option value="Administrativo" {{ request('cargo') == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Área</label>
                            <select name="area" class="form-control">
                                <option value="">Todas</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>
                                        {{ $area }}
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
                                <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('admin.administradores.index') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Administradores Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createAdministradorModal">
                            <i class="fas fa-plus"></i> Registrar Administrador
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="administradoresTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 4%">ID</th>
                                <th style="width: 8%">DNI</th>
                                <th style="width: 22%">Nombre Completo</th>
                                <th style="width: 12%">Cargo</th>
                                <th style="width: 15%">Área</th>
                                <th style="width: 10%">F. Asignación</th>
                                <th style="width: 10%">Antigüedad</th>
                                <th style="width: 8%">Estado</th>
                                <th style="width: 11%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($administradores as $administrador)
                            <tr>
                                <td class="text-center">{{ $administrador->id }}</td>
                                <td>{{ $administrador->persona->dni }}</td>
                                <td>
                                    <i class="fas {{ $administrador->cargo_icon }}"></i>
                                    <strong>{{ $administrador->persona->apellidos }}, {{ $administrador->persona->nombres }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $administrador->cargo_badge }}">
                                        {{ $administrador->cargo }}
                                    </span>
                                </td>
                                <td>{{ $administrador->area ?? '-' }}</td>
                                <td class="text-center">
                                    <small>{{ $administrador->fecha_asignacion_formateada }}</small>
                                </td>
                                <td class="text-center">
                                    <small>{{ $administrador->antiguedad }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $administrador->estado_persona_badge }}">
                                        {{ $administrador->estado_persona }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.administradores.show', $administrador->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editAdministradorModal{{ $administrador->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteAdministradorModal{{ $administrador->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editAdministradorModal{{ $administrador->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Administrador
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.administradores.update', $administrador->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Persona <span class="text-danger">*</span></label>
                                                            <select name="persona_id" class="form-control" required disabled>
                                                                <option value="{{ $administrador->persona->id }}" selected>
                                                                    {{ $administrador->persona->dni }} - {{ $administrador->persona->nombres }} {{ $administrador->persona->apellidos }}
                                                                </option>
                                                            </select>
                                                            <input type="hidden" name="persona_id" value="{{ $administrador->persona_id }}">
                                                            <small class="text-muted">La persona no puede ser modificada</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Cargo <span class="text-danger">*</span></label>
                                                            <select name="cargo" class="form-control" required>
                                                                <option value="Director" {{ old('cargo', $administrador->cargo) == 'Director' ? 'selected' : '' }}>Director</option>
                                                                <option value="Subdirector" {{ old('cargo', $administrador->cargo) == 'Subdirector' ? 'selected' : '' }}>Subdirector</option>
                                                                <option value="Secretario" {{ old('cargo', $administrador->cargo) == 'Secretario' ? 'selected' : '' }}>Secretario</option>
                                                                <option value="Administrativo" {{ old('cargo', $administrador->cargo) == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Fecha de Asignación</label>
                                                            <input type="date" name="fecha_asignacion" class="form-control" 
                                                                   value="{{ old('fecha_asignacion', $administrador->fecha_asignacion ? $administrador->fecha_asignacion->format('Y-m-d') : '') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Área</label>
                                                            <input type="text" name="area" class="form-control" 
                                                                   value="{{ old('area', $administrador->area) }}" 
                                                                   maxlength="100"
                                                                   placeholder="Ej: Recursos Humanos, Contabilidad, etc.">
                                                            <small class="text-muted">Máximo 100 caracteres</small>
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
                            <div class="modal fade" id="deleteAdministradorModal{{ $administrador->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('admin.administradores.destroy', $administrador->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar este administrador?</p>
                                                <div class="alert alert-info">
                                                    <strong>Nombre:</strong> {{ $administrador->persona->nombres }} {{ $administrador->persona->apellidos }}<br>
                                                    <strong>DNI:</strong> {{ $administrador->persona->dni }}<br>
                                                    <strong>Cargo:</strong> {{ $administrador->cargo }}<br>
                                                    <strong>Área:</strong> {{ $administrador->area ?? 'N/A' }}
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
    <div class="modal fade" id="createAdministradorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Registrar Nuevo Administrador
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.administradores.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if($personasDisponibles->count() > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Persona <span class="text-danger">*</span></label>
                                    <select name="persona_id_create" class="form-control select2" required>
                                        <option value="">-- Seleccione una persona --</option>
                                        @foreach($personasDisponibles as $persona)
                                            <option value="{{ $persona->id }}" {{ old('persona_id_create') == $persona->id ? 'selected' : '' }}>
                                                {{ $persona->dni }} - {{ $persona->apellidos }}, {{ $persona->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Solo se muestran personas que no estén registradas como docentes o estudiantes</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cargo <span class="text-danger">*</span></label>
                                    <select name="cargo_create" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        <option value="Director" {{ old('cargo_create') == 'Director' ? 'selected' : '' }}>Director</option>
                                        <option value="Subdirector" {{ old('cargo_create') == 'Subdirector' ? 'selected' : '' }}>Subdirector</option>
                                        <option value="Secretario" {{ old('cargo_create') == 'Secretario' ? 'selected' : '' }}>Secretario</option>
                                        <option value="Administrativo" {{ old('cargo_create', 'Administrativo') == 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Asignación</label>
                                    <input type="date" name="fecha_asignacion_create" class="form-control" 
                                           value="{{ old('fecha_asignacion_create', date('Y-m-d')) }}">
                                    <small class="text-muted">Fecha en que se asigna el cargo</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Área</label>
                                    <input type="text" name="area_create" class="form-control" 
                                           value="{{ old('area_create') }}" 
                                           maxlength="100"
                                           placeholder="Ej: Recursos Humanos, Contabilidad, Secretaría General, etc.">
                                    <small class="text-muted">Área o departamento al que pertenece (opcional, máximo 100 caracteres)</small>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>No hay personas disponibles.</strong> Todas las personas activas ya están registradas como administradores, docentes o estudiantes.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        @if($personasDisponibles->count() > 0)
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        @endif
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
            $('#administradoresTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[0, 'desc']]
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
                $('#editAdministradorModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('persona_id_create') || $errors->has('cargo_create'))
                $('#createAdministradorModal').modal('show');
            @endif
        });
    </script>
@stop