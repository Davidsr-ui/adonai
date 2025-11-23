@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Cursos</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Cursos Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCursoModal">
                            <i class="fas fa-plus"></i> Nuevo Curso
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="cursosTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 15%">Nivel</th>
                                <th style="width: 10%">Código</th>
                                <th style="width: 20%">Nombre del Curso</th>
                                <th style="width: 15%">Área Curricular</th>
                                <th style="width: 10%">Hrs/Sem</th>
                                <th style="width: 8%">Estado</th>
                                <th style="width: 15%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cursos as $curso)
                            <tr>
                                <td class="text-center">{{ $curso->id }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $curso->nivel->nombre ?? 'Sin nivel' }}
                                    </span>
                                </td>
                                <td>{{ $curso->codigo ?? 'N/A' }}</td>
                                <td><strong>{{ $curso->nombre }}</strong></td>
                                <td>{{ $curso->area_curricular ?? 'No especificada' }}</td>
                                <td class="text-center">{{ $curso->horas_semanales }}</td>
                                <td class="text-center">
                                    @if($curso->estado == 'Activo')
                                        <span class="badge badge-success">{{ $curso->estado }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $curso->estado }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.cursos.show', $curso->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editCursoModal{{ $curso->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteCursoModal{{ $curso->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editCursoModal{{ $curso->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Curso
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
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
                                                                        {{ old('nivel_id', $curso->nivel_id) == $nivel->id ? 'selected' : '' }}>
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
                                                            <label for="codigo">Código del Curso</label>
                                                            <input type="text" 
                                                                   name="codigo" 
                                                                   class="form-control @error('codigo') is-invalid @enderror" 
                                                                   value="{{ old('codigo', $curso->codigo) }}" 
                                                                   placeholder="Ej: MAT-101">
                                                            @error('codigo')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label for="nombre">Nombre del Curso <span class="text-danger">*</span></label>
                                                            <input type="text" 
                                                                   name="nombre" 
                                                                   class="form-control @error('nombre') is-invalid @enderror" 
                                                                   value="{{ old('nombre', $curso->nombre) }}" 
                                                                   required>
                                                            @error('nombre')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="estado">Estado <span class="text-danger">*</span></label>
                                                            <select name="estado" 
                                                                    class="form-control @error('estado') is-invalid @enderror" 
                                                                    required>
                                                                <option value="Activo" {{ old('estado', $curso->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                                <option value="Inactivo" {{ old('estado', $curso->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                            </select>
                                                            @error('estado')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="area_curricular">Área Curricular</label>
                                                            <input type="text" 
                                                                   name="area_curricular" 
                                                                   class="form-control @error('area_curricular') is-invalid @enderror" 
                                                                   value="{{ old('area_curricular', $curso->area_curricular) }}" 
                                                                   placeholder="Ej: Ciencias, Humanidades, etc.">
                                                            @error('area_curricular')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="horas_semanales">Horas Semanales <span class="text-danger">*</span></label>
                                                            <input type="number" 
                                                                   name="horas_semanales" 
                                                                   class="form-control @error('horas_semanales') is-invalid @enderror" 
                                                                   value="{{ old('horas_semanales', $curso->horas_semanales) }}" 
                                                                   min="1" 
                                                                   max="40" 
                                                                   required>
                                                            @error('horas_semanales')
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
                            <div class="modal fade" id="deleteCursoModal{{ $curso->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar el curso?</p>
                                                <p class="mb-0"><strong>{{ $curso->nombre }}</strong></p>
                                                @if($curso->codigo)
                                                    <p class="text-muted">Código: {{ $curso->codigo }}</p>
                                                @endif
                                                <div class="alert alert-warning mt-3">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    Esta acción no se puede deshacer. Si el curso tiene docentes asignados o matrículas, no podrá eliminarse.
                                                </div>
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
    <div class="modal fade" id="createCursoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Crear Nuevo Curso
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.cursos.store') }}" method="POST">
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
                                    <label for="codigo_create">Código del Curso</label>
                                    <input type="text" 
                                           name="codigo_create" 
                                           class="form-control @error('codigo_create') is-invalid @enderror" 
                                           value="{{ old('codigo_create') }}" 
                                           placeholder="Ej: MAT-101">
                                    @error('codigo_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nombre_create">Nombre del Curso <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="nombre_create" 
                                           class="form-control @error('nombre_create') is-invalid @enderror" 
                                           value="{{ old('nombre_create') }}" 
                                           placeholder="Ej: Matemáticas" 
                                           required>
                                    @error('nombre_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="area_curricular_create">Área Curricular</label>
                                    <input type="text" 
                                           name="area_curricular_create" 
                                           class="form-control @error('area_curricular_create') is-invalid @enderror" 
                                           value="{{ old('area_curricular_create') }}" 
                                           placeholder="Ej: Ciencias, Humanidades, Comunicación, etc.">
                                    @error('area_curricular_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="horas_semanales_create">Horas Semanales <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="horas_semanales_create" 
                                           class="form-control @error('horas_semanales_create') is-invalid @enderror" 
                                           value="{{ old('horas_semanales_create', 2) }}" 
                                           min="1" 
                                           max="40" 
                                           required>
                                    @error('horas_semanales_create')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Entre 1 y 40 horas</small>
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
            $('#cursosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [[3, 'asc']] // Ordenar por nombre del curso
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
                $('#editCursoModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('nombre_create') || $errors->has('codigo_create') || $errors->has('horas_semanales_create') || $errors->has('nivel_id_create'))
                $('#createCursoModal').modal('show');
            @endif
        });
    </script>
@stop