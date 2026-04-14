
@section('title', 'Detalle Comportamiento')
@section('page_title')Detalle <span>Comportamiento</span>@endsection

@section('content')
<div style="padding:0 28px 32px">
    <div style="margin-bottom:16px">
        <a href="{{ route('docente.comportamientos.index') }}" class="d-btn-back"><i class="fas fa-arrow-left"></i> Volver al Listado</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
        {{-- Estudiante --}}
        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-user-graduate"></i></span>Datos del Estudiante</div>
            </div>
            <div style="padding:20px">
                <div class="d-info-grid">
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Nombre</span><span class="d-info-val" style="font-size:.95rem">{{ $comportamiento->estudiante->persona->nombres }} {{ $comportamiento->estudiante->persona->apellidos }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">DNI</span><span class="d-info-val d-mono">{{ $comportamiento->estudiante->persona->dni }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $comportamiento->estudiante->codigo_estudiante }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Grado</span>
                        <span class="d-info-val">@if($comportamiento->estudiante->grado)<span class="d-badge d-badge--sky">{{ $comportamiento->estudiante->grado->nombre_completo }}</span>@else —@endif</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Docente --}}
        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-chalkboard-teacher"></i></span>Datos del Docente</div>
            </div>
            <div style="padding:20px">
                @if($comportamiento->docente)
                <div class="d-info-grid">
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Nombre</span><span class="d-info-val" style="font-size:.95rem">{{ $comportamiento->docente->persona->nombres }} {{ $comportamiento->docente->persona->apellidos }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">DNI</span><span class="d-info-val d-mono">{{ $comportamiento->docente->persona->dni }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $comportamiento->docente->codigo_docente }}</span></div>
                </div>
                @else
                <div class="d-empty-state" style="padding:20px"><i class="fas fa-user-slash"></i><span>Sin docente asignado</span></div>
                @endif
            </div>
        </div>
    </div>

    {{-- Detalle --}}
    <div class="d-card" style="margin-bottom:20px">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--{{ $comportamiento->tipo_badge }}"><i class="fas {{ $comportamiento->tipo_icon }}"></i></span>Detalle del Comportamiento</div>
            <div style="display:flex;gap:8px">
                <span class="d-badge d-badge--{{ $comportamiento->tipo_badge }}"><i class="fas {{ $comportamiento->tipo_icon }}"></i> {{ $comportamiento->tipo }}</span>
                @if($comportamiento->notificado_tutor)<span class="d-badge d-badge--green"><i class="fas fa-bell"></i> Notificado</span>@endif
            </div>
        </div>
        <div style="padding:20px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px">
                <div class="d-info-item"><span class="d-info-lbl">Fecha</span><span class="d-info-val">{{ $comportamiento->fecha_formateada }}</span><span style="font-size:.7rem;color:var(--muted)">{{ $comportamiento->dia_semana }}</span></div>
                <div class="d-info-item"><span class="d-info-lbl">Tipo</span><span class="d-info-val"><span class="d-badge d-badge--{{ $comportamiento->tipo_badge }}">{{ $comportamiento->tipo }}</span></span></div>
                <div class="d-info-item"><span class="d-info-lbl">Notificado</span><span class="d-info-val"><span class="d-badge d-badge--{{ $comportamiento->notificado_tutor?'green':'slate' }}">{{ $comportamiento->notificado_tutor?'Sí':'No' }}</span></span></div>
                <div class="d-info-item"><span class="d-info-lbl">F. Notificación</span><span class="d-info-val">{{ $comportamiento->fecha_notificacion_formateada }}</span></div>
            </div>
            <div class="d-info-item" style="margin-bottom:14px">
                <span class="d-info-lbl">Descripción</span>
                <div style="margin-top:6px;background:var(--surface2);border:1px solid var(--border);border-left:4px solid var(--{{ $comportamiento->tipo_badge=='success'?'green':($comportamiento->tipo_badge=='danger'?'rose':'amber') }});border-radius:0 10px 10px 0;padding:14px 16px;font-size:.84rem;color:var(--text);line-height:1.7">{{ $comportamiento->descripcion }}</div>
            </div>
            @if($comportamiento->sancion)
            <div class="d-info-item">
                <span class="d-info-lbl">Sanción Aplicada</span>
                <div style="margin-top:6px;background:rgba(245,158,11,.06);border:1px solid rgba(245,158,11,.2);border-radius:10px;padding:12px 14px;font-size:.83rem;color:var(--amber);font-weight:600">
                    <i class="fas fa-exclamation-triangle"></i> {{ $comportamiento->sancion }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Resumen --}}
    <div class="d-kpi-grid4" style="margin-bottom:20px">
        <div class="d-stat-card d-stat-card--sky"><div class="d-stat-card__ico"><i class="fas fa-list"></i></div><div><div class="d-stat-card__val">{{ $resumen['total'] }}</div><div class="d-stat-card__lbl">Total</div></div></div>
        <div class="d-stat-card d-stat-card--green"><div class="d-stat-card__ico"><i class="fas fa-smile"></i></div><div><div class="d-stat-card__val">{{ $resumen['positivos'] }}</div><div class="d-stat-card__lbl">Positivos</div></div></div>
        <div class="d-stat-card d-stat-card--rose"><div class="d-stat-card__ico"><i class="fas fa-frown"></i></div><div><div class="d-stat-card__val">{{ $resumen['negativos'] }}</div><div class="d-stat-card__lbl">Negativos</div></div></div>
        <div class="d-stat-card d-stat-card--amber"><div class="d-stat-card__ico"><i class="fas fa-bell"></i></div><div><div class="d-stat-card__val">{{ $resumen['notificados'] }}</div><div class="d-stat-card__lbl">Notificados</div></div></div>
    </div>

    {{-- Historial --}}
    <div class="d-card" style="margin-bottom:20px">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-history"></i></span>Últimos 10 Comportamientos</div>
        </div>
        <div style="overflow-x:auto">
            <table class="d-table">
                <thead><tr><th>Fecha</th><th>Tipo</th><th>Descripción</th><th>Docente</th><th style="text-align:center">Notif.</th></tr></thead>
                <tbody>
                    @forelse($ultimosComportamientos as $item)
                    <tr style="{{ $item->id==$comportamiento->id?'background:var(--surface2)':'' }}">
                        <td style="font-size:.79rem;white-space:nowrap">{{ $item->fecha_formateada }}</td>
                        <td><span class="d-badge d-badge--{{ $item->tipo_badge }}"><i class="fas {{ $item->tipo_icon }}"></i> {{ $item->tipo }}</span></td>
                        <td style="font-size:.8rem;color:var(--muted)">{{ \Str::limit($item->descripcion,80) }}</td>
                        <td style="font-size:.8rem">@if($item->docente){{ $item->docente->persona->apellidos }}@else —@endif</td>
                        <td style="text-align:center">@if($item->notificado_tutor)<i class="fas fa-check" style="color:var(--green)"></i>@else<i class="fas fa-times" style="color:var(--rose)"></i>@endif</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:24px">Sin historial</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Acciones --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="{{ route('docente.comportamientos.index') }}" class="d-btn d-btn--ghost"><i class="fas fa-arrow-left"></i> Volver</a>
        @if(!$comportamiento->notificado_tutor)
        <form action="{{ route('docente.comportamientos.notificar',$comportamiento->id) }}" method="POST" style="display:inline">
            @csrf<button type="submit" class="d-btn d-btn--amber"><i class="fas fa-bell"></i> Notificar a Tutor</button>
        </form>
        @else
        <form action="{{ route('docente.comportamientos.cancelar-notificacion',$comportamiento->id) }}" method="POST" style="display:inline">
            @csrf<button type="submit" class="d-btn d-btn--ghost"><i class="fas fa-bell-slash"></i> Cancelar Notificación</button>
        </form>
        @endif
    </div>
</div>
@endsection

@section('css')
<style>
.d-btn-back{display:inline-flex;align-items:center;gap:6px;font-size:.79rem;font-weight:600;color:var(--muted);text-decoration:none;transition:color .2s}.d-btn-back:hover{color:var(--brand)}
.d-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.d-info-item{display:flex;flex-direction:column;gap:2px}
.d-info-item--full{grid-column:1/-1}
.d-info-lbl{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted)}
.d-info-val{font-size:.84rem;font-weight:600;color:var(--text)}
.d-mono{font-family:monospace;font-size:.8rem}
.d-kpi-grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
@media(max-width:700px){.d-kpi-grid4{grid-template-columns:repeat(2,1fr)}}
.d-stat-card{display:flex;align-items:center;gap:14px;padding:18px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius)}
.d-stat-card__ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.d-stat-card__val{font-size:1.6rem;font-weight:800;color:var(--text);line-height:1}
.d-stat-card__lbl{font-size:.67rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-top:2px}
.d-stat-card--sky .d-stat-card__ico{background:rgba(14,165,233,.1);color:var(--brand)}
.d-stat-card--green .d-stat-card__ico{background:rgba(16,185,129,.1);color:var(--green)}
.d-stat-card--rose .d-stat-card__ico{background:rgba(244,63,94,.1);color:var(--rose)}
.d-stat-card--amber .d-stat-card__ico{background:rgba(245,158,11,.1);color:var(--amber)}
.d-table{width:100%;border-collapse:collapse;font-size:.82rem}
.d-table thead tr{border-bottom:2px solid var(--border)}
.d-table thead th{padding:11px 14px;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted)}
.d-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
.d-table tbody tr:hover{background:var(--surface2)}
.d-table tbody td{padding:11px 14px;color:var(--text)}
.d-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s}
.d-btn--ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.d-btn--amber{background:var(--amber);color:#fff}.d-btn--amber:hover{filter:brightness(1.1)}
.d-badge--slate{background:rgba(100,116,139,.1);color:var(--slate)}
.d-empty-state{display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--muted);text-align:center}
.d-empty-state i{font-size:1.5rem;opacity:.2}
.d-empty-state span{font-size:.78rem}
@media(max-width:900px){div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}}
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>@if(session('mensaje'))Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});@endif</script>
@endsection

