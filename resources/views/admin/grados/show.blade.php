@extends('layouts.admin')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-layer-group text-primary"></i>
        <span class="fw-bold fs-4">Detalle del Grado</span>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-3 pb-1">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Información del Grado
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5><i class="fas fa-layer-group"></i> Datos Generales</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Nivel Educativo</label>
                                    <p>
                                        <span class="badge bg-cyan text-white">
                                            <i class="fas fa-graduation-cap me-1"></i> 
                                            {{ $grado->nivel->nombre ?? 'Sin nivel asignado' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Nombre del Grado</label>
                                    <p><strong>{{ $grado->nombre }}</strong></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Sección</label>
                                    <p>
                                        @if($grado->seccion)
                                            <span class="badge bg-secondary">{{ $grado->seccion }}</span>
                                        @else
                                            <span class="text-muted">No tiene sección</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Estado</label>
                                    <p>
                                        @if($grado->estado == 'Activo')
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Activo</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Inactivo</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Turno</label>
                                    <p>
                                        @if($grado->turno)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i> {{ $grado->turno->nombre }}
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($grado->turno->hora_inicio)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($grado->turno->hora_fin)->format('H:i') }}
                                            </small>
                                        @else
                                            <span class="text-muted">Sin turno asignado</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Fecha de Creación</label>
                                    <p>{{ \Carbon\Carbon::parse($grado->created_at)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-3"><i class="fas fa-users"></i> Capacidad y Ocupación</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Capacidad Máxima</label>
                                    <p>
                                        <span class="badge bg-primary"><i class="fas fa-users me-1"></i> {{ $grado->capacidad_maxima }} estudiantes</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Estudiantes Matriculados</label>
                                    <p>
                                        <span class="badge bg-info">{{ $grado->estudiantes->count() }} estudiantes</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Vacantes Disponibles</label>
                                    <p>
                                        <span class="badge bg-success">{{ $grado->capacidad_disponible }} vacantes</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Porcentaje de Ocupación</label>
                                    <p>
                                        @php
                                            $porcentaje = $grado->porcentaje_ocupacion;
                                            $colorBadge = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 70 ? 'warning' : 'success');
                                        @endphp
                                        <span class="badge bg-{{ $colorBadge }}">{{ $porcentaje }}%</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Barra de progreso -->
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fw-semibold">Ocupación Visual:</label>
                                <div class="progress" style="height: 30px;">
                                    @php
                                        $colorProgress = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 70 ? 'warning' : 'success');
                                    @endphp
                                    <div class="progress-bar bg-{{ $colorProgress }}" 
                                         role="progressbar" 
                                         style="width: {{ $porcentaje }}%;" 
                                         aria-valuenow="{{ $porcentaje }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $grado->estudiantes->count() }} / {{ $grado->capacidad_maxima }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estudiantes -->
                        <h5 class="mt-4"><i class="fas fa-user-graduate"></i> Estudiantes Matriculados</h5>
                        <hr>
                        @if($grado->estudiantes->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>N°</th>
                                            <th>DNI</th>
                                            <th>Apellidos y Nombres</th>
                                            <th>Género</th>
                                            <th>Edad</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grado->estudiantes as $index => $estudiante)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $estudiante->persona->dni }}</td>
                                            <td><i class="fas fa-user me-1"></i> {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</td>
                                            <td class="text-center">
                                                @if($estudiante->persona->genero == 'M')
                                                    <span class="badge bg-primary">M</span>
                                                @elseif($estudiante->persona->genero == 'F')
                                                    <span class="badge bg-pink text-white">F</span>
                                                @else
                                                    <span class="badge bg-secondary">Otro</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($estudiante->persona->fecha_nacimiento)->age }} años</td>
                                            <td class="text-center">
                                                @if($estudiante->persona->estado == 'Activo')
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-danger">Inactivo</span>
                                                @endif
                                             </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No hay estudiantes matriculados en este grado actualmente.
                            </div>
                        @endif

                        <!-- Horarios -->
                        <h5 class="mt-4"><i class="fas fa-calendar-alt"></i> Horarios del Grado</h5>
                        <hr>
                        @if($grado->horarios->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Día</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Fin</th>
                                            <th>Curso</th>
                                            <th>Docente</th>
                                            <th>Aula</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($grado->horarios->sortBy('dia_semana') as $horario)
                                        <tr>
                                            <td><strong>{{ $horario->dia_semana }}</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                                            <td>{{ $horario->curso->nombre ?? 'N/A' }}</td>
                                            <td>
                                                @if($horario->docente)
                                                    {{ $horario->docente->persona->nombres }} {{ $horario->docente->persona->apellidos }}
                                                @else
                                                    <span class="text-muted">Sin asignar</span>
                                                @endif
                                             </td>
                                            <td>{{ $horario->aula ?? 'No asignada' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> No hay horarios configurados para este grado.
                            </div>
                        @endif

                        <!-- Estadísticas -->
                        <h5 class="mt-4"><i class="fas fa-chart-bar"></i> Estadísticas</h5>
                        <hr>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="small-box bg-info p-3 rounded text-center">
                                    <h3>{{ $grado->estudiantes->count() }}</h3>
                                    <p class="mb-0">Estudiantes</p>
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-success p-3 rounded text-center">
                                    <h3>{{ $grado->capacidad_disponible }}</h3>
                                    <p class="mb-0">Vacantes</p>
                                    <i class="fas fa-chair"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-warning p-3 rounded text-center">
                                    <h3>{{ $grado->horarios->count() }}</h3>
                                    <p class="mb-0">Horarios</p>
                                    <i class="fas fa-calendar"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-danger p-3 rounded text-center">
                                    <h3>{{ $grado->matriculas->count() }}</h3>
                                    <p class="mb-0">Matrículas</p>
                                    <i class="fas fa-file-alt"></i>
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
                                    <p>{{ \Carbon\Carbon::parse($grado->updated_at)->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-semibold">Tiempo desde creación</label>
                                    <p>{{ \Carbon\Carbon::parse($grado->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="d-flex gap-2 justify-content-start">
                                    <a href="{{ route('admin.grados.index') }}" class="btn btn-secondary rounded-pill">
                                        <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                                    </a>
                                    <button type="button" class="btn btn-outline-success rounded-pill" data-bs-toggle="modal" data-bs-target="#editGradoModal">
                                        <i class="fas fa-edit me-1"></i> Editar Grado
                                    </button>
                                    @if($grado->estudiantes->count() == 0)
                                        <button type="button" class="btn btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteGradoModal">
                                            <i class="fas fa-trash me-1"></i> Eliminar Grado
                                        </button>
                                    @endif
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
<div class="modal fade" id="editGradoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Editar Grado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.grados.update', $grado->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nivel <span class="text-danger">*</span></label>
                            <select name="nivel_id" class="form-select" required>
                                <option value="">-- Seleccione un nivel --</option>
                                @foreach(\App\Models\Nivel::where('estado', 'Activo')->orderBy('orden')->get() as $nivel)
                                    <option value="{{ $nivel->id }}" {{ $grado->nivel_id == $nivel->id ? 'selected' : '' }}>
                                        {{ $nivel->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Turno</label>
                            <select name="turno_id" class="form-select">
                                <option value="">-- Sin turno --</option>
                                @foreach(\App\Models\Turno::where('estado', 'activo')->get() as $turno)
                                    <option value="{{ $turno->id }}" {{ $grado->turno_id == $turno->id ? 'selected' : '' }}>
                                        {{ $turno->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Grado <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" value="{{ $grado->nombre }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sección</label>
                            <input type="text" name="seccion" class="form-control" value="{{ $grado->seccion }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacidad Máxima <span class="text-danger">*</span></label>
                            <input type="number" name="capacidad_maxima" class="form-control" value="{{ $grado->capacidad_maxima }}" min="{{ $grado->estudiantes->count() }}" max="100" required>
                            <small class="text-muted">Mínimo: {{ $grado->estudiantes->count() }} (estudiantes actuales)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado <span class="text-danger">*</span></label>
                            <select name="estado" class="form-select" required>
                                <option value="Activo" {{ $grado->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Inactivo" {{ $grado->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
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
@if($grado->estudiantes->count() == 0)
<div class="modal fade" id="deleteGradoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.grados.destroy', $grado->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>¿Está seguro de que desea eliminar el grado?</p>
                    <p class="mb-0"><strong>{{ $grado->nombre_completo }}</strong></p>
                    <p class="text-muted">Nivel: {{ $grado->nivel->nombre ?? 'N/A' }}</p>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-circle me-1"></i> Esta acción no se puede deshacer.
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
@endif
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