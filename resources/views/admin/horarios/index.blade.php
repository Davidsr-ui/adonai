@extends('layouts.admin')

@section('title', 'Gestión de Horarios')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-calendar-alt text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Horarios</span>
    </div>
@stop

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Horarios Registrados</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#createHorarioModal">
                <i class="fas fa-plus me-1"></i> Nuevo Horario
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="horariosTable" class="table table-bordered table-hover table-sm align-middle">
                <thead>
                    <tr>
                        <th class="w-1">#</th>
                        <th>Gestión</th>
                        <th>Curso</th>
                        <th>Grado</th>
                        <th>Docente</th>
                        <th>Día</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Aula</th>
                        <th class="w-1">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($horarios as $index => $horario)
                        <tr>
                            <td class="text-muted">{{ $index + 1 }}</td>
                            <td><span class="badge bg-secondary">{{ $horario->gestion->nombre }}</span></td>
                            <td class="text-muted">{{ $horario->curso->nombre }}</td>
                            <td class="text-muted">{{ $horario->grado->nombre }} {{ $horario->grado->seccion ?? '' }}</td>
                            <td>
                                @if($horario->docente)
                                    <div class="fw-semibold">{{ $horario->docente->persona->apellidos }}, {{ $horario->docente->persona->nombres }}</div>
                                @else
                                    <span class="text-muted fst-italic">Sin asignar</span>
                                @endif
                             </div>
                            <td>
                                @php
                                    $diaClase = match($horario->dia_semana) {
                                        'Lunes'     => 'bg-primary',
                                        'Martes'    => 'bg-info',
                                        'Miércoles' => 'bg-secondary',
                                        'Jueves'    => 'bg-success',
                                        'Viernes'   => 'bg-warning text-dark',
                                        'Sábado'    => 'bg-danger',
                                        default     => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $diaClase }}">{{ $horario->dia_semana }}</span>
                             </div>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                            <td class="text-muted">{{ $horario->aula ?? '—' }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.horarios.show', $horario->id) }}" class="btn btn-outline-info btn-sm rounded-pill" title="Ver">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                    <button class="btn btn-outline-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editHorarioModal{{ $horario->id }}" title="Editar">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteHorarioModal{{ $horario->id }}" title="Eliminar">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Editar --}}
                        <div class="modal fade" id="editHorarioModal{{ $horario->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Horario</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.horarios.update', $horario->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            @if (session('modal_id') == $horario->id && $errors->any())
                                                @foreach ($errors->all() as $error)
                                                    <div class="alert alert-danger py-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                                                @endforeach
                                            @endif

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Gestión <span class="text-danger">*</span></label>
                                                    <select name="gestion_id" class="form-select" required>
                                                        <option value="">Seleccione una gestión...</option>
                                                        @foreach ($gestiones as $gestion)
                                                            <option value="{{ $gestion->id }}" {{ $horario->gestion_id == $gestion->id ? 'selected' : '' }}>
                                                                {{ $gestion->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Curso <span class="text-danger">*</span></label>
                                                    <select name="curso_id" class="form-select" required>
                                                        <option value="">Seleccione un curso...</option>
                                                        @foreach ($cursos as $curso)
                                                            <option value="{{ $curso->id }}" {{ $horario->curso_id == $curso->id ? 'selected' : '' }}>
                                                                {{ $curso->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Grado <span class="text-danger">*</span></label>
                                                    <select name="grado_id" class="form-select" required>
                                                        <option value="">Seleccione un grado...</option>
                                                        @foreach ($grados as $grado)
                                                            <option value="{{ $grado->id }}" {{ $horario->grado_id == $grado->id ? 'selected' : '' }}>
                                                                {{ $grado->nombre }} {{ $grado->seccion ?? '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Docente</label>
                                                    <select name="docente_id" class="form-select">
                                                        <option value="">Sin asignar...</option>
                                                        @foreach ($docentes as $docente)
                                                            <option value="{{ $docente->id }}" {{ $horario->docente_id == $docente->id ? 'selected' : '' }}>
                                                                {{ $docente->persona->apellidos }} {{ $docente->persona->nombres }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Día <span class="text-danger">*</span></label>
                                                    <select name="dia_semana" class="form-select" required>
                                                        <option value="">Seleccione...</option>
                                                        @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'] as $dia)
                                                            <option value="{{ $dia }}" {{ $horario->dia_semana == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                                    <input type="time" name="hora_inicio"
                                                        value="{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                                    <input type="time" name="hora_fin"
                                                        value="{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}"
                                                        class="form-control" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Aula</label>
                                                    <input type="text" name="aula" value="{{ $horario->aula }}"
                                                        class="form-control" maxlength="20" placeholder="Ej: A-101">
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
                        <div class="modal fade" id="deleteHorarioModal{{ $horario->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.horarios.destroy', $horario->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>{{ $horario->curso->nombre }}</strong><br>
                                                <small>{{ $horario->grado->nombre }} {{ $horario->grado->seccion ?? '' }} — {{ $horario->gestion->nombre }}</small><br>
                                                <small>{{ $horario->dia_semana }}, {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} – {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</small>
                                            </div>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer.
                                            </div>
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
</div>

{{-- Modal Crear --}}
<div class="modal fade" id="createHorarioModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nuevo Horario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.horarios.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any() && !session('modal_id'))
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger py-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Gestión <span class="text-danger">*</span></label>
                            <select name="gestion_id" class="form-select" required>
                                <option value="">Seleccione una gestión...</option>
                                @foreach ($gestiones as $gestion)
                                    <option value="{{ $gestion->id }}" {{ old('gestion_id') == $gestion->id ? 'selected' : '' }}>
                                        {{ $gestion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Curso <span class="text-danger">*</span></label>
                            <select name="curso_id" class="form-select" required>
                                <option value="">Seleccione un curso...</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}" {{ old('curso_id') == $curso->id ? 'selected' : '' }}>
                                        {{ $curso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Grado <span class="text-danger">*</span></label>
                            <select name="grado_id" class="form-select" required>
                                <option value="">Seleccione un grado...</option>
                                @foreach ($grados as $grado)
                                    <option value="{{ $grado->id }}" {{ old('grado_id') == $grado->id ? 'selected' : '' }}>
                                        {{ $grado->nombre }} {{ $grado->seccion ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Docente</label>
                            <select name="docente_id" class="form-select">
                                <option value="">Sin asignar...</option>
                                @foreach ($docentes as $docente)
                                    <option value="{{ $docente->id }}" {{ old('docente_id') == $docente->id ? 'selected' : '' }}>
                                        {{ $docente->persona->apellidos }} {{ $docente->persona->nombres }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Día <span class="text-danger">*</span></label>
                            <select name="dia_semana" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'] as $dia)
                                    <option value="{{ $dia }}" {{ old('dia_semana') == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                            <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                            <input type="time" name="hora_fin" value="{{ old('hora_fin') }}" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Aula</label>
                            <input type="text" name="aula" value="{{ old('aula') }}" class="form-control" maxlength="20" placeholder="Ej: A-101">
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    .gap-2 { gap: 0.5rem; }
    .rounded-pill { border-radius: 50rem !important; padding-left: 0.9rem; padding-right: 0.9rem; }

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
    /* DataTables oscuro */
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_length,
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_filter,
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_info,
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_paginate {
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button {
        color: #e9ecef !important;
        background: #1a1e2c !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4e73df !important;
        color: white !important;
    }
    body[data-bs-theme="dark"] .dataTables_wrapper .dataTables_filter input {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    /* Select2 en modo oscuro */
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
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#horariosTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
            responsive: true,
            autoWidth: false,
            order: [[1, 'desc'], [5, 'asc']]
        });

        $('.select2').select2({
            width: '100%',
            placeholder: 'Seleccione una opción',
            allowClear: true
        });

        @if (session('mensaje'))
            Swal.fire({
                icon: '{{ session('icono') }}',
                title: '{{ session('mensaje') }}',
                showConfirmButton: false,
                timer: 2500
            });
        @endif

        @if ($errors->any() && session('modal_id'))
            var modal = new bootstrap.Modal(document.getElementById('editHorarioModal{{ session('modal_id') }}'));
            modal.show();
        @endif

        @if ($errors->has('gestion_id') || $errors->has('curso_id') || $errors->has('grado_id') || $errors->has('dia_semana'))
            var modalCreate = new bootstrap.Modal(document.getElementById('createHorarioModal'));
            modalCreate.show();
        @endif
    });
</script>
@stop