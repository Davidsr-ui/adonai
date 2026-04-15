@extends('layouts.admin')

@section('title', 'Detalle de Usuario')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-user-circle text-primary"></i>
        <span class="fw-bold fs-4">Detalle del Usuario</span>
    </div>
@stop

@section('content')
<div class="row g-3">
    {{-- COLUMNA IZQUIERDA (Ajustada para ser más compacta en pantallas grandes) --}}
    <div class="col-12 col-md-5 col-lg-4">
        {{-- TARJETA DE PERFIL --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body text-center py-3">
                <div class="mb-2">
                    {{-- Avatar con letra centrada vertical y horizontalmente --}}
                    <span class="avatar rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center" style="background-image: url('{{ $usuario->avatar }}'); width: 65px; height: 65px; border: 2px solid #e9ecef; background-size: cover; background-position: center; font-size: 1.5rem; font-weight: bold; line-height: 1;"></span>
                </div>
                <h5 class="mb-1 fs-6 fw-bold">{{ $usuario->nombre_completo ?? $usuario->name }}</h5>
                <p class="text-muted small mb-2 text-truncate" title="{{ $usuario->email }}">{{ $usuario->email }}</p>
                <div class="mb-3">
                    @if($usuario->persona && $usuario->persona->estado == 'Activo')
                        <span class="badge bg-success px-3 py-1">Activo</span>
                    @else
                        <span class="badge bg-danger px-3 py-1">Inactivo</span>
                    @endif
                </div>
                <div class="d-flex gap-2 justify-content-center mb-3">
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm rounded-pill w-100">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <button class="btn btn-outline-success btn-sm rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#editUserModal">
                        <i class="fas fa-edit me-1"></i> Editar
                    </button>
                </div>
            </div>
            <div class="table-responsive px-2 pb-2">
                <table class="table table-sm table-borderless m-0">
                    <tbody>
                        <tr>
                            <td class="text-muted small">ID</td>
                            <td class="fw-semibold text-end small">{{ $usuario->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Usuario</td>
                            <td class="text-end small"><code>{{ $usuario->name }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Roles</td>
                            <td class="text-end small"><span class="badge bg-info text-dark">{{ $usuario->roles->count() }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Permisos</td>
                            <td class="text-end small"><span class="badge bg-cyan text-white">{{ $usuario->getAllPermissions()->count() }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted small">Registrado</td>
                            <td class="text-end small">{{ $usuario->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ACCIONES RÁPIDAS --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-2">
                <h3 class="card-title h6 mb-0 text-muted"><i class="fas fa-bolt me-2 text-warning"></i>Acciones Rápidas</h3>
            </div>
            <div class="card-body p-2 d-grid gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-key me-1"></i> Contraseña
                </button>

                @if($usuario->persona && $usuario->persona->estado == 'Activo')
                    <form action="{{ route('admin.usuarios.desactivar', $usuario->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100 rounded-pill">
                            <i class="fas fa-ban me-1"></i> Desactivar
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.usuarios.activar', $usuario->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm w-100 rounded-pill">
                            <i class="fas fa-check me-1"></i> Activar
                        </button>
                    </form>
                @endif

                <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                    <i class="fas fa-trash me-1"></i> Eliminar
                </button>
            </div>
        </div>
    </div>

    {{-- COLUMNA DERECHA --}}
    <div class="col-12 col-md-7 col-lg-8">
        {{-- PERSONA VINCULADA --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title h6 mb-0"><i class="fas fa-id-card me-2 text-primary"></i>Persona Vinculada</h3>
                <div class="card-tools">
                    @if($usuario->persona)
                        <button class="btn btn-outline-warning btn-sm rounded-pill me-1" data-bs-toggle="modal" data-bs-target="#changePersonaModal">
                            <i class="fas fa-exchange-alt me-1"></i> Cambiar
                        </button>
                        <form action="{{ route('admin.usuarios.desvincular-persona', $usuario->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('¿Desvincular persona?')">
                                <i class="fas fa-unlink me-1"></i> Desvincular
                            </button>
                        </form>
                    @else
                        <button class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#vincularPersonaModal">
                            <i class="fas fa-link me-1"></i> Vincular Persona
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body py-3">
                @if($usuario->persona)
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label text-muted small mb-0">Nombre Completo</label>
                            <p class="fw-semibold mb-0">{{ $usuario->persona->nombres }} {{ $usuario->persona->apellidos }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label text-muted small mb-0">DNI</label>
                            <p class="fw-semibold mb-0"><code>{{ $usuario->persona->dni }}</code></p>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label text-muted small mb-0">Fecha de Nacimiento</label>
                            <p class="fw-semibold mb-0">
                                {{ \Carbon\Carbon::parse($usuario->persona->fecha_nacimiento)->format('d/m/Y') }}
                                <span class="text-muted small">({{ \Carbon\Carbon::parse($usuario->persona->fecha_nacimiento)->age }} años)</span>
                            </p>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label text-muted small mb-0">Género</label>
                            <p class="mb-0 mt-1">
                                @if($usuario->persona->genero == 'M')
                                    <span class="badge bg-primary">Masculino</span>
                                @elseif($usuario->persona->genero == 'F')
                                    <span class="badge bg-pink text-white">Femenino</span>
                                @else
                                    <span class="badge bg-secondary">Otro</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label text-muted small mb-0">Teléfono</label>
                            <p class="fw-semibold mb-0">{{ $usuario->persona->telefono ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label text-muted small mb-0">Teléfono Emergencia</label>
                            <p class="fw-semibold mb-0">{{ $usuario->persona->telefono_emergencia ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small mb-0">Dirección</label>
                            <p class="fw-semibold mb-0">{{ $usuario->persona->direccion ?? 'No registrada' }}</p>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning py-2 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Este usuario no tiene una persona vinculada.
                    </div>
                @endif
            </div>
        </div>

        {{-- ROLES ASIGNADOS --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title h6 mb-0">
                    <i class="fas fa-user-tag me-2 text-info"></i>Roles Asignados
                    <span class="badge bg-info text-dark ms-1">{{ $usuario->roles->count() }}</span>
                </h3>
                <div class="card-tools">
                    <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editRolesModal">
                        <i class="fas fa-edit me-1"></i> Gestionar Roles
                    </button>
                </div>
            </div>
            <div class="card-body py-2">
                @if($usuario->roles->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Rol</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Permisos del Rol</th>
                                    <th class="text-center">Usuarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuario->roles as $rol)
                                <tr>
                                    <td><span class="badge bg-info text-dark">{{ $rol->display_name }}</span></td>
                                    <td class="text-muted small">{{ \Str::limit($rol->description, 50) }}</td>
                                    <td class="text-center"><span class="badge bg-cyan text-white">{{ $rol->permissions->count() }}</span></td>
                                    <td class="text-center"><span class="badge bg-warning text-dark">{{ $rol->users->count() }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning py-2 mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Este usuario no tiene roles asignados.
                    </div>
                @endif
            </div>
        </div>

        {{-- PERMISOS EFECTIVOS — 3 por fila --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header">
                <h3 class="card-title h6 mb-0">
                    <i class="fas fa-key me-2 text-warning"></i>Permisos Efectivos
                    <span class="badge bg-cyan text-white ms-1">{{ $usuario->getAllPermissions()->count() }}</span>
                </h3>
            </div>
            <div class="card-body py-3">
                @php $permisosEfectivos = $usuario->getAllPermissions(); @endphp
                @if($permisosEfectivos->count() > 0)
                    @php $permisosAgrupados = $permisosEfectivos->groupBy('module'); @endphp
                    @foreach($permisosAgrupados as $modulo => $permisos)
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2 pb-1 border-bottom">
                                <i class="fas fa-folder-open text-muted"></i>
                                <strong class="small text-uppercase text-secondary">{{ $modulo ?? 'Sin Módulo' }}</strong>
                                <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary">{{ $permisos->count() }}</span>
                            </div>
                            {{-- Grilla forzada de 3 en 3 --}}
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                                @foreach($permisos as $permiso)
                                    <div class="col">
                                        <div class="border rounded px-2 py-1 bg-light text-dark text-truncate small custom-permission-item">
                                            <i class="fas fa-check text-success me-1"></i> {{ $permiso->display_name }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info py-2 mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Este usuario no tiene permisos efectivos. Asígnale roles para otorgarle permisos.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODALES ===================== --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
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
                    <button type="submit" class="btn btn-success rounded-pill"><i class="fas fa-save me-1"></i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="changePasswordModal" tabindex="-1">
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
                    <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-save me-1"></i>Cambiar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="vincularPersonaModal" tabindex="-1">
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
                    <select name="persona_id" class="form-select" id="select2-vincular" required>
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
                    <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-link me-1"></i>Vincular</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="changePersonaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>Cambiar Persona Vinculada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.usuarios.vincular-persona', $usuario->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning py-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Al cambiar la persona, la anterior se desvinculará.
                    </div>
                    <label class="form-label">Nueva Persona <span class="text-danger">*</span></label>
                    <select name="persona_id" class="form-select" id="select2-cambiar" required>
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
                    <button type="submit" class="btn btn-warning rounded-pill"><i class="fas fa-exchange-alt me-1"></i>Cambiar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL GESTIONAR ROLES — rediseñado --}}
<div class="modal fade" id="editRolesModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:rgba(255,255,255,0.2);">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">Gestionar Roles</h5>
                        <small class="opacity-75">Selecciona los roles para <strong>{{ $usuario->name }}</strong></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="name" value="{{ $usuario->name }}">
                <input type="hidden" name="email" value="{{ $usuario->email }}">
                @if($usuario->persona)
                    <input type="hidden" name="persona_id" value="{{ $usuario->persona->id }}">
                @endif
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        <i class="fas fa-info-circle me-1 text-info"></i>
                        Los permisos efectivos del usuario se calcularán automáticamente según los roles asignados.
                    </p>
                    <div class="row g-3">
                        @foreach($todosLosRoles as $role)
                            <div class="col-12 col-sm-6">
                                <label class="role-card w-100 d-flex align-items-start gap-3 p-3 rounded border cursor-pointer {{ $usuario->roles->contains($role->id) ? 'role-card--active' : '' }}" for="role_{{ $role->id }}" style="cursor:pointer; transition: all 0.2s;">
                                    <input class="form-check-input mt-1 flex-shrink-0 role-checkbox" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ $usuario->roles->contains($role->id) ? 'checked' : '' }}>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-info text-dark">{{ $role->display_name }}</span>
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary small">
                                                <i class="fas fa-key me-1"></i>{{ $role->permissions->count() }} permisos
                                            </span>
                                        </div>
                                        @if($role->description)
                                            <p class="text-muted small mb-0 text-truncate" title="{{ $role->description }}">{{ \Str::limit($role->description, 55) }}</p>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn rounded-pill text-white" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                        <i class="fas fa-save me-1"></i> Guardar Roles
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1">
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
                    <div class="alert alert-info py-2">
                        <strong>{{ $usuario->name }}</strong><br>
                        <small>{{ $usuario->email }}</small>
                    </div>
                    @if($usuario->persona)
                    <div class="alert alert-warning py-2">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        La persona vinculada <strong>NO</strong> se eliminará.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger w-50 rounded-pill"><i class="fas fa-trash me-1"></i>Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    .gap-2 { gap: 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; padding-left: 0.9rem; padding-right: 0.9rem; }
    .avatar { border-radius: 50%; background-size: cover; background-position: center; display: inline-flex !important; align-items: center !important; justify-content: center !important; }

    /* Pequeño ajuste para las cajitas de los permisos */
    .custom-permission-item { border-color: #dee2e6; transition: all 0.2s; }
    .custom-permission-item:hover { background-color: #e9ecef !important; }

    /* Tarjeta de rol en el modal */
    .role-card {
        border-color: #dee2e6 !important;
        background-color: #f8f9fa;
        transition: all 0.2s;
    }
    .role-card:hover {
        border-color: #17a2b8 !important;
        background-color: #e8f7fa !important;
    }
    .role-card--active {
        border-color: #17a2b8 !important;
        background-color: #e0f5f8 !important;
    }

    /* Modo oscuro */
    body[data-bs-theme="dark"] .card {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .card-header {
        background-color: #171c2c !important;
        border-bottom-color: #2a3446 !important;
        color: #f8f9fa;
    }
    body[data-bs-theme="dark"] .card-footer {
        background-color: #171c2c !important;
        border-top-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .table,
    body[data-bs-theme="dark"] .table-bordered {
        background-color: #1a1e2c !important;
        color: #e9ecef !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .table td,
    body[data-bs-theme="dark"] .table th,
    body[data-bs-theme="dark"] .table-bordered th,
    body[data-bs-theme="dark"] .table-bordered td {
        border-color: #2a3446 !important;
        color: #e9ecef !important;
        background-color: #1a1e2c !important;
    }
    body[data-bs-theme="dark"] .table thead th {
        background-color: #0f1220 !important;
        color: #f8f9fa !important;
        border-bottom-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .table-hover > tbody > tr:hover > * {
        background-color: #2c3145 !important;
    }
    body[data-bs-theme="dark"] .form-control,
    body[data-bs-theme="dark"] .form-select {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .btn-outline-success {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-success:hover {
        background-color: #6fcf97;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-primary {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-primary:hover {
        background-color: #6fcf97;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }
    body[data-bs-theme="dark"] .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-danger {
        color: #e74c5c;
        border-color: #e74c5c;
    }
    body[data-bs-theme="dark"] .btn-outline-danger:hover {
        background-color: #e74c5c;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-secondary {
        color: #a8b3cf;
        border-color: #3a4458;
    }
    body[data-bs-theme="dark"] .btn-outline-secondary:hover {
        background-color: #3a4458;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .btn-secondary {
        background-color: #2a3446;
        border-color: #3a4458;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .text-muted {
        color: #a8b3cf !important;
    }
    body[data-bs-theme="dark"] .badge.bg-info {
        background-color: #17a2b8 !important;
        color: #0f1220 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-warning {
        background-color: #d39e00 !important;
        color: #1a1e2c !important;
    }
    body[data-bs-theme="dark"] .badge.bg-cyan {
        background-color: #17a2b8 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-pink {
        background-color: #e83e8c !important;
    }
    body[data-bs-theme="dark"] .alert-warning {
        background-color: #2a1e0c;
        border-color: #664d00;
        color: #ffd966;
    }
    body[data-bs-theme="dark"] .alert-info {
        background-color: #1a1e2c;
        border-color: #2a3446;
        color: #e9ecef;
    }

    /* Clases personalizadas extra modo oscuro */
    body[data-bs-theme="dark"] .custom-permission-item {
        background-color: #1a1e2c !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .custom-permission-item:hover {
        background-color: #2c3145 !important;
    }

    /* Tarjetas de rol modo oscuro */
    body[data-bs-theme="dark"] .role-card {
        background-color: #1a1e2c !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .role-card:hover {
        border-color: #17a2b8 !important;
        background-color: #1a2a30 !important;
    }
    body[data-bs-theme="dark"] .role-card--active {
        border-color: #17a2b8 !important;
        background-color: #15252c !important;
    }
    body[data-bs-theme="dark"] .modal-content {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .modal-footer {
        background-color: #171c2c !important;
        border-top-color: #2a3446 !important;
    }

    /* Select2 modo oscuro */
    body[data-bs-theme="dark"] .select2-container--default .select2-selection--single {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .select2-dropdown {
        background-color: #1a1e2c !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .select2-container--default .select2-results__option {
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #2c3145 !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    $('#select2-vincular').select2({
        dropdownParent: $('#vincularPersonaModal'),
        width: '100%',
        placeholder: 'Seleccione una persona',
        allowClear: true
    });
    $('#select2-cambiar').select2({
        dropdownParent: $('#changePersonaModal'),
        width: '100%',
        placeholder: 'Seleccione una persona',
        allowClear: true
    });

    // Actualizar clase activa al marcar/desmarcar rol
    $(document).on('change', '.role-checkbox', function () {
        var card = $(this).closest('.role-card');
        if ($(this).is(':checked')) {
            card.addClass('role-card--active');
        } else {
            card.removeClass('role-card--active');
        }
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