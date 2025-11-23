@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Roles</b></h1>
    <hr>
@stop

@section('content')
    <!-- Estadísticas -->
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total'] }}</h3>
                    <p>Total Roles</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tag"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['con_usuarios'] }}</h3>
                    <p>Con Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['total_usuarios_asignados'] }}</h3>
                    <p>Usuarios Asignados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $estadisticas['total_permisos_asignados'] }}</h3>
                    <p>Permisos Asignados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-key"></i>
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
            <form action="{{ route('admin.roles.index') }}" method="GET">
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
                            <label>Tiene Usuarios</label>
                            <select name="tiene_usuarios" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('tiene_usuarios') === '1' ? 'selected' : '' }}>Con usuarios</option>
                                <option value="0" {{ request('tiene_usuarios') === '0' ? 'selected' : '' }}>Sin usuarios</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tiene Permisos</label>
                            <select name="tiene_permisos" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('tiene_permisos') === '1' ? 'selected' : '' }}>Con permisos</option>
                                <option value="0" {{ request('tiene_permisos') === '0' ? 'selected' : '' }}>Sin permisos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Roles Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createRoleModal">
                            <i class="fas fa-plus"></i> Crear Rol
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="rolesTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 4%">ID</th>
                                <th style="width: 15%">Nombre</th>
                                <th style="width: 20%">Nombre para Mostrar</th>
                                <th style="width: 30%">Descripción</th>
                                <th style="width: 10%">Usuarios</th>
                                <th style="width: 10%">Permisos</th>
                                <th style="width: 11%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td class="text-center">{{ $role->id }}</td>
                                <td>
                                    <code>{{ $role->name }}</code>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $role->badge_color }} badge-lg">
                                        <i class="fas {{ $role->icono }}"></i>
                                        {{ $role->display_name }}
                                    </span>
                                </td>
                                <td>{{ \Str::limit($role->description, 60) }}</td>
                                <td class="text-center">
                                    @if($role->users->count() > 0)
                                        <span class="badge badge-success">
                                            {{ $role->users->count() }} usuario(s)
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Sin usuarios</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($role->permissions->count() > 0)
                                        <span class="badge badge-primary">
                                            {{ $role->permissions->count() }} permiso(s)
                                        </span>
                                    @else
                                        <span class="badge badge-warning">Sin permisos</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.roles.show', $role->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editRoleModal{{ $role->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteRoleModal{{ $role->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Rol: {{ $role->display_name }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nombre (Slug) <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" 
                                                                   value="{{ old('name', $role->name) }}" 
                                                                   required maxlength="50"
                                                                   placeholder="ej: administrador">
                                                            <small class="text-muted">Sin espacios, minúsculas (ej: administrador, docente)</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nombre para Mostrar <span class="text-danger">*</span></label>
                                                            <input type="text" name="display_name" class="form-control" 
                                                                   value="{{ old('display_name', $role->display_name) }}" 
                                                                   required maxlength="100"
                                                                   placeholder="ej: Administrador">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Descripción</label>
                                                            <textarea name="description" class="form-control" rows="2" 
                                                                      placeholder="Descripción del rol">{{ old('description', $role->description) }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <hr>
                                                <h5><i class="fas fa-key"></i> Asignar Permisos</h5>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @foreach($permisosAgrupados as $modulo => $permisos)
                                                        <div class="card card-outline card-secondary mb-2">
                                                            <div class="card-header p-2">
                                                                <h6 class="mb-0">
                                                                    <i class="fas fa-folder"></i> 
                                                                    <strong>{{ $modulo ?? 'Sin Módulo' }}</strong>
                                                                    <span class="badge badge-info">{{ $permisos->count() }}</span>
                                                                </h6>
                                                            </div>
                                                            <div class="card-body p-2">
                                                                <div class="row">
                                                                    @foreach($permisos as $permiso)
                                                                    <div class="col-md-3">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" 
                                                                                   class="custom-control-input" 
                                                                                   id="permiso_edit_{{ $role->id }}_{{ $permiso->id }}" 
                                                                                   name="permissions[]" 
                                                                                   value="{{ $permiso->id }}"
                                                                                   {{ $role->permissions->contains($permiso->id) ? 'checked' : '' }}>
                                                                            <label class="custom-control-label" for="permiso_edit_{{ $role->id }}_{{ $permiso->id }}">
                                                                                {{ $permiso->display_name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
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
                            <div class="modal fade" id="deleteRoleModal{{ $role->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar este rol?</p>
                                                <div class="alert alert-info">
                                                    <strong>Nombre:</strong> {{ $role->name }}<br>
                                                    <strong>Display:</strong> {{ $role->display_name }}<br>
                                                    <strong>Descripción:</strong> {{ $role->description ?? 'N/A' }}
                                                </div>
                                                @if($role->users->count() > 0)
                                                <div class="alert alert-danger">
                                                    <i class="fas fa-ban"></i>
                                                    Este rol tiene <strong>{{ $role->users->count() }} usuario(s)</strong> asignado(s). 
                                                    No se puede eliminar.
                                                </div>
                                                @endif
                                                @if($role->permissions->count() > 0)
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    Este rol tiene <strong>{{ $role->permissions->count() }} permiso(s)</strong> asignado(s). 
                                                    Se removerán todos los permisos.
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                                @if($role->users->count() == 0)
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                                @endif
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
    <div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Crear Nuevo Rol
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre (Slug) <span class="text-danger">*</span></label>
                                    <input type="text" name="name_create" class="form-control" 
                                           value="{{ old('name_create') }}" 
                                           required maxlength="50"
                                           placeholder="ej: administrador">
                                    <small class="text-muted">Sin espacios, minúsculas (ej: administrador, docente)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre para Mostrar <span class="text-danger">*</span></label>
                                    <input type="text" name="display_name_create" class="form-control" 
                                           value="{{ old('display_name_create') }}" 
                                           required maxlength="100"
                                           placeholder="ej: Administrador">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="description_create" class="form-control" rows="2" 
                                              placeholder="Descripción del rol">{{ old('description_create') }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <h5><i class="fas fa-key"></i> Asignar Permisos (Opcional)</h5>
                        
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($permisosAgrupados as $modulo => $permisos)
                                <div class="card card-outline card-secondary mb-2">
                                    <div class="card-header p-2">
                                        <h6 class="mb-0">
                                            <i class="fas fa-folder"></i> 
                                            <strong>{{ $modulo ?? 'Sin Módulo' }}</strong>
                                            <span class="badge badge-info">{{ $permisos->count() }}</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="row">
                                            @foreach($permisos as $permiso)
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" 
                                                           class="custom-control-input" 
                                                           id="permiso_create_{{ $permiso->id }}" 
                                                           name="permissions_create[]" 
                                                           value="{{ $permiso->id }}">
                                                    <label class="custom-control-label" for="permiso_create_{{ $permiso->id }}">
                                                        {{ $permiso->display_name }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
    <style>
        .badge-lg {
            font-size: 0.95rem;
            padding: 0.5rem 0.75rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[0, 'desc']]
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
                $('#editRoleModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('name_create') || $errors->has('display_name_create'))
                $('#createRoleModal').modal('show');
            @endif
        });
    </script>
@stop