@extends('layouts.docente')

@section('title', 'Detalle de Asistencia')
@section('page_title')Detalle <span>Asistencia</span>@endsection

@section('content')
<div style="padding:0 28px 32px">
    <div style="margin-bottom:16px">
        <a href="{{ route('docente.asistencias.index') }}" class="d-btn-back"><i class="fas fa-arrow-left"></i> Volver al Listado</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
        {{-- Estudiante --}}
        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-user-graduate"></i></span>Datos del Estudiante</div>
            </div>
            <div style="padding:20px">
                <div class="d-info-grid">
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Nombre</span><span class="d-info-val" style="font-size:.95rem">{{ $asistencia->estudiante->persona->nombres }} {{ $asistencia->estudiante->persona->apellidos }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">DNI</span><span class="d-info-val d-mono">{{ $asistencia->estudiante->persona->dni }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $asistencia->estudiante->codigo_estudiante }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Grado</span>
                        <span class="d-info-val">@if($asistencia->estudiante->grado)<span class="d-badge d-badge--sky">{{ $asistencia->estudiante->grado->nombre_completo }}</span>@else —@endif</span>
                    </div>
                    <div class="d-info-item"><span class="d-info-lbl">Estado</span>
                        <span class="d-info-val"><span class="d-badge d-badge--{{ $asistencia->estudiante->persona->estado=='Activo'?'green':'rose' }}">{{ $asistencia->estudiante->persona->estado }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Curso --}}
        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-book"></i></span>Datos del Curso</div>
            </div>
            <div style="padding:20px">
                <div class="d-info-grid">
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Curso</span><span class="d-info-val" style="font-size:.95rem">{{ $asistencia->curso->nombre }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $asistencia->curso->codigo ?? 'N/A' }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Área Curricular</span><span class="d-info-val">{{ $asistencia->curso->area_curricular ?? '—' }}</span></div>
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Docente</span>
                        <span class="d-info-val">@if($asistencia->docente){{ $asistencia->docente->persona->nombres }} {{ $asistencia->docente->persona->apellidos }}@else —@endif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detalle asistencia --}}
    <div class="d-card" style="margin-bottom:20px">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--amber"><i class="fas fa-calendar-check"></i></span>Detalle de la Asistencia</div>
            <span class="d-badge d-badge--{{ $asistencia->estado_badge }}" style="font-size:.82rem;padding:6px 14px">{{ $asistencia->estado }}</span>
        </div>
        <div style="padding:20px">
            <div class="d-info-grid" style="grid-template-columns:repeat(4,1fr)">
                <div class="d-info-item"><span class="d-info-lbl">Fecha</span><span class="d-info-val">{{ $asistencia->fecha_formateada }}</span><span style="font-size:.7rem;color:var(--muted)">{{ $asistencia->dia_semana }}</span></div>
                <div class="d-info-item"><span class="d-info-lbl">Estado</span><span class="d-info-val"><span class="d-badge d-badge--{{ $asistencia->estado_badge }}">{{ $asistencia->estado }}</span></span></div>
                <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Observaciones</span><span class="d-info-val">{{ $asistencia->observaciones ?? '—' }}</span></div>
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="d-kpi-grid4" style="margin-bottom:20px">
        @php
            $total = $asistencia->estudiante->asistencias()->count();
            $presentes = $asistencia->estudiante->asistencias()->where('estado','Presente')->count();
            $ausentes = $asistencia->estudiante->asistencias()->where('estado','Ausente')->count();
            $tardanzas = $asistencia->estudiante->asistencias()->where('estado','Tardanza')->count();
            $pct = \App\Models\Asistencia::calcularPorcentajeAsistencia($asistencia->estudiante_id);
        @endphp
        <div class="d-stat-card d-stat-card--sky"><div class="d-stat-card__ico"><i class="fas fa-list"></i></div><div><div class="d-stat-card__val">{{ $total }}</div><div class="d-stat-card__lbl">Total</div></div></div>
        <div class="d-stat-card d-stat-card--green"><div class="d-stat-card__ico"><i class="fas fa-check"></i></div><div><div class="d-stat-card__val">{{ $presentes }}</div><div class="d-stat-card__lbl">Presentes</div></div></div>
        <div class="d-stat-card d-stat-card--rose"><div class="d-stat-card__ico"><i class="fas fa-times"></i></div><div><div class="d-stat-card__val">{{ $ausentes }}</div><div class="d-stat-card__lbl">Ausencias</div></div></div>
        <div class="d-stat-card d-stat-card--amber"><div class="d-stat-card__ico"><i class="fas fa-clock"></i></div><div><div class="d-stat-card__val">{{ $tardanzas }}</div><div class="d-stat-card__lbl">Tardanzas</div></div></div>
    </div>

    {{-- Porcentaje --}}
    <div class="d-card">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--green"><i class="fas fa-percentage"></i></span>Porcentaje de Asistencia General</div>
            <span class="d-badge d-badge--{{ $pct>=75?'green':'rose' }}" style="font-size:.9rem;padding:7px 16px">{{ $pct }}%</span>
        </div>
        <div style="padding:20px">
            <div style="height:10px;background:var(--surface2);border-radius:100px">
                <div style="height:100%;border-radius:100px;background:{{ $pct>=75?'var(--green)':'var(--rose)' }};width:{{ $pct }}%;transition:width 1s"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.72rem;color:var(--muted);margin-top:6px"><span>0%</span><span>100%</span></div>
        </div>
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
@media(max-width:900px){div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}}
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('mensaje'))Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});@endif
</script>
@endsection

