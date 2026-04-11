@extends('layouts.admin')

@section('title', 'Gestión de Horarios')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-calendar-alt text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Horarios</span>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Horarios Registrados</h3>
            <div class="card-options">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createHorarioModal">
                    <i class="fas fa-plus me-1"></i> Nuevo Horario
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="horariosTable" class="table table-vcenter table-hover card-table">
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
                                <td><span class="badge bg-secondary-lt text-secondary">{{ $horario->gestion->nombre }}</span></td>
                                <td class="text-muted">{{ $horario->curso->nombre }}</td>
                                <td class="text-muted">{{ $horario->grado->nombre }} {{ $horario->grado->seccion ?? '' }}</td>
                                <td>
                                    @if($horario->docente)
                                        <div class="fw-semibold">{{ $horario->docente->persona->apellidos }}, {{ $horario->docente->persona->nombres }}</div>
                                    @else
                                        <span class="text-muted fst-italic">Sin asignar</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $diaClase = match($horario->dia_semana) {
                                            'Lunes'     => 'bg-blue-lt text-blue',
                                            'Martes'    => 'bg-cyan-lt text-cyan',
                                            'Miércoles' => 'bg-indigo-lt text-indigo',
                                            'Jueves'    => 'bg-purple-lt text-purple',
                                            'Viernes'   => 'bg-pink-lt text-pink',
                                            'Sábado'    => 'bg-orange-lt text-orange',
                                            default     => 'bg-secondary-lt text-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $diaClase }}">{{ $horario->dia_semana }}</span>
                                </td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</td>
                                <td class="text-muted">{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</td>
                                <td class="text-muted">{{ $horario->aula ?? '—' }}</td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.horarios.show', $horario->id) }}" class="btn btn-sm btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editHorarioModal{{ $horario->id }}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteHorarioModal{{ $horario->id }}" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Editar --}}
                            <div class="modal modal-blur fade" id="editHorarioModal{{ $horario->id }}" tabindex="-1">
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
                                                        <select name="curso_id" class="form-select select2" required>
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
                                                        <select name="grado_id" class="form-select select2" required>
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
                                                        <select name="docente_id" class="form-select select2">
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
                            <div class="modal modal-blur fade" id="deleteHorarioModal{{ $horario->id }}" tabindex="-1">
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
    <div class="modal modal-blur fade" id="createHorarioModal" tabindex="-1">
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
                                <select name="curso_id" class="form-select select2" required>
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
                                <select name="grado_id" class="form-select select2" required>
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
                                <select name="docente_id" class="form-select select2">
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
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                new bootstrap.Modal(document.getElementById('editHorarioModal{{ session('modal_id') }}')).show();
            @endif

            @if ($errors->has('gestion_id') || $errors->has('curso_id') || $errors->has('grado_id') || $errors->has('dia_semana'))
                new bootstrap.Modal(document.getElementById('createHorarioModal')).show();
            @endif
        });
    </script>
@stop