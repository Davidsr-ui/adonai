@extends('layouts.admin')

@section('title', 'Gestión de Grados')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-layer-group text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Grados</span>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Grados Registrados</h3>
            <div class="card-options">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createGradoModal">
                    <i class="fas fa-plus me-1"></i> Nuevo Grado
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="gradosTable" class="table table-vcenter table-hover card-table">
                    <thead>
                        <tr>
                            <th class="w-1">ID</th>
                            <th>Nivel</th>
                            <th>Nombre</th>
                            <th>Sección</th>
                            <th>Turno</th>
                            <th>Capacidad</th>
                            <th>Ocupación</th>
                            <th>Estado</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grados as $grado)
                        <tr>
                            <td class="text-muted">{{ $grado->id }}</td>
                            <td><span class="badge bg-cyan-lt text-cyan">{{ $grado->nivel->nombre ?? 'Sin nivel' }}</span></td>
                            <td><div class="fw-semibold">{{ $grado->nombre }}</div></td>
                            <td>
                                @if($grado->seccion)
                                    <span class="badge bg-secondary-lt text-secondary">{{ $grado->seccion }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($grado->turno)
                                    <span class="badge bg-warning-lt text-warning">{{ $grado->turno->nombre }}</span>
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </td>
                            <td class="text-center text-muted">{{ $grado->estudiantes->count() }} / {{ $grado->capacidad_maxima }}</td>
                            <td class="text-center">
                                @php
                                    $porcentaje = $grado->porcentaje_ocupacion;
                                    $clase = $porcentaje >= 90 ? 'bg-danger-lt text-danger' : ($porcentaje >= 70 ? 'bg-warning-lt text-warning' : 'bg-success-lt text-success');
                                @endphp
                                <span class="badge {{ $clase }}">{{ $porcentaje }}%</span>
                            </td>
                            <td>
                                @if($grado->estado == 'Activo')
                                    <span class="badge bg-success-lt text-success">Activo</span>
                                @else
                                    <span class="badge bg-danger-lt text-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.grados.show', $grado->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editGradoModal{{ $grado->id }}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteGradoModal{{ $grado->id }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Editar --}}
                        <div class="modal modal-blur fade" id="editGradoModal{{ $grado->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Grado</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.grados.update', $grado->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nivel <span class="text-danger">*</span></label>
                                                    <select name="nivel_id" class="form-select" required>
                                                        <option value="">-- Seleccione --</option>
                                                        @foreach($niveles as $nivel)
                                                            <option value="{{ $nivel->id }}" {{ old('nivel_id', $grado->nivel_id) == $nivel->id ? 'selected' : '' }}>{{ $nivel->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Turno</label>
                                                    <select name="turno_id" class="form-select">
                                                        <option value="">-- Sin turno --</option>
                                                        @foreach($turnos as $turno)
                                                            <option value="{{ $turno->id }}" {{ old('turno_id', $grado->turno_id) == $turno->id ? 'selected' : '' }}>{{ $turno->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nombre del Grado <span class="text-danger">*</span></label>
                                                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $grado->nombre) }}" placeholder="Ej: 1er Grado" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Sección</label>
                                                    <input type="text" name="seccion" class="form-control" value="{{ old('seccion', $grado->seccion) }}" placeholder="Ej: A, B, C">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Capacidad Máxima <span class="text-danger">*</span></label>
                                                    <input type="number" name="capacidad_maxima" class="form-control" value="{{ old('capacidad_maxima', $grado->capacidad_maxima) }}" min="1" max="100" required>
                                                    <small class="text-muted">Actuales: {{ $grado->estudiantes->count() }}</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                    <select name="estado" class="form-select" required>
                                                        <option value="Activo" {{ old('estado', $grado->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                        <option value="Inactivo" {{ old('estado', $grado->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                    </select>
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

                        {{-- Modal Eliminar --}}
                        <div class="modal modal-blur fade" id="deleteGradoModal{{ $grado->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.grados.destroy', $grado->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>{{ $grado->nombre_completo }}</strong><br>
                                                <small>Nivel: {{ $grado->nivel->nombre ?? 'N/A' }}</small>
                                            </div>
                                            @if($grado->estudiantes->count() > 0)
                                                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-1"></i>Este grado tiene {{ $grado->estudiantes->count() }} estudiante(s) y no puede eliminarse.</div>
                                            @else
                                                <div class="alert alert-warning"><i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer.</div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger w-50" {{ $grado->estudiantes->count() > 0 ? 'disabled' : '' }}><i class="fas fa-trash me-1"></i>Eliminar</button>
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

    {{-- Modal Crear --}}
    <div class="modal modal-blur fade" id="createGradoModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Crear Nuevo Grado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.grados.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nivel <span class="text-danger">*</span></label>
                                <select name="nivel_id_create" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($niveles as $nivel)
                                        <option value="{{ $nivel->id }}" {{ old('nivel_id_create') == $nivel->id ? 'selected' : '' }}>{{ $nivel->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Turno</label>
                                <select name="turno_id_create" class="form-select">
                                    <option value="">-- Sin turno --</option>
                                    @foreach($turnos as $turno)
                                        <option value="{{ $turno->id }}" {{ old('turno_id_create') == $turno->id ? 'selected' : '' }}>
                                            {{ $turno->nombre }} ({{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre del Grado <span class="text-danger">*</span></label>
                                <input type="text" name="nombre_create" class="form-control" value="{{ old('nombre_create') }}" placeholder="Ej: 1er Grado" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sección</label>
                                <input type="text" name="seccion_create" class="form-control" value="{{ old('seccion_create') }}" placeholder="Ej: A, B, C">
                                <small class="text-muted">Opcional — para diferenciar paralelos</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Capacidad Máxima <span class="text-danger">*</span></label>
                                <input type="number" name="capacidad_maxima_create" class="form-control" value="{{ old('capacidad_maxima_create', 30) }}" min="1" max="100" required>
                                <small class="text-muted">Entre 1 y 100 estudiantes</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select name="estado_create" class="form-select" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#gradosTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                responsive: true, autoWidth: false, order: [[1, 'asc'], [2, 'asc']]
            });
            @if(session('mensaje'))
                Swal.fire({ icon: '{{ session('icono') }}', title: '{{ session('mensaje') }}', timer: 3000 });
            @endif
            @if($errors->any() && session('modal_id'))
                new bootstrap.Modal(document.getElementById('editGradoModal{{ session('modal_id') }}')).show();
            @endif
            @if($errors->has('nombre_create') || $errors->has('nivel_id_create') || $errors->has('capacidad_maxima_create'))
                new bootstrap.Modal(document.getElementById('createGradoModal')).show();
            @endif
        });
    </script>
@stop