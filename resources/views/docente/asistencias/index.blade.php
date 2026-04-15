@extends('layouts.docente')

@section('title', 'Asistencias')
@section('page_title')Control <span>Asistencias</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    {{-- Filtros --}}
    <div class="d-filters-card" style="margin:16px 0;background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:16px 20px">
        <form method="GET" action="{{ route('docente.asistencias.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
            <div style="flex:1;min-width:130px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Fecha</label>
                <input type="date" name="fecha" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem" value="{{ request('fecha') }}">
            </div>
            <div style="flex:2;min-width:180px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Estudiante</label>
                <select name="estudiante_id" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">Todos</option>
                    @foreach($estudiantes as $e)
                    <option value="{{ $e->id }}" {{ request('estudiante_id')==$e->id?'selected':'' }}>{{ $e->persona->apellidos }}, {{ $e->persona->nombres }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1.5;min-width:140px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Curso</label>
                <select name="curso_id" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">Todos</option>
                    @foreach($cursos as $c)
                    <option value="{{ $c->id }}" {{ request('curso_id')==$c->id?'selected':'' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;min-width:120px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Estado</label>
                <select name="estado" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">Todos</option>
                    @foreach(['Presente','Ausente','Tardanza','Justificado'] as $s)
                    <option value="{{ $s }}" {{ request('estado')==$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:center">
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;cursor:pointer;font-size:.75rem">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="{{ route('docente.asistencias.index') }}" class="d-btn" style="background:var(--surface2);border:1px solid var(--border);border-radius:30px;padding:5px 12px;color:var(--text);font-weight:500;font-size:.7rem;text-decoration:none">
                    <i class="fas fa-eraser"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- Tabla --}}
    <div class="d-card" style="margin-top:20px">
        <div class="d-card__hdr d-flex justify-content-between align-items-center">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--amber"><i class="fas fa-clipboard-check"></i></span>Asistencias Registradas</div>
            <div style="display: flex; justify-content: flex-end; width: 100%;">
                <a href="{{ route('docente.asistencias.create') }}" class="d-btn d-btn--sky" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;font-size:.75rem">
                    <i class="fas fa-plus"></i> Registrar Asistencias
                </a>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table id="asistenciasTable" class="d-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Docente</th>
                        <th>Estado</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asistencias as $a)
                    <tr>
                        <td class="d-mono" style="color:var(--muted)">{{ $a->id }}</td>
                        <td>
                            <div style="font-weight:600;font-size:.82rem">{{ $a->fecha_formateada }}</div>
                            <div style="font-size:.7rem;color:var(--muted)">{{ $a->dia_semana }}</div>
                        </td>
                        <td>
                            <div class="d-av" style="display:inline-flex;margin-right:8px">{{ strtoupper(substr($a->estudiante->persona->apellidos,0,1)) }}</div>
                            <div style="display:inline-block">
                                <div style="font-weight:600;font-size:.82rem">{{ $a->estudiante->persona->apellidos }}, {{ $a->estudiante->persona->nombres }}</div>
                                <div class="d-mono" style="font-size:.7rem;color:var(--muted)">{{ $a->estudiante->codigo_estudiante }}</div>
                            </div>
                        </td>
                        <td style="font-size:.82rem">{{ $a->curso->nombre }}</td>
                        <td style="font-size:.8rem;color:var(--muted)">@if($a->docente) {{ $a->docente->persona->apellidos }}, {{ $a->docente->persona->nombres }} @else — @endif</td>
                        <td><span class="d-badge d-badge--{{ $a->estado_badge }}">{{ $a->estado }}</span></td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center">
                                <a href="{{ route('docente.asistencias.show', $a->id) }}" class="d-btn-icon d-btn-icon--sky" title="Ver detalle"><i class="fas fa-eye"></i></a>
                                <button type="button" class="d-btn-icon d-btn-icon--green open-edit-modal" data-id="{{ $a->id }}" title="Editar"><i class="fas fa-edit"></i></button>
                                <button type="button" class="d-btn-icon d-btn-icon--rose open-delete-modal" data-id="{{ $a->id }}" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </div>
                         </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDITAR (único, se llena dinámicamente) --}}
<div id="editModal" class="modal-custom" style="display:none;">
    <div class="modal-custom-overlay"></div>
    <div class="modal-custom-container">
        <div class="modal-custom-header">
            <h5>Editar Asistencia <span id="editAsistenciaId"></span></h5>
            <button type="button" class="close-modal">&times;</button>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-custom-body">
                <div style="display:flex;flex-wrap:wrap;gap:16px">
                    <div style="flex:1">
                        <label>Estudiante</label>
                        <select name="estudiante_id" id="edit_estudiante_id" class="d-select" required style="width:100%">
                            @foreach($estudiantes as $e)
                                <option value="{{ $e->id }}">{{ $e->persona->apellidos }}, {{ $e->persona->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1">
                        <label>Curso</label>
                        <select name="curso_id" id="edit_curso_id" class="d-select" required style="width:100%">
                            @foreach($cursos as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:12px">
                    <div style="flex:1">
                        <label>Fecha</label>
                        <input type="date" name="fecha" id="edit_fecha" class="d-input" required style="width:100%">
                    </div>
                    <div style="flex:1">
                        <label>Estado</label>
                        <select name="estado" id="edit_estado" class="d-select" required style="width:100%">
                            <option value="Presente">Presente</option>
                            <option value="Ausente">Ausente</option>
                            <option value="Tardanza">Tardanza</option>
                            <option value="Justificado">Justificado</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top:12px">
                    <label>Observaciones</label>
                    <textarea name="observaciones" id="edit_observaciones" class="d-input" rows="2" style="width:100%"></textarea>
                </div>
            </div>
            <div class="modal-custom-footer">
                <button type="button" class="d-btn close-modal" style="background:var(--surface2);border:1px solid var(--border)">Cancelar</button>
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand);color:#fff">Actualizar Asistencia</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL ELIMINAR (único) --}}
<div id="deleteModal" class="modal-custom" style="display:none;">
    <div class="modal-custom-overlay"></div>
    <div class="modal-custom-container" style="max-width:450px;">
        <div class="modal-custom-header">
            <h5>Confirmar eliminación</h5>
            <button type="button" class="close-modal">&times;</button>
        </div>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="modal-custom-body">
                <p>¿Estás seguro de eliminar esta asistencia?</p>
                <p class="text-muted" style="color:var(--muted);font-size:.75rem">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-custom-footer">
                <button type="button" class="d-btn close-modal" style="background:var(--surface2);border:1px solid var(--border)">Cancelar</button>
                <button type="submit" class="d-btn" style="background:var(--rose);color:#fff">Sí, eliminar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('css')
<style>
    .d-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .82rem;
    }
    .d-table thead tr {
        border-bottom: 2px solid var(--border);
    }
    .d-table thead th {
        padding: 11px 14px;
        font-size: .67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--muted);
        white-space: nowrap;
    }
    .d-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .15s;
    }
    .d-table tbody tr:hover {
        background: var(--surface2);
    }
    .d-table tbody td {
        padding: 11px 14px;
        color: var(--text);
    }
    .d-av {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(14,165,233,.12);
        color: var(--brand);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: .8rem;
        flex-shrink: 0;
    }
    .d-mono {
        font-family: monospace;
        font-size: .79rem;
        color: var(--muted);
    }
    .d-btn-icon {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        border: none;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }
    .d-btn-icon--sky {
        background: rgba(14,165,233,.1);
        color: var(--brand);
    }
    .d-btn-icon--sky:hover {
        background: var(--brand);
        color: #fff;
    }
    .d-btn-icon--green {
        background: rgba(16,185,129,.1);
        color: var(--green);
    }
    .d-btn-icon--green:hover {
        background: var(--green);
        color: #fff;
    }
    .d-btn-icon--rose {
        background: rgba(244,63,94,.1);
        color: var(--rose);
    }
    .d-btn-icon--rose:hover {
        background: var(--rose);
        color: #fff;
    }
    .d-badge--sky { background: rgba(14,165,233,.1); color: var(--brand); }
    .d-badge--green { background: rgba(16,185,129,.1); color: var(--green); }
    .d-badge--amber { background: rgba(245,158,11,.1); color: var(--amber); }
    .d-badge--rose { background: rgba(244,63,94,.1); color: var(--rose); }
    .d-badge--slate { background: rgba(100,116,139,.1); color: var(--slate); }
    .d-select, .d-input {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 10px;
        color: var(--text);
        font-size: .8rem;
        transition: all .2s;
    }
    .d-select:focus, .d-input:focus {
        outline: none;
        border-color: var(--brand);
        box-shadow: 0 0 0 2px rgba(14,165,233,.2);
    }
    .d-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: .75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }
    .d-btn--sky { background: var(--brand); color: #fff; }
    .d-btn--sky:hover { background: var(--brand-d); transform: translateY(-1px); }
    .d-btn--ghost { background: var(--surface2); border: 1px solid var(--border); color: var(--text); }
    .d-btn--ghost:hover { background: var(--border); }
    .d-btn--green { background: var(--green); color: #fff; }
    .d-btn--green:hover { filter: brightness(1.1); }
    .d-btn--rose { background: var(--rose); color: #fff; }
    .d-btn--rose:hover { filter: brightness(1.1); }

    /* Modales personalizados */
    .modal-custom {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-custom-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
    }
    .modal-custom-container {
        position: relative;
        background: var(--surface);
        border-radius: 20px;
        border: 1px solid var(--border);
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-lg);
        z-index: 1001;
    }
    .modal-custom-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        font-weight: 800;
    }
    .modal-custom-body {
        padding: 20px;
    }
    .modal-custom-footer {
        padding: 12px 20px;
        border-top: 1px solid var(--border);
        background: var(--surface2);
        text-align: right;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--muted);
        transition: color .2s;
    }
    .close-modal:hover {
        color: var(--text);
    }

    /* DataTables sin Bootstrap */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin: 12px 0;
        font-size: 0.8rem;
        color: var(--text2);
    }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 5px 8px;
        margin: 0 5px;
        color: var(--text);
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 4px 10px;
        margin: 0 2px;
        border-radius: 6px;
        background: var(--surface2);
        border: 1px solid var(--border);
        color: var(--text);
        cursor: pointer;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--brand-d);
        color: #fff;
    }
</style>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {
    $('#asistenciasTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[1, 'desc'], [2, 'asc']]
    });

    // EDITAR
    $(document).on('click', '.open-edit-modal', function() {
        var asistenciaId = $(this).data('id');
        Swal.fire({
            title: 'Cargando...',
            text: 'Obteniendo datos de la asistencia',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        $.ajax({
            url: '{{ url("docente/asistencias") }}/' + asistenciaId + '/edit',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Swal.close();
                $('#editAsistenciaId').text('#' + data.id);
                $('#edit_estudiante_id').val(data.estudiante_id);
                $('#edit_curso_id').val(data.curso_id);
                $('#edit_fecha').val(data.fecha);
                $('#edit_estado').val(data.estado);
                $('#edit_observaciones').val(data.observaciones || '');
                $('#editForm').attr('action', '{{ url("docente/asistencias") }}/' + asistenciaId);
                $('#editModal').css('display', 'flex');
            },
            error: function(xhr) {
                Swal.close();
                let msg = xhr.responseJSON?.error || 'Error al cargar la asistencia';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // ELIMINAR
    $(document).on('click', '.open-delete-modal', function() {
        var asistenciaId = $(this).data('id');
        $('#deleteForm').attr('action', '{{ url("docente/asistencias") }}/' + asistenciaId);
        $('#deleteModal').css('display', 'flex');
    });

    // CERRAR MODALES
    $(document).on('click', '.close-modal, .modal-custom-overlay', function() {
        $('#editModal, #deleteModal').css('display', 'none');
    });
    $(document).on('click', '.modal-custom-container', function(e) {
        e.stopPropagation();
    });
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') $('#editModal, #deleteModal').css('display', 'none');
    });
});

@if(session('mensaje'))
Swal.fire({
    icon: '{{ session("icono") }}',
    title: '{{ session("mensaje") }}',
    showConfirmButton: true,
    timer: 3000
});
@endif
</script>
@endsection