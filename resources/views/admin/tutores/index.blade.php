@extends('layouts.admin')

@section('title', 'Tutores')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-user-tie text-primary"></i>
        <span class="fw-bold fs-4">Tutores</span>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tutores Registrados</h3>
            <div class="card-options">
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTutorModal">
                    <i class="fas fa-plus me-1"></i> Crear nuevo
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tutoresTable" class="table table-vcenter table-hover card-table">
                    <thead>
                        <tr>
                            <th class="w-1">Nro</th>
                            <th>DNI</th>
                            <th>Apellidos y Nombres</th>
                            <th>Código</th>
                            <th>Ocupación</th>
                            <th>Teléfono</th>
                            <th>Estudiantes</th>
                            <th>Estado</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $contador = 1; @endphp
                        @foreach($tutores as $tutor)
                            @if($tutor->persona)
                            <tr>
                                <td class="text-muted text-center">{{ $contador++ }}</td>
                                <td class="text-muted">{{ $tutor->persona->dni ?? 'N/A' }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $tutor->persona->apellidos ?? '' }} {{ $tutor->persona->nombres ?? 'N/A' }}</div>
                                </td>
                                <td class="text-muted">{{ $tutor->codigo_tutor ?? '-' }}</td>
                                <td class="text-muted">{{ $tutor->ocupacion ?? '-' }}</td>
                                <td class="text-muted">{{ $tutor->persona->telefono ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-cyan-lt text-cyan">{{ $tutor->estudiantes->count() }}</span>
                                </td>
                                <td>
                                    @if($tutor->persona->estado == 'Activo')
                                        <span class="badge bg-success-lt text-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger-lt text-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.tutores.show', $tutor->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editTutorModal{{ $tutor->id }}"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.tutores.destroy', $tutor->id) }}" method="POST" id="formDelete{{ $tutor->id }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmarEliminar({{ $tutor->id }})"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Crear --}}
    <div class="modal modal-blur fade" id="createTutorModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Crear Nuevo Tutor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.tutores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <h6 class="fw-bold text-muted text-uppercase mb-3">Datos Personales</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">DNI <span class="text-danger">*</span></label>
                                <input type="text" name="dni_create" value="{{ old('dni_create') }}" class="form-control" required maxlength="20">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" name="nombres_create" value="{{ old('nombres_create') }}" class="form-control" required maxlength="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" name="apellidos_create" value="{{ old('apellidos_create') }}" class="form-control" required maxlength="100">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_nacimiento_create" value="{{ old('fecha_nacimiento_create') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Género <span class="text-danger">*</span></label>
                                <select name="genero_create" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <option value="M" {{ old('genero_create') == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('genero_create') == 'F' ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ old('genero_create') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select name="estado_create" class="form-select" required>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion_create" value="{{ old('direccion_create') }}" class="form-control" maxlength="255">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono_create" value="{{ old('telefono_create') }}" class="form-control" maxlength="20">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Teléfono Emergencia</label>
                                <input type="text" name="telefono_emergencia_create" value="{{ old('telefono_emergencia_create') }}" class="form-control" maxlength="20">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Foto de Perfil</label>
                                <input type="file" name="foto_perfil" class="form-control">
                                <small class="text-muted">JPG, JPEG, PNG — Máx: 2MB</small>
                            </div>
                        </div>

                        <h6 class="fw-bold text-muted text-uppercase mt-4 mb-3">Datos del Tutor</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Código Tutor</label>
                                <input type="text" name="codigo_tutor_create" value="{{ old('codigo_tutor_create') }}" class="form-control" maxlength="50">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ocupación</label>
                                <input type="text" name="ocupacion_create" value="{{ old('ocupacion_create') }}" class="form-control" maxlength="100">
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

    {{-- Modales Editar --}}
    @foreach($tutores as $tutor)
        @if($tutor->persona)
        <div class="modal modal-blur fade" id="editTutorModal{{ $tutor->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Tutor</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.tutores.update', $tutor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            @if(session('modal_id') == $tutor->id && $errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <h6 class="fw-bold text-muted text-uppercase mb-3">Datos Personales</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">DNI <span class="text-danger">*</span></label>
                                    <input type="text" name="dni" value="{{ $tutor->persona->dni ?? '' }}" class="form-control" required maxlength="20">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" name="nombres" value="{{ $tutor->persona->nombres ?? '' }}" class="form-control" required maxlength="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" name="apellidos" value="{{ $tutor->persona->apellidos ?? '' }}" class="form-control" required maxlength="100">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_nacimiento" value="{{ $tutor->persona->fecha_nacimiento ?? '' }}" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Género <span class="text-danger">*</span></label>
                                    <select name="genero" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="M" {{ ($tutor->persona->genero ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ ($tutor->persona->genero ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
                                        <option value="Otro" {{ ($tutor->persona->genero ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select name="estado" class="form-select" required>
                                        <option value="Activo" {{ ($tutor->persona->estado ?? 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="Inactivo" {{ ($tutor->persona->estado ?? '') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" name="direccion" value="{{ $tutor->persona->direccion ?? '' }}" class="form-control" maxlength="255">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" name="telefono" value="{{ $tutor->persona->telefono ?? '' }}" class="form-control" maxlength="20">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Teléfono Emergencia</label>
                                    <input type="text" name="telefono_emergencia" value="{{ $tutor->persona->telefono_emergencia ?? '' }}" class="form-control" maxlength="20">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Foto de Perfil</label>
                                    <input type="file" name="foto_perfil" class="form-control">
                                    @if($tutor->persona->foto_perfil ?? false)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $tutor->persona->foto_perfil) }}" class="avatar avatar-xl rounded">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <h6 class="fw-bold text-muted text-uppercase mt-4 mb-3">Datos del Tutor</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Código Tutor</label>
                                    <input type="text" name="codigo_tutor" value="{{ $tutor->codigo_tutor ?? '' }}" class="form-control" maxlength="50">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ocupación</label>
                                    <input type="text" name="ocupacion" value="{{ $tutor->ocupacion ?? '' }}" class="form-control" maxlength="100">
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
        @if(session('modal_id') == $tutor->id && $errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new bootstrap.Modal(document.getElementById('editTutorModal{{ $tutor->id }}')).show();
                });
            </script>
        @endif
        @endif
    @endforeach

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tutoresTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                responsive: true, autoWidth: false, order: [[2, 'asc']]
            });
        });

        function confirmarEliminar(id) {
            Swal.fire({
                title: '¿Seguro que quiere eliminar este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d63939'
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('formDelete' + id).submit();
            });
        }

        @if(session('mensaje'))
            Swal.fire({ icon: '{{ session('icono') }}', title: '{{ session('mensaje') }}', timer: 2500, showConfirmButton: false });
        @endif
    </script>
@stop