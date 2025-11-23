@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestión de Reportes Académicos</b></h1>
    <hr>
@stop

@section('content')

    @php
        // Detectar si es admin o docente y usar prefijo correcto
        $routePrefix = Auth::user()->hasRole('Administrador') ? 'admin' : 'docente';
    @endphp
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
            <form action="{{ route('admin.reportes.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Estudiante</label>
                            <select name="estudiante_id" class="form-control select2">
                                <option value="">Todos</option>
                                @foreach ($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->id }}"
                                        {{ request('estudiante_id') == $estudiante->id ? 'selected' : '' }}>
                                        {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Periodo</label>
                            <select name="periodo_id" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($periodos as $periodo)
                                    <option value="{{ $periodo->id }}"
                                        {{ request('periodo_id') == $periodo->id ? 'selected' : '' }}>
                                        {{ $periodo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Gestión</label>
                            <select name="gestion_id" class="form-control">
                                <option value="">Todas</option>
                                @foreach ($gestiones as $gestion)
                                    <option value="{{ $gestion->id }}"
                                        {{ request('gestion_id') == $gestion->id ? 'selected' : '' }}>
                                        {{ $gestion->año }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="tipo" class="form-control">
                                <option value="">Todos</option>
                                <option value="Bimestral" {{ request('tipo') == 'Bimestral' ? 'selected' : '' }}>Bimestral
                                </option>
                                <option value="Trimestral" {{ request('tipo') == 'Trimestral' ? 'selected' : '' }}>
                                    Trimestral</option>
                                <option value="Anual" {{ request('tipo') == 'Anual' ? 'selected' : '' }}>Anual</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Visible Tutores</label>
                            <select name="visible" class="form-control">
                                <option value="">Todos</option>
                                <option value="1" {{ request('visible') === '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ request('visible') === '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="{{ route($routePrefix . '.reportes.index') }}" class="btn btn-secondary">
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
                    <h3 class="card-title">Reportes Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#createReporteModal">
                            <i class="fas fa-plus"></i> Generar Reporte
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="reportesTable" class="table table-striped table-bordered table-hover table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 3%">ID</th>
                                <th style="width: 18%">Estudiante</th>
                                <th style="width: 10%">Periodo</th>
                                <th style="width: 8%">Gestión</th>
                                <th style="width: 10%">Tipo</th>
                                <th style="width: 8%">Promedio</th>
                                <th style="width: 8%">Asistencia</th>
                                <th style="width: 5%">PDF</th>
                                <th style="width: 5%">Visible</th>
                                <th style="width: 8%">Fecha Gen.</th>
                                <th style="width: 12%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reportes as $reporte)
                                <tr>
                                    <td class="text-center">{{ $reporte->id }}</td>
                                    <td>
                                        <i class="fas fa-user-graduate"></i>
                                        <strong>{{ $reporte->estudiante->persona->apellidos }},
                                            {{ $reporte->estudiante->persona->nombres }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $reporte->periodo->nombre }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $reporte->gestion->año }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $reporte->tipo_badge }}">
                                            {{ $reporte->tipo }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($reporte->promedio_general)
                                            <span class="badge badge-{{ $reporte->estado_promedio_badge }} badge-lg">
                                                {{ number_format($reporte->promedio_general, 2) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($reporte->porcentaje_asistencia)
                                            <span class="badge badge-{{ $reporte->estado_asistencia_badge }}">
                                                {{ number_format($reporte->porcentaje_asistencia, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($reporte->tienePdf())
                                            <a href="{{ route($routePrefix . '.reportes.descargar-pdf', $reporte->id) }}"
                                                class="btn btn-sm btn-danger" title="Descargar PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($reporte->visible_tutor)
                                            <span class="badge badge-success">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-eye-slash"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $reporte->fecha_generacion_formateada }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{route($routePrefix . '.reportes.show', $reporte->id) }}"
                                                class="btn btn-info btn-sm" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#editReporteModal{{ $reporte->id }}" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteReporteModal{{ $reporte->id }}" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Editar -->
                                <div class="modal fade" id="editReporteModal{{ $reporte->id }}" tabindex="-1"
                                    role="dialog">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit"></i> Editar Reporte
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route($routePrefix . '.reportes.update', $reporte->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Estudiante <span
                                                                        class="text-danger">*</span></label>
                                                                <select name="estudiante_id" class="form-control select2"
                                                                    required>
                                                                    @foreach ($estudiantes as $estudiante)
                                                                        <option value="{{ $estudiante->id }}"
                                                                            {{ old('estudiante_id', $reporte->estudiante_id) == $estudiante->id ? 'selected' : '' }}>
                                                                            {{ $estudiante->persona->apellidos }},
                                                                            {{ $estudiante->persona->nombres }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Docente <span class="text-danger">*</span></label>
                                                                <select name="docente_id" class="form-control select2"
                                                                    required>
                                                                    @foreach ($docentes as $docente)
                                                                        <option value="{{ $docente->id }}"
                                                                            {{ old('docente_id', $reporte->docente_id) == $docente->id ? 'selected' : '' }}>
                                                                            {{ $docente->persona->apellidos }},
                                                                            {{ $docente->persona->nombres }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Tipo <span class="text-danger">*</span></label>
                                                                <select name="tipo" class="form-control" required>
                                                                    <option value="Bimestral"
                                                                        {{ old('tipo', $reporte->tipo) == 'Bimestral' ? 'selected' : '' }}>
                                                                        Bimestral</option>
                                                                    <option value="Trimestral"
                                                                        {{ old('tipo', $reporte->tipo) == 'Trimestral' ? 'selected' : '' }}>
                                                                        Trimestral</option>
                                                                    <option value="Anual"
                                                                        {{ old('tipo', $reporte->tipo) == 'Anual' ? 'selected' : '' }}>
                                                                        Anual</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Periodo <span class="text-danger">*</span></label>
                                                                <select name="periodo_id" class="form-control" required>
                                                                    @foreach ($periodos as $periodo)
                                                                        <option value="{{ $periodo->id }}"
                                                                            {{ old('periodo_id', $reporte->periodo_id) == $periodo->id ? 'selected' : '' }}>
                                                                            {{ $periodo->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Gestión <span class="text-danger">*</span></label>
                                                                <select name="gestion_id" class="form-control" required>
                                                                    @foreach ($gestiones as $gestion)
                                                                        <option value="{{ $gestion->id }}"
                                                                            {{ old('gestion_id', $reporte->gestion_id) == $gestion->id ? 'selected' : '' }}>
                                                                            {{ $gestion->año }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>&nbsp;</label>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="visible_tutor{{ $reporte->id }}"
                                                                        name="visible_tutor" value="1"
                                                                        {{ old('visible_tutor', $reporte->visible_tutor) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label"
                                                                        for="visible_tutor{{ $reporte->id }}">
                                                                        <strong>Visible para Tutores</strong>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Promedio General (0-20)</label>
                                                                <input type="number" name="promedio_general"
                                                                    class="form-control" step="0.01" min="0"
                                                                    max="20"
                                                                    value="{{ old('promedio_general', $reporte->promedio_general) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Asistencia % (0-100)</label>
                                                                <input type="number" name="porcentaje_asistencia"
                                                                    class="form-control" step="0.01" min="0"
                                                                    max="100"
                                                                    value="{{ old('porcentaje_asistencia', $reporte->porcentaje_asistencia) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label>Archivo PDF</label>
                                                                <input type="file" name="archivo_pdf"
                                                                    class="form-control-file" accept=".pdf">
                                                                @if ($reporte->tienePdf())
                                                                    <small class="text-success">
                                                                        <i class="fas fa-check"></i> PDF actual disponible
                                                                    </small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Comentario Final del Docente</label>
                                                                <textarea name="comentario_final" class="form-control" rows="3" maxlength="2000">{{ old('comentario_final', $reporte->comentario_final) }}</textarea>
                                                                <small class="text-muted">Máximo 2000 caracteres</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">
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
                                <div class="modal fade" id="deleteReporteModal{{ $reporte->id }}" tabindex="-1"
                                    role="dialog">
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
                                            <form action="{{route($routePrefix . '.reportes.destroy', $reporte->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <p>¿Está seguro de que desea eliminar este reporte?</p>
                                                    <div class="alert alert-info">
                                                        <strong>Estudiante:</strong>
                                                        {{ $reporte->estudiante->persona->nombres }}
                                                        {{ $reporte->estudiante->persona->apellidos }}<br>
                                                        <strong>Tipo:</strong> {{ $reporte->tipo }}<br>
                                                        <strong>Periodo:</strong> {{ $reporte->periodo->nombre }}<br>
                                                        <strong>Promedio:</strong>
                                                        {{ $reporte->promedio_general ?? 'N/A' }}
                                                    </div>
                                                    @if ($reporte->tienePdf())
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-circle"></i>
                                                            El archivo PDF también será eliminado.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">
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
    <div class="modal fade" id="createReporteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Generar Nuevo Reporte
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route($routePrefix . '.reportes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Tip:</strong> Puedes dejar el promedio y asistencia en blanco y usar el botón "Calcular
                            Automáticamente" después de crear el reporte.
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estudiante <span class="text-danger">*</span></label>
                                    <select name="estudiante_id_create" class="form-control select2" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($estudiantes as $estudiante)
                                            <option value="{{ $estudiante->id }}"
                                                {{ old('estudiante_id_create') == $estudiante->id ? 'selected' : '' }}>
                                                {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Docente <span class="text-danger">*</span></label>
                                    <select name="docente_id_create" class="form-control select2" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($docentes as $docente)
                                            <option value="{{ $docente->id }}"
                                                {{ old('docente_id_create') == $docente->id ? 'selected' : '' }}>
                                                {{ $docente->persona->apellidos }}, {{ $docente->persona->nombres }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo <span class="text-danger">*</span></label>
                                    <select name="tipo_create" class="form-control" required>
                                        <option value="Bimestral"
                                            {{ old('tipo_create', 'Bimestral') == 'Bimestral' ? 'selected' : '' }}>
                                            Bimestral</option>
                                        <option value="Trimestral"
                                            {{ old('tipo_create') == 'Trimestral' ? 'selected' : '' }}>Trimestral</option>
                                        <option value="Anual" {{ old('tipo_create') == 'Anual' ? 'selected' : '' }}>Anual
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Periodo <span class="text-danger">*</span></label>
                                    <select name="periodo_id_create" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($periodos as $periodo)
                                            <option value="{{ $periodo->id }}"
                                                {{ old('periodo_id_create') == $periodo->id ? 'selected' : '' }}>
                                                {{ $periodo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gestión <span class="text-danger">*</span></label>
                                    <select name="gestion_id_create" class="form-control" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($gestiones as $gestion)
                                            <option value="{{ $gestion->id }}"
                                                {{ old('gestion_id_create') == $gestion->id ? 'selected' : '' }}>
                                                {{ $gestion->año }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="visible_tutor_create"
                                            name="visible_tutor_create" value="1"
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Promedio General (0-20)</label>
                                    <input type="number" name="promedio_general_create" class="form-control"
                                        step="0.01" min="0" max="20"
                                        value="{{ old('promedio_general_create') }}" placeholder="Opcional">
                                    <small class="text-muted">Dejar vacío para calcular después</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Porcentaje Asistencia (0-100)</label>
                                    <input type="number" name="porcentaje_asistencia_create" class="form-control"
                                        step="0.01" min="0" max="100"
                                        value="{{ old('porcentaje_asistencia_create') }}" placeholder="Opcional">
                                    <small class="text-muted">Dejar vacío para calcular después</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Archivo PDF</label>
                                    <input type="file" name="archivo_pdf_create" class="form-control-file"
                                        accept=".pdf">
                                    <small class="text-muted">Máximo 5MB</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Comentario Final del Docente</label>
                                    <textarea name="comentario_final_create" class="form-control" rows="3" maxlength="2000"
                                        placeholder="Escriba aquí el comentario final sobre el desempeño del estudiante...">{{ old('comentario_final_create') }}</textarea>
                                    <small class="text-muted">Máximo 2000 caracteres</small>
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
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#reportesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "responsive": true,
                "autoWidth": false,
                "order": [
                    [0, 'desc']
                ]
            });

            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Seleccione una opción',
                allowClear: true
            });

            @if (session('mensaje'))
                Swal.fire({
                    icon: '{{ session('icono') }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: true,
                    timer: 3000
                });
            @endif

            @if ($errors->any() && session('modal_id'))
                $('#editReporteModal{{ session('modal_id') }}').modal('show');
            @endif

            @if ($errors->has('estudiante_id_create') || $errors->has('docente_id_create'))
                $('#createReporteModal').modal('show');
            @endif
        });
    </script>
@stop
