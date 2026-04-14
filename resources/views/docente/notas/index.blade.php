@extends('layouts.docente')

@section('title', 'Gestión de Notas')
@section('page_title')Gestión <span>Notas</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    {{-- Filtros --}}
    <details class="d-filter-box" style="margin-bottom:18px">
        <summary class="d-filter-box__toggle"><i class="fas fa-filter"></i> Filtros de Búsqueda</summary>
        <div style="padding:16px 0 0">
            <form action="{{ route('docente.notas.index') }}" method="GET">
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
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
                        <label class="d-label">Periodo</label>
                        <select name="periodo_id" class="d-select">
                            <option value="">Todos</option>
                            @foreach($periodos as $p)
                            <option value="{{ $p->id }}" {{ request('periodo_id')==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-form-group">
                        <label class="d-label">Tipo Evaluación</label>
                        <select name="tipo_evaluacion" class="d-select">
                            <option value="">Todos</option>
                            @foreach(['Parcial','Final','Práctica','Oral','Trabajo'] as $t)
                            <option value="{{ $t }}" {{ request('tipo_evaluacion')==$t?'selected':'' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="margin-top:12px;display:flex;gap:8px">
                    <button type="submit" class="d-btn d-btn--sky"><i class="fas fa-search"></i> Buscar</button>
                    <a href="{{ route('docente.notas.index') }}" class="d-btn d-btn--ghost"><i class="fas fa-eraser"></i> Limpiar</a>
                </div>
            </form>
        </div>
    </details>

    <div class="d-card">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--rose"><i class="fas fa-star"></i></span>Notas Registradas</div>
            <button class="d-btn d-btn--sky" data-toggle="modal" data-target="#createNotaModal"><i class="fas fa-plus"></i> Registrar Nota</button>
        </div>
        <div style="overflow-x:auto">
            <table id="notasTable" class="d-table">
                <thead><tr>
                    <th>ID</th><th>Estudiante</th><th>Curso</th><th>Periodo</th><th>Tipo</th>
                    <th style="text-align:center">Práct.</th><th style="text-align:center">Teoría</th>
                    <th style="text-align:center">Final</th><th>Estado</th><th style="text-align:center">Vis.</th><th style="text-align:center">Acc.</th>
                </tr></thead>
                <tbody>
                    @foreach($notas as $n)
                    <tr>
                        <td class="d-mono" style="color:var(--muted)">{{ $n->id }}</td>
                        <td>
                            <div style="font-weight:600;font-size:.82rem;color:var(--text)">{{ $n->matricula->estudiante->persona->apellidos }}, {{ $n->matricula->estudiante->persona->nombres }}</div>
                        </td>
                        <td style="font-size:.8rem;color:var(--muted)">{{ $n->matricula->curso->nombre }}</td>
                        <td><span class="d-badge d-badge--slate">{{ $n->periodo->nombre }}</span></td>
                        <td><span class="d-badge d-badge--{{ $n->tipo_evaluacion_badge }}">{{ $n->tipo_evaluacion }}</span></td>
                        <td style="text-align:center;font-size:.82rem">{{ $n->nota_practica ?? '—' }}</td>
                        <td style="text-align:center;font-size:.82rem">{{ $n->nota_teoria ?? '—' }}</td>
                        <td style="text-align:center"><span class="d-score d-score--{{ $n->estado_nota_badge }}">{{ $n->nota_final }}</span></td>
                        <td><span class="d-badge d-badge--{{ $n->estado_nota_badge }}">{{ $n->estado_nota_texto }}</span></td>
                        <td style="text-align:center">
                            @if($n->visible_tutor)<span class="d-badge d-badge--green" style="font-size:.6rem"><i class="fas fa-eye"></i></span>
                            @else<span class="d-badge d-badge--slate" style="font-size:.6rem"><i class="fas fa-eye-slash"></i></span>@endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:5px;justify-content:center">
                                <a href="{{ route('docente.notas.show',$n->id) }}" class="d-btn-icon d-btn-icon--sky"><i class="fas fa-eye"></i></a>
                                <button class="d-btn-icon d-btn-icon--green" data-toggle="modal" data-target="#editNotaModal{{ $n->id }}"><i class="fas fa-edit"></i></button>
                                <button class="d-btn-icon d-btn-icon--rose" data-toggle="modal" data-target="#deleteNotaModal{{ $n->id }}"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="editNotaModal{{ $n->id }}" tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content d-modal-content">
                                <div class="modal-header d-modal-hdr">
                                    <h5 class="d-modal-title"><i class="fas fa-edit" style="color:var(--green)"></i> Editar Nota</h5>
                                    <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('docente.notas.update',$n->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body d-modal-body">
                                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px">
                                            <div class="d-form-group" style="grid-column:1/3">
                                                <label class="d-label">Matrícula (Estudiante - Curso) <b class="d-req">*</b></label>
                                                <select name="matricula_id" class="d-select" required>
                                                    <option value="">— Seleccione —</option>
                                                    @foreach($matriculas as $m)
                                                    <option value="{{ $m->id }}" {{ old('matricula_id',$n->matricula_id)==$m->id?'selected':'' }}>{{ $m->estudiante->persona->apellidos }}, {{ $m->estudiante->persona->nombres }} - {{ $m->curso->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">Periodo <b class="d-req">*</b></label>
                                                <select name="periodo_id" class="d-select" required>
                                                    <option value="">— Sel. —</option>
                                                    @foreach($periodos as $p)
                                                    <option value="{{ $p->id }}" {{ old('periodo_id',$n->periodo_id)==$p->id?'selected':'' }}>{{ $p->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px">
                                            <div class="d-form-group">
                                                <label class="d-label">Tipo <b class="d-req">*</b></label>
                                                <select name="tipo_evaluacion" class="d-select" required>
                                                    @foreach(['Parcial','Final','Práctica','Oral','Trabajo'] as $t)
                                                    <option value="{{ $t }}" {{ old('tipo_evaluacion',$n->tipo_evaluacion)==$t?'selected':'' }}>{{ $t }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">N. Práctica</label>
                                                <input type="number" name="nota_practica" class="d-input" step="0.01" min="0" max="20" value="{{ old('nota_practica',$n->nota_practica) }}">
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">N. Teoría</label>
                                                <input type="number" name="nota_teoria" class="d-input" step="0.01" min="0" max="20" value="{{ old('nota_teoria',$n->nota_teoria) }}">
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">N. Final <b class="d-req">*</b></label>
                                                <input type="number" name="nota_final" class="d-input" step="0.01" min="0" max="20" value="{{ old('nota_final',$n->nota_final) }}" required>
                                            </div>
                                        </div>
                                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                                            <div class="d-form-group">
                                                <label class="d-label">F. Evaluación</label>
                                                <input type="date" name="fecha_evaluacion" class="d-input" value="{{ old('fecha_evaluacion',$n->fecha_evaluacion?$n->fecha_evaluacion->format('Y-m-d'):'') }}">
                                            </div>
                                            <div class="d-form-group" style="grid-column:2/4">
                                                <label class="d-label">Descripción</label>
                                                <input type="text" name="descripcion" class="d-input" value="{{ old('descripcion',$n->descripcion) }}" maxlength="500">
                                            </div>
                                        </div>
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px">
                                            <div class="d-form-group">
                                                <label class="d-label">Observaciones</label>
                                                <textarea name="observaciones" class="d-textarea" rows="2" maxlength="500">{{ old('observaciones',$n->observaciones) }}</textarea>
                                            </div>
                                            <div class="d-form-group" style="justify-content:flex-end">
                                                <label class="d-label">&nbsp;</label>
                                                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--text)">
                                                    <input type="checkbox" name="visible_tutor" value="1" {{ old('visible_tutor',$n->visible_tutor)?'checked':'' }}>
                                                    Visible para Tutores
                                                </label>
                                            </div>
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
                    <div class="modal fade" id="deleteNotaModal{{ $n->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content d-modal-content">
                                <div class="modal-header d-modal-hdr">
                                    <h5 class="d-modal-title"><i class="fas fa-exclamation-triangle" style="color:var(--rose)"></i> Confirmar Eliminación</h5>
                                    <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('docente.notas.destroy',$n->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body d-modal-body">
                                        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;font-size:.8rem;line-height:1.8;color:var(--muted)">
                                            <strong style="color:var(--text)">{{ $n->matricula->estudiante->persona->nombres }} {{ $n->matricula->estudiante->persona->apellidos }}</strong><br>
                                            {{ $n->matricula->curso->nombre }} · {{ $n->tipo_evaluacion }} · Nota: {{ $n->nota_final }}
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
<div class="modal fade" id="createNotaModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content d-modal-content">
            <div class="modal-header d-modal-hdr">
                <h5 class="d-modal-title"><i class="fas fa-plus" style="color:var(--brand)"></i> Registrar Nueva Nota</h5>
                <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('docente.notas.store') }}" method="POST">
                @csrf
                <div class="modal-body d-modal-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px">
                        <div class="d-form-group" style="grid-column:1/3">
                            <label class="d-label">Matrícula (Estudiante - Curso) <b class="d-req">*</b></label>
                            <select name="matricula_id_create" class="d-select" required>
                                <option value="">— Seleccione —</option>
                                @foreach($matriculas as $m)
                                <option value="{{ $m->id }}">{{ $m->estudiante->persona->apellidos }}, {{ $m->estudiante->persona->nombres }} - {{ $m->curso->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Periodo <b class="d-req">*</b></label>
                            <select name="periodo_id_create" class="d-select" required>
                                <option value="">— Sel. —</option>
                                @foreach($periodos as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:12px">
                        <div class="d-form-group">
                            <label class="d-label">Tipo <b class="d-req">*</b></label>
                            <select name="tipo_evaluacion_create" class="d-select" required>
                                <option value="Parcial">Parcial</option><option value="Final">Final</option>
                                <option value="Práctica">Práctica</option><option value="Oral">Oral</option><option value="Trabajo">Trabajo</option>
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">N. Práctica</label>
                            <input type="number" name="nota_practica_create" class="d-input" step="0.01" min="0" max="20">
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">N. Teoría</label>
                            <input type="number" name="nota_teoria_create" class="d-input" step="0.01" min="0" max="20">
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">N. Final <b class="d-req">*</b></label>
                            <input type="number" name="nota_final_create" class="d-input" step="0.01" min="0" max="20" required>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px">
                        <div class="d-form-group">
                            <label class="d-label">F. Evaluación</label>
                            <input type="date" name="fecha_evaluacion_create" class="d-input" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="d-form-group" style="grid-column:2/4">
                            <label class="d-label">Descripción</label>
                            <input type="text" name="descripcion_create" class="d-input" placeholder="Ej: Examen Parcial Unidad 1" maxlength="500">
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="d-form-group">
                            <label class="d-label">Observaciones</label>
                            <textarea name="observaciones_create" class="d-textarea" rows="2" maxlength="500"></textarea>
                        </div>
                        <div class="d-form-group" style="justify-content:flex-end">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--text);margin-top:auto">
                                <input type="checkbox" name="visible_tutor_create" value="1">
                                Visible para Tutores <small style="color:var(--muted);font-weight:400">(publicar ahora)</small>
                            </label>
                        </div>
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
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<style>
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
.d-score{display:inline-flex;align-items:center;justify-content:center;min-width:34px;padding:3px 9px;border-radius:6px;font-size:.85rem;font-weight:800}
.d-score--green{background:rgba(16,185,129,.1);color:var(--green)}
.d-score--sky{background:rgba(14,165,233,.1);color:var(--brand)}
.d-score--rose{background:rgba(244,63,94,.1);color:var(--rose)}
.d-badge--slate{background:rgba(100,116,139,.1);color:var(--slate)}
.d-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s}
.d-btn--sky{background:var(--brand);color:#fff}.d-btn--sky:hover{background:var(--brand-d);color:#fff}
.d-btn--green{background:var(--green);color:#fff}.d-btn--green:hover{filter:brightness(1.1);color:#fff}
.d-btn--ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.d-btn--rose{background:var(--rose);color:#fff}
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
    $('#notasTable').DataTable({language:{url:'//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'},responsive:true,autoWidth:false,order:[[0,'desc']]});
    @if(session('mensaje'))Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});@endif
    @if($errors->any() && session('modal_id'))$('#editNotaModal{{ session("modal_id") }}').modal('show');@endif
    @if($errors->has('matricula_id_create')||$errors->has('nota_final_create'))$('#createNotaModal').modal('show');@endif
});
</script>
@endsection

