@extends('layouts.admin')

@section('title', 'Gestión de Talleres')

@section('content_header')
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-chalkboard text-primary"></i>
        <span class="fw-bold fs-4">Gestión de Talleres</span>
    </div>
@stop

@section('content')

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- CABECERA CON BOTÓN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-0">Lista de Talleres</h2>
            <p class="text-muted mb-0 small">Administra los talleres disponibles del colegio</p>
        </div>
        <button class="btn btn-primary" onclick="abrirModal()">
            <i class="fas fa-plus me-1"></i> Agregar Taller
        </button>
    </div>

    {{-- TABLA --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list me-2 text-muted"></i>Talleres Registrados
            </h3>
            <div class="card-options">
                <span class="badge bg-blue-lt text-blue">
                    {{ $talleres->count() }} taller{{ $talleres->count() !== 1 ? 'es' : '' }}
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-vcenter table-hover card-table">
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
                            <th class="w-1">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($talleres as $taller)
                        <tr>
                            <td>
                                @if($taller->imagen)
                                    <img src="{{ asset('storage/'.$taller->imagen) }}"
                                         class="rounded" style="width:64px;height:46px;object-fit:cover;">
                                @else
                                    <span class="avatar rounded bg-secondary-lt text-secondary">
                                        <i class="fas fa-image"></i>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $taller->nombre }}</div>
                                @if($taller->descripcion)
                                    <div class="text-muted small text-truncate" style="max-width:180px">{{ $taller->descripcion }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar avatar-xs rounded-circle bg-blue-lt text-blue">
                                        {{ strtoupper(substr($taller->instructor, 0, 1)) }}
                                    </span>
                                    <span>{{ $taller->instructor }}</span>
                                </div>
                            </td>
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
                            </td>
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
                            </td>
                            <td>
                                @if($taller->categoria)
                                    <span class="badge bg-purple-lt text-purple">{{ $taller->categoria }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($taller->costo)
                                    <span class="fw-semibold text-green">S/ {{ number_format($taller->costo, 2) }}</span>
                                @else
                                    <span class="badge bg-green-lt text-green">Gratuito</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="fas fa-users text-muted small"></i>
                                    <span class="fw-semibold">{{ $taller->cupos_maximos }}</span>
                                </div>
                            </td>
                            <td>
                                @if($taller->activo)
                                    <span class="badge bg-success-lt text-success">
                                        <i class="fas fa-check me-1"></i>Activo
                                    </span>
                                @else
                                    <span class="badge bg-danger-lt text-danger">
                                        <i class="fas fa-times me-1"></i>Inactivo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <button class="btn btn-sm btn-warning" onclick='editarTaller(@json($taller))' title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarTaller({{ $taller->id }})" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
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
                                        <button class="btn btn-primary" onclick="abrirModal()">
                                            <i class="fas fa-plus me-1"></i> Agregar Taller
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL AGREGAR / EDITAR --}}
    <div class="modal modal-blur fade" id="tallerModal" tabindex="-1">
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
                                <label class="form-check form-switch">
                                    <input type="hidden" name="activo" value="0">
                                    <input type="checkbox" name="activo" id="activoCheck" class="form-check-input" value="1" checked>
                                    <span class="form-check-label">Taller Activo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
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
    /* ── Título de página visible en modo oscuro ── */
    [data-bs-theme="dark"] .page-title {
        color: #c8d3e0 !important;
    }

    /* ── Badges en modo oscuro ── */
    [data-bs-theme="dark"] .badge.bg-success-lt {
        background-color: #1a3329 !important;
        color: #6edbb4 !important;
        border: 1px solid rgba(70,200,140,0.25) !important;
    }
    [data-bs-theme="dark"] .badge.bg-danger-lt {
        background-color: #3d1f1f !important;
        color: #ff8a8a !important;
        border: 1px solid rgba(255,100,100,0.25) !important;
    }
    [data-bs-theme="dark"] .badge.bg-purple-lt {
        background-color: #2a1f3d !important;
        color: #c084fc !important;
        border: 1px solid rgba(160,100,255,0.25) !important;
    }
    [data-bs-theme="dark"] .badge.bg-green-lt {
        background-color: #1a3329 !important;
        color: #6edbb4 !important;
        border: 1px solid rgba(70,200,140,0.25) !important;
    }
    [data-bs-theme="dark"] .badge.bg-blue-lt {
        background-color: #1a2a3d !important;
        color: #7ec8f7 !important;
        border: 1px solid rgba(80,160,230,0.25) !important;
    }
    [data-bs-theme="dark"] .badge.bg-secondary-lt {
        background-color: #2a2a2a !important;
        color: #aaaaaa !important;
    }

    /* ── Cards en modo oscuro ── */
    [data-bs-theme="dark"] .card {
        background-color: #1e2a3a !important;
        border-color: rgba(255,255,255,0.07) !important;
        color: #c8d3e0 !important;
    }
    [data-bs-theme="dark"] .card-header,
    [data-bs-theme="dark"] .card-footer {
        background-color: #1e2a3a !important;
        border-color: rgba(255,255,255,0.07) !important;
    }
    [data-bs-theme="dark"] .table td,
    [data-bs-theme="dark"] .table th {
        border-color: rgba(255,255,255,0.06) !important;
        color: #c8d3e0 !important;
    }
    [data-bs-theme="dark"] .table thead th {
        color: #7a8fa8 !important;
    }
    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        background-color: rgba(255,255,255,0.04) !important;
    }
    [data-bs-theme="dark"] .text-muted {
        color: #7a8fa8 !important;
    }
    [data-bs-theme="dark"] .text-green {
        color: #6edbb4 !important;
    }
    [data-bs-theme="dark"] .empty-title {
        color: #c8d3e0 !important;
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    setTimeout(function() { $('.alert').fadeOut('slow'); }, 5000);
});

function abrirModal(taller = null) {
    const form = document.getElementById('tallerForm');
    const title = document.getElementById('modalTitle');
    const header = document.getElementById('modalHeader');
    const methodInput = document.getElementById('formMethod');

    form.reset();

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
        document.getElementById('activoCheck').checked = true;
    }

    new bootstrap.Modal(document.getElementById('tallerModal')).show();
}

function editarTaller(taller) { abrirModal(taller); }

function eliminarTaller(id) {
    if (!confirm('¿Está seguro de eliminar este taller?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ url('admin/talleres') }}/" + id;
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="_method" value="DELETE">`;
    document.body.appendChild(form);
    form.submit();
}
</script>
@stop