@extends('layouts.admin')

@section('title', 'Detalle de Usuario')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-user-circle text-primary"></i>
        <span class="fw-bold fs-4">Detalle del Usuario</span>
    </div>
@stop

@section('content')

    <div class="row g-4">

        {{-- ===================== COLUMNA IZQUIERDA ===================== --}}
        <div class="col-md-4">

            {{-- TARJETA DE PERFIL --}}
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <span class="avatar avatar-xl rounded-circle"
                              style="background-image: url('{{ $usuario->avatar }}'); width: 100px; height: 100px;"></span>
                    </div>
                    <h3 class="mb-1">{{ $usuario->nombre_completo ?? $usuario->name }}</h3>
                    <p class="text-muted mb-2">{{ $usuario->email }}</p>
                    <div class="mb-3">
                        @if($usuario->esta_activo)
                            <span class="badge bg-success-lt text-success me-1">Activo</span>
                        @else
                            <span class="badge bg-danger-lt text-danger me-1">Inactivo</span>
                        @endif
                        @if($usuario->esta_verificado)
                            <span class="badge bg-blue-lt text-blue">Verificado</span>
                        @else
                            <span class="badge bg-warning-lt text-warning">Sin verificar</span>
                        @endif
                    </div>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal">
                            <i class="fas fa-edit me-1"></i> Editar
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm card-table">
                        <tbody>
                            <tr>
                                <td class="text-muted">ID</td>
                                <td class="fw-semibold text-end">{{ $usuario->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Username</td>
                                <td class="text-end"><code>{{ $usuario->name }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Roles</td>
                                <td class="text-end">
                                    <span class="badge bg-blue-lt text-blue">{{ $usuario->roles->count() }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Permisos</td>
                                <td class="text-end">
                                    <span class="badge bg-purple-lt text-purple">{{ $usuario->permisos->count() }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Registrado</td>
                                <td class="text-end">{{ $usuario->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ACCIONES RÁPIDAS --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h3>
                </div>
                <div class="card-body d-grid gap-2">

                    {{-- Verificar / Quitar verificación --}}
                    @if(!$usuario->esta_verificado)
                        <form action="{{ route('admin.usuarios.verificar-email', $usuario->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">
                                <i class="fas fa-envelope-open me-1"></i> Verificar Email
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.usuarios.quitar-verificacion', $usuario->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-envelope me-1"></i> Quitar Verificación
                            </button>
                        </form>
                    @endif

                    {{-- Cambiar contraseña --}}
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="fas fa-key me-1"></i> Cambiar Contraseña
                    </button>

                    {{-- Activar / Desactivar --}}
                    @if($usuario->esta_activo)
                        <form action="{{ route('admin.usuarios.desactivar', $usuario->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-ban me-1"></i> Desactivar Usuario
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.usuarios.activar', $usuario->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-1"></i> Activar Usuario
                            </button>
                        </form>
                    @endif

                    {{-- Eliminar --}}
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                        <i class="fas fa-trash me-1"></i> Eliminar Usuario
                    </button>
                </div>
            </div>
        </div>

        {{-- ===================== COLUMNA DERECHA ===================== --}}
        <div class="col-md-8">

            {{-- PERSONA VINCULADA --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-id-card me-2"></i>Persona Vinculada</h3>
                    <div class="card-options">
                        @if($usuario->persona)
                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#changePersonaModal">
                                <i class="fas fa-exchange-alt me-1"></i> Cambiar
                            </button>
                            <form action="{{ route('admin.usuarios.desvincular-persona', $usuario->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Desvincular persona?')">
                                    <i class="fas fa-unlink me-1"></i> Desvincular
                                </button>
                            </form>
                        @else
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#vincularPersonaModal">
                                <i class="fas fa-link me-1"></i> Vincular Persona
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($usuario->persona)
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Nombre Completo</label>
                                <p class="fw-semibold mb-0">{{ $usuario->persona->nombres }} {{ $usuario->persona->apellidos }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">DNI</label>
                                <p class="fw-semibold mb-0"><code>{{ $usuario->persona->dni }}</code></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Fecha de Nacimiento</label>
                                <p class="fw-semibold mb-0">
                                    {{ \Carbon\Carbon::parse($usuario->persona->fecha_nacimiento)->format('d/m/Y') }}
                                    <span class="text-muted">({{ \Carbon\Carbon::parse($usuario->persona->fecha_nacimiento)->age }} años)</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Género</label>
                                <p class="mb-0">
                                    @if($usuario->persona->genero == 'M')
                                        <span class="badge bg-blue-lt text-blue">Masculino</span>
                                    @elseif($usuario->persona->genero == 'F')
                                        <span class="badge bg-pink-lt text-pink">Femenino</span>
                                    @else
                                        <span class="badge bg-secondary-lt">Otro</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Teléfono</label>
                                <p class="fw-semibold mb-0">{{ $usuario->persona->telefono ?? 'No registrado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Teléfono Emergencia</label>
                                <p class="fw-semibold mb-0">{{ $usuario->persona->telefono_emergencia ?? 'No registrado' }}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small mb-1">Dirección</label>
                                <p class="fw-semibold mb-0">{{ $usuario->persona->direccion ?? 'No registrada' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Este usuario no tiene una persona vinculada.
                        </div>
                    @endif
                </div>
            </div>

            {{-- ROLES ASIGNADOS --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tag me-2"></i>Roles Asignados
                        <span class="badge bg-blue-lt text-blue ms-1">{{ $usuario->roles->count() }}</span>
                    </h3>
                    <div class="card-options">
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editRolesModal">
                            <i class="fas fa-edit me-1"></i> Gestionar Roles
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($usuario->roles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-vcenter table-sm">
                                <thead>
                                    <tr>
                                        <th>Rol</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Permisos</th>
                                        <th class="text-center">Usuarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usuario->roles as $rol)
                                    <tr>
                                        <td>
                                            <span class="badge bg-blue-lt text-blue">
                                                <i class="fas {{ $rol->icono ?? 'fa-user-tag' }} me-1"></i>
                                                {{ $rol->display_name }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ \Str::limit($rol->description, 50) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-info-lt text-info">{{ $rol->permissions->count() }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-lt text-warning">{{ $rol->users->count() }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Este usuario no tiene roles asignados.
                        </div>
                    @endif
                </div>
            </div>

            {{-- PERMISOS --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key me-2"></i>Permisos
                        <span class="badge bg-purple-lt text-purple ms-1">{{ $usuario->permisos->count() }}</span>
                    </h3>
                </div>
                <div class="card-body">
                    @if($usuario->permisos->count() > 0)
                        @php $permisosAgrupados = $usuario->permisos->groupBy('module'); @endphp
                        @foreach($permisosAgrupados as $modulo => $permisos)
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-folder text-muted"></i>
                                    <strong>{{ $modulo ?? 'Sin Módulo' }}</strong>
                                    <span class="badge bg-info-lt text-info">{{ $permisos->count() }}</span>
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($permisos as $permiso)
                                        <span class="badge bg-secondary-lt text-secondary">
                                            <i class="fas fa-check me-1"></i>{{ $permiso->display_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @if(!$loop->last)<hr class="my-2">@endif
                        @endforeach
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Este usuario no tiene permisos. Asígnale roles para otorgarle permisos.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== MODALES ===================== --}}

    {{-- MODAL EDITAR USUARIO --}}
    <div class="modal modal-blur fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CAMBIAR CONTRASEÑA --}}
    <div class="modal modal-blur fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-key me-2"></i>Cambiar Contraseña</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.usuarios.cambiar-password', $usuario->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="nueva_password" class="form-control" required placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="nueva_password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Cambiar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL VINCULAR PERSONA --}}
    <div class="modal modal-blur fade" id="vincularPersonaModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-link me-2"></i>Vincular Persona</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.usuarios.vincular-persona', $usuario->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label">Seleccionar Persona <span class="text-danger">*</span></label>
                        <select name="persona_id" class="form-select select2-vincular" required>
                            <option value="">-- Seleccione una persona --</option>
                            @foreach($personasSinUsuario as $persona)
                                <option value="{{ $persona->id }}">
                                    {{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Solo personas sin usuario asignado</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-link me-1"></i>Vincular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL CAMBIAR PERSONA --}}
    <div class="modal modal-blur fade" id="changePersonaModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>Cambiar Persona Vinculada</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.usuarios.vincular-persona', $usuario->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Al cambiar la persona, la anterior se desvinculará.
                        </div>
                        <label class="form-label">Nueva Persona <span class="text-danger">*</span></label>
                        <select name="persona_id" class="form-select select2-cambiar" required>
                            <option value="">-- Seleccione una persona --</option>
                            @foreach($personasSinUsuario as $persona)
                                <option value="{{ $persona->id }}">
                                    {{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning"><i class="fas fa-exchange-alt me-1"></i>Cambiar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL GESTIONAR ROLES --}}
    <div class="modal modal-blur fade" id="editRolesModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-user-tag me-2"></i>Gestionar Roles</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="name" value="{{ $usuario->name }}">
                    <input type="hidden" name="email" value="{{ $usuario->email }}">
                    @if($usuario->persona)
                        <input type="hidden" name="persona_id" value="{{ $usuario->persona->id }}">
                    @endif
                    <div class="modal-body">
                        <div class="row g-2">
                            @foreach($todosLosRoles as $role)
                            <div class="col-md-6">
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="roles[]" value="{{ $role->id }}"
                                           {{ $usuario->roles->contains($role->id) ? 'checked' : '' }}>
                                    <span class="form-check-label">
                                        <span class="badge bg-blue-lt text-blue me-1">{{ $role->display_name }}</span>
                                        <small class="text-muted">{{ \Str::limit($role->description, 40) }}</small>
                                    </span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Guardar Roles</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL ELIMINAR --}}
    <div class="modal modal-blur fade" id="deleteUserModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <div class="modal-body">
                        <p class="text-center mb-2">¿Eliminar este usuario?</p>
                        <div class="alert alert-info">
                            <strong>{{ $usuario->name }}</strong><br>
                            <small>{{ $usuario->email }}</small>
                        </div>
                        @if($usuario->persona)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            La persona vinculada <strong>NO</strong> se eliminará.
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger w-50"><i class="fas fa-trash me-1"></i>Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2-vincular').select2({
                dropdownParent: $('#vincularPersonaModal'),
                width: '100%',
                placeholder: 'Seleccione una persona',
                allowClear: true
            });
            $('.select2-cambiar').select2({
                dropdownParent: $('#changePersonaModal'),
                width: '100%',
                placeholder: 'Seleccione una persona',
                allowClear: true
            });

            @if(session('mensaje'))
                Swal.fire({
                    icon: "{{ session('icono') }}",
                    title: "{{ session('mensaje') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@stop