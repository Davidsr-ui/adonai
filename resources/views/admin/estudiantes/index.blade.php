@extends('layouts.admin')

@section('title', 'Gestión de Estudiantes')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-user-graduate text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Estudiantes</span>
    </div>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Estudiantes Registrados</h3>
            <div class="card-options gap-2">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalImportar">
                    <i class="fas fa-file-excel me-1"></i> Importar Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createEstudianteModal">
                    <i class="fas fa-plus me-1"></i> Nuevo Estudiante
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="estudiantesTable" class="table table-vcenter table-hover card-table">
                    <thead>
                        <tr>
                            <th class="w-1">#</th>
                            <th>Código</th>
                            <th>Estudiante</th>
                            <th>DNI</th>
                            <th>Grado</th>
                            <th>Tutor Principal</th>
                            <th>Año Ingreso</th>
                            <th>Condición</th>
                            <th>Estado</th>
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estudiantes as $index => $estudiante)
                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td class="text-muted small">{{ $estudiante->codigo_estudiante }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($estudiante->persona->foto_perfil)
                                            <span class="avatar avatar-sm"
                                                style="background-image: url('{{ asset('storage/' . $estudiante->persona->foto_perfil) }}')"></span>
                                        @else
                                            <span class="avatar avatar-sm bg-blue-lt text-blue fw-bold">
                                                {{ strtoupper(substr($estudiante->persona->nombres, 0, 1)) }}{{ strtoupper(substr($estudiante->persona->apellidos, 0, 1)) }}
                                            </span>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $estudiante->persona->dni }}</td>
                                <td class="text-muted">{{ $estudiante->grado ? $estudiante->grado->nombre_completo : '—' }}</td>
                                <td class="text-muted">{{ $estudiante->tutor_principal ? $estudiante->tutor_principal->nombre_completo : '—' }}</td>
                                <td class="text-center text-muted">{{ $estudiante->año_ingreso }}</td>
                                <td>
                                    @php
                                        $condClase = match($estudiante->condicion) {
                                            'Regular'   => 'bg-success-lt text-success',
                                            'Irregular' => 'bg-warning-lt text-warning',
                                            'Retirado'  => 'bg-danger-lt text-danger',
                                            default     => 'bg-secondary-lt text-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $condClase }}">{{ $estudiante->condicion }}</span>
                                </td>
                                <td>
                                    @if ($estudiante->persona->estado == 'Activo')
                                        <span class="badge bg-success-lt text-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger-lt text-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('admin.estudiantes.show', $estudiante->id) }}"
                                            class="btn btn-sm btn-info" title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#editEstudianteModal{{ $estudiante->id }}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteEstudianteModal{{ $estudiante->id }}" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Editar --}}
                            <div class="modal modal-blur fade" id="editEstudianteModal{{ $estudiante->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Estudiante</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.estudiantes.update', $estudiante->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                @if (session('modal_id') == $estudiante->id && $errors->any())
                                                    @foreach ($errors->all() as $error)
                                                        <div class="alert alert-danger py-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                                                    @endforeach
                                                @endif

                                                <h6 class="text-muted fw-bold mb-3"><i class="fas fa-id-card me-1"></i> Datos Personales</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">DNI <span class="text-danger">*</span></label>
                                                        <input type="text" name="dni" value="{{ $estudiante->persona->dni }}" class="form-control" required maxlength="20">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Nombres <span class="text-danger">*</span></label>
                                                        <input type="text" name="nombres" value="{{ $estudiante->persona->nombres }}" class="form-control" required maxlength="100">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                                                        <input type="text" name="apellidos" value="{{ $estudiante->persona->apellidos }}" class="form-control" required maxlength="100">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                                        <input type="date" name="fecha_nacimiento" value="{{ $estudiante->persona->fecha_nacimiento }}" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Género <span class="text-danger">*</span></label>
                                                        <select name="genero" class="form-select" required>
                                                            <option value="">Seleccione...</option>
                                                            <option value="M" {{ $estudiante->persona->genero == 'M' ? 'selected' : '' }}>Masculino</option>
                                                            <option value="F" {{ $estudiante->persona->genero == 'F' ? 'selected' : '' }}>Femenino</option>
                                                            <option value="Otro" {{ $estudiante->persona->genero == 'Otro' ? 'selected' : '' }}>Otro</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Estado <span class="text-danger">*</span></label>
                                                        <select name="estado" class="form-select" required>
                                                            <option value="Activo" {{ $estudiante->persona->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                            <option value="Inactivo" {{ $estudiante->persona->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Dirección</label>
                                                        <input type="text" name="direccion" value="{{ $estudiante->persona->direccion }}" class="form-control" maxlength="255">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Teléfono</label>
                                                        <input type="text" name="telefono" value="{{ $estudiante->persona->telefono }}" class="form-control" maxlength="20">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Teléfono Emergencia</label>
                                                        <input type="text" name="telefono_emergencia" value="{{ $estudiante->persona->telefono_emergencia }}" class="form-control" maxlength="20">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Foto de Perfil</label>
                                                        <input type="file" name="foto_perfil" class="form-control">
                                                        @if ($estudiante->persona->foto_perfil)
                                                            <img src="{{ asset('storage/' . $estudiante->persona->foto_perfil) }}" class="img-thumbnail mt-2" width="80">
                                                        @else
                                                            <p class="text-muted mt-1 small">Sin foto actual</p>
                                                        @endif
                                                        <small class="text-muted">JPG, JPEG, PNG — Máx. 2MB</small>
                                                    </div>
                                                </div>

                                                <h6 class="text-muted fw-bold mt-4 mb-3"><i class="fas fa-graduation-cap me-1"></i> Datos Académicos</h6>
                                                <div class="row g-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Código Estudiante <span class="text-danger">*</span></label>
                                                        <input type="text" name="codigo_estudiante" value="{{ $estudiante->codigo_estudiante }}" class="form-control" required maxlength="50">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Grado</label>
                                                        <select name="grado_id" class="form-select select2">
                                                            <option value="">Sin asignar...</option>
                                                            @foreach ($grados as $grado)
                                                                <option value="{{ $grado->id }}" {{ $estudiante->grado_id == $grado->id ? 'selected' : '' }}>
                                                                    {{ $grado->nombre_completo }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Año de Ingreso <span class="text-danger">*</span></label>
                                                        <input type="number" name="año_ingreso" value="{{ $estudiante->año_ingreso }}" class="form-control" required min="1900" max="{{ date('Y') }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Condición <span class="text-danger">*</span></label>
                                                        <select name="condicion" class="form-select" required>
                                                            <option value="">Seleccione...</option>
                                                            @foreach(['Regular','Irregular','Retirado'] as $cond)
                                                                <option value="{{ $cond }}" {{ $estudiante->condicion == $cond ? 'selected' : '' }}>{{ $cond }}</option>
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
                            <div class="modal modal-blur fade" id="deleteEstudianteModal{{ $estudiante->id }}" tabindex="-1">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmar</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.estudiantes.destroy', $estudiante->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <strong>{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</strong><br>
                                                    <small>Código: {{ $estudiante->codigo_estudiante }}</small><br>
                                                    <small>{{ $estudiante->grado ? $estudiante->grado->nombre_completo : 'Sin grado' }}</small>
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
    <div class="modal modal-blur fade" id="createEstudianteModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Nuevo Estudiante</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.estudiantes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->any() && !session('modal_id'))
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger py-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                            @endforeach
                        @endif

                        <h6 class="text-muted fw-bold mb-3"><i class="fas fa-id-card me-1"></i> Datos Personales</h6>
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
                                    <option value="Activo" {{ old('estado_create', 'Activo') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Inactivo" {{ old('estado_create') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
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
                                <small class="text-muted">JPG, JPEG, PNG — Máx. 2MB</small>
                            </div>
                        </div>

                        <h6 class="text-muted fw-bold mt-4 mb-3"><i class="fas fa-graduation-cap me-1"></i> Datos Académicos</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Código Estudiante <span class="text-danger">*</span></label>
                                <input type="text" name="codigo_estudiante_create" value="{{ old('codigo_estudiante_create') }}" class="form-control" required maxlength="50">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Grado</label>
                                <select name="grado_id_create" class="form-select select2">
                                    <option value="">Sin asignar...</option>
                                    @foreach ($grados as $grado)
                                        <option value="{{ $grado->id }}" {{ old('grado_id_create') == $grado->id ? 'selected' : '' }}>
                                            {{ $grado->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Año de Ingreso <span class="text-danger">*</span></label>
                                <input type="number" name="año_ingreso_create" value="{{ old('año_ingreso_create', date('Y')) }}" class="form-control" required min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Condición <span class="text-danger">*</span></label>
                                <select name="condicion_create" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach(['Regular','Irregular','Retirado'] as $cond)
                                        <option value="{{ $cond }}" {{ old('condicion_create') == $cond ? 'selected' : '' }}>{{ $cond }}</option>
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

    {{-- Modal Importar --}}
    <div class="modal modal-blur fade" id="modalImportar" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-file-excel me-2"></i>Importar Estudiantes</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.importar.proceso') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info py-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Selecciona el archivo Excel con la lista de alumnos y sus tutores.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Archivo Excel (.xlsx)</label>
                            <input type="file" name="archivo_excel" class="form-control" required accept=".xlsx,.xls,.csv">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success w-50"><i class="fas fa-upload me-1"></i>Importar</button>
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
            $('#estudiantesTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                responsive: true,
                autoWidth: false,
                order: [[0, 'asc']]
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
                new bootstrap.Modal(document.getElementById('editEstudianteModal{{ session('modal_id') }}')).show();
            @endif

            @if ($errors->has('dni_create') || $errors->has('nombres_create') || $errors->has('apellidos_create') || $errors->has('codigo_estudiante_create'))
                new bootstrap.Modal(document.getElementById('createEstudianteModal')).show();
            @endif
        });
    </script>
@stop