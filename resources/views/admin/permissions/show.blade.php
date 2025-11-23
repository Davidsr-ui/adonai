@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Permiso: {{ $permission->display_name }}</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <!-- Información del Permiso -->
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Permiso</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-key fa-5x text-warning"></i>
                        <h3 class="mt-2">
                            <span class="badge badge-warning badge-lg">
                                {{ $permission->display_name }}
                            </span>
                        </h3>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">ID:</th>
                            <td>{{ $permission->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre (Slug):</th>
                            <td><code>{{ $permission->name }}</code></td>
                        </tr>
                        <tr>
                            <th>Módulo:</th>
                            <td>
                                @if($permission->module)
                                    <span class="badge badge-{{ $permission->modulo_badge }}">
                                        <i class="fas {{ $permission->modulo_icon }}"></i>
                                        {{ $permission->module }}
                                    </span>
                                @else
                                    <span class="text-muted">Sin módulo</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Descripción:</th>
                            <td>{{ $permission->description ?? 'Sin descripción' }}</td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td>{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td>{{ $permission->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editPermissionModal">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Estadísticas</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-user-tag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Roles Asignados</span>
                                    <span class="info-box-number">{{ $permission->roles->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Usuarios con este permiso</span>
                                    <span class="info-box-number">
                                        {{ $permission->roles->sum(function($role) { return $role->users->count(); }) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#asignarRolModal">
                        <i class="fas fa-plus"></i> Asignar a Rol
                    </button>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deletePermissionModal">
                        <i class="fas fa-trash"></i> Eliminar Permiso
                    </button>
                </div>
            </div>
        </div>

        <!-- Roles Asignados -->
        <div class="col-md-8">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tag"></i> Roles que tienen este Permiso ({{ $permission->roles->count() }})
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#asignarRolModal">
                            <i class="fas fa-plus"></i> Asignar Rol
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($permission->roles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="20%">Nombre</th>
                                        <th width="25%">Display Name</th>
                                        <th width="30%">Descripción</th>
                                        <th width="10%">Usuarios</th>
                                        <th width="5%">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permission->roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td><code>{{ $role->name }}</code></td>
                                        <td>
                                            <span class="badge badge-{{ $role->badge_color }}">
                                                <i class="fas {{ $role->icono }}"></i>
                                                {{ $role->display_name }}
                                            </span>
                                        </td>
                                        <td>{{ \Str::limit($role->description, 40) }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-info">
                                                {{ $role->users->count() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-danger btn-xs" 
                                                    data-toggle="modal" 
                                                    data-target="#removerRolModal{{ $role->id }}"
                                                    title="Remover de este rol">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Remover Rol -->
                                    <div class="modal fade" id="removerRolModal{{ $role->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle"></i> Confirmar Acción
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.permissions.remover-rol', $permission->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                    <div class="modal-body">
                                                        <p>¿Está seguro de que desea remover este permiso del rol?</p>
                                                        <div class="alert alert-info">
                                                            <strong>Permiso:</strong> {{ $permission->display_name }}<br>
                                                            <strong>Rol:</strong> {{ $role->display_name }}<br>
                                                            <strong>Usuarios afectados:</strong> {{ $role->users->count() }}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                            <i class="fas fa-times"></i> Cancelar
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash"></i> Remover
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
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Este permiso no está asignado a ningún rol.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usuarios que tienen este permiso (a través de roles) -->
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Usuarios con este Permiso
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $usuarios = collect();
                        foreach($permission->roles as $role) {
                            $usuarios = $usuarios->merge($role->users);
                        }
                        $usuarios = $usuarios->unique('id');
                    @endphp

                    @if($usuarios->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="20%">Usuario</th>
                                        <th width="25%">Email</th>
                                        <th width="25%">Persona</th>
                                        <th width="20%">Roles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuarios as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td><code>{{ $user->name }}</code></td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->persona)
                                                {{ $user->persona->nombres }} {{ $user->persona->apellidos }}
                                            @else
                                                <span class="text-muted">Sin persona</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $rolesUsuario = $user->roles->filter(function($role) use ($permission) {
                                                    return $role->permissions->contains($permission->id);
                                                });
                                            @endphp
                                            @foreach($rolesUsuario as $rol)
                                                <span class="badge badge-{{ $rol->badge_color }} badge-sm">
                                                    {{ $rol->display_name }}
                                                </span>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No hay usuarios con este permiso.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Permiso -->
    <div class="modal fade" id="editPermissionModal" tabindex="-1" role="dialog">
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
                <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre (Slug) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ old('name', $permission->name) }}" 
                                           required maxlength="100">
                                    <small class="text-muted">Formato: accion.entidad</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre para Mostrar <span class="text-danger">*</span></label>
                                    <input type="text" name="display_name" class="form-control" 
                                           value="{{ old('display_name', $permission->display_name) }}" 
                                           required maxlength="150">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $permission->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Módulo</label>
                                    <select name="module" class="form-control">
                                        <option value="">Sin módulo</option>
                                        <option value="Usuarios" {{ old('module', $permission->module) == 'Usuarios' ? 'selected' : '' }}>Usuarios</option>
                                        <option value="Academico" {{ old('module', $permission->module) == 'Academico' ? 'selected' : '' }}>Academico</option>
                                        <option value="Comunicacion" {{ old('module', $permission->module) == 'Comunicacion' ? 'selected' : '' }}>Comunicacion</option>
                                        <option value="Procesos" {{ old('module', $permission->module) == 'Procesos' ? 'selected' : '' }}>Procesos</option>
                                        <option value="Registros" {{ old('module', $permission->module) == 'Registros' ? 'selected' : '' }}>Registros</option>
                                        <option value="Roles" {{ old('module', $permission->module) == 'Roles' ? 'selected' : '' }}>Roles</option>
                                        <option value="Seguridad" {{ old('module', $permission->module) == 'Seguridad' ? 'selected' : '' }}>Seguridad</option>
                                        <option value="Sistema" {{ old('module', $permission->module) == 'Sistema' ? 'selected' : '' }}>Sistema</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Asignar a Roles</label>
                                    <select name="roles[]" class="form-control select2" multiple>
                                        @foreach($todosLosRoles as $role)
                                            <option value="{{ $role->id }}" 
                                                    {{ $permission->roles->contains($role->id) ? 'selected' : '' }}>
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

    <!-- Modal Asignar Rol -->
    <div class="modal fade" id="asignarRolModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Asignar Permiso a Rol
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.permissions.asignar-rol', $permission->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Seleccionar Rol <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-control" required>
                                <option value="">-- Seleccione un rol --</option>
                                @foreach($todosLosRoles as $role)
                                    @if(!$permission->roles->contains($role->id))
                                        <option value="{{ $role->id }}">
                                            {{ $role->display_name }} 
                                            ({{ $role->users->count() }} usuarios)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            El permiso se asignará al rol seleccionado.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Asignar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="deletePermissionModal" tabindex="-1" role="dialog">
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
                <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>¿Está seguro de que desea eliminar este permiso?</p>
                        <div class="alert alert-info">
                            <strong>Nombre:</strong> {{ $permission->name }}<br>
                            <strong>Display:</strong> {{ $permission->display_name }}<br>
                            <strong>Módulo:</strong> {{ $permission->module ?? 'N/A' }}
                        </div>
                        @if($permission->roles->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i>
                            Este permiso está asignado a <strong>{{ $permission->roles->count() }} rol(es)</strong>. 
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
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <style>
        .badge-lg {
            font-size: 1.2rem;
            padding: 0.6rem 1rem;
        }
        .badge-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .info-box-number {
            font-size: 1.5rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Inicializar Select2 cuando se abre el modal
            $('#editPermissionModal').on('shown.bs.modal', function () {
                $(this).find('.select2').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Seleccione roles',
                    allowClear: true,
                    dropdownParent: $(this)
                });
            });

            // Limpiar Select2 al cerrar modal
            $('#editPermissionModal').on('hidden.bs.modal', function () {
                $(this).find('.select2').select2('destroy');
            });

            @if(session('mensaje'))
                Swal.fire({
                    icon: '{{ session('icono') }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            @endif
        });
    </script>
@stop