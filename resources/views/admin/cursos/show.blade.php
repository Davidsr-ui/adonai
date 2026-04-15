@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-book text-primary"></i>
        <span class="fw-bold fs-4">Detalle del Curso</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información del Curso
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5><i class="fas fa-book"></i> Datos Generales</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Grado</label>
                                    <p>
                                        <span class="badge bg-primary text-white">
                                            <i class="fas fa-graduation-cap me-1"></i> 
                                            {{ $curso->grado->nombre_completo ?? 'Sin grado asignado' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Nivel Educativo</label>
                                    <p>
                                        <span class="badge bg-cyan text-white">
                                            <i class="fas fa-layer-group me-1"></i> 
                                            {{ $curso->nivel->nombre ?? 'Sin nivel asignado' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Estado</label>
                                    <p>
                                        @if($curso->estado == 'Activo')
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Activo</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Inactivo</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Código del Curso</label>
                                    <p>{{ $curso->codigo ?? 'No asignado' }}</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Nombre del Curso</label>
                                    <p><strong>{{ $curso->nombre }}</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Área Curricular</label>
                                    <p>{{ $curso->area_curricular ?? 'No especificada' }}</p>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-3"><i class="fas fa-graduation-cap"></i> Información Académica</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Horas Semanales</label>
                                    <p>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i> {{ $curso->horas_semanales }} horas
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Total de Horas/Semestre</label>
                                    <p>
                                        <span class="badge bg-secondary">{{ $curso->horas_semanales * 16 }} horas</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Fecha de Creación</label>
                                    <p>{{ \Carbon\Carbon::parse($curso->created_at)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Docentes Asignados -->
                        <h5 class="mt-4"><i class="fas fa-chalkboard-teacher"></i> Docentes Asignados</h5>
                        <hr>
                        @if($curso->docentes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>DNI</th>
                                            <th>Docente</th>
                                            <th>Código Docente</th>
                                            <th>Especialidad</th>
                                            <th>Grado</th>
                                            <th class="text-center">Tutor de Aula</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($curso->docentes as $docente)
                                        <tr>
                                            <td>{{ $docente->persona->dni }}</td>
                                            <td><i class="fas fa-user me-1"></i> {{ $docente->persona->nombres }} {{ $docente->persona->apellidos }}</td>
                                            <td>{{ $docente->codigo_docente }}</td>
                                            <td>{{ $docente->especialidad ?? 'No especificada' }}</td>
                                            <td>
                                                @if($docente->pivot->grado_id)
                                                    {{ \App\Models\Grado::find($docente->pivot->grado_id)->nombre_completo ?? 'N/A' }}
                                                @else
                                                    <span class="text-muted">No asignado</span>
                                                @endif
                                             </div>
                                            <td class="text-center">
                                                @if($docente->pivot->es_tutor_aula)
                                                    <span class="badge bg-primary"><i class="fas fa-check"></i> Sí</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                             </div>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No hay docentes asignados a este curso actualmente.
                            </div>
                        @endif

                        <!-- Horarios -->
                        <h5 class="mt-4"><i class="fas fa-calendar-alt"></i> Horarios</h5>
                        <hr>
                        @if($curso->horarios->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Día</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Fin</th>
                                            <th>Aula</th>
                                            <th>Grado</th>
                                            <th>Docente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($curso->horarios as $horario)
                                        <tr>
                                            <td><strong>{{ $horario->dia_semana }}</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                                            <td>{{ $horario->aula ?? 'No asignada' }}</td>
                                            <td>{{ $horario->grado->nombre_completo ?? 'N/A' }}</td>
                                            <td>
                                                @if($horario->docente)
                                                    {{ $horario->docente->persona->nombres }} {{ $horario->docente->persona->apellidos }}
                                                @else
                                                    <span class="text-muted">Sin asignar</span>
                                                @endif
                                             </div>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No hay horarios configurados para este curso.
                            </div>
                        @endif

                        <!-- Estadísticas -->
                        <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
                        <hr>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="small-box bg-info p-3 rounded text-center">
                                    <h3>{{ $curso->docentes->count() }}</h3>
                                    <p class="mb-0">Docentes Asignados</p>
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-success p-3 rounded text-center">
                                    <h3>{{ $curso->matriculas->count() }}</h3>
                                    <p class="mb-0">Matrículas</p>
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-warning p-3 rounded text-center">
                                    <h3>{{ $curso->horarios->count() }}</h3>
                                    <p class="mb-0">Horarios</p>
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-danger p-3 rounded text-center">
                                    <h3>{{ $curso->asistencias->count() }}</h3>
                                    <p class="mb-0">Registros de Asistencia</p>
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <h5 class="mt-4"><i class="fas fa-info-circle"></i> Información Adicional</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Última Actualización</label>
                                    <p>{{ \Carbon\Carbon::parse($curso->updated_at)->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Tiempo desde creación</label>
                                    <p>{{ \Carbon\Carbon::parse($curso->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex gap-2 justify-content-start">
                                    <a href="{{ route('admin.cursos.index') }}" class="btn btn-secondary rounded-pill">
                                        <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                                    </a>
                                    <button type="button" class="btn btn-outline-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editCursoModal">
                                        <i class="fas fa-edit me-1"></i> Editar Curso
                                    </button>
                                    <button type="button" class="btn btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteCursoModal">
                                        <i class="fas fa-trash me-1"></i> Eliminar Curso
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Editar (simplificado) --}}
<div class="modal fade" id="editCursoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Editar Curso</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Grado <span class="text-danger">*</span></label>
                            <select name="grado_id" class="form-select" required>
                                <option value="">-- Seleccione un grado --</option>
                                @foreach(\App\Models\Grado::where('estado', 'Activo')->orderBy('nombre')->get() as $grado)
                                    <option value="{{ $grado->id }}" {{ $curso->grado_id == $grado->id ? 'selected' : '' }}>
                                        {{ $grado->nombre_completo }} ({{ $grado->nivel->nombre ?? 'Sin nivel' }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">El nivel se actualizará automáticamente según el grado.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código del Curso</label>
                            <input type="text" name="codigo" class="form-control" value="{{ $curso->codigo }}" placeholder="Ej: MAT-101">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Curso <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" value="{{ $curso->nombre }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Área Curricular</label>
                            <input type="text" name="area_curricular" class="form-control" value="{{ $curso->area_curricular }}" placeholder="Ej: Ciencias, Humanidades, etc.">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Horas Semanales <span class="text-danger">*</span></label>
                            <input type="number" name="horas_semanales" class="form-control" value="{{ $curso->horas_semanales }}" min="1" max="40" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select" required>
                                <option value="Activo" {{ $curso->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ $curso->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Eliminar --}}
<div class="modal fade" id="deleteCursoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                    <p class="text-muted">Grado: {{ $curso->grado->nombre_completo ?? 'N/A' }}</p>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-circle me-1"></i> Esta acción no se puede deshacer. Si el curso tiene docentes asignados o matrículas, no podrá eliminarse.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .gap-2 { gap: 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; padding-left: 0.9rem; padding-right: 0.9rem; }
    .small-box { background: rgba(0,0,0,0.05); transition: all 0.2s; }
    .small-box i { font-size: 2rem; margin-top: -0.5rem; opacity: 0.4; }

    /* Modo oscuro */
    body[data-bs-theme="dark"] .card,
    body[data-bs-theme="dark"] .modal-content {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .card-header {
        background-color: #171c2c !important;
        border-bottom-color: #2a3446 !important;
        color: #f8f9fa;
    }
    body[data-bs-theme="dark"] .table {
        background-color: #1a1e2c !important;
        color: #e9ecef !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .table td,
    body[data-bs-theme="dark"] .table th {
        border-color: #2a3446 !important;
        background-color: #1a1e2c !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .table thead th {
        background-color: #0f1220 !important;
        color: #f8f9fa !important;
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
    body[data-bs-theme="dark"] .small-box {
        background-color: #0f1220;
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .small-box i {
        opacity: 0.6;
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