@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Usuario: {{ $usuario->name }}</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <!-- Columna Izquierda: Información del Usuario -->
        <div class="col-md-4">
            <!-- Tarjeta de Perfil -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" 
                             src="{{ $usuario->avatar }}" 
                             alt="Avatar"
                             style="width: 150px; height: 150px;">
                    </div>

                    <h3 class="profile-username text-center">{{ $usuario->nombre_completo }}</h3>

                    <p class="text-muted text-center">
                        <span class="badge badge-{{ $usuario->badge_tipo }} badge-lg">
                            <i class="fas {{ $usuario->icono_tipo }}"></i>
                            {{ $usuario->tipo_usuario }}
                        </span>
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>ID Usuario</b> 
                            <a class="float-right">{{ $usuario->id }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Username</b> 
                            <a class="float-right"><code>{{ $usuario->name }}</code></a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> 
                            <a class="float-right">{{ $usuario->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Estado</b> 
                            <span class="float-right">
                                <span class="badge badge-{{ $usuario->badge_estado }}">
                                    {{ $usuario->texto_estado }}
                                </span>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Email Verificado</b> 
                            <span class="float-right">
                                @if($usuario->esta_verificado)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Sí
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-times"></i> No
                                    </span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Roles</b> 
                            <span class="float-right">{{ $usuario->roles->count() }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Permisos</b> 
                            <span class="float-right">{{ $usuario->permisos->count() }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Registrado</b> 
                            <a class="float-right">{{ $usuario->created_at->format('d/m/Y') }}</a>
                        </li>
                    </ul>

                    <div class="text-center">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#editUserModal">
                            <i class="fas fa-edit"></i> Editar Usuario
                        </button>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    @if(!$usuario->esta_verificado)
                        <form action="{{ route('admin.usuarios.verificar-email', $usuario->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fas fa-envelope-open"></i> Verificar Email
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.usuarios.quitar-verificacion', $usuario->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-block">
                                <i class="fas fa-envelope"></i> Quitar Verificación
                            </button>
                        </form>
                    @endif

                    <button type="button" class="btn btn-primary btn-block mb-2" data-toggle="modal" data-target="#changePasswordModal">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>

                    @if($usuario->esta_activo)
                        <form action="{{ route('admin.usuarios.desactivar', $usuario->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-ban"></i> Desactivar Usuario
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.usuarios.activar', $usuario->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Activar Usuario
                            </button>
                        </form>
                    @endif

                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteUserModal">
                        <i class="fas fa-trash"></i> Eliminar Usuario
                    </button>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Detalles -->
        <div class="col-md-8">
            <!-- Información de Persona -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-id-card"></i> Información de Persona Vinculada</h3>
                    <div class="card-tools">
                        @if($usuario->persona)
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#changePersonaModal">
                                <i class="fas fa-exchange-alt"></i> Cambiar Persona
                            </button>
                            <form action="{{ route('admin.usuarios.desvincular-persona', $usuario->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Desvincular persona?')">
                                    <i class="fas fa-unlink"></i> Desvincular
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#vincularPersonaModal">
                                <i class="fas fa-link"></i> Vincular Persona
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($usuario->persona)
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fas fa-user"></i> Nombre Completo</strong>
                                <p class="text-muted">
                                    {{ $usuario->persona->nombres }} {{ $usuario->persona->apellidos }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-id-badge"></i> DNI</strong>
                                <p class="text-muted">{{ $usuario->persona->dni }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fas fa-birthday-cake"></i> Fecha de Nacimiento</strong>
                                <p class="text-muted">
                                    {{ \Carbon\Carbon::parse($usuario->persona->fecha_nacimiento)->format('d/m/Y') }}
                                    ({{ \Carbon\Carbon::parse($usuario->persona->fecha_nacimiento)->age }} años)
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-venus-mars"></i> Género</strong>
                                <p class="text-muted">
                                    @if($usuario->persona->genero == 'M')
                                        <span class="badge badge-primary">Masculino</span>
                                    @elseif($usuario->persona->genero == 'F')
                                        <span class="badge badge-danger">Femenino</span>
                                    @else
                                        <span class="badge badge-secondary">Otro</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fas fa-phone"></i> Teléfono</strong>
                                <p class="text-muted">{{ $usuario->persona->telefono ?? 'No registrado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fas fa-phone-alt"></i> Teléfono Emergencia</strong>
                                <p class="text-muted">{{ $usuario->persona->telefono_emergencia ?? 'No registrado' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <strong><i class="fas fa-map-marker-alt"></i> Dirección</strong>
                                <p class="text-muted">{{ $usuario->persona->direccion ?? 'No registrada' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Este usuario no tiene una persona vinculada.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Roles Asignados -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-tag"></i> Roles Asignados ({{ $usuario->roles->count() }})</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editRolesModal">
                            <i class="fas fa-edit"></i> Gestionar Roles
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($usuario->roles->count() > 0)
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Descripción</th>
                                    <th>Permisos</th>
                                    <th>Usuarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuario->roles as $rol)
                                <tr>
                                    <td>
                                        <span class="badge badge-{{ $rol->badge_color }}">
                                            <i class="fas {{ $rol->icono }}"></i>
                                            {{ $rol->display_name }}
                                        </span>
                                    </td>
                                    <td>{{ \Str::limit($rol->description, 50) }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $rol->permissions->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-warning">{{ $rol->users->count() }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Este usuario no tiene roles asignados.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Permisos (a través de roles) -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-key"></i> Permisos ({{ $usuario->permisos->count() }})</h3>
                </div>
                <div class="card-body">
                    @if($usuario->permisos->count() > 0)
                        @php
                            $permisosAgrupados = $usuario->permisos->groupBy('module');
                        @endphp
                        @foreach($permisosAgrupados as $modulo => $permisos)
                        <div class="mb-3">
                            <h6>
                                <i class="fas fa-folder"></i> 
                                <strong>{{ $modulo ?? 'Sin Módulo' }}</strong>
                                <span class="badge badge-info">{{ $permisos->count() }}</span>
                            </h6>
                            <div class="row">
                                @foreach($permisos as $permiso)
                                <div class="col-md-6">
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-check"></i> {{ $permiso->display_name }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Este usuario no tiene permisos asignados. Asígnale roles para otorgarle permisos.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i> Editar Usuario
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
                                           value="{{ old('name', $usuario->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" 
                                           value="{{ old('email', $usuario->email) }}" required>
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

    <!-- Modal Cambiar Contraseña -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.usuarios.cambiar-password', $usuario->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nueva Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="nueva_password" class="form-control" 
                                   required placeholder="••••••••">
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>
                        <div class="form-group">
                            <label>Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="nueva_password_confirmation" class="form-control" 
                                   required placeholder="••••••••">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cambiar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Vincular Persona -->
    <div class="modal fade" id="vincularPersonaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-link"></i> Vincular Persona
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.usuarios.vincular-persona', $usuario->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Seleccionar Persona <span class="text-danger">*</span></label>
                            <select name="persona_id" class="form-control select2" required>
                                <option value="">-- Seleccione una persona --</option>
                                @foreach($personasSinUsuario as $persona)
                                    <option value="{{ $persona->id }}">
                                        {{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Solo docentes, tutores y administradores sin usuario asignado</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-link"></i> Vincular
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Cambiar Persona -->
    <div class="modal fade" id="changePersonaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-exchange-alt"></i> Cambiar Persona Vinculada
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.usuarios.vincular-persona', $usuario->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Al cambiar la persona, la anterior se desvinculará.
                        </div>
                        <div class="form-group">
                            <label>Seleccionar Nueva Persona <span class="text-danger">*</span></label>
                            <select name="persona_id" class="form-control select2" required>
                                <option value="">-- Seleccione una persona --</option>
                                @foreach($personasSinUsuario as $persona)
                                    <option value="{{ $persona->id }}">
                                        {{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Solo docentes, tutores y administradores sin usuario asignado</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-exchange-alt"></i> Cambiar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Gestionar Roles -->
    <div class="modal fade" id="editRolesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        <i class="fas fa-user-tag"></i> Gestionar Roles
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $usuario->name }}">
                    <input type="hidden" name="email" value="{{ $usuario->email }}">
                    @if($usuario->persona)
                        <input type="hidden" name="persona_id" value="{{ $usuario->persona->id }}">
                    @endif
                    
                    <div class="modal-body">
                        @foreach($todosLosRoles as $role)
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" 
                                   class="custom-control-input" 
                                   id="role_{{ $role->id }}" 
                                   name="roles[]" 
                                   value="{{ $role->id }}"
                                   {{ $usuario->roles->contains($role->id) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="role_{{ $role->id }}">
                                <span class="badge badge-{{ $role->badge_color }}">
                                    <i class="fas {{ $role->icono }}"></i>
                                    {{ $role->display_name }}
                                </span>
                                <small class="text-muted">- {{ $role->description }}</small>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Roles
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog">
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
                            <strong>Email:</strong> {{ $usuario->email }}
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
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
        }
        .profile-user-img {
            border: 3px solid #adb5bd;
            margin: 0 auto;
            padding: 3px;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
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
        });
    </script>
@stop