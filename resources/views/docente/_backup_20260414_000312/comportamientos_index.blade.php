@extends('layouts.docente')

@section('title', 'Comportamientos')
@section('page_title')GestiÃ³n <span>Comportamientos</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    {{-- Filtros --}}
    <details class="d-filter-box" style="margin-bottom:18px">
        <summary class="d-filter-box__toggle"><i class="fas fa-filter"></i> Filtros de BÃºsqueda</summary>
        <div style="padding:16px 0 0">
            <form action="{{ route('docente.comportamientos.index') }}" method="GET">
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
                            <option value="{{ $e->id }}" {{ request('estudiante_id')==$e->id?'selected':'' }}>
                                {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-form-group">
                        <label class="d-label">Tipo</label>
                        <select name="tipo" class="d-select">
                            <option value="">Todos</option>
                            <option value="Positivo"  {{ request('tipo')=='Positivo' ?'selected':'' }}>Positivo</option>
                            <option value="Negativo"  {{ request('tipo')=='Negativo' ?'selected':'' }}>Negativo</option>
                            <option value="Neutro"    {{ request('tipo')=='Neutro'   ?'selected':'' }}>Neutro</option>
                        </select>
                    </div>
                    <div class="d-form-group">
                        <label class="d-label">Notificado</label>
                        <select name="notificado" class="d-select">
                            <option value="">Todos</option>
                            <option value="1" {{ request('notificado')==='1'?'selected':'' }}>SÃ­</option>
                            <option value="0" {{ request('notificado')==='0'?'selected':'' }}>No</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top:12px;display:flex;gap:8px">
                    <button type="submit" class="d-btn d-btn--sky"><i class="fas fa-search"></i> Buscar</button>
                    <a href="{{ route('docente.comportamientos.index') }}" class="d-btn d-btn--ghost"><i class="fas fa-eraser"></i> Limpiar</a>
                </div>
            </form>
        </div>
    </details>

    {{-- Tabla --}}
    <div class="d-card">
        <div class="d-card__hdr">
            <div class="d-card__title">
                <span class="d-card__ico d-card__ico--violet"><i class="fas fa-user-check"></i></span>
                Comportamientos Registrados
            </div>
            <button class="d-btn d-btn--sky" data-toggle="modal" data-target="#createComportamientoModal">
                <i class="fas fa-plus"></i> Registrar Comportamiento
            </button>
        </div>
        <div style="padding:0;overflow-x:auto">
            <table id="comportamientosTable" class="d-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Estudiante</th>
                        <th>Docente</th>
                        <th>DescripciÃ³n</th>
                        <th style="text-align:center">Tipo</th>
                        <th>SanciÃ³n</th>
                        <th style="text-align:center">Notif.</th>
                        <th style="text-align:center">Acc.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comportamientos as $c)
                    <tr>
                        <td class="d-mono" style="color:var(--muted)">{{ $c->id }}</td>
                        <td>
                            <div style="font-weight:600;font-size:.82rem">{{ $c->fecha_formateada }}</div>
                            <div style="font-size:.7rem;color:var(--muted)">{{ $c->dia_semana }}</div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="d-av">{{ strtoupper(substr($c->estudiante->persona->apellidos,0,1)) }}</div>
                                <div>
                                    <div style="font-weight:600;font-size:.82rem;color:var(--text)">{{ $c->estudiante->persona->apellidos }}, {{ $c->estudiante->persona->nombres }}</div>
                                    <div class="d-mono" style="font-size:.7rem;color:var(--muted)">{{ $c->estudiante->codigo_estudiante }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.8rem;color:var(--muted)">
                            @if($c->docente)
                                {{ $c->docente->persona->apellidos }}, {{ $c->docente->persona->nombres }}
                            @else â€”
                            @endif
                        </td>
                        <td style="max-width:220px;font-size:.8rem;color:var(--text2)">{{ \Str::limit($c->descripcion, 60) }}</td>
                        <td style="text-align:center">
                            <span class="d-badge d-badge--{{ $c->tipo_badge }}">
                                <i class="fas {{ $c->tipo_icon }}"></i> {{ $c->tipo }}
                            </span>
                        </td>
                        <td style="font-size:.8rem;color:var(--muted)">
                            @if($c->sancion)
                                <span class="d-badge d-badge--amber">{{ \Str::limit($c->sancion,25) }}</span>
                            @else â€”
                            @endif
                        </td>
                        <td style="text-align:center">
                            @if($c->notificado_tutor)
                                <span class="d-badge d-badge--green" style="font-size:.62rem"><i class="fas fa-check"></i> SÃ­</span>
                            @else
                                <span class="d-badge d-badge--slate" style="font-size:.62rem"><i class="fas fa-times"></i> No</span>
                            @endif
                        </td>
                        <td style="text-align:center">
                            <div style="display:flex;gap:5px;justify-content:center">
                                <a href="{{ route('docente.comportamientos.show',$c->id) }}" class="d-btn-icon d-btn-icon--sky" title="Ver"><i class="fas fa-eye"></i></a>
                                <button class="d-btn-icon d-btn-icon--green" data-toggle="modal" data-target="#editComportamientoModal{{ $c->id }}" title="Editar"><i class="fas fa-edit"></i></button>
                                <button class="d-btn-icon d-btn-icon--rose" data-toggle="modal" data-target="#deleteComportamientoModal{{ $c->id }}" title="Eliminar"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Editar --}}
                    <div class="modal fade" id="editComportamientoModal{{ $c->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content d-modal-content">
                                <div class="modal-header d-modal-hdr">
                                    <h5 class="d-modal-title"><i class="fas fa-edit" style="color:var(--green)"></i> Editar Comportamiento</h5>
                                    <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('docente.comportamientos.update',$c->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body d-modal-body">
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:12px">
                                            <div class="d-form-group">
                                                <label class="d-label">Estudiante <b class="d-req">*</b></label>
                                                <select name="estudiante_id" class="d-select" required>
                                                    <option value="">â€” Seleccione â€”</option>
                                                    @foreach($estudiantes as $e)
                                                    <option value="{{ $e->id }}" {{ old('estudiante_id',$c->estudiante_id)==$e->id?'selected':'' }}>
                                                        {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">Fecha <b class="d-req">*</b></label>
                                                <input type="date" name="fecha" class="d-input" value="{{ old('fecha', $c->fecha instanceof \Carbon\Carbon ? $c->fecha->format('Y-m-d') : $c->fecha) }}" required>
                                            </div>
                                        </div>
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:12px">
                                            <div class="d-form-group">
                                                <label class="d-label">Tipo <b class="d-req">*</b></label>
                                                <select name="tipo" class="d-select" required>
                                                    @foreach(['Positivo','Negativo','Neutro'] as $t)
                                                    <option value="{{ $t }}" {{ old('tipo',$c->tipo)==$t?'selected':'' }}>{{ $t }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="d-form-group">
                                                <label class="d-label">SanciÃ³n</label>
                                                <input type="text" name="sancion" class="d-input" value="{{ old('sancion',$c->sancion) }}" maxlength="255" placeholder="Opcional">
                                            </div>
                                        </div>
                                        <div class="d-form-group" style="margin-bottom:12px">
                                            <label class="d-label">DescripciÃ³n <b class="d-req">*</b></label>
                                            <textarea name="descripcion" class="d-textarea" rows="3" required maxlength="1000">{{ old('descripcion',$c->descripcion) }}</textarea>
                                        </div>
                                        <div class="d-form-group">
                                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--text)">
                                                <input type="checkbox" name="notificado_tutor" value="1" {{ old('notificado_tutor',$c->notificado_tutor)?'checked':'' }}>
                                                Notificar al Tutor
                                            </label>
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
                    <div class="modal fade" id="deleteComportamientoModal{{ $c->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content d-modal-content">
                                <div class="modal-header d-modal-hdr">
                                    <h5 class="d-modal-title"><i class="fas fa-exclamation-triangle" style="color:var(--rose)"></i> Confirmar EliminaciÃ³n</h5>
                                    <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="{{ route('docente.comportamientos.destroy',$c->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="modal-body d-modal-body">
                                        <p style="font-size:.84rem;color:var(--text)">Â¿Eliminar este registro de comportamiento?</p>
                                        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;font-size:.8rem;line-height:1.8;color:var(--muted)">
                                            <strong style="color:var(--text)">{{ $c->estudiante->persona->nombres }} {{ $c->estudiante->persona->apellidos }}</strong><br>
                                            <span class="d-badge d-badge--{{ $c->tipo_badge }}">{{ $c->tipo }}</span> Â· {{ $c->fecha_formateada }}<br>
                                            {{ \Str::limit($c->descripcion, 80) }}
                                        </div>
                                        <div style="margin-top:12px;background:rgba(244,63,94,.08);border:1px solid rgba(244,63,94,.2);border-radius:10px;padding:10px 14px;font-size:.78rem;color:var(--rose)">
                                            <i class="fas fa-exclamation-circle"></i> Esta acciÃ³n no se puede deshacer.
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
<div class="modal fade" id="createComportamientoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content d-modal-content">
            <div class="modal-header d-modal-hdr">
                <h5 class="d-modal-title"><i class="fas fa-plus" style="color:var(--brand)"></i> Registrar Comportamiento</h5>
                <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('docente.comportamientos.store') }}" method="POST">
                @csrf
                <div class="modal-body d-modal-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:12px">
                        <div class="d-form-group">
                            <label class="d-label">Estudiante <b class="d-req">*</b></label>
                            <select name="estudiante_id_create" class="d-select" required>
                                <option value="">â€” Seleccione â€”</option>
                                @foreach($estudiantes as $e)
                                <option value="{{ $e->id }}" {{ old('estudiante_id_create')==$e->id?'selected':'' }}>
                                    {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Fecha <b class="d-req">*</b></label>
                            <input type="date" name="fecha_create" class="d-input" value="{{ old('fecha_create', date('Y-m-d')) }}" required>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:12px">
                        <div class="d-form-group">
                            <label class="d-label">Tipo <b class="d-req">*</b></label>
                            <select name="tipo_create" class="d-select" required>
                                <option value="Positivo" {{ old('tipo_create','Positivo')=='Positivo'?'selected':'' }}>Positivo</option>
                                <option value="Negativo" {{ old('tipo_create')=='Negativo'?'selected':'' }}>Negativo</option>
                                <option value="Neutro"   {{ old('tipo_create')=='Neutro'  ?'selected':'' }}>Neutro</option>
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">SanciÃ³n <span style="color:var(--muted);font-weight:400">(Opcional)</span></label>
                            <input type="text" name="sancion_create" class="d-input" value="{{ old('sancion_create') }}" maxlength="255" placeholder="Ej: Llamado de atenciÃ³n">
                        </div>
                    </div>
                    <div class="d-form-group" style="margin-bottom:12px">
                        <label class="d-label">DescripciÃ³n <b class="d-req">*</b></label>
                        <textarea name="descripcion_create" class="d-textarea" rows="4" required maxlength="1000" placeholder="Describa el comportamiento observado...">{{ old('descripcion_create') }}</textarea>
                    </div>
                    <div class="d-form-group">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--text)">
                            <input type="checkbox" name="notificado_tutor_create" value="1" {{ old('notificado_tutor_create')?'checked':'' }}>
                            Notificar al Tutor
                            <small style="color:var(--muted);font-weight:400">(enviar notificaciÃ³n automÃ¡tica)</small>
                        </label>
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
.d-av{width:34px;height:34px;border-radius:9px;background:rgba(14,165,233,.12);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0}
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
.d-badge--slate{background:rgba(100,116,139,.1);color:var(--slate)}
.d-badge--amber{background:rgba(245,158,11,.1);color:var(--amber)}
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
    $('#comportamientosTable').DataTable({
        language:{url:'//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'},
        responsive:true,
        autoWidth:false,
        order:[[1,'desc'],[2,'asc']]
    });
    @if(session('mensaje'))
    Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});
    @endif
    @if($errors->any() && session('modal_id'))
    $('#editComportamientoModal{{ session("modal_id") }}').modal('show');
    @endif
    @if($errors->has('estudiante_id_create') || $errors->has('descripcion_create'))
    $('#createComportamientoModal').modal('show');
    @endif
});
</script>
@endsection