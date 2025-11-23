@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Permisos</b></h1>
    <hr>
@stop

@section('content')
    <!-- Estadísticas -->
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total'] }}</h3>
                    <p>Total Permisos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-key"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['asignados'] }}</h3>
                    <p>Asignados a Roles</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['sin_asignar'] }}</h3>
                    <p>Sin Asignar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ count($modulos) }}</h3>
                    <p>Módulos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cube"></i>
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
            <form action="{{ route('admin.permissions.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Buscar</label>
                            <input type="text" name="buscar" class="form-control" 
                                   value="{{ request('buscar') }}" 
                                   placeholder="Nombre, descripción...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Módulo</label>
                            <select name="module" class="form-control">
                                <option value="">Todos</option>
                                @foreach($modulos as $modulo)
                                    <option value="{{ $modulo }}" {{ request('module') == $modulo ? 'selected' : '' }}>
                                        {{ $modulo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Estado de Asignación</label>
                            <select name="asignado" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('asignado') === '1' ? 'selected' : '' }}>Asignados</option>
                                <option value="0" {{ request('asignado') === '0' ? 'selected' : '' }}>Sin Asignar</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Permisos Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createCRUDModal">
                            <i class="fas fa-magic"></i> Crear Permisos CRUD
                        </button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPermissionModal">
                            <i class="fas fa-plus"></i> Crear Permiso
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="permissionsTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 3%">ID</th>
                                <th style="width: 15%">Nombre</th>
                                <th style="width: 20%">Nombre para Mostrar</th>
                                <th style="width: 25%">Descripción</th>
                                <th style="width: 10%">Módulo</th>
                                <th style="width: 10%">Roles</th>
                                <th style="width: 12%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($permisos as $permiso)
                            <tr>
                                <td class="text-center">{{ $permiso->id }}</td>
                                <td>
                                    <code>{{ $permiso->name }}</code>
                                </td>
                                <td><strong>{{ $permiso->display_name }}</strong></td>
                                <td>{{ \Str::limit($permiso->description, 50) }}</td>
                                <td class="text-center">
                                    @if($permiso->module)
                                        <span class="badge badge-{{ $permiso->modulo_badge }}">
                                            <i class="fas {{ $permiso->modulo_icon }}"></i>
                                            {{ $permiso->module }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($permiso->roles->count() > 0)
                                        <span class="badge badge-success">
                                            {{ $permiso->roles->count() }} rol(es)
                                        </span>
                                    @else
                                        <span class="badge badge-warning">Sin asignar</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.permissions.show', $permiso->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editPermissionModal{{ $permiso->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deletePermissionModal{{ $permiso->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editPermissionModal{{ $permiso->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Permiso
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.permissions.update', $permiso->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nombre (Slug) <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" 
                                                                   value="{{ old('name', $permiso->name) }}" 
                                                                   required maxlength="100"
                                                                   placeholder="ej: ver.usuarios">
                                                            <small class="text-muted">Formato: accion.entidad (ej: ver.usuarios)</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nombre para Mostrar <span class="text-danger">*</span></label>
                                                            <input type="text" name="display_name" class="form-control" 
                                                                   value="{{ old('display_name', $permiso->display_name) }}" 
                                                                   required maxlength="150"
                                                                   placeholder="ej: Ver Usuarios">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Descripción</label>
                                                            <textarea name="description" class="form-control" rows="2" 
                                                                      placeholder="Descripción del permiso">{{ old('description', $permiso->description) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Módulo</label>
                                                            <select name="module" class="form-control">
                                                                <option value="">Sin módulo</option>
                                                                <option value="Usuarios" {{ old('module', $permiso->module) == 'Usuarios' ? 'selected' : '' }}>Usuarios</option>
                                                                <option value="Academico" {{ old('module', $permiso->module) == 'Academico' ? 'selected' : '' }}>Academico</option>
                                                                <option value="Comunicacion" {{ old('module', $permiso->module) == 'Comunicacion' ? 'selected' : '' }}>Comunicacion</option>
                                                                <option value="Procesos" {{ old('module', $permiso->module) == 'Procesos' ? 'selected' : '' }}>Procesos</option>
                                                                <option value="Registros" {{ old('module', $permiso->module) == 'Registros' ? 'selected' : '' }}>Registros</option>
                                                                <option value="Roles" {{ old('module', $permiso->module) == 'Roles' ? 'selected' : '' }}>Roles</option>
                                                                <option value="Seguridad" {{ old('module', $permiso->module) == 'Seguridad' ? 'selected' : '' }}>Seguridad</option>
                                                                <option value="Sistema" {{ old('module', $permiso->module) == 'Sistema' ? 'selected' : '' }}>Sistema</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Asignar a Roles</label>
                                                            <select name="roles[]" class="form-control select2" multiple>
                                                                @foreach($roles as $role)
                                                                    <option value="{{ $role->id }}" 
                                                                            {{ $permiso->roles->contains($role->id) ? 'selected' : '' }}>
                                                                        {{ $role->display_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
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
                            <div class="modal fade" id="deletePermissionModal{{ $permiso->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('admin.permissions.destroy', $permiso->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar este permiso?</p>
                                                <div class="alert alert-info">
                                                    <strong>Nombre:</strong> {{ $permiso->name }}<br>
                                                    <strong>Display:</strong> {{ $permiso->display_name }}<br>
                                                    <strong>Módulo:</strong> {{ $permiso->module ?? 'N/A' }}
                                                </div>
                                                @if($permiso->roles->count() > 0)
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    Este permiso está asignado a <strong>{{ $permiso->roles->count() }} rol(es)</strong>. 
                                                    Se removerá de todos los roles.
                                                </div>
                                                @endif
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
    <div class="modal fade" id="createPermissionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Crear Nuevo Permiso
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre (Slug) <span class="text-danger">*</span></label>
                                    <input type="text" name="name_create" class="form-control" 
                                           value="{{ old('name_create') }}" 
                                           required maxlength="100"
                                           placeholder="ej: ver.usuarios">
                                    <small class="text-muted">Formato: accion.entidad (ej: ver.usuarios, crear.estudiantes)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre para Mostrar <span class="text-danger">*</span></label>
                                    <input type="text" name="display_name_create" class="form-control" 
                                           value="{{ old('display_name_create') }}" 
                                           required maxlength="150"
                                           placeholder="ej: Ver Usuarios">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="description_create" class="form-control" rows="2" 
                                              placeholder="Descripción del permiso">{{ old('description_create') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Módulo</label>
                                    <select name="module_create" class="form-control">
                                        <option value="">Sin módulo</option>
                                        <option value="Usuarios">Usuarios</option>
                                        <option value="Academico">Academico</option>
                                        <option value="Comunicacion">Comunicacion</option>
                                        <option value="Procesos">Procesos</option>
                                        <option value="Registros">Registros</option>
                                        <option value="Roles">Roles</option>
                                        <option value="Seguridad">Seguridad</option>
                                        <option value="Sistema">Sistema</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Asignar a Roles (Opcional)</label>
                                    <select name="roles_create[]" class="form-control select2" multiple>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                        @endforeach
                                    </select>
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

    <!-- Modal Crear Permisos CRUD -->
    <div class="modal fade" id="createCRUDModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        <i class="fas fa-magic"></i> Crear Permisos CRUD
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.permissions.crear-crud') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Esto creará automáticamente 4 permisos: ver, crear, editar y eliminar.
                        </div>
                        <div class="form-group">
                            <label>Módulo <span class="text-danger">*</span></label>
                            <select name="modulo" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                <option value="Usuarios">Usuarios</option>
                                <option value="Academico">Academico</option>
                                <option value="Comunicacion">Comunicacion</option>
                                <option value="Procesos">Procesos</option>
                                <option value="Registros">Registros</option>
                                <option value="Roles">Roles</option>
                                <option value="Seguridad">Seguridad</option>
                                <option value="Sistema">Sistema</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Entidad <span class="text-danger">*</span></label>
                            <input type="text" name="entidad" class="form-control" required
                                   placeholder="ej: estudiantes, docentes, cursos">
                            <small class="text-muted">Nombre de la entidad en minúsculas (ej: estudiantes)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-magic"></i> Crear Permisos
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
            // DataTable
            $('#permissionsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[0, 'desc']],
                "pageLength": 25
            });

            // Inicializar Select2 en modales de edición cuando se abren
            $('[id^="editPermissionModal"]').on('shown.bs.modal', function () {
                $(this).find('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Seleccione roles',
                    allowClear: true,
                    dropdownParent: $(this)
                });
            });

            // Inicializar Select2 en modal de crear cuando se abre
            $('#createPermissionModal').on('shown.bs.modal', function () {
                $(this).find('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Seleccione roles',
                    allowClear: true,
                    dropdownParent: $(this)
                });
            });

            // Limpiar Select2 al cerrar modales
            $('.modal').on('hidden.bs.modal', function () {
                $(this).find('.select2').select2('destroy');
            });

            // Mostrar alertas
            @if(session('mensaje'))
                Swal.fire({
                    icon: '{{ session('icono') }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            @endif

            // Reabrir modal si hay errores de validación
            @if($errors->any() && session('modal_id'))
                $('#editPermissionModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('name_create') || $errors->has('display_name_create') || $errors->has('module_create'))
                $('#createPermissionModal').modal('show');
            @endif
        });
    </script>
@stop