@extends('layouts.docente')

@section('title', 'Gestión de Notas')
@section('page_title')Gestión <span>Notas</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    <div class="d-page-hdr">
        <div>
            <h2 class="d-page-hdr__title"><i class="fas fa-star"></i> Notas Registradas</h2>
            <p class="d-page-hdr__sub">Todas las notas asignadas a tus cursos</p>
        </div>
        <span class="d-badge d-badge--sky" style="font-size:.85rem;padding:8px 18px">
            Total: {{ $notas->count() }} notas
        </span>
    </div>

    {{-- Filtros --}}
    <div class="d-filters-card" style="margin:16px 0;background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:16px 20px">
        <form method="GET" action="{{ route('docente.notas.index') }}" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
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
            <div style="flex:1.5;min-width:140px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Periodo</label>
                <select name="periodo_id" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">Todos</option>
                    @foreach($periodos as $p)
                        <option value="{{ $p->id }}" {{ request('periodo_id')==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1.5;min-width:140px">
                <label style="font-size:.65rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block">Tipo Evaluación</label>
                <select name="tipo_evaluacion" style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:7px 10px;color:var(--text);width:100%;font-size:.8rem">
                    <option value="">Todos</option>
                    @foreach(['Parcial','Final','Práctica','Oral','Trabajo'] as $t)
                        <option value="{{ $t }}" {{ request('tipo_evaluacion')==$t?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:center">
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;cursor:pointer;font-size:.75rem">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <a href="{{ route('docente.notas.index') }}" class="d-btn" style="background:var(--surface2);border:1px solid var(--border);border-radius:30px;padding:5px 12px;color:var(--text);font-weight:500;font-size:.7rem;text-decoration:none">
                    <i class="fas fa-eraser"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- Tabla de notas --}}
    <div class="d-card" style="margin-top:20px">
        <div class="d-card__hdr d-flex justify-content-between align-items-center">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--rose"><i class="fas fa-star"></i></span>Notas Registradas</div>
            <div style="display: flex; justify-content: flex-end; width: 100%;">
                <a href="{{ route('docente.notas.create') }}" class="d-btn d-btn--sky" style="background:var(--brand);border:none;border-radius:30px;padding:5px 14px;color:#fff;font-weight:600;font-size:.75rem">
                    <i class="fas fa-plus"></i> Registrar Notas por Curso
                </a>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table id="notasTable" class="d-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Periodo</th>
                        <th>Tipo</th>
                        <th style="text-align:center">Práct.</th>
                        <th style="text-align:center">Teoría</th>
                        <th style="text-align:center">Final</th>
                        <th style="text-align:center">F. Evaluación</th>
                        <th>Estado</th>
                        <th style="text-align:center">Vis.</th>
                        <th style="text-align:center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notas as $n)
                    <tr>
                        <td class="d-mono">{{ $n->id }}</td>
                        <td>
                            <div class="d-av" style="display:inline-flex;margin-right:8px">{{ strtoupper(substr($n->matricula->estudiante->persona->apellidos,0,1)) }}</div>
                            {{ $n->matricula->estudiante->persona->apellidos }}, {{ $n->matricula->estudiante->persona->nombres }}
                        </td>
                        <td>{{ $n->matricula->curso->nombre }}</td>
                        <td><span class="d-badge d-badge--slate">{{ $n->periodo->nombre }}</span></td>
                        <td><span class="d-badge d-badge--{{ $n->tipo_evaluacion_badge }}">{{ $n->tipo_evaluacion }}</span></td>
                        <td style="text-align:center">{{ $n->nota_practica ?? '—' }}</td>
                        <td style="text-align:center">{{ $n->nota_teoria ?? '—' }}</td>
                        <td style="text-align:center"><span class="d-score d-score--{{ $n->estado_nota_badge }}">{{ $n->nota_final }}</span></td>
                        <td style="text-align:center">{{ $n->fecha_evaluacion ? \Carbon\Carbon::parse($n->fecha_evaluacion)->format('d/m/Y') : '—' }}</td>
                        <td><span class="d-badge d-badge--{{ $n->estado_nota_badge }}">{{ $n->estado_nota_texto }}</span></td>
                        <td style="text-align:center">
                            @if($n->visible_tutor)
                                <span class="d-badge d-badge--green" style="font-size:.6rem"><i class="fas fa-eye"></i></span>
                            @else
                                <span class="d-badge d-badge--slate" style="font-size:.6rem"><i class="fas fa-eye-slash"></i></span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:6px;justify-content:center">
                                <a href="{{ route('docente.notas.show', $n->id) }}" class="d-btn-icon d-btn-icon--sky" title="Ver detalle"><i class="fas fa-eye"></i></a>
                                <button type="button" class="d-btn-icon d-btn-icon--green open-edit-modal" data-id="{{ $n->id }}" title="Editar"><i class="fas fa-edit"></i></button>
                                <button type="button" class="d-btn-icon d-btn-icon--rose open-delete-modal" data-id="{{ $n->id }}" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div id="editModal" class="modal-custom" style="display:none;">
    <div class="modal-custom-overlay"></div>
    <div class="modal-custom-container">
        <div class="modal-custom-header">
            <h5>Editar Nota <span id="editNotaId"></span></h5>
            <button type="button" class="close-modal">&times;</button>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-custom-body">
                <div style="display:flex;flex-wrap:wrap;gap:16px">
                    <div style="flex:1">
                        <label>Matrícula ID</label>
                        <input type="number" name="matricula_id" id="edit_matricula_id" class="d-input" required style="width:100%">
                    </div>
                    <div style="flex:1">
                        <label>Periodo ID</label>
                        <input type="number" name="periodo_id" id="edit_periodo_id" class="d-input" required style="width:100%">
                    </div>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:16px;margin-top:12px">
                    <div style="flex:1">
                        <label>Práctica</label>
                        <input type="number" step="0.01" name="nota_practica" id="edit_nota_practica" class="d-input" style="width:100%">
                    </div>
                    <div style="flex:1">
                        <label>Teoría</label>
                        <input type="number" step="0.01" name="nota_teoria" id="edit_nota_teoria" class="d-input" style="width:100%">
                    </div>
                    <div style="flex:1">
                        <label>Final *</label>
                        <input type="number" step="0.01" name="nota_final" id="edit_nota_final" class="d-input" required style="width:100%">
                    </div>
                </div>
                <div style="margin-top:12px">
                    <label>Tipo Evaluación</label>
                    <select name="tipo_evaluacion" id="edit_tipo_evaluacion" class="d-select" style="width:100%">
                        @foreach(['Parcial','Final','Práctica','Oral','Trabajo'] as $tipo)
                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-top:12px">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="edit_descripcion" class="d-input" rows="2" style="width:100%"></textarea>
                </div>
                <div style="margin-top:12px">
                    <label>Observaciones</label>
                    <textarea name="observaciones" id="edit_observaciones" class="d-input" rows="2" style="width:100%"></textarea>
                </div>
                <div style="margin-top:12px">
                    <label>Fecha Evaluación</label>
                    <input type="date" name="fecha_evaluacion" id="edit_fecha_evaluacion" class="d-input" style="width:100%">
                </div>
                <div style="margin-top:12px">
                    <label style="display:flex;align-items:center;gap:8px">
                        <input type="checkbox" name="visible_tutor" value="1" id="edit_visible_tutor">
                        Visible para tutores
                    </label>
                </div>
            </div>
            <div class="modal-custom-footer">
                <button type="button" class="d-btn close-modal" style="background:var(--surface2);border:1px solid var(--border)">Cancelar</button>
                <button type="submit" class="d-btn d-btn--sky" style="background:var(--brand);color:#fff">Actualizar Nota</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
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
                <p>¿Estás seguro de eliminar la nota <strong id="deleteNotaId"></strong>?</p>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
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
    .d-score {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: .85rem;
        font-weight: 800;
    }
    .d-score--green { background: rgba(16,185,129,.1); color: var(--green); }
    .d-score--sky { background: rgba(14,165,233,.1); color: var(--brand); }
    .d-score--rose { background: rgba(244,63,94,.1); color: var(--rose); }
    .d-badge--slate { background: rgba(100,116,139,.1); color: var(--slate); }
    .d-badge--green { background: rgba(16,185,129,.1); color: var(--green); }
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
    .d-btn-icon--sky { background: rgba(14,165,233,.1); color: var(--brand); }
    .d-btn-icon--sky:hover { background: var(--brand); color: #fff; }
    .d-btn-icon--green { background: rgba(16,185,129,.1); color: var(--green); }
    .d-btn-icon--green:hover { background: var(--green); color: #fff; }
    .d-btn-icon--rose { background: rgba(244,63,94,.1); color: var(--rose); }
    .d-btn-icon--rose:hover { background: var(--rose); color: #fff; }
    .d-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
    .d-table thead tr { border-bottom: 2px solid var(--border); }
    .d-table thead th { padding: 11px 14px; font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); white-space: nowrap; }
    .d-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
    .d-table tbody tr:hover { background: var(--surface2); }
    .d-table tbody td { padding: 11px 14px; color: var(--text); }
    .d-filters-card .d-btn { transition: all .2s; }
    .d-filters-card .d-btn:hover { transform: translateY(-1px); }

    /* Estilos para modales personalizados */
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
    .d-input, .d-select {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 8px 10px;
        color: var(--text);
        font-size: .8rem;
        transition: all .2s;
    }
    .d-input:focus, .d-select:focus {
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
    /* Estilos para DataTables sin Bootstrap */
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
    $('#notasTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[0, 'desc']]
    });

    $(document).on('click', '.open-edit-modal', function() {
        var notaId = $(this).data('id');
        Swal.fire({
            title: 'Cargando...',
            text: 'Obteniendo datos de la nota',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        $.ajax({
            url: '{{ url("docente/notas") }}/' + notaId + '/edit',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Swal.close();
                $('#editNotaId').text('#' + data.id);
                $('#edit_matricula_id').val(data.matricula_id);
                $('#edit_periodo_id').val(data.periodo_id);
                $('#edit_nota_practica').val(data.nota_practica || '');
                $('#edit_nota_teoria').val(data.nota_teoria || '');
                $('#edit_nota_final').val(data.nota_final);
                $('#edit_tipo_evaluacion').val(data.tipo_evaluacion);
                $('#edit_descripcion').val(data.descripcion || '');
                $('#edit_observaciones').val(data.observaciones || '');
                $('#edit_fecha_evaluacion').val(data.fecha_evaluacion ? data.fecha_evaluacion.split('T')[0] : '');
                $('#edit_visible_tutor').prop('checked', data.visible_tutor == 1);
                $('#editForm').attr('action', '{{ url("docente/notas") }}/' + notaId);
                $('#editModal').css('display', 'flex');
            },
            error: function(xhr) {
                Swal.close();
                let msg = xhr.responseJSON?.error || 'Error al cargar la nota';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    $(document).on('click', '.open-delete-modal', function() {
        var notaId = $(this).data('id');
        $('#deleteNotaId').text('#' + notaId);
        $('#deleteForm').attr('action', '{{ url("docente/notas") }}/' + notaId);
        $('#deleteModal').css('display', 'flex');
    });

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