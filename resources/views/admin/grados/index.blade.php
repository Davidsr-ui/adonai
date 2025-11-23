@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Grados</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Grados Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createGradoModal">
                            <i class="fas fa-plus"></i> Nuevo Grado
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="gradosTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 15%">Nivel</th>
                                <th style="width: 20%">Nombre</th>
                                <th style="width: 10%">Sección</th>
                                <th style="width: 15%">Turno</th>
                                <th style="width: 10%">Capacidad</th>
                                <th style="width: 10%">Ocupación</th>
                                <th style="width: 8%">Estado</th>
                                <th style="width: 12%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grados as $grado)
                            <tr>
                                <td class="text-center">{{ $grado->id }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $grado->nivel->nombre ?? 'Sin nivel' }}
                                    </span>
                                </td>
                                <td><strong>{{ $grado->nombre }}</strong></td>
                                <td class="text-center">
                                    @if($grado->seccion)
                                        <span class="badge badge-secondary">{{ $grado->seccion }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($grado->turno)
                                        <span class="badge badge-warning">
                                            {{ $grado->turno->nombre }}
                                        </span>
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $grado->estudiantes->count() }} / {{ $grado->capacidad_maxima }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $porcentaje = $grado->porcentaje_ocupacion;
                                        $colorBadge = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 70 ? 'warning' : 'success');
                                    @endphp
                                    <span class="badge badge-{{ $colorBadge }}">
                                        {{ $porcentaje }}%
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($grado->estado == 'Activo')
                                        <span class="badge badge-success">{{ $grado->estado }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $grado->estado }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.grados.show', $grado->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editGradoModal{{ $grado->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteGradoModal{{ $grado->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editGradoModal{{ $grado->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Grado
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.grados.update', $grado->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="nivel_id">Nivel <span class="text-danger">*</span></label>
                                                            <select name="nivel_id" 
                                                                    class="form-control @error('nivel_id') is-invalid @enderror" 
                                                                    required>
                                                                <option value="">-- Seleccione un nivel --</option>
                                                                @foreach($niveles as $nivel)
                                                                    <option value="{{ $nivel->id }}" 
                                                                        {{ old('nivel_id', $grado->nivel_id) == $nivel->id ? 'selected' : '' }}>
                                                                        {{ $nivel->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('nivel_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="turno_id">Turno</label>
                                                            <select name="turno_id" 
                                                                    class="form-control @error('turno_id') is-invalid @enderror">
                                                                <option value="">-- Sin turno --</option>
                                                                @foreach($turnos as $turno)
                                                                    <option value="{{ $turno->id }}" 
                                                                        {{ old('turno_id', $grado->turno_id) == $turno->id ? 'selected' : '' }}>
                                                                        {{ $turno->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('turno_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre del Grado <span class="text-danger">*</span></label>
                                                            <input type="text" 
                                                                   name="nombre" 
                                                                   class="form-control @error('nombre') is-invalid @enderror" 
                                                                   value="{{ old('nombre', $grado->nombre) }}" 
                                                                   placeholder="Ej: 1er Grado, 2do Grado"
                                                                   required>
                                                            @error('nombre')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="seccion">Sección</label>
                                                            <input type="text" 
                                                                   name="seccion" 
                                                                   class="form-control @error('seccion') is-invalid @enderror" 
                                                                   value="{{ old('seccion', $grado->seccion) }}" 
                                                                   placeholder="Ej: A, B, C">
                                                            @error('seccion')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="capacidad_maxima">Capacidad Máxima <span class="text-danger">*</span></label>
                                                            <input type="number" 
                                                                   name="capacidad_maxima" 
                                                                   class="form-control @error('capacidad_maxima') is-invalid @enderror" 
                                                                   value="{{ old('capacidad_maxima', $grado->capacidad_maxima) }}" 
                                                                   min="1" 
                                                                   max="100" 
                                                                   required>
                                                            @error('capacidad_maxima')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                            <small class="form-text text-muted">
                                                                Estudiantes actuales: {{ $grado->estudiantes->count() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="estado">Estado <span class="text-danger">*</span></label>
                                                            <select name="estado" 
                                                                    class="form-control @error('estado') is-invalid @enderror" 
                                                                    required>
                                                                <option value="Activo" {{ old('estado', $grado->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                                <option value="Inactivo" {{ old('estado', $grado->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                            </select>
                                                            @error('estado')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
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

                            <!-- Modal Eliminar -->
                            <div class="modal fade" id="deleteGradoModal{{ $grado->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('admin.grados.destroy', $grado->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar el grado?</p>
                                                <p class="mb-0"><strong>{{ $grado->nombre_completo }}</strong></p>
                                                <p class="text-muted">Nivel: {{ $grado->nivel->nombre ?? 'N/A' }}</p>
                                                
                                                @if($grado->estudiantes->count() > 0)
                                                    <div class="alert alert-danger mt-3">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                        Este grado tiene {{ $grado->estudiantes->count() }} estudiante(s) matriculado(s) y no puede ser eliminado.
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning mt-3">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                        Esta acción no se puede deshacer.
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times"></i> Cancelar
                                                </button>
                                                <button type="submit" class="btn btn-danger" {{ $grado->estudiantes->count() > 0 ? 'disabled' : '' }}>
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
    <div class="modal fade" id="createGradoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Crear Nuevo Grado
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.grados.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nivel_id_create">Nivel <span class="text-danger">*</span></label>
                                    <select name="nivel_id_create" 
                                            class="form-control @error('nivel_id_create') is-invalid @enderror" 
                                            required>
                                        <option value="">-- Seleccione un nivel --</option>
                                        @foreach($niveles as $nivel)
                                            <option value="{{ $nivel->id }}" {{ old('nivel_id_create') == $nivel->id ? 'selected' : '' }}>
                                                {{ $nivel->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nivel_id_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="turno_id_create">Turno</label>
                                    <select name="turno_id_create" 
                                            class="form-control @error('turno_id_create') is-invalid @enderror">
                                        <option value="">-- Sin turno --</option>
                                        @foreach($turnos as $turno)
                                            <option value="{{ $turno->id }}" {{ old('turno_id_create') == $turno->id ? 'selected' : '' }}>
                                                {{ $turno->nombre }} ({{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('turno_id_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre_create">Nombre del Grado <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="nombre_create" 
                                           class="form-control @error('nombre_create') is-invalid @enderror" 
                                           value="{{ old('nombre_create') }}" 
                                           placeholder="Ej: 1er Grado, 2do Grado, Primero" 
                                           required>
                                    @error('nombre_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seccion_create">Sección</label>
                                    <input type="text" 
                                           name="seccion_create" 
                                           class="form-control @error('seccion_create') is-invalid @enderror" 
                                           value="{{ old('seccion_create') }}" 
                                           placeholder="Ej: A, B, C">
                                    @error('seccion_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Opcional - para diferenciar paralelos</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="capacidad_maxima_create">Capacidad Máxima <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="capacidad_maxima_create" 
                                           class="form-control @error('capacidad_maxima_create') is-invalid @enderror" 
                                           value="{{ old('capacidad_maxima_create', 30) }}" 
                                           min="1" 
                                           max="100" 
                                           required>
                                    @error('capacidad_maxima_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Entre 1 y 100 estudiantes</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado_create">Estado <span class="text-danger">*</span></label>
                                    <select name="estado_create" 
                                            class="form-control @error('estado_create') is-invalid @enderror" 
                                            required>
                                        <option value="Activo" {{ old('estado_create', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                    @error('estado_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
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
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            $('#gradosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[1, 'asc'], [2, 'asc']] // Ordenar por nivel y nombre
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
                $('#editGradoModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('nombre_create') || $errors->has('nivel_id_create') || $errors->has('capacidad_maxima_create'))
                $('#createGradoModal').modal('show');
            @endif
        });
    </script>
@stop