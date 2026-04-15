@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-users text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Usuarios</span>
    </div>
@stop

@section('content')
    {{-- TARJETAS ESTADÍSTICAS --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar rounded-circle"><i class="fas fa-users"></i></span>
                        </div>
                        <div class="col">
                            <div class="fw-bold fs-3">{{ $estadisticas['total'] }}</div>
                            <div class="text-muted">Total Usuarios</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-success text-white avatar rounded-circle"><i class="fas fa-user-check"></i></span>
                        </div>
                        <div class="col">
                            <div class="fw-bold fs-3">{{ $estadisticas['activos'] }}</div>
                            <div class="text-muted">Activos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-cyan text-white avatar rounded-circle"><i class="fas fa-id-card"></i></span>
                        </div>
                        <div class="col">
                            <div class="fw-bold fs-3">{{ $estadisticas['con_persona'] }}</div>
                            <div class="text-muted">Con Persona Vinculada</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header" style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#filtros">
            <h3 class="card-title"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h3>
            <div class="card-options"><i class="fas fa-chevron-down"></i></div>
        </div>
        <div class="collapse" id="filtros">
            <div class="card-body">
                <form action="{{ route('admin.usuarios.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Buscar</label>
                            <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}" placeholder="Nombre, email, DNI...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Rol</label>
                            <select name="rol" class="form-select">
                                <option value="">Todos</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('rol') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos</option>
                                <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tiene Persona</label>
                            <select name="tiene_persona" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('tiene_persona') === '1' ? 'selected' : '' }}>Con persona</option>
                                <option value="0" {{ request('tiene_persona') === '0' ? 'selected' : '' }}>Sin persona</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-search"></i></button>
                            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary rounded-pill"><i class="fas fa-eraser"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Usuarios Registrados</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-plus me-1"></i> Crear Usuario
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-bordered table-hover table-sm align-middle">
                    <thead>
                        <tr>
                            <th class="w-1">ID</th>
                            <th class="w-1">Avatar</th>
                            <th>Usuario</th>
                            <th>Persona</th>
                            <th>Roles</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td class="text-muted">{{ $usuario->id }}</td>
                            <td>
                                <span class="avatar avatar-sm rounded-circle" style="background-image: url('{{ $usuario->avatar }}')"></span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $usuario->name }}</div>
                                <div class="text-muted small"><i class="fas fa-envelope me-1"></i>{{ $usuario->email }}</div>
                            </td>
                            <td>
                                @if($usuario->persona)
                                    <div class="fw-semibold">{{ $usuario->persona->nombres }} {{ $usuario->persona->apellidos }}</div>
                                    <div class="text-muted small"><i class="fas fa-id-card me-1"></i>{{ $usuario->persona->dni }}</div>
                                @else
                                    <span class="badge bg-warning text-dark">Sin persona vinculada</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->roles->count() > 0)
                                    @foreach($usuario->roles as $rol)
                                        <span class="badge bg-info text-dark me-1">{{ $rol->display_name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">Sin roles</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->persona && $usuario->persona->estado == 'Activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.usuarios.show', $usuario->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Ver">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                    <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $usuario->id }}" title="Editar">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $usuario->id }}" title="Eliminar">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                </div>
                             </div>
                        </tr>

                        {{-- MODAL EDITAR (mismo contenido, pero sin verificación) --}}
                        <div class="modal fade" id="editUserModal{{ $usuario->id }}" tabindex="-1">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar: {{ $usuario->name }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                                    <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nueva Contraseña</label>
                                                    <input type="password" name="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Confirmar Contraseña</label>
                                                    <input type="password" name="password_confirmation" class="form-control">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Vincular con Persona</label>
                                                    <select name="persona_id" class="form-select" id="select2-edit-{{ $usuario->id }}">
                                                        <option value="">Sin persona</option>
                                                        @foreach($personasSinUsuario as $persona)
                                                            <option value="{{ $persona->id }}" {{ $usuario->persona && $usuario->persona->id == $persona->id ? 'selected' : '' }}>
                                                                {{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}
                                                            </option>
                                                        @endforeach
                                                        @if($usuario->persona)
                                                            <option value="{{ $usuario->persona->id }}" selected>
                                                                {{ $usuario->persona->nombres }} {{ $usuario->persona->apellidos }} - DNI: {{ $usuario->persona->dni }}
                                                            </option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Roles</label>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($roles as $role)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}_{{ $usuario->id }}" {{ $usuario->roles->contains($role->id) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="role_{{ $role->id }}_{{ $usuario->id }}">
                                                                    <span class="badge bg-info text-dark">{{ $role->display_name }}</span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
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

                        {{-- MODAL ELIMINAR --}}
                        <div class="modal fade" id="deleteUserModal{{ $usuario->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.usuarios.destroy', $usuario->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <div class="modal-body">
                                            <p class="text-center mb-2">¿Estás seguro de eliminar a:</p>
                                            <div class="alert alert-info">
                                                <strong>{{ $usuario->name }}</strong><br>
                                                <small>{{ $usuario->email }}</small>
                                            </div>
                                            @if($usuario->persona)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>La persona vinculada <strong>NO</strong> se eliminará.
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex align-items-center">
            @if($usuarios instanceof \Illuminate\Pagination\LengthAwarePaginator && $usuarios->hasPages())
                <p class="m-0 text-muted">
                    Mostrando <span class="fw-semibold">{{ $usuarios->firstItem() }}</span> a
                    <span class="fw-semibold">{{ $usuarios->lastItem() }}</span> de
                    <span class="fw-semibold">{{ $usuarios->total() }}</span> usuarios
                </p>
                <ul class="pagination m-0 ms-auto">
                    {{ $usuarios->appends(request()->query())->links('pagination::bootstrap-5') }}
                </ul>
            @elseif($usuarios instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                <ul class="pagination m-0 ms-auto">
                    {{ $usuarios->appends(request()->query())->links('pagination::bootstrap-5') }}
                </ul>
            @else
                <p class="m-0 text-muted">Mostrando {{ $usuarios->count() }} usuarios</p>
            @endif
        </div>
    </div>

    {{-- MODAL CREAR USUARIO (sin verificación) --}}
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Crear Nuevo Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.usuarios.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="ej: juan.perez">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="ej: juan@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required placeholder="Mínimo 8 caracteres">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Vincular con Persona (Opcional)</label>
                                <select name="persona_id" class="form-select" id="select2-create">
                                    <option value="">Sin persona</option>
                                    @foreach($personasSinUsuario as $persona)
                                        <option value="{{ $persona->id }}">{{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Asignar Roles</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($roles as $role)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_create_{{ $role->id }}">
                                            <label class="form-check-label" for="role_create_{{ $role->id }}">
                                                <span class="badge bg-info text-dark">{{ $role->display_name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-save me-1"></i>Guardar</button>
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
    .avatar { width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background-size: cover; background-position: center; }

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
    body[data-bs-theme="dark"] .form-select,
    body[data-bs-theme="dark"] .input-group-text {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .btn-outline-info {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-info:hover {
        background-color: #6fcf97;
        color: #0f1220;
    }
    body[data-bs-theme="dark"] .btn-outline-success {
        color: #6fcf97;
        border-color: #6fcf97;
    }
    body[data-bs-theme="dark"] .btn-outline-success:hover {
        background-color: #6fcf97;
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
    body[data-bs-theme="dark"] .badge.bg-secondary {
        background-color: #3a4458 !important;
    }
    body[data-bs-theme="dark"] .alert-info {
        background-color: #1a1e2c;
        border-color: #2a3446;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .alert-warning {
        background-color: #2a1e0c;
        border-color: #664d00;
        color: #ffd966;
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
$(document).ready(function() {
    $('#select2-create').select2({
        dropdownParent: $('#createUserModal'),
        width: '100%',
        placeholder: 'Seleccione una persona',
        allowClear: true
    });

    @foreach($usuarios as $usuario)
    $('#select2-edit-{{ $usuario->id }}').select2({
        dropdownParent: $('#editUserModal{{ $usuario->id }}'),
        width: '100%',
        placeholder: 'Seleccione una persona',
        allowClear: true
    });
    @endforeach

    @if(session('mensaje'))
        Swal.fire({
            icon: "{{ session('icono') }}",
            title: "{{ session('mensaje') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if($errors->any() && session('modal_id'))
        var modal = new bootstrap.Modal(document.getElementById('editUserModal{{ session('modal_id') }}'));
        modal.show();
    @endif

    @if($errors->has('name') || $errors->has('email') || $errors->has('password'))
        var modalCreate = new bootstrap.Modal(document.getElementById('createUserModal'));
        modalCreate.show();
    @endif
});
</script>
@stop