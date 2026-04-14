@extends('layouts.docente')

@section('title', 'Detalle de Nota')
@section('page_title')Detalle <span>Nota</span>@endsection

@section('content')
<div style="padding:0 28px 32px">
    <div style="margin-bottom:16px">
        <a href="{{ route('docente.notas.index') }}" class="d-btn-back"><i class="fas fa-arrow-left"></i> Volver al Listado</a>
    </div>

    {{-- Scores --}}
    <div class="d-kpi-grid4" style="margin-bottom:20px">
        <div class="d-stat-card d-stat-card--sky"><div class="d-stat-card__ico"><i class="fas fa-pen"></i></div><div><div class="d-stat-card__val">{{ $nota->nota_practica ?? '—' }}</div><div class="d-stat-card__lbl">N. Práctica</div></div></div>
        <div class="d-stat-card d-stat-card--violet"><div class="d-stat-card__ico"><i class="fas fa-book"></i></div><div><div class="d-stat-card__val">{{ $nota->nota_teoria ?? '—' }}</div><div class="d-stat-card__lbl">N. Teoría</div></div></div>
        <div class="d-stat-card d-stat-card--{{ $nota->estado_nota_badge }}"><div class="d-stat-card__ico"><i class="fas fa-trophy"></i></div><div><div class="d-stat-card__val" style="font-size:2rem">{{ $nota->nota_final }}</div><div class="d-stat-card__lbl">N. Final</div></div></div>
        <div class="d-stat-card d-stat-card--slate"><div class="d-stat-card__ico"><i class="fas fa-clipboard-list"></i></div><div><div class="d-stat-card__val" style="font-size:1.1rem">{{ $nota->tipo_evaluacion }}</div><div class="d-stat-card__lbl">Tipo</div></div></div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
        {{-- Estudiante --}}
        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-user-graduate"></i></span>Datos del Estudiante</div>
            </div>
            <div style="padding:20px">
                <div class="d-info-grid">
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Nombre</span><span class="d-info-val" style="font-size:.95rem">{{ $nota->matricula->estudiante->persona->nombres }} {{ $nota->matricula->estudiante->persona->apellidos }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">DNI</span><span class="d-info-val d-mono">{{ $nota->matricula->estudiante->persona->dni }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $nota->matricula->estudiante->codigo_estudiante }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Grado</span>
                        <span class="d-info-val">@if($nota->matricula->grado)<span class="d-badge d-badge--sky">{{ $nota->matricula->grado->nombre_completo }}</span>@else —@endif</span>
                    </div>
                    <div class="d-info-item"><span class="d-info-lbl">Estado Matrícula</span>
                        <span class="d-info-val"><span class="d-badge d-badge--{{ $nota->matricula->estado_badge }}">{{ $nota->matricula->estado }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Curso y Docente --}}
        <div class="d-card">
            <div class="d-card__hdr">
                <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-book"></i></span>Curso y Docente</div>
            </div>
            <div style="padding:20px">
                <div class="d-info-grid">
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Curso</span><span class="d-info-val" style="font-size:.95rem">{{ $nota->matricula->curso->nombre }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Código</span><span class="d-info-val d-mono">{{ $nota->matricula->curso->codigo ?? 'N/A' }}</span></div>
                    <div class="d-info-item"><span class="d-info-lbl">Créditos</span><span class="d-info-val"><span class="d-badge d-badge--sky">{{ $nota->matricula->curso->creditos }}</span></span></div>
                    <div class="d-info-item d-info-item--full"><span class="d-info-lbl">Docente</span><span class="d-info-val">{{ $nota->docente->persona->nombres }} {{ $nota->docente->persona->apellidos }}</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detalle evaluación --}}
    <div class="d-card" style="margin-bottom:20px">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--rose"><i class="fas fa-clipboard-check"></i></span>Detalles de la Evaluación</div>
            <div style="display:flex;gap:8px">
                <span class="d-badge d-badge--{{ $nota->tipo_evaluacion_badge }}">{{ $nota->tipo_evaluacion }}</span>
                <span class="d-badge d-badge--{{ $nota->estado_nota_badge }}">{{ $nota->estado_nota_texto }}</span>
                @if($nota->visible_tutor)<span class="d-badge d-badge--green"><i class="fas fa-eye"></i> Visible Tutores</span>@endif
            </div>
        </div>
        <div style="padding:20px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:16px">
                <div class="d-info-item"><span class="d-info-lbl">Periodo</span><span class="d-info-val"><span class="d-badge d-badge--slate">{{ $nota->periodo->nombre }}</span></span></div>
                <div class="d-info-item"><span class="d-info-lbl">F. Evaluación</span><span class="d-info-val">{{ $nota->fecha_evaluacion ? $nota->fecha_evaluacion_formateada : '—' }}</span></div>
                <div class="d-info-item"><span class="d-info-lbl">Visible Tutores</span><span class="d-info-val"><span class="d-badge d-badge--{{ $nota->visible_tutor?'green':'slate' }}">{{ $nota->visible_tutor?'Sí':'No' }}</span></span></div>
                <div class="d-info-item"><span class="d-info-lbl">F. Publicación</span><span class="d-info-val">{{ $nota->fecha_publicacion ? $nota->fecha_publicacion_formateada : '—' }}</span></div>
            </div>
            @if($nota->descripcion)
            <div class="d-info-item" style="margin-bottom:12px"><span class="d-info-lbl">Descripción</span><span class="d-info-val">{{ $nota->descripcion }}</span></div>
            @endif
            @if($nota->observaciones)
            <div class="d-info-item"><span class="d-info-lbl">Observaciones</span>
                <div style="margin-top:6px;background:rgba(14,165,233,.05);border:1px solid rgba(14,165,233,.15);border-radius:9px;padding:10px 14px;font-size:.82rem;color:var(--text)">{{ $nota->observaciones }}</div>
            </div>
            @endif

            <div style="margin-top:16px;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:14px 16px;font-size:.84rem;color:var(--text)">
                <i class="fas fa-info-circle" style="color:var(--brand)"></i>
                El estudiante obtuvo <strong>{{ $nota->nota_final }}</strong> puntos — <strong>{{ $nota->estado_nota_texto }}</strong>.
                @if($nota->estaAprobada()) ✅ <strong>APROBADO</strong>. @else ❌ <strong>DESAPROBADO</strong>. @endif
            </div>
        </div>
    </div>

    {{-- Stats promedio --}}
    <div class="d-kpi-grid4" style="margin-bottom:20px">
        <div class="d-stat-card d-stat-card--sky" style="grid-column:1/2"><div class="d-stat-card__ico"><i class="fas fa-clipboard-check"></i></div><div><div class="d-stat-card__val">{{ $nota->matricula->estudiante->asistencias()->count() }}</div><div class="d-stat-card__lbl">Asistencias</div></div></div>
        <div class="d-stat-card d-stat-card--green"><div class="d-stat-card__ico"><i class="fas fa-calculator"></i></div><div><div class="d-stat-card__val" style="font-size:1.3rem">{{ number_format(\App\Models\Nota::calcularPromedioPorMatricula($nota->matricula_id,$nota->periodo_id)??0,2) }}</div><div class="d-stat-card__lbl">Prom. Periodo</div></div></div>
        <div class="d-stat-card d-stat-card--amber"><div class="d-stat-card__ico"><i class="fas fa-star"></i></div><div><div class="d-stat-card__val" style="font-size:1.3rem">{{ number_format(\App\Models\Nota::calcularPromedioPorMatricula($nota->matricula_id)??0,2) }}</div><div class="d-stat-card__lbl">Prom. General</div></div></div>
    </div>

    {{-- Historial --}}
    <div class="d-card" style="margin-bottom:20px">
        <div class="d-card__hdr">
            <div class="d-card__title"><span class="d-card__ico d-card__ico--sky"><i class="fas fa-history"></i></span>Historial de Notas en este Curso</div>
        </div>
        <div style="overflow-x:auto">
            @php
                $historial = \App\Models\Nota::where('matricula_id',$nota->matricula_id)->with('periodo')->orderBy('periodo_id')->orderBy('created_at')->get();
            @endphp
            <table class="d-table">
                <thead><tr><th>Periodo</th><th>Tipo</th><th style="text-align:center">Práct.</th><th style="text-align:center">Teoría</th><th style="text-align:center">Final</th><th>Estado</th><th>Fecha</th></tr></thead>
                <tbody>
                    @forelse($historial as $h)
                    <tr style="{{ $h->id==$nota->id?'background:var(--surface2)':'' }}">
                        <td>{{ $h->periodo->nombre }}</td>
                        <td><span class="d-badge d-badge--{{ $h->tipo_evaluacion_badge }}">{{ $h->tipo_evaluacion }}</span></td>
                        <td style="text-align:center">{{ $h->nota_practica ?? '—' }}</td>
                        <td style="text-align:center">{{ $h->nota_teoria ?? '—' }}</td>
                        <td style="text-align:center"><span class="d-score d-score--{{ $h->estado_nota_badge }}">{{ $h->nota_final }}</span></td>
                        <td><span class="d-badge d-badge--{{ $h->estado_nota_badge }}">{{ $h->estado_nota_texto }}</span></td>
                        <td style="font-size:.78rem;color:var(--muted)">{{ $h->fecha_evaluacion_formateada }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:24px">Sin historial</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Acciones --}}
    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="{{ route('docente.notas.index') }}" class="d-btn d-btn--ghost"><i class="fas fa-arrow-left"></i> Volver</a>
        @if(!$nota->visible_tutor)
        <form action="{{ route('docente.notas.publicar',$nota->id) }}" method="POST" style="display:inline">
            @csrf<button type="submit" class="d-btn d-btn--amber"><i class="fas fa-eye"></i> Publicar para Tutores</button>
        </form>
        @else
        <form action="{{ route('docente.notas.despublicar',$nota->id) }}" method="POST" style="display:inline">
            @csrf<button type="submit" class="d-btn d-btn--ghost"><i class="fas fa-eye-slash"></i> Despublicar</button>
        </form>
        @endif
    </div>
</div>
@endsection

@section('css')
<style>
.d-btn-back{display:inline-flex;align-items:center;gap:6px;font-size:.79rem;font-weight:600;color:var(--muted);text-decoration:none;transition:color .2s}.d-btn-back:hover{color:var(--brand)}
.d-kpi-grid4{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
@media(max-width:700px){.d-kpi-grid4{grid-template-columns:repeat(2,1fr)}}
.d-stat-card{display:flex;align-items:center;gap:14px;padding:18px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius)}
.d-stat-card__ico{width:42px;height:42px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.d-stat-card__val{font-size:1.6rem;font-weight:800;color:var(--text);line-height:1}
.d-stat-card__lbl{font-size:.67rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-top:2px}
.d-stat-card--sky .d-stat-card__ico{background:rgba(14,165,233,.1);color:var(--brand)}
.d-stat-card--green .d-stat-card__ico{background:rgba(16,185,129,.1);color:var(--green)}
.d-stat-card--amber .d-stat-card__ico{background:rgba(245,158,11,.1);color:var(--amber)}
.d-stat-card--rose .d-stat-card__ico{background:rgba(244,63,94,.1);color:var(--rose)}
.d-stat-card--violet .d-stat-card__ico{background:rgba(139,92,246,.1);color:var(--violet)}
.d-stat-card--slate .d-stat-card__ico{background:rgba(100,116,139,.1);color:var(--slate)}
.d-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.d-info-item{display:flex;flex-direction:column;gap:2px}
.d-info-item--full{grid-column:1/-1}
.d-info-lbl{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted)}
.d-info-val{font-size:.84rem;font-weight:600;color:var(--text)}
.d-mono{font-family:monospace;font-size:.8rem}
.d-score{display:inline-flex;align-items:center;justify-content:center;min-width:34px;padding:3px 9px;border-radius:6px;font-size:.85rem;font-weight:800}
.d-score--green{background:rgba(16,185,129,.1);color:var(--green)}
.d-score--sky{background:rgba(14,165,233,.1);color:var(--brand)}
.d-score--rose{background:rgba(244,63,94,.1);color:var(--rose)}
.d-badge--slate{background:rgba(100,116,139,.1);color:var(--slate)}
.d-table{width:100%;border-collapse:collapse;font-size:.82rem}
.d-table thead tr{border-bottom:2px solid var(--border)}
.d-table thead th{padding:11px 14px;font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted)}
.d-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
.d-table tbody tr:hover{background:var(--surface2)}
.d-table tbody td{padding:11px 14px;color:var(--text)}
.d-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:.79rem;font-weight:700;border:none;cursor:pointer;text-decoration:none;transition:all .2s}
.d-btn--ghost{background:var(--surface2);color:var(--text);border:1px solid var(--border)}
.d-btn--amber{background:var(--amber);color:#fff}.d-btn--amber:hover{filter:brightness(1.1)}
@media(max-width:900px){div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}}
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>@if(session('mensaje'))Swal.fire({icon:'{{ session("icono") }}',title:'{{ session("mensaje") }}',showConfirmButton:true,timer:3000});@endif</script>
@endsection

