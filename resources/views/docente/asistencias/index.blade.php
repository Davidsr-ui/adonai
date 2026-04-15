@extends('layouts.docente')

@section('title', 'Asistencias')
@section('page_title')Control <span>Asistencias</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    {{-- Filtros --}}
    <details class="d-filter-box" style="margin-bottom:18px">
        <summary class="d-filter-box__toggle"><i class="fas fa-filter"></i> Filtros de Búsqueda</summary>
        <div style="padding:16px 0 0">
            <form action="{{ route('docente.asistencias.index') }}" method="GET">
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
                    <div class="d-form-group">
                        <label class="d-label">Fecha</label>
                        <input type="date" name="fecha" class="d-input" value="{{ request('fecha') }}">
                    </div>
                    <div class="d-form-group">
                        <label class="d-label">Estudiante</label>
                        <select name="estudiante_id" class="d-select">
                            <option value="">Todos</option>
                            @foreach($estudiantes as $e)
                            <option value="{{ $e->id }}" {{ request('estudiante_id')==$e->id?'selected':'' }}>{{ $e->persona->apellidos }}, {{ $e->persona->nombres }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-form-group">
                        <label class="d-label">Curso</label>
                        <select name="curso_id" class="d-select">
                            <option value="">Todos</option>
                            @foreach($cursos as $c)
                            <option value="{{ $c->id }}" {{ request('curso_id')==$c->id?'selected':'' }}>{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-form-group">
                        <label class="d-label">Estado</label>
                        <select name="estado" class="d-select">
                            <option value="">Todos</option>
                            @foreach(['Presente','Ausente','Tardanza','Justificado'] as $s)
                            <option value="{{ $s }}" {{ request('estado')==$s?'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="margin-top:12px;display:flex;gap:8px">
                    <button type="submit" class="d-btn d-btn--sky"><i class="fas fa-search"></i> Buscar</button>
                    <a href="{{ route('docente.asistencias.index') }}" class="d-btn d-btn--ghost"><i class="fas fa-eraser"></i> Limpiar</a>
                </div>
            </form>
        </div>
    </details>

    {{-- Tabla --}}
    <div class="d-card">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--amber"><i class="fas fa-clipboard-check"></i></span>Asistencias Registradas</div>
            <div style="display:flex;gap:8px">
                <button class="d-btn d-btn--sky" data-toggle="modal" data-target="#createAsistenciaModal"><i class="fas fa-plus"></i> Registrar</button>
                <button class="d-btn d-btn--green" data-toggle="modal" data-target="#registroMasivoModal"><i class="fas fa-users"></i> Masivo</button>
            </div>
        </div>
        <div style="padding:0;overflow-x:auto">
            <table id="asistenciasTable" class="d-table">
                <thead><tr>
                    <th>ID</th><th>Fecha</th><th>Estudiante</th><th>Curso</th><th>Docente</th><th>Estado</th><th style="text-align:center">Acc.</th>
                </tr></thead>
                <tbody>
                    @foreach($asistencias as $a)
                    <tr>
                        <td class="d-mono" style="color:var(--muted)">{{ $a->id }}</td>
                        <td>
                            <div style="font-weight:600;font-size:.82rem">{{ $a->fecha_formateada }}</div>
                            <div style="font-size:.7rem;color:var(--muted)">{{ $a->dia_semana }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:.82rem">{{ $a->estudiante->persona->apellidos }}, {{ $a->estudiante->persona->nombres }}</div>
                            <div class="d-mono" style="font-size:.7rem;color:var(--muted)">{{ $a->estudiante->codigo_estudiante }}</div>
                        </td>
                        <td style="font-size:.82rem">{{ $a->curso->nombre }}</td>
                        <td style="font-size:.8rem;color:var(--muted)">
                            @if($a->docente) {{ $a->docente->persona->apellidos }}, {{ $a->docente->persona->nombres }} @else — @endif
                        </td>
                        <td><span class="d-badge d-badge--{{ $a->estado_badge }}">{{ $a->estado }}</span></td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:5px;justify-content:center">
                                <a href="{{ route('docente.asistencias.show',$a->id) }}" class="d-btn-icon d-btn-icon--sky"><i class="fas fa-eye"></i></a>
                                <button class="d-btn-icon d-btn-icon--green" data-toggle="modal" data-target="#editAsistenciaModal{{ $a->id }}"><i class="fas fa-edit"></i></button>
                                <button class="d-btn-icon d-btn-icon--rose" data-toggle="modal" data-target="#deleteAsistenciaModal{{ $a->id }}"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="editAsistenciaModal{{ $a->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content d-modal-content">
                                <div class="modal-header d-modal-hdr">
                                    <h5 class="d-modal-title"><i class="fas fa-edit" style="color:var(--green)"></i> Editar Asistencia</h5>
                                    <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('docente.asistencias.update',$a->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body d-modal-body">
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                                            <div class="d-form-group">
                                                <label class="d-label">Estudiante <b class="d-req">*</b></label>
                                                <select name="estudiante_id" class="d-select" required>
                                                    <option value="">— Seleccione —</option>
                                                    @foreach($estudiantes as $e)
                                                    <option value="{{ $e->id }}" {{ old('estudiante_id',$a->estudiante_id)==$e->id?'selected':'' }}>{{ $e->persona->apellidos }}, {{ $e->persona->nombres }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">Curso <b class="d-req">*</b></label>
                                                <select name="curso_id" class="d-select" required>
                                                    <option value="">— Seleccione —</option>
                                                    @foreach($cursos as $c)
                                                    <option value="{{ $c->id }}" {{ old('curso_id',$a->curso_id)==$c->id?'selected':'' }}>{{ $c->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">Fecha <b class="d-req">*</b></label>
                                                <input type="date" name="fecha" class="d-input" value="{{ old('fecha',$a->fecha->format('Y-m-d')) }}" required>
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">Estado <b class="d-req">*</b></label>
                                                <select name="estado" class="d-select" required>
                                                    @foreach(['Presente','Ausente','Tardanza','Justificado'] as $s)
                                                    <option value="{{ $s }}" {{ old('estado',$a->estado)==$s?'selected':'' }}>{{ $s }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-form-group" style="margin-top:12px">
                                            <label class="d-label">Observaciones</label>
                                            <textarea name="observaciones" class="d-textarea" rows="2" maxlength="500">{{ old('observaciones',$a->observaciones) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-modal-ftr">
                                        <button type="button" class="d-btn d-btn--ghost" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="d-btn d-btn--green"><i class="fas fa-save"></i> Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Eliminar --}}
                    <div class="modal fade" id="deleteAsistenciaModal{{ $a->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content d-modal-content">
                                <div class="modal-header d-modal-hdr">
                                    <h5 class="d-modal-title"><i class="fas fa-exclamation-triangle" style="color:var(--rose)"></i> Confirmar Eliminación</h5>
                                    <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('docente.asistencias.destroy',$a->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body d-modal-body">
                                        <p style="font-size:.84rem;color:var(--text)">¿Eliminar este registro de asistencia?</p>
                                        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;font-size:.8rem;line-height:1.8;color:var(--muted)">
                                            <strong style="color:var(--text)">{{ $a->estudiante->persona->nombres }} {{ $a->estudiante->persona->apellidos }}</strong><br>
                                            {{ $a->curso->nombre }} · {{ $a->fecha_formateada }} · {{ $a->estado }}
                                        </div>
                                        <div style="margin-top:12px;background:rgba(244,63,94,.08);border:1px solid rgba(244,63,94,.2);border-radius:10px;padding:10px 14px;font-size:.78rem;color:var(--rose)">
                                            <i class="fas fa-exclamation-circle"></i> Esta acción no se puede deshacer.
                                        </div>
                                    </div>
                                    <div class="modal-footer d-modal-ftr">
                                        <button type="button" class="d-btn d-btn--ghost" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="d-btn d-btn--rose"><i class="fas fa-trash"></i> Eliminar</button>
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
<div class="modal fade" id="createAsistenciaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content d-modal-content">
            <div class="modal-header d-modal-hdr">
                <h5 class="d-modal-title"><i class="fas fa-plus" style="color:var(--brand)"></i> Registrar Asistencia</h5>
                <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('docente.asistencias.store') }}" method="POST">
                @csrf
                <div class="modal-body d-modal-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                        <div class="d-form-group">
                            <label class="d-label">Estudiante <b class="d-req">*</b></label>
                            <select name="estudiante_id_create" class="d-select" required>
                                <option value="">— Seleccione —</option>
                                @foreach($estudiantes as $e)
                                <option value="{{ $e->id }}">{{ $e->persona->apellidos }}, {{ $e->persona->nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Curso <b class="d-req">*</b></label>
                            <select name="curso_id_create" class="d-select" required>
                                <option value="">— Seleccione —</option>
                                @foreach($cursos as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Fecha <b class="d-req">*</b></label>
                            <input type="date" name="fecha_create" class="d-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Estado <b class="d-req">*</b></label>
                            <select name="estado_create" class="d-select" required>
                                <option value="Presente">Presente</option>
                                <option value="Ausente">Ausente</option>
                                <option value="Tardanza">Tardanza</option>
                                <option value="Justificado">Justificado</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-form-group" style="margin-top:12px">
                        <label class="d-label">Observaciones</label>
                        <textarea name="observaciones_create" class="d-textarea" rows="2" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-modal-ftr">
                    <button type="button" class="d-btn d-btn--ghost" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="d-btn d-btn--sky"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Masivo --}}
<div class="modal fade" id="registroMasivoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content d-modal-content">
            <div class="modal-header d-modal-hdr">
                <h5 class="d-modal-title"><i class="fas fa-users" style="color:var(--green)"></i> Registro Masivo</h5>
                <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('docente.asistencias.registro-masivo') }}" method="POST">
                @csrf
                <div class="modal-body d-modal-body">
                    <div style="background:rgba(14,165,233,.06);border:1px solid rgba(14,165,233,.2);border-radius:10px;padding:12px 14px;font-size:.8rem;color:var(--brand);margin-bottom:16px">
                        <i class="fas fa-info-circle"></i> Se registrarán todos los estudiantes del curso como <strong>Presentes</strong>.
                    </div>
                    <div class="d-form-group" style="margin-bottom:12px">
                        <label class="d-label">Curso <b class="d-req">*</b></label>
                        <select name="curso_id" class="d-select" required>
                            <option value="">— Seleccione un curso —</option>
                            @foreach($cursos as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-form-group">
                        <label class="d-label">Fecha <b class="d-req">*</b></label>
                        <input type="date" name="fecha" class="d-input" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer d-modal-ftr">
                    <button type="button" class="d-btn d-btn--ghost" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="d-btn d-btn--green"><i class="fas fa-check"></i> Registrar Masivo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
.d-filter-box{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:14px 18px}
.d-filter-box__toggle{font-size:.82rem;font-weight:700;color:var(--text);cursor:pointer;display:flex;align-items:center;gap:8px;list-style:none}
.d-filter-box__toggle::-webkit-details-marker{display:none}
.d-filter-box__toggle i{color:var(--brand)}
.d-table{width:100%;border-collapse:collapse;font-size:.82rem}
.d-table thead tr{border-bottom:2px solid var(--border)}
.d-table thead th{padding:11px 14px;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);white-space:nowrap}
.d-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
.d-table tbody tr:hover{background:var(--surface2)}
.d-table tbody td{padding:11px 14px;color:var(--text)}
.d-mono{font-family:monospace;font-size:.79rem}
.d-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s}
.d-btn--sky{background:var(--brand);color:#fff}.d-btn--sky:hover{background:var(--brand-d);color:#fff}
.d-btn--green{background:var(--green);color:#fff}.d-btn--green:hover{filter:brightness(1.1);color:#fff}
.d-btn--ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.d-btn--rose{background:var(--rose);color:#fff}.d-btn--rose:hover{filter:brightness(1.1)}
.d-btn-icon{width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
.d-btn-icon--sky{background:rgba(14,165,233,.1);color:var(--brand)}.d-btn-icon--sky:hover{background:var(--brand);color:#fff}
.d-btn-icon--green{background:rgba(16,185,129,.1);color:var(--green)}.d-btn-icon--green:hover{background:var(--green);color:#fff}
.d-btn-icon--rose{background:rgba(244,63,94,.1);color:var(--rose)}.d-btn-icon--rose:hover{background:var(--rose);color:#fff}
.d-modal-content{border-radius:16px;border:1px solid var(--border);background:var(--surface)}
.d-modal-hdr{border-bottom:1px solid var(--border);padding:18px 24px;background:none}
.d-modal-title{margin:0;font-size:.92rem;font-weight:700;color:var(--text);display:flex;align-items:center;gap:8px}
.d-modal-close{color:var(--muted)}
.d-modal-body{padding:20px 24px}
.d-modal-ftr{border-top:1px solid var(--border);padding:14px 24px;gap:8px}
.d-form-group{display:flex;flex-direction:column;gap:5px}
.d-label{font-size:.73rem;font-weight:600;color:var(--text)}
.d-req{color:var(--rose)}
.d-input,.d-select,.d-textarea{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;font-size:.82rem;color:var(--text);outline:none;transition:border .2s;font-family:var(--ff)}
.d-input:focus,.d-select:focus,.d-textarea:focus{border-color:var(--brand)}
.d-textarea{resize:vertical}
</style>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    $('#asistenciasTable').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'},responsive:true,autoWidth:false,order:[[1,'desc'],[2,'asc']]});
    @if(session('mensaje'))Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});@endif
    @if($errors->any() && session('modal_id'))$('#editAsistenciaModal{{ session("modal_id") }}').modal('show');@endif
    @if($errors->has('estudiante_id_create')||$errors->has('curso_id_create')||$errors->has('fecha_create'))$('#createAsistenciaModal').modal('show');@endif
});
</script>
@endsection

