@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Notas</b></h1>
    <hr>
@stop

@section('content')
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
            <form action="{{ route('docente.notas.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Estudiante</label>
                            <select name="estudiante_id" class="form-control select2">
                                <option value="">Todos</option>
                                @foreach($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}" {{ request('estudiante_id') == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Curso</label>
                            <select name="curso_id" class="form-control select2">
                                <option value="">Todos</option>
                                @foreach($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Periodo</label>
                            <select name="periodo_id" class="form-control">
                                <option value="">Todos</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}" {{ request('periodo_id') == $periodo->id ? 'selected' : '' }}>
                                        {{ $periodo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tipo Evaluación</label>
                            <select name="tipo_evaluacion" class="form-control">
                                <option value="">Todos</option>
                                <option value="Parcial" {{ request('tipo_evaluacion') == 'Parcial' ? 'selected' : '' }}>Parcial</option>
                                <option value="Final" {{ request('tipo_evaluacion') == 'Final' ? 'selected' : '' }}>Final</option>
                                <option value="Práctica" {{ request('tipo_evaluacion') == 'Práctica' ? 'selected' : '' }}>Práctica</option>
                                <option value="Oral" {{ request('tipo_evaluacion') == 'Oral' ? 'selected' : '' }}>Oral</option>
                                <option value="Trabajo" {{ request('tipo_evaluacion') == 'Trabajo' ? 'selected' : '' }}>Trabajo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route('docente.notas.index') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Notas Registradas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createNotaModal">
                            <i class="fas fa-plus"></i> Registrar Nota
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="notasTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 4%">ID</th>
                                <th style="width: 20%">Estudiante</th>
                                <th style="width: 15%">Curso</th>
                                <th style="width: 10%">Periodo</th>
                                <th style="width: 10%">Tipo</th>
                                <th style="width: 6%">N.Práct</th>
                                <th style="width: 6%">N.Teoría</th>
                                <th style="width: 7%">N.Final</th>
                                <th style="width: 8%">Estado</th>
                                <th style="width: 6%">Visible</th>
                                <th style="width: 8%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notas as $nota)
                            <tr>
                                <td class="text-center">{{ $nota->id }}</td>
                                <td>
                                    <i class="fas fa-user-graduate"></i> 
                                    <strong>{{ $nota->matricula->estudiante->persona->apellidos }}, {{ $nota->matricula->estudiante->persona->nombres }}</strong>
                                </td>
                                <td>{{ $nota->matricula->curso->nombre }}</td>
                                <td>
                                    <span class="badge badge-secondary">
                                        {{ $nota->periodo->nombre }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $nota->tipo_evaluacion_badge }}">
                                        {{ $nota->tipo_evaluacion }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $nota->nota_practica ?? '-' }}</td>
                                <td class="text-center">{{ $nota->nota_teoria ?? '-' }}</td>
                                <td class="text-center">
                                    <strong class="badge badge-{{ $nota->estado_nota_badge }} badge-lg">
                                        {{ $nota->nota_final }}
                                    </strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $nota->estado_nota_badge }}">
                                        {{ $nota->estado_nota_texto }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($nota->visible_tutor)
                                        <span class="badge badge-success">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('docente.notas.show', $nota->id) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-success btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#editNotaModal{{ $nota->id }}"
                                                title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#deleteNotaModal{{ $nota->id }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Editar -->
                            <div class="modal fade" id="editNotaModal{{ $nota->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Editar Nota
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('docente.notas.update', $nota->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Matrícula (Estudiante - Curso) <span class="text-danger">*</span></label>
                                                            <select name="matricula_id" class="form-control select2" required>
                                                                <option value="">-- Seleccione --</option>
                                                                @foreach($matriculas as $matricula)
                                                                    <option value="{{ $matricula->id }}" {{ old('matricula_id', $nota->matricula_id) == $matricula->id ? 'selected' : '' }}>
                                                                        {{ $matricula->estudiante->persona->apellidos }}, {{ $matricula->estudiante->persona->nombres }} - {{ $matricula->curso->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Periodo <span class="text-danger">*</span></label>
                                                            <select name="periodo_id" class="form-control" required>
                                                                <option value="">-- Seleccione --</option>
                                                                @foreach($periodos as $periodo)
                                                                    <option value="{{ $periodo->id }}" {{ old('periodo_id', $nota->periodo_id) == $periodo->id ? 'selected' : '' }}>
                                                                        {{ $periodo->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Tipo Evaluación <span class="text-danger">*</span></label>
                                                            <select name="tipo_evaluacion" class="form-control" required>
                                                                <option value="Parcial" {{ old('tipo_evaluacion', $nota->tipo_evaluacion) == 'Parcial' ? 'selected' : '' }}>Parcial</option>
                                                                <option value="Final" {{ old('tipo_evaluacion', $nota->tipo_evaluacion) == 'Final' ? 'selected' : '' }}>Final</option>
                                                                <option value="Práctica" {{ old('tipo_evaluacion', $nota->tipo_evaluacion) == 'Práctica' ? 'selected' : '' }}>Práctica</option>
                                                                <option value="Oral" {{ old('tipo_evaluacion', $nota->tipo_evaluacion) == 'Oral' ? 'selected' : '' }}>Oral</option>
                                                                <option value="Trabajo" {{ old('tipo_evaluacion', $nota->tipo_evaluacion) == 'Trabajo' ? 'selected' : '' }}>Trabajo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Nota Práctica (0-20)</label>
                                                            <input type="number" name="nota_practica" class="form-control" 
                                                                   step="0.01" min="0" max="20" 
                                                                   value="{{ old('nota_practica', $nota->nota_practica) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Nota Teoría (0-20)</label>
                                                            <input type="number" name="nota_teoria" class="form-control" 
                                                                   step="0.01" min="0" max="20" 
                                                                   value="{{ old('nota_teoria', $nota->nota_teoria) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Nota Final (0-20) <span class="text-danger">*</span></label>
                                                            <input type="number" name="nota_final" class="form-control" 
                                                                   step="0.01" min="0" max="20" 
                                                                   value="{{ old('nota_final', $nota->nota_final) }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Fecha Evaluación</label>
                                                            <input type="date" name="fecha_evaluacion" class="form-control" 
                                                                   value="{{ old('fecha_evaluacion', $nota->fecha_evaluacion ? $nota->fecha_evaluacion->format('Y-m-d') : '') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Descripción</label>
                                                            <input type="text" name="descripcion" class="form-control" 
                                                                   value="{{ old('descripcion', $nota->descripcion) }}" 
                                                                   maxlength="500">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" 
                                                                       id="visible_tutor{{ $nota->id }}" name="visible_tutor" value="1"
                                                                       {{ old('visible_tutor', $nota->visible_tutor) ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="visible_tutor{{ $nota->id }}">
                                                                    <strong>Visible para Tutores</strong>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Observaciones</label>
                                                            <textarea name="observaciones" class="form-control" rows="2" maxlength="500">{{ old('observaciones', $nota->observaciones) }}</textarea>
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
                            <div class="modal fade" id="deleteNotaModal{{ $nota->id }}" tabindex="-1" role="dialog">
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
                                        <form action="{{ route('docente.notas.destroy', $nota->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                <p>¿Está seguro de que desea eliminar esta nota?</p>
                                                <div class="alert alert-info">
                                                    <strong>Estudiante:</strong> {{ $nota->matricula->estudiante->persona->nombres }} {{ $nota->matricula->estudiante->persona->apellidos }}<br>
                                                    <strong>Curso:</strong> {{ $nota->matricula->curso->nombre }}<br>
                                                    <strong>Tipo:</strong> {{ $nota->tipo_evaluacion }}<br>
                                                    <strong>Nota Final:</strong> {{ $nota->nota_final }}
                                                </div>
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    Esta acción no se puede deshacer.
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
    <div class="modal fade" id="createNotaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Registrar Nueva Nota
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('docente.notas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Matrícula (Estudiante - Curso) <span class="text-danger">*</span></label>
                                    <select name="matricula_id_create" class="form-control select2" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($matriculas as $matricula)
                                            <option value="{{ $matricula->id }}" {{ old('matricula_id_create') == $matricula->id ? 'selected' : '' }}>
                                                {{ $matricula->estudiante->persona->apellidos }}, {{ $matricula->estudiante->persona->nombres }} - {{ $matricula->curso->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Periodo <span class="text-danger">*</span></label>
                                    <select name="periodo_id_create" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($periodos as $periodo)
                                            <option value="{{ $periodo->id }}" {{ old('periodo_id_create') == $periodo->id ? 'selected' : '' }}>
                                                {{ $periodo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo <span class="text-danger">*</span></label>
                                    <select name="tipo_evaluacion_create" class="form-control" required>
                                        <option value="Parcial" {{ old('tipo_evaluacion_create', 'Parcial') == 'Parcial' ? 'selected' : '' }}>Parcial</option>
                                        <option value="Final" {{ old('tipo_evaluacion_create') == 'Final' ? 'selected' : '' }}>Final</option>
                                        <option value="Práctica" {{ old('tipo_evaluacion_create') == 'Práctica' ? 'selected' : '' }}>Práctica</option>
                                        <option value="Oral" {{ old('tipo_evaluacion_create') == 'Oral' ? 'selected' : '' }}>Oral</option>
                                        <option value="Trabajo" {{ old('tipo_evaluacion_create') == 'Trabajo' ? 'selected' : '' }}>Trabajo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nota Práctica (0-20)</label>
                                    <input type="number" name="nota_practica_create" class="form-control" 
                                           step="0.01" min="0" max="20" 
                                           value="{{ old('nota_practica_create') }}">
                                    <small class="text-muted">Opcional</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nota Teoría (0-20)</label>
                                    <input type="number" name="nota_teoria_create" class="form-control" 
                                           step="0.01" min="0" max="20" 
                                           value="{{ old('nota_teoria_create') }}">
                                    <small class="text-muted">Opcional</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nota Final (0-20) <span class="text-danger">*</span></label>
                                    <input type="number" name="nota_final_create" class="form-control" 
                                           step="0.01" min="0" max="20" 
                                           value="{{ old('nota_final_create') }}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha Evaluación</label>
                                    <input type="date" name="fecha_evaluacion_create" class="form-control" 
                                           value="{{ old('fecha_evaluacion_create', date('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <input type="text" name="descripcion_create" class="form-control" 
                                           value="{{ old('descripcion_create') }}" 
                                           placeholder="Ej: Examen Parcial Unidad 1" maxlength="500">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="visible_tutor_create" name="visible_tutor_create" value="1"
                                               {{ old('visible_tutor_create') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="visible_tutor_create">
                                            <strong>Visible para Tutores</strong>
                                            <br>
                                            <small class="text-muted">Publicar automáticamente</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones_create" class="form-control" rows="2" 
                                              maxlength="500" placeholder="Observaciones adicionales...">{{ old('observaciones_create') }}</textarea>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#notasTable').DataTable({
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
                placeholder: 'Seleccione una opción',
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
                $('#editNotaModal{{ session('modal_id') }}').modal('show');
            @endif

            @if($errors->has('matricula_id_create') || $errors->has('nota_final_create'))
                $('#createNotaModal').modal('show');
            @endif
        });
    </script>
@stop