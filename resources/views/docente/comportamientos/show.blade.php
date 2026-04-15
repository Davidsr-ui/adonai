@extends('layouts.docente')

@section('title', 'Detalle Comportamiento')
@section('page_title')Detalle <span>Comportamiento</span>@endsection

@section('content')
<div style="padding:0 28px 32px">

    <div style="margin-bottom:16px">
        <a href="{{ route('docente.comportamientos.index') }}" class="d-btn-back">
            <i class="fas fa-arrow-left"></i> Volver a Comportamientos
        </a>
    </div>

    @php
        $tipoBadge = match($comportamiento->tipo) {'Positivo'=>'green','Negativo'=>'rose',default=>'slate'};
        $tipoIcon  = match($comportamiento->tipo) {'Positivo'=>'fa-thumbs-up','Negativo'=>'fa-thumbs-down',default=>'fa-minus'};
        $fechaFmt  = $comportamiento->fecha instanceof \Carbon\Carbon
            ? $comportamiento->fecha->format('d/m/Y')
            : \Carbon\Carbon::parse($comportamiento->fecha)->format('d/m/Y');
        $diaSemana = $comportamiento->fecha instanceof \Carbon\Carbon
            ? $comportamiento->fecha->locale('es')->isoFormat('dddd')
            : \Carbon\Carbon::parse($comportamiento->fecha)->locale('es')->isoFormat('dddd');
    @endphp

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">

        {{-- Columna Principal --}}
        <div style="display:flex;flex-direction:column;gap:18px">

            {{-- Detalle del comportamiento --}}
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title">
                        <span class="d-card__ico d-card__ico--violet"><i class="fas fa-user-check"></i></span>
                        Detalle del Registro
                    </div>
                    <span class="d-badge d-badge--{{ $tipoBadge }}" style="font-size:.75rem;padding:5px 12px">
                        <i class="fas {{ $tipoIcon }}"></i> {{ $comportamiento->tipo }}
                    </span>
                </div>
                <div style="padding:20px">
                    <div class="d-info-grid" style="grid-template-columns:repeat(3,1fr)">
                        <div class="d-info-item">
                            <span class="d-info-lbl">Fecha</span>
                            <span class="d-info-val">{{ $fechaFmt }}</span>
                            <span style="font-size:.7rem;color:var(--muted)">{{ ucfirst($diaSemana) }}</span>
                        </div>
                        <div class="d-info-item">
                            <span class="d-info-lbl">Tipo</span>
                            <span class="d-info-val">
                                <span class="d-badge d-badge--{{ $tipoBadge }}"><i class="fas {{ $tipoIcon }}"></i> {{ $comportamiento->tipo }}</span>
                            </span>
                        </div>
                        <div class="d-info-item">
                            <span class="d-info-lbl">Notificado al Tutor</span>
                            <span class="d-info-val">
                                @if($comportamiento->notificado_tutor)
                                    <span class="d-badge d-badge--green"><i class="fas fa-check"></i> Si</span>
                                @else
                                    <span class="d-badge d-badge--slate"><i class="fas fa-times"></i> No</span>
                                @endif
                            </span>
                        </div>
                        @if($comportamiento->sancion)
                        <div class="d-info-item d-info-item--full">
                            <span class="d-info-lbl">Sancion</span>
                            <span class="d-info-val"><span class="d-badge d-badge--amber">{{ $comportamiento->sancion }}</span></span>
                        </div>
                        @endif
                        <div class="d-info-item d-info-item--full">
                            <span class="d-info-lbl">Descripcion</span>
                            <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:14px 16px;font-size:.84rem;color:var(--text);line-height:1.7;margin-top:4px">
                                {{ $comportamiento->descripcion }}
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding:0 20px 16px;display:flex;gap:8px;flex-wrap:wrap">
                    <button class="d-btn d-btn--green" data-toggle="modal" data-target="#editComportamientoModal">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="d-btn d-btn--rose" data-toggle="modal" data-target="#deleteComportamientoModal">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                    <a href="{{ route('docente.comportamientos.index') }}" class="d-btn d-btn--ghost">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            {{-- Historial del estudiante --}}
            @if(isset($historial) && $historial->count() > 0)
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title">
                        <span class="d-card__ico d-card__ico--amber"><i class="fas fa-history"></i></span>
                        Historial del Estudiante
                    </div>
                    <span class="d-card__tag">{{ $historial->count() }} registros</span>
                </div>
                <div style="padding:0;overflow-x:auto">
                    <table class="d-table">
                        <thead><tr><th>Fecha</th><th>Tipo</th><th>Descripcion</th><th>Sancion</th></tr></thead>
                        <tbody>
                            @foreach($historial as $h)
                            @php $hb = match($h->tipo) {'Positivo'=>'green','Negativo'=>'rose',default=>'slate'}; @endphp
                            <tr>
                                <td style="font-size:.8rem;white-space:nowrap">
                                    {{ $h->fecha instanceof \Carbon\Carbon ? $h->fecha->format('d/m/Y') : \Carbon\Carbon::parse($h->fecha)->format('d/m/Y') }}
                                </td>
                                <td><span class="d-badge d-badge--{{ $hb }}">{{ $h->tipo }}</span></td>
                                <td style="font-size:.8rem;max-width:260px">{{ \Str::limit($h->descripcion,60) }}</td>
                                <td style="font-size:.78rem;color:var(--muted)">{{ $h->sancion ?? 'â€”' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

        {{-- Columna lateral --}}
        <div style="display:flex;flex-direction:column;gap:18px">

            {{-- Datos del estudiante --}}
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title">
                        <span class="d-card__ico d-card__ico--sky"><i class="fas fa-user-graduate"></i></span>
                        Estudiante
                    </div>
                </div>
                <div style="padding:20px">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                        <div style="width:48px;height:48px;border-radius:12px;background:rgba(14,165,233,.12);color:var(--brand);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;flex-shrink:0">
                            {{ strtoupper(substr($comportamiento->estudiante->persona->apellidos,0,1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:var(--text)">{{ $comportamiento->estudiante->persona->apellidos }}, {{ $comportamiento->estudiante->persona->nombres }}</div>
                            <div class="d-mono" style="font-size:.72rem;color:var(--muted)">{{ $comportamiento->estudiante->codigo_estudiante }}</div>
                        </div>
                    </div>
                    <div class="d-info-grid" style="grid-template-columns:1fr">
                        <div class="d-info-item">
                            <span class="d-info-lbl">Grado</span>
                            <span class="d-info-val">{{ $comportamiento->estudiante->grado->nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="d-info-item">
                            <span class="d-info-lbl">Nivel</span>
                            <span class="d-info-val">{{ $comportamiento->estudiante->grado->nivel->nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="d-info-item">
                            <span class="d-info-lbl">Condicion</span>
                            <span class="d-info-val">
                                @php $cond = $comportamiento->estudiante->condicion; @endphp
                                <span class="d-badge d-badge--{{ $cond=='Regular'?'green':'amber' }}">{{ $cond }}</span>
                            </span>
                        </div>
                    </div>
                    <div style="margin-top:14px">
                        <a href="{{ route('docente.alumno.ficha', $comportamiento->estudiante->id) }}" class="d-btn d-btn--sky" style="width:100%;justify-content:center">
                            <i class="fas fa-id-card"></i> Ver Ficha Completa
                        </a>
                    </div>
                </div>
            </div>

            {{-- Datos del docente --}}
            @if($comportamiento->docente)
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title">
                        <span class="d-card__ico d-card__ico--green"><i class="fas fa-chalkboard-teacher"></i></span>
                        Docente Registrador
                    </div>
                </div>
                <div style="padding:18px 20px">
                    <div class="d-info-grid" style="grid-template-columns:1fr">
                        <div class="d-info-item">
                            <span class="d-info-lbl">Nombre</span>
                            <span class="d-info-val">{{ $comportamiento->docente->persona->apellidos }}, {{ $comportamiento->docente->persona->nombres }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Resumen tipo --}}
            @if(isset($resumen))
            <div class="d-card">
                <div class="d-card__hdr">
                    <div class="d-card__title">
                        <span class="d-card__ico d-card__ico--violet"><i class="fas fa-chart-bar"></i></span>
                        Resumen del Estudiante
                    </div>
                </div>
                <div style="padding:18px 20px;display:flex;flex-direction:column;gap:10px">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem">
                        <span style="color:var(--green);font-weight:600"><i class="fas fa-thumbs-up"></i> Positivos</span>
                        <span class="d-badge d-badge--green">{{ $resumen['positivos'] ?? 0 }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem">
                        <span style="color:var(--rose);font-weight:600"><i class="fas fa-thumbs-down"></i> Negativos</span>
                        <span class="d-badge d-badge--rose">{{ $resumen['negativos'] ?? 0 }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.82rem">
                        <span style="color:var(--slate);font-weight:600"><i class="fas fa-minus"></i> Neutros</span>
                        <span class="d-badge d-badge--slate">{{ $resumen['neutros'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="editComportamientoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content d-modal-content">
            <div class="modal-header d-modal-hdr">
                <h5 class="d-modal-title"><i class="fas fa-edit" style="color:var(--green)"></i> Editar Comportamiento</h5>
                <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('docente.comportamientos.update',$comportamiento->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body d-modal-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:12px">
                        <div class="d-form-group">
                            <label class="d-label">Estudiante <b class="d-req">*</b></label>
                            <select name="estudiante_id" class="d-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($estudiantes as $e)
                                <option value="{{ $e->id }}" {{ $e->id==$comportamiento->estudiante_id?'selected':'' }}>
                                    {{ $e->persona->apellidos }}, {{ $e->persona->nombres }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Fecha <b class="d-req">*</b></label>
                            <input type="date" name="fecha" class="d-input"
                                value="{{ $comportamiento->fecha instanceof \Carbon\Carbon ? $comportamiento->fecha->format('Y-m-d') : \Carbon\Carbon::parse($comportamiento->fecha)->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:12px">
                        <div class="d-form-group">
                            <label class="d-label">Tipo <b class="d-req">*</b></label>
                            <select name="tipo" class="d-select" required>
                                @foreach(['Positivo','Negativo','Neutro'] as $t)
                                <option value="{{ $t }}" {{ $comportamiento->tipo==$t?'selected':'' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-form-group">
                            <label class="d-label">Sancion</label>
                            <input type="text" name="sancion" class="d-input" value="{{ $comportamiento->sancion }}" maxlength="255" placeholder="Opcional">
                        </div>
                    </div>
                    <div class="d-form-group" style="margin-bottom:12px">
                        <label class="d-label">Descripcion <b class="d-req">*</b></label>
                        <textarea name="descripcion" class="d-textarea" rows="3" required maxlength="1000">{{ $comportamiento->descripcion }}</textarea>
                    </div>
                    <div class="d-form-group">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--text)">
                            <input type="checkbox" name="notificado_tutor" value="1" {{ $comportamiento->notificado_tutor?'checked':'' }}>
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
<div class="modal fade" id="deleteComportamientoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content d-modal-content">
            <div class="modal-header d-modal-hdr">
                <h5 class="d-modal-title"><i class="fas fa-exclamation-triangle" style="color:var(--rose)"></i> Confirmar Eliminacion</h5>
                <button type="button" class="close d-modal-close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('docente.comportamientos.destroy',$comportamiento->id) }}" method="POST">
                @csrf @method('DELETE')
                <div class="modal-body d-modal-body">
                    <p style="font-size:.84rem;color:var(--text)">Estas seguro de eliminar este registro de comportamiento?</p>
                    <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 14px;font-size:.8rem;line-height:1.8;color:var(--muted)">
                        <strong style="color:var(--text)">{{ $comportamiento->estudiante->persona->apellidos }}, {{ $comportamiento->estudiante->persona->nombres }}</strong><br>
                        <span class="d-badge d-badge--{{ $tipoBadge }}">{{ $comportamiento->tipo }}</span> &middot; {{ $fechaFmt }}<br>
                        {{ \Str::limit($comportamiento->descripcion,80) }}
                    </div>
                    <div style="margin-top:12px;background:rgba(244,63,94,.08);border:1px solid rgba(244,63,94,.2);border-radius:10px;padding:10px 14px;font-size:.78rem;color:var(--rose)">
                        <i class="fas fa-exclamation-circle"></i> Esta accion no se puede deshacer.
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
@endsection

@section('css')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
.d-btn-back{display:inline-flex;align-items:center;gap:6px;font-size:.79rem;font-weight:600;color:var(--muted);text-decoration:none;transition:color .2s}.d-btn-back:hover{color:var(--brand)}
.d-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow)}
.d-card__hdr{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border)}
.d-card__title{display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:700;color:var(--text)}
.d-card__ico{width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.75rem;flex-shrink:0}
.d-card__ico--violet{background:rgba(139,92,246,.12);color:var(--violet)}
.d-card__ico--sky{background:rgba(14,165,233,.12);color:var(--brand)}
.d-card__ico--amber{background:rgba(245,158,11,.12);color:var(--amber)}
.d-card__ico--green{background:rgba(16,185,129,.12);color:var(--green)}
.d-card__tag{background:var(--surface2);border:1px solid var(--border);border-radius:100px;padding:3px 10px;font-size:.67rem;font-weight:700;color:var(--muted)}
.d-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.d-info-item{display:flex;flex-direction:column;gap:2px}
.d-info-item--full{grid-column:1/-1}
.d-info-lbl{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted)}
.d-info-val{font-size:.84rem;font-weight:600;color:var(--text)}
.d-mono{font-family:monospace;font-size:.79rem}
.d-table{width:100%;border-collapse:collapse;font-size:.82rem}
.d-table thead tr{border-bottom:2px solid var(--border)}
.d-table thead th{padding:11px 14px;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);white-space:nowrap}
.d-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
.d-table tbody tr:hover{background:var(--surface2)}
.d-table tbody td{padding:11px 14px;color:var(--text)}
.d-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s}
.d-btn--sky{background:var(--brand);color:#fff}.d-btn--sky:hover{background:var(--brand-d);color:#fff}
.d-btn--green{background:var(--green);color:#fff}.d-btn--green:hover{filter:brightness(1.1);color:#fff}
.d-btn--ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.d-btn--rose{background:var(--rose);color:#fff}.d-btn--rose:hover{filter:brightness(1.1)}
.d-btn-icon{width:30px;height:30px;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;border:none;cursor:pointer;transition:all .2s;text-decoration:none}
.d-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:100px;font-size:.67rem;font-weight:700}
.d-badge--green{background:rgba(16,185,129,.1);color:var(--green)}
.d-badge--rose{background:rgba(244,63,94,.1);color:var(--rose)}
.d-badge--amber{background:rgba(245,158,11,.1);color:var(--amber)}
.d-badge--slate{background:rgba(100,116,139,.1);color:var(--slate)}
.d-badge--sky{background:rgba(14,165,233,.1);color:var(--brand)}
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
@media(max-width:900px){div[style*="grid-template-columns:2fr 1fr"]{grid-template-columns:1fr!important}}
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    @if(session('mensaje'))
    Swal.fire({icon:'{{ session("icono","success") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});
    @endif
});
</script>
@endsection