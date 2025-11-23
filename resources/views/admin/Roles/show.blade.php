@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle del Rol: {{ $role->display_name }}</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <!-- Información del Rol -->
        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Rol</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas {{ $role->icono }} fa-5x text-{{ $role->badge_color }}"></i>
                        <h3 class="mt-2">
                            <span class="badge badge-{{ $role->badge_color }} badge-lg">
                                {{ $role->display_name }}
                            </span>
                        </h3>
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">ID:</th>
                            <td>{{ $role->id }}</td>
                        </tr>
                        <tr>
                            <th>Nombre (Slug):</th>
                            <td><code>{{ $role->name }}</code></td>
                        </tr>
                        <tr>
                            <th>Descripción:</th>
                            <td>{{ $role->description ?? 'Sin descripción' }}</td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td>{{ $role->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editRoleModal">
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
                        <div class="col-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Usuarios</span>
                                    <span class="info-box-number">{{ $role->users->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-key"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Permisos</span>
                                    <span class="info-box-number">{{ $role->permissions->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permisos Asignados -->
        <div class="col-md-8">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-key"></i> Permisos Asignados ({{ $role->permissions->count() }})</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPermisosModal">
                            <i class="fas fa-edit"></i> Gestionar Permisos
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                        @php
                            $permisosAgrupados = $role->permissions->groupBy('module');
                        @endphp
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
                                    <div class="col-md-6 mb-2">
                                        <span class="badge badge-{{ $permiso->modulo_badge }}">
                                            <i class="fas fa-check"></i> {{ $permiso->display_name }}
                                        </span>
                                        <small class="text-muted d-block"><code>{{ $permiso->name }}</code></small>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Este rol no tiene permisos asignados.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usuarios Asignados -->
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> Usuarios Asignados ({{ $role->users->count() }})</h3>
                </div>
                <div class="card-body">
                    @if($role->users->count() > 0)
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Persona</th>
                                    <th>Asignado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td><code>{{ $user->name }}</code></td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->persona)
                                            {{ $user->persona->nombre_completo }}
                                        @else
                                            <span class="text-muted">Sin persona</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $pivot = $user->roles()->where('role_id', $role->id)->first()->pivot ?? null;
                                        @endphp
                                        @if($pivot)
                                            {{ \Carbon\Carbon::parse($pivot->created_at)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Este rol no tiene usuarios asignados.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Rol -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i> Editar Rol
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
                                           required maxlength="50">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre para Mostrar <span class="text-danger">*</span></label>
                                    <input type="text" name="display_name" class="form-control" 
                                           value="{{ old('display_name', $role->display_name) }}" 
                                           required maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $role->description) }}</textarea>
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

    <!-- Modal Gestionar Permisos -->
    <div class="modal fade" id="editPermisosModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-key"></i> Gestionar Permisos del Rol
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $role->name }}">
                    <input type="hidden" name="display_name" value="{{ $role->display_name }}">
                    <input type="hidden" name="description" value="{{ $role->description }}">
                    
                    <div class="modal-body">
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
                                                   id="permiso_{{ $permiso->id }}" 
                                                   name="permissions[]" 
                                                   value="{{ $permiso->id }}"
                                                   {{ $role->permissions->contains($permiso->id) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="permiso_{{ $permiso->id }}">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Guardar Permisos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 1.2rem;
            padding: 0.6rem 1rem;
        }
        .info-box-number {
            font-size: 1.5rem;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        @if(session('mensaje'))
            Swal.fire({
                icon: '{{ session('icono') }}',
                title: '{{ session('mensaje') }}',
                showConfirmButton: true,
                timer: 3000
            });
        @endif
    </script>
@stop