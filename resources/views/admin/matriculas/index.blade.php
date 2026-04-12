@extends('layouts.admin')

@section('title', 'Gestión de Matrículas')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-file-signature text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Matrículas</span>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Matrículas Registradas</h3>
            <div class="card-options">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createMatriculaModal">
                    <i class="fas fa-plus me-1"></i> Nueva Matrícula
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="matriculasTable" class="table table-vcenter table-hover card-table">
                    <thead>
                        <tr>
                            <th class="w-1">ID</th>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Grado</th>
                            <th>Nivel</th>
                            <th>Gestión</th>
                            <th>Estado</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matriculas as $matricula)
                        <tr>
                            <td class="text-muted">{{ $matricula->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $matricula->estudiante->persona->apellidos }}, {{ $matricula->estudiante->persona->nombres }}</div>
                                <div class="text-muted small">{{ $matricula->estudiante->codigo_estudiante }}</div>
                            </td>
                            <td class="text-muted">{{ $matricula->curso->nombre }}</td>
                            <td class="text-muted">{{ $matricula->grado->nombre_completo }}</td>
                            <td><span class="badge bg-cyan-lt text-cyan">{{ $matricula->grado->nivel->nombre ?? 'N/A' }}</span></td>
                            <td><span class="badge bg-secondary-lt text-secondary">{{ $matricula->gestion->nombre }}</span></td>
                            <td>
                                @php
                                    $estadoClase = match($matricula->estado) {
                                        'Matriculado' => 'bg-blue-lt text-blue',
                                        'Aprobado' => 'bg-success-lt text-success',
                                        'Retirado' => 'bg-warning-lt text-warning',
                                        'Desaprobado' => 'bg-danger-lt text-danger',
                                        default => 'bg-secondary-lt text-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $estadoClase }}">{{ $matricula->estado }}</span>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.matriculas.show', $matricula->id) }}" class="btn btn-sm btn-info" title="Ver"><i class="fas fa-eye"></i></a>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editMatriculaModal{{ $matricula->id }}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMatriculaModal{{ $matricula->id }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Editar --}}
                        <div class="modal modal-blur fade" id="editMatriculaModal{{ $matricula->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Matrícula</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.matriculas.update', $matricula->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                                                    <select name="estudiante_id" class="form-select select2" required>
                                                        <option value="">-- Seleccione --</option>
                                                        @foreach($estudiantes as $estudiante)
                                                            <option value="{{ $estudiante->id }}" {{ old('estudiante_id', $matricula->estudiante_id) == $estudiante->id ? 'selected' : '' }}>
                                                                {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }} - {{ $estudiante->codigo_estudiante }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Curso <span class="text-danger">*</span></label>
                                                    <select name="curso_id" class="form-select select2" required>
                                                        <option value="">-- Seleccione --</option>
                                                        @foreach($cursos as $curso)
                                                            <option value="{{ $curso->id }}" {{ old('curso_id', $matricula->curso_id) == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Grado <span class="text-danger">*</span></label>
                                                    <select name="grado_id" class="form-select select2" required>
                                                        <option value="">-- Seleccione --</option>
                                                        @foreach($grados as $grado)
                                                            <option value="{{ $grado->id }}" {{ old('grado_id', $matricula->grado_id) == $grado->id ? 'selected' : '' }}>
                                                                {{ $grado->nivel->nombre }} - {{ $grado->nombre_completo }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Gestión <span class="text-danger">*</span></label>
                                                    <select name="gestion_id" class="form-select" required>
                                                        <option value="">-- Seleccione --</option>
                                                        @foreach($gestiones as $gestion)
                                                            <option value="{{ $gestion->id }}" {{ old('gestion_id', $matricula->gestion_id) == $gestion->id ? 'selected' : '' }}>{{ $gestion->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                    <select name="estado" class="form-select" required>
                                                        @foreach(['Matriculado','Retirado','Aprobado','Desaprobado'] as $est)
                                                            <option value="{{ $est }}" {{ old('estado', $matricula->estado) == $est ? 'selected' : '' }}>{{ $est }}</option>
                                                        @endforeach
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
                        <div class="modal modal-blur fade" id="deleteMatriculaModal{{ $matricula->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.matriculas.destroy', $matricula->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>{{ $matricula->estudiante->persona->nombres }} {{ $matricula->estudiante->persona->apellidos }}</strong><br>
                                                <small>{{ $matricula->curso->nombre }} — {{ $matricula->grado->nombre_completo }}</small><br>
                                                <small>{{ $matricula->gestion->nombre }}</small>
                                            </div>
                                            <div class="alert alert-warning"><i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer. Si tiene notas no podrá eliminarse.</div>
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
    <div class="modal modal-blur fade" id="createMatriculaModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nueva Matrícula</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.matriculas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                                <select name="estudiante_id_create" class="form-select select2" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($estudiantes as $estudiante)
                                        <option value="{{ $estudiante->id }}" {{ old('estudiante_id_create') == $estudiante->id ? 'selected' : '' }}>
                                            {{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }} - {{ $estudiante->codigo_estudiante }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Curso <span class="text-danger">*</span></label>
                                <select name="curso_id_create" class="form-select select2" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($cursos as $curso)
                                        <option value="{{ $curso->id }}" {{ old('curso_id_create') == $curso->id ? 'selected' : '' }}>{{ $curso->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Grado <span class="text-danger">*</span></label>
                                <select name="grado_id_create" class="form-select select2" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($grados as $grado)
                                        <option value="{{ $grado->id }}" {{ old('grado_id_create') == $grado->id ? 'selected' : '' }}>
                                            {{ $grado->nivel->nombre }} - {{ $grado->nombre_completo }} ({{ $grado->estudiantes->count() }}/{{ $grado->capacidad_maxima }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gestión <span class="text-danger">*</span></label>
                                <select name="gestion_id_create" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($gestiones as $gestion)
                                        <option value="{{ $gestion->id }}" {{ old('gestion_id_create') == $gestion->id ? 'selected' : '' }}>{{ $gestion->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select name="estado_create" class="form-select" required>
                                    @foreach(['Matriculado','Retirado','Aprobado','Desaprobado'] as $est)
                                        <option value="{{ $est }}" {{ old('estado_create', 'Matriculado') == $est ? 'selected' : '' }}>{{ $est }}</option>
                                    @endforeach
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#matriculasTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                responsive: true, autoWidth: false, order: [[5, 'desc'], [3, 'asc']]
            });
            $('.select2').select2({ width: '100%', placeholder: 'Seleccione una opción', allowClear: true });
            @if(session('mensaje'))
                Swal.fire({ icon: '{{ session('icono') }}', title: '{{ session('mensaje') }}', timer: 3000 });
            @endif
            @if($errors->any() && session('modal_id'))
                new bootstrap.Modal(document.getElementById('editMatriculaModal{{ session('modal_id') }}')).show();
            @endif
            @if($errors->has('estudiante_id_create') || $errors->has('curso_id_create') || $errors->has('grado_id_create') || $errors->has('gestion_id_create'))
                new bootstrap.Modal(document.getElementById('createMatriculaModal')).show();
            @endif
        });
    </script>
@stop