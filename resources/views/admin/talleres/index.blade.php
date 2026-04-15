@extends('layouts.admin')

@section('title', 'Gestión de Talleres')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-chalkboard text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Talleres</span>
    </div>
@stop

@section('content')
    {{-- ALERTAS CON SWEETALERT (usando session('success') del controlador) --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2500
                });
            });
        </script>
    @endif

    {{-- CABECERA CON BOTÓN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-0">Lista de Talleres</h2>
            <p class="text-muted mb-0 small">Administra los talleres disponibles del colegio</p>
        </div>
        <button class="btn btn-primary rounded-pill" onclick="abrirModal()">
            <i class="fas fa-plus me-1"></i> Agregar Taller
        </button>
    </div>

    {{-- TABLA SIN DATATABLES --}}
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-list me-2 text-muted"></i>Talleres Registrados
            </h3>
            <div class="card-tools">
                <span class="badge bg-primary">
                    {{ $talleres->count() }} taller{{ $talleres->count() !== 1 ? 'es' : '' }}
                </span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm align-middle">
                    <thead>
                        <tr>
                            <th style="width:80px">Imagen</th>
                            <th>Nombre</th>
                            <th>Instructor</th>
                            <th>Duración</th>
                            <th>Horario</th>
                            <th>Categoría</th>
                            <th>Costo</th>
                            <th>Cupos</th>
                            <th>Estado</th>
                            <th style="width:120px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($talleres as $taller)
                        <tr>
                            <td>
                                @if($taller->imagen && Storage::disk('public')->exists($taller->imagen))
                                    <img src="{{ asset('storage/'.$taller->imagen) }}"
                                         class="rounded" style="width:64px;height:46px;object-fit:cover;">
                                @else
                                    <div class="avatar rounded bg-secondary-lt d-flex align-items-center justify-content-center" style="width:64px;height:46px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                             </div>
                            <td>
                                <div class="fw-semibold">{{ $taller->nombre }}</div>
                                @if($taller->descripcion)
                                    <div class="text-muted small text-truncate" style="max-width:180px">{{ $taller->descripcion }}</div>
                                @endif
                             </div>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar avatar-xs rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:24px;height:24px;">
                                        {{ strtoupper(substr($taller->instructor, 0, 1)) }}
                                    </span>
                                    <span>{{ $taller->instructor }}</span>
                                </div>
                             </div>
                            <td>
                                @if($taller->duracion_inicio && $taller->duracion_fin)
                                    @php
                                        $ini = \Carbon\Carbon::parse($taller->duracion_inicio);
                                        $fin = \Carbon\Carbon::parse($taller->duracion_fin);
                                    @endphp
                                    <div class="small">
                                        <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                        {{ $ini->format('d M') }} — {{ $fin->format('d M Y') }}
                                    </div>
                                @else
                                    <span class="text-muted small">Sin fechas</span>
                                @endif
                             </div>
                            <td>
                                @if($taller->horario_inicio && $taller->horario_fin)
                                    <div class="small">
                                        <i class="fas fa-clock me-1 text-muted"></i>
                                        {{ \Carbon\Carbon::parse($taller->horario_inicio)->format('H:i') }}
                                        – {{ \Carbon\Carbon::parse($taller->horario_fin)->format('H:i') }}
                                    </div>
                                @else
                                    <span class="text-muted small">No definido</span>
                                @endif
                             </div>
                            <td>
                                @if($taller->categoria)
                                    <span class="badge bg-secondary">{{ $taller->categoria }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                             </div>
                            <td>
                                @if($taller->costo)
                                    <span class="fw-semibold text-success">S/ {{ number_format($taller->costo, 2) }}</span>
                                @else
                                    <span class="badge bg-success text-white">Gratuito</span>
                                @endif
                             </div>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="fas fa-users text-muted small"></i>
                                    <span class="fw-semibold">{{ $taller->cupos_maximos }}</span>
                                </div>
                             </div>
                            <td>
                                @if($taller->activo)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Activo</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactivo</span>
                                @endif
                             </div>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-outline-warning btn-sm rounded-pill" onclick='editarTaller(@json($taller))' title="Editar">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill" onclick="eliminarTaller({{ $taller->id }})" title="Eliminar">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                </div>
                             </div>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="empty">
                                        <div class="empty-icon">
                                            <i class="fas fa-chalkboard fa-2x text-muted"></i>
                                        </div>
                                        <p class="empty-title mt-2">No hay talleres registrados</p>
                                        <p class="empty-subtitle text-muted">Agrega el primer taller haciendo clic en el botón de arriba.</p>
                                        <div class="empty-action">
                                            <button class="btn btn-primary rounded-pill" onclick="abrirModal()">
                                                <i class="fas fa-plus me-1"></i> Agregar Taller
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL AGREGAR / EDITAR (con estilos corregidos para modo oscuro) --}}
    <div class="modal fade" id="tallerModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white" id="modalHeader">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-plus me-2"></i>Agregar Taller
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="tallerForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nombre del Taller <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Ej: Taller de Pintura">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="2" class="form-control" placeholder="Descripción breve del taller..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instructor <span class="text-danger">*</span></label>
                                <input type="text" name="instructor" id="instructor" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Categoría</label>
                                <input type="text" name="categoria" id="categoria" class="form-control" placeholder="Arte, Deporte, Música...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                <input type="date" name="duracion_inicio" id="duracion_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" name="duracion_fin" id="duracion_fin" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                <input type="time" name="horario_inicio" id="horario_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                <input type="time" name="horario_fin" id="horario_fin" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Costo (S/)</label>
                                <input type="number" name="costo" id="costo" class="form-control" step="0.01" min="0" placeholder="0.00 = Gratuito">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cupos Máximos <span class="text-danger">*</span></label>
                                <input type="number" name="cupos_maximos" id="cupos_maximos" min="1" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Imagen</label>
                                <input type="file" name="imagen" id="imagen" accept="image/*" class="form-control">
                                <small class="text-muted">Formatos: JPG, PNG — Máx: 2MB</small>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="hidden" name="activo" value="0">
                                    <input type="checkbox" name="activo" id="activoCheck" class="form-check-input" value="1" checked>
                                    <label class="form-check-label" for="activoCheck">Taller Activo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary rounded-pill">
                            <i class="fas fa-save me-1"></i> Guardar Taller
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
    .avatar.avatar-xs { width: 24px; height: 24px; font-size: 0.75rem; }

    /* Modo oscuro para la tabla y cards */
    body[data-bs-theme="dark"] .card {
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

    /* Modo oscuro para el modal */
    body[data-bs-theme="dark"] .modal-content {
        background-color: #1e2438 !important;
        border-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .modal-header {
        border-bottom-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .modal-footer {
        border-top-color: #2a3446 !important;
    }
    body[data-bs-theme="dark"] .modal-body {
        background-color: #1e2438 !important;
        color: #e9ecef !important;
    }

    /* Inputs y selects dentro del modal en modo oscuro */
    body[data-bs-theme="dark"] .form-control,
    body[data-bs-theme="dark"] .form-select {
        background-color: #0f1220 !important;
        border-color: #2a3446 !important;
        color: #e9ecef !important;
    }
    body[data-bs-theme="dark"] .form-control:focus,
    body[data-bs-theme="dark"] .form-select:focus {
        background-color: #0f1220 !important;
        color: #e9ecef !important;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    body[data-bs-theme="dark"] .form-check-input {
        background-color: #0f1220;
        border-color: #2a3446;
    }
    body[data-bs-theme="dark"] .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    body[data-bs-theme="dark"] .form-check-label {
        color: #e9ecef;
    }
    body[data-bs-theme="dark"] .btn-close-white {
        filter: invert(1);
    }

    /* Botones y badges */
    body[data-bs-theme="dark"] .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }
    body[data-bs-theme="dark"] .btn-outline-warning:hover {
        background-color: #ffc107;
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
    body[data-bs-theme="dark"] .text-success {
        color: #6fcf97 !important;
    }
    body[data-bs-theme="dark"] .badge.bg-secondary {
        background-color: #3a4458 !important;
    }
    body[data-bs-theme="dark"] .avatar.bg-secondary-lt {
        background-color: #2a3446 !important;
        color: #a8b3cf;
    }
</style>
@stop

@section('js')
<script>
function abrirModal(taller = null) {
    const form = document.getElementById('tallerForm');
    const title = document.getElementById('modalTitle');
    const header = document.getElementById('modalHeader');
    const methodInput = document.getElementById('formMethod');

    form.reset();
    document.getElementById('activoCheck').checked = true;

    if (taller) {
        title.innerHTML = '<i class="fas fa-edit me-2"></i>Editar Taller';
        header.className = 'modal-header bg-success text-white';
        form.action = "{{ url('admin/talleres') }}/" + taller.id;
        methodInput.value = 'PUT';

        document.getElementById('nombre').value         = taller.nombre || '';
        document.getElementById('descripcion').value    = taller.descripcion || '';
        document.getElementById('instructor').value     = taller.instructor || '';
        document.getElementById('categoria').value      = taller.categoria || '';
        document.getElementById('duracion_inicio').value = taller.duracion_inicio ? taller.duracion_inicio.substring(0,10) : '';
        document.getElementById('duracion_fin').value   = taller.duracion_fin ? taller.duracion_fin.substring(0,10) : '';
        document.getElementById('horario_inicio').value = taller.horario_inicio || '';
        document.getElementById('horario_fin').value    = taller.horario_fin || '';
        document.getElementById('costo').value          = taller.costo || '';
        document.getElementById('cupos_maximos').value  = taller.cupos_maximos || '';
        document.getElementById('activoCheck').checked  = taller.activo == 1;
    } else {
        title.innerHTML = '<i class="fas fa-plus me-2"></i>Agregar Taller';
        header.className = 'modal-header bg-primary text-white';
        form.action = "{{ route('admin.talleres.store') }}";
        methodInput.value = '';
    }

    new bootstrap.Modal(document.getElementById('tallerModal')).show();
}

function editarTaller(taller) { abrirModal(taller); }

function eliminarTaller(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará el taller permanentemente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ url('admin/talleres') }}/" + id;
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@stop