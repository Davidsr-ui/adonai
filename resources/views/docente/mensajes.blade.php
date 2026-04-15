@extends('layouts.docente')

@section('title', 'Mensajería')
@section('page_title')Mensajería <span>Tutores</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    <div class="d-page-hdr">
        <div>
            <h2 class="d-page-hdr__title"><i class="fas fa-envelope"></i> Mensajería</h2>
            <p class="d-page-hdr__sub">Comunicación directa con tutores y apoderados</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
            @if($mensajesNoLeidos > 0)
            <span class="d-badge d-badge--rose" style="font-size:.82rem;padding:7px 14px">
                <i class="fas fa-bell"></i> {{ $mensajesNoLeidos }} no leídos
            </span>
            @endif
            <button class="d-btn d-btn--sky" data-toggle="modal" data-target="#nuevoMensajeModal">
                <i class="fas fa-plus"></i> Nuevo Mensaje
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="d-tabs" style="margin-top:20px">
        <button class="d-tab d-tab--active" data-tab="recibidos"><i class="fas fa-inbox"></i> Recibidos <span class="d-tab-count">{{ $mensajesRecibidos->count() }}</span></button>
        <button class="d-tab" data-tab="enviados"><i class="fas fa-paper-plane"></i> Enviados <span class="d-tab-count">{{ $mensajesEnviados->count() }}</span></button>
    </div>

    {{-- Recibidos --}}
    <div id="tab-recibidos" class="d-tab-pane d-tab-pane--active d-card">
        @if($mensajesRecibidos->count() > 0)
        <div style="overflow-x:auto">
            <table class="d-table">
                <thead><tr>
                    <th style="width:80px"></th>
                    <th>De (Tutor)</th>
                    <th>Estudiante</th>
                    <th>Asunto</th>
                    <th>Fecha</th>
                    <th style="width:60px"></th>
                </tr></thead>
                <tbody>
                    @foreach($mensajesRecibidos as $m)
                    @php $leido = $m->destinatarios->where('destinatario_id', Auth::id())->first()->leido ?? false; @endphp
                    <tr style="{{ !$leido ? 'font-weight:700' : '' }}">
                        <td>
                            @if(!$leido)<span class="d-badge d-badge--sky" style="font-size:.62rem">Nuevo</span>@endif
                            <span class="d-badge d-badge--{{ $m->badge_prioridad }}" style="font-size:.6rem"><i class="fas {{ $m->icono_prioridad }}"></i></span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="d-av">{{ strtoupper(substr($m->remitente->persona->nombres ?? 'T', 0, 1)) }}</div>
                                <span>{{ $m->remitente->persona->nombres ?? 'N/A' }} {{ $m->remitente->persona->apellidos ?? '' }}</span>
                            </div>
                        </td>
                        <td style="font-size:.8rem;color:var(--muted)">
                            @if($m->estudiante) {{ $m->estudiante->persona->nombres }} {{ $m->estudiante->persona->apellidos }} @else — @endif
                        </td>
                        <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ Str::limit($m->asunto, 45) }}</td>
                        <td style="font-size:.75rem;color:var(--muted);white-space:nowrap">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('docente.mensajeria.ver', $m->id) }}" class="d-btn-icon d-btn-icon--sky" title="Ver"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="d-empty-state"><i class="fas fa-inbox"></i><strong>Sin mensajes recibidos</strong></div>
        @endif
    </div>

    {{-- Enviados --}}
    <div id="tab-enviados" class="d-tab-pane d-card" style="display:none">
        @if($mensajesEnviados->count() > 0)
        <div style="overflow-x:auto">
            <table class="d-table">
                <thead><tr>
                    <th style="width:50px"></th>
                    <th>Para (Tutor)</th>
                    <th>Estudiante</th>
                    <th>Asunto</th>
                    <th>Fecha</th>
                    <th style="width:60px"></th>
                </tr></thead>
                <tbody>
                    @foreach($mensajesEnviados as $m)
                    <tr>
                        <td><span class="d-badge d-badge--{{ $m->badge_prioridad }}" style="font-size:.6rem"><i class="fas {{ $m->icono_prioridad }}"></i></span></td>
                        <td>
                            @if($m->destinatarios->first())
                            <div style="display:flex;align-items:center;gap:9px">
                                <div class="d-av">{{ strtoupper(substr($m->destinatarios->first()->destinatario->persona->nombres ?? 'T', 0, 1)) }}</div>
                                <span>{{ $m->destinatarios->first()->destinatario->persona->nombres ?? 'N/A' }} {{ $m->destinatarios->first()->destinatario->persona->apellidos ?? '' }}</span>
                            </div>
                            @endif
                        </td>
                        <td style="font-size:.8rem;color:var(--muted)">
                            @if($m->estudiante) {{ $m->estudiante->persona->nombres }} {{ $m->estudiante->persona->apellidos }} @else — @endif
                        </td>
                        <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ Str::limit($m->asunto, 45) }}</td>
                        <td style="font-size:.75rem;color:var(--muted);white-space:nowrap">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                        <td><a href="{{ route('docente.mensajeria.ver', $m->id) }}" class="d-btn-icon d-btn-icon--sky"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="d-empty-state"><i class="fas fa-paper-plane"></i><strong>Sin mensajes enviados</strong></div>
        @endif
    </div>
</div>

{{-- Modal Nuevo Mensaje --}}
<div class="modal fade" id="nuevoMensajeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:1px solid var(--border);background:var(--surface)">
            <form action="{{ route('docente.mensajeria.enviar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" style="border-bottom:1px solid var(--border);padding:18px 24px">
                    <h5 style="margin:0;font-size:.92rem;font-weight:700;color:var(--text)"><i class="fas fa-envelope" style="color:var(--brand)"></i> Nuevo Mensaje a Tutor</h5>
                    <button type="button" class="close" data-dismiss="modal" style="color:var(--muted)">&times;</button>
                </div>
                <div class="modal-body" style="padding:20px 24px">
                    <div class="d-form-row">
                        <div class="d-form-group">
                            <label class="d-label">Estudiante <b class="d-req">*</b></label>
                            <select name="estudiante_id" id="estudiante_id" class="d-select" required onchange="cargarTutores()">
                                <option value="">Seleccione un estudiante</option>
                                @foreach($tutores->unique('estudiante_id') as $item)
                                <option value="{{ $item->estudiante_id }}">{{ $item->estudiante_apellidos }} {{ $item->estudiante_nombres }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Tutor <b class="d-req">*</b></label>
                            <select name="destinatario_user_id" id="tutor_id" class="d-select" required>
                                <option value="">Primero seleccione un estudiante</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-form-row">
                        <div class="d-form-group">
                            <label class="d-label">Prioridad</label>
                            <select name="prioridad" class="d-select">
                                <option>Normal</option><option>Alta</option><option>Urgente</option>
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Asunto <b class="d-req">*</b></label>
                            <input type="text" name="asunto" class="d-input" required maxlength="255">
                        </div>
                    </div>
                    <div class="d-form-group" style="margin-top:12px">
                        <label class="d-label">Mensaje <b class="d-req">*</b></label>
                        <textarea name="contenido" class="d-textarea" rows="4" required></textarea>
                    </div>
                    <div class="d-form-group" style="margin-top:12px">
                        <label class="d-label">Adjuntos <span style="color:var(--muted);font-weight:400">(Opcional)</span></label>
                        <input type="file" name="archivos[]" class="d-file" multiple>
                        <small style="color:var(--muted);font-size:.72rem">Máximo 10MB por archivo</small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border);padding:14px 24px;gap:10px">
                    <button type="button" class="d-btn d-btn--ghost" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="d-btn d-btn--sky"><i class="fas fa-paper-plane"></i> Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.d-page-hdr{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:24px 0 8px}
.d-page-hdr__title{font-size:1.1rem;font-weight:800;color:var(--text);margin:0;display:flex;align-items:center;gap:8px}
.d-page-hdr__title i{color:var(--brand)}
.d-page-hdr__sub{font-size:.78rem;color:var(--muted);margin:4px 0 0}
.d-tabs{display:flex;gap:4px;border-bottom:2px solid var(--border)}
.d-tab{background:none;border:none;padding:10px 20px;font-size:.79rem;font-weight:600;color:var(--muted);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .2s;display:flex;align-items:center;gap:6px}
.d-tab--active{color:var(--brand);border-bottom-color:var(--brand)}
.d-tab-count{background:var(--surface2);border:1px solid var(--border);border-radius:100px;padding:1px 7px;font-size:.62rem}
.d-tab-pane--active{}
.d-table{width:100%;border-collapse:collapse;font-size:.82rem}
.d-table thead tr{border-bottom:2px solid var(--border)}
.d-table thead th{padding:11px 14px;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted)}
.d-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
.d-table tbody tr:hover{background:var(--surface2)}
.d-table tbody td{padding:11px 14px;color:var(--text)}
.d-av{width:32px;height:32px;border-radius:8px;background:rgba(14,165,233,.12);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.78rem;flex-shrink:0}
.d-btn-icon{width:32px;height:32px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
.d-btn-icon--sky{background:rgba(14,165,233,.1);color:var(--brand)}.d-btn-icon--sky:hover{background:var(--brand);color:#fff}
.d-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s}
.d-btn--sky{background:var(--brand);color:#fff}.d-btn--sky:hover{background:var(--brand-d);color:#fff}
.d-btn--ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}.d-btn--ghost:hover{border-color:var(--border2)}
.d-form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.d-form-group{display:flex;flex-direction:column;gap:5px;margin-bottom:0}
.d-label{font-size:.73rem;font-weight:600;color:var(--text)}
.d-req{color:var(--rose)}
.d-input,.d-select,.d-textarea{width:100%;background:var(--surface2);border:1px solid var(--border);border-radius:8px;padding:8px 12px;font-size:.82rem;color:var(--text);outline:none;transition:border .2s;font-family:var(--ff)}
.d-input:focus,.d-select:focus,.d-textarea:focus{border-color:var(--brand)}
.d-textarea{resize:vertical}
.d-file{font-size:.8rem;color:var(--text)}
.d-empty-state{display:flex;flex-direction:column;align-items:center;gap:10px;padding:48px 20px;color:var(--muted)}
.d-empty-state i{font-size:2rem;opacity:.2}
.d-empty-state strong{font-size:.9rem;color:var(--text)}
</style>
@endsection

@section('js')
<script>
const tutoresPorEstudiante = @json($tutores->groupBy('estudiante_id'));
function cargarTutores(){
    const id = document.getElementById('estudiante_id').value;
    const sel = document.getElementById('tutor_id');
    sel.innerHTML='<option value="">Seleccione un tutor</option>';
    if(id && tutoresPorEstudiante[id]){
        tutoresPorEstudiante[id].forEach(t=>{
            const o=document.createElement('option');
            o.value=t.user_id;
            o.textContent=t.tutor_nombres+' '+t.tutor_apellidos+' ('+t.relacion_familiar+')';
            sel.appendChild(o);
        });
    }
}
document.querySelectorAll('.d-tab').forEach(t=>{
    t.addEventListener('click',()=>{
        document.querySelectorAll('.d-tab').forEach(x=>x.classList.remove('d-tab--active'));
        t.classList.add('d-tab--active');
        document.querySelectorAll('.d-tab-pane').forEach(p=>p.style.display='none');
        document.getElementById('tab-'+t.dataset.tab).style.display='block';
    });
});
</script>
@endsection

