@extends('adminlte::page')

@section('title', 'Gestión de Talleres')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Lista de Talleres</h1>
        <button class="btn btn-primary" onclick="abrirModal()">
            <i class="fas fa-plus"></i> Agregar Taller
        </button>
    </div>
@stop

@section('content')

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Lista de Talleres -->
    <div class="card mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Instructor</th>
                            <th>Duración</th>
                            <th>Horario</th>
                            <th>Categoría</th>
                            <th>Costo</th>
                            <th>Cupos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($talleres as $taller)
                        <tr>
                            <td>
                                @if($taller->imagen)
                                    <img src="{{ asset('storage/'.$taller->imagen) }}"
                                         style="width: 70px; height: 50px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <span class="text-muted">Sin imagen</span>
                                @endif
                            </td>

                            <td>{{ $taller->nombre }}</td>
                            <td>{{ $taller->instructor }}</td>

                            <!-- DURACIÓN -->
                            <td>
                                @if($taller->duracion_inicio && $taller->duracion_fin)
                                    @php
                                        $ini = \Carbon\Carbon::parse($taller->duracion_inicio);
                                        $fin = \Carbon\Carbon::parse($taller->duracion_fin);
                                        $textoDuracion = $ini->format('d M') . ' — ' . $fin->format('d M Y');
                                    @endphp
                                    <span class="text-dark fw-semibold">
                                        <i class="bi bi-calendar-event" style="font-size: 1rem; color:#1a73e8;"></i>
                                        {{ $textoDuracion }}
                                    </span>
                                @else
                                    <span class="text-muted">Sin fechas</span>
                                @endif
                            </td>

                            <!-- HORARIO -->
                            <td>
                                @if($taller->horario_inicio && $taller->horario_fin)
                                    {{ \Carbon\Carbon::parse($taller->horario_inicio)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($taller->horario_fin)->format('H:i') }}
                                @else
                                    <span class="text-muted">No definido</span>
                                @endif
                            </td>

                            <td>{{ $taller->categoria ?? '—' }}</td>

                            <td>{{ $taller->costo ? 'S/ '.$taller->costo : 'Gratuito' }}</td>

                            <td>{{ $taller->cupos_maximos }}</td>

                            <td>
                                <span class="badge {{ $taller->activo ? 'badge-success' : 'badge-danger' }}">
                                    {{ $taller->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-warning"
                                        onclick='editarTaller(@json($taller))'>
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-danger"
                                        onclick="eliminarTaller({{ $taller->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                No hay talleres registrados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>


    <!-- Modal Agregar/Editar -->
    <div class="modal fade" id="tallerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Agregar Taller</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <form id="tallerForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="">

                    <div class="modal-body">

                        <!-- Nombre -->
                        <div class="form-group">
                            <label class="form-label">Nombre del Taller <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>

                        <!-- Descripción -->
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="form-control"></textarea>
                        </div>

                        <!-- Instructor / Categoría -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Instructor <span class="text-danger">*</span></label>
                                    <input type="text" name="instructor" id="instructor" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Categoría</label>
                                    <input type="text" name="categoria" id="categoria" class="form-control"
                                           placeholder="Ej: Arte, Deporte, Música...">
                                </div>
                            </div>
                        </div>

                        <!-- Duración (FECHAS) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" name="duracion_inicio" id="duracion_inicio" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                    <input type="date" name="duracion_fin" id="duracion_fin" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Horario (HORAS) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                    <input type="time" name="horario_inicio" id="horario_inicio" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                    <input type="time" name="horario_fin" id="horario_fin" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Costos / Cupos -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Costo (S/)</label>
                                    <input type="number" name="costo" id="costo" class="form-control" step="0.01" min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Cupos Máximos <span class="text-danger">*</span></label>
                                    <input type="number" name="cupos_maximos" id="cupos_maximos" min="1" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Imagen -->
                        <div class="form-group">
                            <label class="form-label">Imagen</label>
                            <input type="file" name="imagen" id="imagen" accept="image/*" class="form-control">
                            <small class="text-muted">Formatos: JPG, PNG - Máx: 2MB</small>
                        </div>

                        <!-- Estado -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="activo" value="0">
                                <input type="checkbox" name="activo" id="activoCheck" class="custom-control-input" value="1" checked>
                                <label for="activoCheck" class="custom-control-label">Taller Activo</label>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Taller</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('js')
<script>
$(document).ready(function() {
    // Auto-cerrar alertas después de 5 segundos
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});

function abrirModal(taller = null) {
    const form = document.getElementById('tallerForm');
    const title = document.getElementById('modalTitle');
    const methodInput = document.getElementById('formMethod');

    // Limpiar form
    form.reset();

    if (taller) {
        // Modo Editar
        title.textContent = 'Editar Taller';
        form.action = "{{ url('admin/talleres') }}/" + taller.id;
        methodInput.value = 'PUT';

        // Llenar campos
        document.getElementById('nombre').value = taller.nombre || '';
        document.getElementById('descripcion').value = taller.descripcion || '';
        document.getElementById('instructor').value = taller.instructor || '';
        document.getElementById('categoria').value = taller.categoria || '';

        document.getElementById('duracion_inicio').value =
    taller.duracion_inicio ? taller.duracion_inicio.substring(0, 10) : '';

document.getElementById('duracion_fin').value =
    taller.duracion_fin ? taller.duracion_fin.substring(0, 10) : '';


        document.getElementById('horario_inicio').value = taller.horario_inicio || '';
        document.getElementById('horario_fin').value = taller.horario_fin || '';

        document.getElementById('costo').value = taller.costo || '';
        document.getElementById('cupos_maximos').value = taller.cupos_maximos || '';
        document.getElementById('activoCheck').checked = taller.activo == 1;

    } else {
        // Modo Crear
        title.textContent = 'Agregar Taller';
        form.action = "{{ route('admin.talleres.store') }}";
        methodInput.value = '';
        document.getElementById('activoCheck').checked = true;
    }

    $('#tallerModal').modal('show');
}

function editarTaller(taller) {
    abrirModal(taller);
}

function eliminarTaller(id) {
    if (!confirm('¿Está seguro de eliminar este taller? Esta acción no se puede deshacer.')) {
        return;
    }

    // Crear formulario para DELETE
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ url('admin/talleres') }}/" + id;

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';

    form.appendChild(csrfToken);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}
</script>
@stop

@section('css')
<style>
    .badge-lg {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
    }
</style>
@stop