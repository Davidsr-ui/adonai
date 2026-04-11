@extends('layouts.admin')

@section('title', 'Gestión de Cursos')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-book text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Cursos</span>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Cursos Registrados</h3>
            <div class="card-options">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCursoModal">
                    <i class="fas fa-plus me-1"></i> Nuevo Curso
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="cursosTable" class="table table-vcenter table-hover card-table">
                    <thead>
                        <tr>
                            <th class="w-1">ID</th>
                            <th>Nivel</th>
                            <th>Código</th>
                            <th>Nombre del Curso</th>
                            <th>Área Curricular</th>
                            <th>Hrs/Sem</th>
                            <th>Estado</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cursos as $curso)
                        <tr>
                            <td class="text-muted">{{ $curso->id }}</td>
                            <td><span class="badge bg-cyan-lt text-cyan">{{ $curso->nivel->nombre ?? 'Sin nivel' }}</span></td>
                            <td class="text-muted small">{{ $curso->codigo ?? '—' }}</td>
                            <td class="fw-semibold">{{ $curso->nombre }}</td>
                            <td class="text-muted">{{ $curso->area_curricular ?? '—' }}</td>
                            <td class="text-center text-muted">{{ $curso->horas_semanales }}</td>
                            <td>
                                @if($curso->estado == 'Activo')
                                    <span class="badge bg-success-lt text-success">Activo</span>
                                @else
                                    <span class="badge bg-danger-lt text-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="{{ route('admin.cursos.show', $curso->id) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editCursoModal{{ $curso->id }}" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCursoModal{{ $curso->id }}" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Editar --}}
                        <div class="modal modal-blur fade" id="editCursoModal{{ $curso->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Curso</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nivel <span class="text-danger">*</span></label>
                                                    <select name="nivel_id" class="form-select" required>
                                                        <option value="">-- Seleccione --</option>
                                                        @foreach($niveles as $nivel)
                                                            <option value="{{ $nivel->id }}" {{ old('nivel_id', $curso->nivel_id) == $nivel->id ? 'selected' : '' }}>
                                                                {{ $nivel->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Código del Curso</label>
                                                    <input type="text" name="codigo" class="form-control"
                                                        value="{{ old('codigo', $curso->codigo) }}" placeholder="Ej: MAT-101">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Nombre del Curso <span class="text-danger">*</span></label>
                                                    <input type="text" name="nombre" class="form-control"
                                                        value="{{ old('nombre', $curso->nombre) }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                    <select name="estado" class="form-select" required>
                                                        <option value="Activo" {{ old('estado', $curso->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                        <option value="Inactivo" {{ old('estado', $curso->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Área Curricular</label>
                                                    <input type="text" name="area_curricular" class="form-control"
                                                        value="{{ old('area_curricular', $curso->area_curricular) }}"
                                                        placeholder="Ej: Ciencias, Humanidades, etc.">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Horas Semanales <span class="text-danger">*</span></label>
                                                    <input type="number" name="horas_semanales" class="form-control"
                                                        value="{{ old('horas_semanales', $curso->horas_semanales) }}"
                                                        min="1" max="40" required>
                                                    <small class="text-muted">Entre 1 y 40 horas</small>
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
                        <div class="modal modal-blur fade" id="deleteCursoModal{{ $curso->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>{{ $curso->nombre }}</strong><br>
                                                @if($curso->codigo)
                                                    <small>Código: {{ $curso->codigo }}</small><br>
                                                @endif
                                                <small>{{ $curso->nivel->nombre ?? 'Sin nivel' }} — {{ $curso->horas_semanales }} hrs/sem</small>
                                            </div>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>Esta acción no se puede deshacer. Si el curso tiene docentes o matrículas asignadas, no podrá eliminarse.
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
    <div class="modal modal-blur fade" id="createCursoModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nuevo Curso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.cursos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->any() && !session('modal_id'))
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger py-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                            @endforeach
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nivel <span class="text-danger">*</span></label>
                                <select name="nivel_id_create" class="form-select" required>
                                    <option value="">-- Seleccione --</option>
                                    @foreach($niveles as $nivel)
                                        <option value="{{ $nivel->id }}" {{ old('nivel_id_create') == $nivel->id ? 'selected' : '' }}>
                                            {{ $nivel->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Código del Curso</label>
                                <input type="text" name="codigo_create" class="form-control"
                                    value="{{ old('codigo_create') }}" placeholder="Ej: MAT-101">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Nombre del Curso <span class="text-danger">*</span></label>
                                <input type="text" name="nombre_create" class="form-control"
                                    value="{{ old('nombre_create') }}" placeholder="Ej: Matemáticas" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Área Curricular</label>
                                <input type="text" name="area_curricular_create" class="form-control"
                                    value="{{ old('area_curricular_create') }}"
                                    placeholder="Ej: Ciencias, Humanidades, Comunicación, etc.">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Horas Semanales <span class="text-danger">*</span></label>
                                <input type="number" name="horas_semanales_create" class="form-control"
                                    value="{{ old('horas_semanales_create', 2) }}" min="1" max="40" required>
                                <small class="text-muted">Entre 1 y 40 horas</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select name="estado_create" class="form-select" required>
                                    <option value="Activo" {{ old('estado_create', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
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
        $(document).ready(function () {
            $('#cursosTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                responsive: true,
                autoWidth: false,
                order: [[3, 'asc']]
            });

            @if(session('mensaje'))
                Swal.fire({
                    icon: '{{ session('icono') }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: false,
                    timer: 2500
                });
            @endif

            @if($errors->any() && session('modal_id'))
                new bootstrap.Modal(document.getElementById('editCursoModal{{ session('modal_id') }}')).show();
            @endif

            @if($errors->has('nombre_create') || $errors->has('codigo_create') || $errors->has('horas_semanales_create') || $errors->has('nivel_id_create'))
                new bootstrap.Modal(document.getElementById('createCursoModal')).show();
            @endif
        });
    </script>
@stop