@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Usuarios</b></h1>
    <hr>
@stop

@section('content')
    <!-- Estadísticas -->
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $estadisticas['total'] }}</h3>
                    <p>Total Usuarios</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $estadisticas['activos'] }}</h3>
                    <p>Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estadisticas['verificados'] }}</h3>
                    <p>Verificados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $estadisticas['con_persona'] }}</h3>
                    <p>Con Persona Vinculada</p>
                </div>
                <div class="icon">
                    <i class="fas fa-id-card"></i>
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
            <form action="{{ route('admin.usuarios.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Buscar</label>
                            <input type="text" name="buscar" class="form-control" 
                                   value="{{ request('buscar') }}" 
                                   placeholder="Nombre, email, DNI...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Rol</label>
                            <select name="rol" class="form-control">
                                <option value="">Todos</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('rol') == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado" class="form-control">
                                <option value="">Todos</option>
                                <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Verificado</label>
                            <select name="verificado" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('verificado') === '1' ? 'selected' : '' }}>Verificados</option>
                                <option value="0" {{ request('verificado') === '0' ? 'selected' : '' }}>No verificados</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tiene Persona</label>
                            <select name="tiene_persona" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('tiene_persona') === '1' ? 'selected' : '' }}>Con persona</option>
                                <option value="0" {{ request('tiene_persona') === '0' ? 'selected' : '' }}>Sin persona</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Usuarios Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                            <i class="fas fa-plus"></i> Crear Usuario
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="usersTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 3%">ID</th>
                                <th style="width: 8%">Avatar</th>
                                <th style="width: 18%">Usuario</th>
                                <th style="width: 18%">Persona</th>
                                <th style="width: 13%">Roles</th>
                                <th style="width: 8%">Estado</th>
                                <th style="width: 8%">Verificado</th>
                                <th style="width: 10%">Registro</th>
                                <th style="width: 14%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td class="text-center">{{ $usuario->id }}</td>
                                <td class="text-center">
                                    <img src="{{ $usuario->avatar }}" 
                                         alt="Avatar" 
                                         class="img-circle elevation-2" 
                                         width="40" height="40">
                                </td>
                                <td>
                                    <strong>{{ $usuario->name }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope"></i> {{ $usuario->email }}
                                    </small>
                                </td>
                                <td>
                                    @if($usuario->persona)
                                        <strong>{{ $usuario->persona->nombres }} {{ $usuario->persona->apellidos }}</strong><br>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card"></i> {{ $usuario->persona->dni }}
                                        </small>
                                    @else
                                        <span class="badge badge-warning">Sin persona vinculada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($usuario->roles->count() > 0)
                                        @foreach($usuario->roles as $rol)
                                            <span class="badge badge-{{ $rol->badge_color }} badge-sm">
                                                <i class="fas {{ $rol->icono }}"></i>
                                                {{ $rol->display_name }}
                                            </span><br>
                                        @endforeach
                                    @else
                                        <span class="badge badge-secondary">Sin roles</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($usuario->esta_activo)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($usuario->esta_verificado)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Sí
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-times"></i> No
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $usuario->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.usuarios.show', $usuario->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editUserModal{{ $usuario->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteUserModal{{ $usuario->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editUserModal{{ $usuario->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Usuario: {{ $usuario->name }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nombre de Usuario <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control" 
                                                                   value="{{ old('name', $usuario->name) }}" 
                                                                   required maxlength="255">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email <span class="text-danger">*</span></label>
                                                            <input type="email" name="email" class="form-control" 
                                                                   value="{{ old('email', $usuario->email) }}" 
                                                                   required maxlength="255">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nueva Contraseña (dejar vacío para no cambiar)</label>
                                                            <input type="password" name="password" class="form-control" 
                                                                   placeholder="••••••••">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Confirmar Contraseña</label>
                                                            <input type="password" name="password_confirmation" class="form-control" 
                                                                   placeholder="••••••••">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Vincular con Persona</label>
                                                            <select name="persona_id" class="form-control select2">
                                                                <option value="">Sin persona</option>
                                                                @foreach($personasSinUsuario as $persona)
                                                                    <option value="{{ $persona->id }}" 
                                                                            {{ $usuario->persona && $usuario->persona->id == $persona->id ? 'selected' : '' }}>
                                                                        {{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <hr>
                                                <h5><i class="fas fa-user-tag"></i> Asignar Roles</h5>
                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        @foreach($roles as $role)
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" 
                                                                   class="custom-control-input" 
                                                                   id="role_edit_{{ $usuario->id }}_{{ $role->id }}" 
                                                                   name="roles[]" 
                                                                   value="{{ $role->id }}"
                                                                   {{ $usuario->roles->contains($role->id) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="role_edit_{{ $usuario->id }}_{{ $role->id }}">
                                                                <span class="badge badge-{{ $role->badge_color }}">
                                                                    <i class="fas {{ $role->icono }}"></i>
                                                                    {{ $role->display_name }}
                                                                </span>
                                                            </label>
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
                            <div class="modal fade" id="deleteUserModal{{ $usuario->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar este usuario?</p>
                                                <div class="alert alert-info">
                                                    <strong>Usuario:</strong> {{ $usuario->name }}<br>
                                                    <strong>Email:</strong> {{ $usuario->email }}<br>
                                                    @if($usuario->persona)
                                                        <strong>Persona:</strong> {{ $usuario->persona->nombres }} {{ $usuario->persona->apellidos }}
                                                    @endif
                                                </div>
                                                @if($usuario->persona)
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    La persona vinculada NO se eliminará, solo se desvinculará.
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
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Crear Nuevo Usuario
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre de Usuario <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" 
                                           value="{{ old('name') }}" 
                                           required maxlength="255"
                                           placeholder="ej: juan.perez">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" 
                                           value="{{ old('email') }}" 
                                           required maxlength="255"
                                           placeholder="ej: juan@example.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" 
                                           required placeholder="••••••••">
                                    <small class="text-muted">Mínimo 8 caracteres</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmar Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" 
                                           required placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Vincular con Persona (Opcional)</label>
                                    <select name="persona_id" class="form-control select2">
                                        <option value="">Sin persona</option>
                                        @foreach($personasSinUsuario as $persona)
                                            <option value="{{ $persona->id }}">
                                                {{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Solo se muestran personas sin usuario asignado</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="verificar_email" name="verificar_email" value="1">
                                    <label class="custom-control-label" for="verificar_email">
                                        Marcar email como verificado
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <h5><i class="fas fa-user-tag"></i> Asignar Roles (Opcional)</h5>
                        
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($roles as $role)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="role_create_{{ $role->id }}" 
                                           name="roles[]" 
                                           value="{{ $role->id }}">
                                    <label class="custom-control-label" for="role_create_{{ $role->id }}">
                                        <span class="badge badge-{{ $role->badge_color }}">
                                            <i class="fas {{ $role->icono }}"></i>
                                            {{ $role->display_name }}
                                        </span>
                                    </label>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <style>
        .badge-sm {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
            margin-bottom: 2px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
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
                placeholder: 'Seleccione una persona',
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
                $('#editUserModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('name') || $errors->has('email') || $errors->has('password'))
                $('#createUserModal').modal('show');
            @endif
        });
    </script>
@stop