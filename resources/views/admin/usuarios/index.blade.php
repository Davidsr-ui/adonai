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
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-blue text-white avatar rounded"><i class="fas fa-users"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ $estadisticas['total'] }}</div>
                            <div class="text-muted">Total Usuarios</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar rounded"><i class="fas fa-user-check"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ $estadisticas['activos'] }}</div>
                            <div class="text-muted">Activos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-yellow text-white avatar rounded"><i class="fas fa-envelope-open"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ $estadisticas['verificados'] }}</div>
                            <div class="text-muted">Verificados</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-purple text-white avatar rounded"><i class="fas fa-id-card"></i></span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">{{ $estadisticas['con_persona'] }}</div>
                            <div class="text-muted">Con Persona Vinculada</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card mb-4">
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
                            <label class="form-label">Verificado</label>
                            <select name="verificado" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" {{ request('verificado') === '1' ? 'selected' : '' }}>Verificados</option>
                                <option value="0" {{ request('verificado') === '0' ? 'selected' : '' }}>No verificados</option>
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
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary"><i class="fas fa-eraser"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Usuarios Registrados</h3>
            <div class="card-options">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="fas fa-plus me-1"></i> Crear Usuario
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="usersTable" class="table table-vcenter table-hover card-table">
                    <thead>
                        <tr>
                            <th class="w-1">ID</th>
                            <th class="w-1">Avatar</th>
                            <th>Usuario</th>
                            <th>Persona</th>
                            <th>Roles</th>
                            <th>Estado</th>
                            <th>Verificado</th>
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
                                    <span class="badge bg-warning-lt text-warning">Sin persona vinculada</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->roles->count() > 0)
                                    @foreach($usuario->roles as $rol)
                                        <span class="badge bg-blue-lt text-blue me-1">{{ $rol->display_name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary-lt">Sin roles</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->esta_activo)
                                    <span class="badge bg-success-lt text-success">Activo</span>
                                @else
                                    <span class="badge bg-danger-lt text-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                @if($usuario->esta_verificado)
                                    <span class="badge bg-success-lt text-success"><i class="fas fa-check me-1"></i>Sí</span>
                                @else
                                    <span class="badge bg-warning-lt text-warning"><i class="fas fa-times me-1"></i>No</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.usuarios.show', $usuario->id) }}" class="btn btn-sm btn-info" title="Ver"><i class="fas fa-eye"></i></a>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $usuario->id }}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $usuario->id }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL EDITAR --}}
                        <div class="modal modal-blur fade" id="editUserModal{{ $usuario->id }}" tabindex="-1">
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
                                                    <select name="persona_id" class="form-select select2-edit-{{ $usuario->id }}">
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
                                                    <div class="row g-2">
                                                        @foreach($roles as $role)
                                                        <div class="col-auto">
                                                            <label class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" {{ $usuario->roles->contains($role->id) ? 'checked' : '' }}>
                                                                <span class="form-check-label"><span class="badge bg-blue-lt">{{ $role->display_name }}</span></span>
                                                            </label>
                                                        </div>
                                                        @endforeach
                                                    </div>
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

                        {{-- MODAL ELIMINAR --}}
                        <div class="modal modal-blur fade" id="deleteUserModal{{ $usuario->id }}" tabindex="-1">
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
                                            <button type="submit" class="btn btn-danger w-50"><i class="fas fa-trash me-1"></i>Eliminar</button>
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

        {{-- PAGINACIÓN --}}
        @if($usuarios instanceof \Illuminate\Pagination\LengthAwarePaginator && $usuarios->hasPages())
        <div class="card-footer d-flex align-items-center">
            <p class="m-0 text-muted">
                Mostrando <span class="fw-semibold">{{ $usuarios->firstItem() }}</span> a
                <span class="fw-semibold">{{ $usuarios->lastItem() }}</span> de
                <span class="fw-semibold">{{ $usuarios->total() }}</span> usuarios
            </p>
            <ul class="pagination m-0 ms-auto">
                {{ $usuarios->appends(request()->query())->links('pagination::bootstrap-5') }}
            </ul>
        </div>
        @endif
    </div>

    {{-- MODAL CREAR USUARIO --}}
    <div class="modal modal-blur fade" id="createUserModal" tabindex="-1">
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
                                <select name="persona_id" class="form-select select2-create">
                                    <option value="">Sin persona</option>
                                    @foreach($personasSinUsuario as $persona)
                                        <option value="{{ $persona->id }}">{{ $persona->nombres }} {{ $persona->apellidos }} - DNI: {{ $persona->dni }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-check">
                                    <input type="checkbox" class="form-check-input" name="verificar_email" value="1">
                                    <span class="form-check-label">Marcar email como verificado</span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Asignar Roles</label>
                                <div class="row g-2">
                                    @foreach($roles as $role)
                                    <div class="col-auto">
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}">
                                            <span class="form-check-label"><span class="badge bg-blue-lt">{{ $role->display_name }}</span></span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        /* ── Badges modo oscuro — colores que combinan con el fondo oscuro ── */
        [data-bs-theme="dark"] .badge.bg-danger-lt {
            background-color: #3d1f1f !important;
            color: #ff8a8a !important;
            border: 1px solid rgba(255,100,100,0.3) !important;
        }
        [data-bs-theme="dark"] .badge.bg-success-lt {
            background-color: #1a3329 !important;
            color: #6edbb4 !important;
            border: 1px solid rgba(70,200,140,0.3) !important;
        }
        [data-bs-theme="dark"] .badge.bg-warning-lt {
            background-color: #3a2d10 !important;
            color: #ffc96e !important;
            border: 1px solid rgba(255,180,50,0.3) !important;
        }
        [data-bs-theme="dark"] .badge.bg-blue-lt {
            background-color: #1a2a3d !important;
            color: #7ec8f7 !important;
            border: 1px solid rgba(80,160,230,0.3) !important;
        }
        [data-bs-theme="dark"] .badge.bg-secondary-lt {
            background-color: #2a2a2a !important;
            color: #aaaaaa !important;
            border: 1px solid rgba(150,150,150,0.2) !important;
        }
        /* ── Cards en modo oscuro ── */
        [data-bs-theme="dark"] .card {
            background-color: #1e2a3a !important;
            border-color: rgba(255,255,255,0.07) !important;
            color: #c8d3e0 !important;
        }
        [data-bs-theme="dark"] .card .text-muted {
            color: #7a8fa8 !important;
        }
        [data-bs-theme="dark"] .card-header {
            background-color: #1e2a3a !important;
            border-bottom-color: rgba(255,255,255,0.07) !important;
        }
        [data-bs-theme="dark"] .card-footer {
            background-color: #1e2a3a !important;
            border-top-color: rgba(255,255,255,0.07) !important;
        }
        [data-bs-theme="dark"] .table td,
        [data-bs-theme="dark"] .table th {
            border-color: rgba(255,255,255,0.06) !important;
        }
        [data-bs-theme="dark"] .table-hover tbody tr:hover {
            background-color: rgba(255,255,255,0.04) !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.select2-create').select2({
                dropdownParent: $('#createUserModal'),
                width: '100%',
                placeholder: 'Seleccione una persona',
                allowClear: true
            });

            @foreach($usuarios as $usuario)
            $('.select2-edit-{{ $usuario->id }}').select2({
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
                new bootstrap.Modal(document.getElementById("editUserModal{{ session('modal_id') }}")).show();
            @endif

            @if($errors->has('name') || $errors->has('email') || $errors->has('password'))
                new bootstrap.Modal(document.getElementById('createUserModal')).show();
            @endif

        });
    </script>