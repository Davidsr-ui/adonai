@extends('layouts.tutor')
@section('title', 'Detalle del Reporte')
@section('page_title')Detalle <span>Reporte</span>@endsection
@section('content')

<div class="t-hero">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Reporte Académico</div>
            <h1 class="t-hero__title">Detalle del <span class="t-hero__accent">Reporte</span></h1>
            <p class="t-hero__sub">Información completa del informe académico del estudiante.</p>
        </div>
        <div class="t-hero__right">
            <a href="{{ route('tutor.reportes.index') }}" class="s-btn s-btn--slate">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @if($reporte->tienePdf())
            <a href="{{ route('tutor.reportes.descargar-pdf', $reporte->id) }}" class="s-btn s-btn--red">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>
            @endif
        </div>
    </div>
</div>

<div class="s-wrapper">

    {{-- Infobar del estudiante --}}
    <div class="s-infobar">
        <div class="s-infobar__avatar">{{ strtoupper(substr($reporte->estudiante->persona->nombres,0,1)) }}</div>
        <div class="s-infobar__data">
            <div class="s-infobar__nombre">{{ $reporte->estudiante->persona->apellidos }}, {{ $reporte->estudiante->persona->nombres }}</div>
            <div class="s-infobar__meta">
                <span><i class="fas fa-id-card"></i> {{ $reporte->estudiante->persona->dni }}</span>
                <span class="s-infobar__sep">·</span>
                <span><i class="fas fa-barcode"></i> {{ $reporte->estudiante->codigo_estudiante }}</span>
                <span class="s-infobar__sep">·</span>
                <span><i class="fas fa-graduation-cap"></i> {{ $reporte->estudiante->grado->nombre_completo ?? 'Sin grado' }}</span>
            </div>
        </div>
        <div class="s-infobar__badges">
            <span class="s-badge s-badge--amber">{{ $reporte->periodo->nombre }}</span>
            <span class="s-badge s-badge--blue">{{ $reporte->tipo }}</span>
            @if($reporte->visible_tutor)
            <span class="s-badge s-badge--green"><i class="fas fa-eye"></i> Publicado</span>
            @endif
        </div>
    </div>

    {{-- KPIs principales --}}
    <div class="s-kpi-row">
        <div class="s-kpi s-kpi--{{ $reporte->promedio_general >= 14 ? 'green' : ($reporte->promedio_general >= 11 ? 'blue' : ($reporte->promedio_general ? 'red' : 'slate')) }}">
            <div class="s-kpi__icon"><i class="fas fa-star"></i></div>
            <div>
                <div class="s-kpi__val">{{ $reporte->promedio_general ? number_format($reporte->promedio_general,2) : 'N/A' }}</div>
                <div class="s-kpi__lbl">Promedio General</div>
            </div>
        </div>
        <div class="s-kpi s-kpi--{{ $reporte->porcentaje_asistencia >= 90 ? 'green' : ($reporte->porcentaje_asistencia >= 70 ? 'amber' : ($reporte->porcentaje_asistencia ? 'red' : 'slate')) }}">
            <div class="s-kpi__icon"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="s-kpi__val">{{ $reporte->porcentaje_asistencia ? number_format($reporte->porcentaje_asistencia,1).'%' : 'N/A' }}</div>
                <div class="s-kpi__lbl">Asistencia</div>
            </div>
        </div>
        <div class="s-kpi s-kpi--blue">
            <div class="s-kpi__icon"><i class="fas fa-graduation-cap"></i></div>
            <div>
                <div class="s-kpi__val">{{ $reporte->promedio_general ? $reporte->estado_promedio_texto : 'N/A' }}</div>
                <div class="s-kpi__lbl">Estado Académico</div>
            </div>
        </div>
        <div class="s-kpi s-kpi--slate">
            <div class="s-kpi__icon"><i class="fas fa-user-tie"></i></div>
            <div>
                <div class="s-kpi__val" style="font-size:.88rem">{{ $reporte->docente->persona->apellidos }}</div>
                <div class="s-kpi__lbl">Docente</div>
            </div>
        </div>
    </div>

    {{-- Callout estado --}}
    @if($reporte->promedio_general)
    <div class="s-callout s-callout--{{ $reporte->estaAprobado() ? 'green' : 'red' }}">
        <i class="fas {{ $reporte->estaAprobado() ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
        <div>
            El estudiante obtuvo un promedio de <strong>{{ number_format($reporte->promedio_general,2) }}</strong> puntos,
            considerado como <strong>{{ $reporte->estado_promedio_texto }}</strong>.
            El estudiante ha <strong>{{ $reporte->estaAprobado() ? 'APROBADO' : 'DESAPROBADO' }}</strong> este periodo.
        </div>
    </div>
    @endif

    {{-- Notas del periodo --}}
    <div class="s-card">
        <div class="s-card__header">
            <div class="s-card__title">
                <span class="s-card__icon s-card__icon--amber"><i class="fas fa-star"></i></span>
                Notas del Periodo
            </div>
            <span class="s-card__tag">{{ $notas->count() }} cursos</span>
        </div>
        <div class="s-card__body">
            @if($notas->count() > 0)
            <table class="s-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Curso</th>
                        <th>Tipo Evaluación</th>
                        <th style="text-align:center">Práctica</th>
                        <th style="text-align:center">Teoría</th>
                        <th style="text-align:center">Final</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notas as $i => $nota)
                    <tr>
                        <td class="s-num">{{ $i+1 }}</td>
                        <td>
                            <div class="s-curso">
                                <div class="s-curso__icon"><i class="fas fa-book"></i></div>
                                <span class="s-curso__nombre">{{ $nota->matricula->curso->nombre }}</span>
                            </div>
                        </td>
                        <td><span class="s-badge s-badge--blue">{{ $nota->tipo_evaluacion }}</span></td>
                        <td style="text-align:center" class="s-muted">{{ $nota->nota_practica ?? '–' }}</td>
                        <td style="text-align:center" class="s-muted">{{ $nota->nota_teoria ?? '–' }}</td>
                        <td style="text-align:center">
                            <span class="s-final s-final--{{ $nota->nota_final >= 14 ? 'green' : ($nota->nota_final >= 11 ? 'blue' : 'red') }}">
                                {{ $nota->nota_final }}
                            </span>
                        </td>
                        <td>
                            <span class="s-badge {{ $nota->nota_final >= 11 ? 's-badge--green' : 's-badge--red' }}">
                                {{ $nota->estado_nota_texto }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="s-empty"><i class="fas fa-star"></i><span>No hay notas registradas en este periodo</span></div>
            @endif
        </div>
    </div>

    {{-- Asistencias --}}
    <div class="s-card">
        <div class="s-card__header">
            <div class="s-card__title">
                <span class="s-card__icon s-card__icon--green"><i class="fas fa-calendar-check"></i></span>
                Registro de Asistencias
            </div>
            <span class="s-card__tag">{{ $asistencias->count() }} registros</span>
        </div>
        <div class="s-card__body">
            <div class="s-asist-grid">
                <div class="s-asist s-asist--blue">
                    <div class="s-asist__val">{{ $asistencias->count() }}</div>
                    <div class="s-asist__lbl"><i class="fas fa-list"></i> Total</div>
                </div>
                <div class="s-asist s-asist--green">
                    <div class="s-asist__val">{{ $asistencias->where('estado','Presente')->count() }}</div>
                    <div class="s-asist__lbl"><i class="fas fa-check"></i> Presentes</div>
                </div>
                <div class="s-asist s-asist--red">
                    <div class="s-asist__val">{{ $asistencias->where('estado','Ausente')->count() }}</div>
                    <div class="s-asist__lbl"><i class="fas fa-times"></i> Ausencias</div>
                </div>
                <div class="s-asist s-asist--amber">
                    <div class="s-asist__val">{{ $asistencias->where('estado','Tardanza')->count() }}</div>
                    <div class="s-asist__lbl"><i class="fas fa-clock"></i> Tardanzas</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Comportamientos --}}
    <div class="s-card">
        <div class="s-card__header">
            <div class="s-card__title">
                <span class="s-card__icon s-card__icon--rose"><i class="fas fa-user-check"></i></span>
                Comportamientos del Periodo
            </div>
            <span class="s-card__tag">{{ $comportamientos->count() }} registros</span>
        </div>
        <div class="s-card__body">
            @if($comportamientos->count() > 0)
            <table class="s-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Sanción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comportamientos as $i => $c)
                    <tr>
                        <td class="s-num">{{ $i+1 }}</td>
                        <td class="s-fecha">{{ $c->fecha_formateada }}</td>
                        <td>
                            <span class="s-badge {{ $c->tipo=='Positivo' ? 's-badge--green' : ($c->tipo=='Negativo' ? 's-badge--red' : 's-badge--amber') }}">
                                <i class="fas {{ $c->tipo=='Positivo' ? 'fa-smile' : ($c->tipo=='Negativo' ? 'fa-frown' : 'fa-meh') }}"></i>
                                {{ $c->tipo }}
                            </span>
                        </td>
                        <td class="s-desc">{{ \Str::limit($c->descripcion,100) }}</td>
                        <td>
                            @if($c->sancion)
                            <span class="s-badge s-badge--amber"><i class="fas fa-exclamation-triangle"></i> {{ $c->sancion }}</span>
                            @else
                            <span class="s-muted">–</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="s-empty"><i class="fas fa-bell-slash"></i><span>No hay comportamientos registrados en este periodo</span></div>
            @endif
        </div>
    </div>

    {{-- Comentario final --}}
    @if($reporte->comentario_final)
    <div class="s-card">
        <div class="s-card__header">
            <div class="s-card__title">
                <span class="s-card__icon s-card__icon--blue"><i class="fas fa-comment-alt"></i></span>
                Comentario Final del Docente
            </div>
        </div>
        <div class="s-card__body">
            <div class="s-comentario">{{ $reporte->comentario_final }}</div>
        </div>
    </div>
    @endif

    {{-- Info del sistema --}}
    <div class="s-card">
        <div class="s-card__header">
            <div class="s-card__title">
                <span class="s-card__icon s-card__icon--slate"><i class="fas fa-info-circle"></i></span>
                Información del Sistema
            </div>
        </div>
        <div class="s-card__body">
            <div class="s-meta-grid">
                <div class="s-meta-item">
                    <div class="s-meta-label">Fecha de Generación</div>
                    <div class="s-meta-val">{{ $reporte->fecha_generacion_formateada }}</div>
                </div>
                <div class="s-meta-item">
                    <div class="s-meta-label">Fecha de Publicación</div>
                    <div class="s-meta-val">{{ $reporte->fecha_publicacion_formateada }}</div>
                </div>
                <div class="s-meta-item">
                    <div class="s-meta-label">Fecha de Registro</div>
                    <div class="s-meta-val">{{ \Carbon\Carbon::parse($reporte->created_at)->format('d/m/Y H:i') }}</div>
                </div>
                <div class="s-meta-item">
                    <div class="s-meta-label">Última Actualización</div>
                    <div class="s-meta-val">{{ \Carbon\Carbon::parse($reporte->updated_at)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Botones finales --}}
    <div class="s-footer-btns">
        <a href="{{ route('tutor.reportes.index') }}" class="s-btn s-btn--slate">
            <i class="fas fa-arrow-left"></i> Volver al Listado
        </a>
        @if($reporte->tienePdf())
        <a href="{{ route('tutor.reportes.descargar-pdf', $reporte->id) }}" class="s-btn s-btn--red">
            <i class="fas fa-file-pdf"></i> Descargar PDF
        </a>
        @endif
    </div>

</div>
@endsection

@section('css')<style>
/* ── Hero ── */
.t-hero{position:relative;overflow:hidden;padding:28px 32px 24px;background:var(--t-hero-bg);border-bottom:1px solid var(--t-border);margin-bottom:0}
.t-hero__orb{position:absolute;border-radius:50%;filter:blur(50px);pointer-events:none}
.t-hero__orb--1{width:250px;height:250px;top:-60px;right:5%;background:var(--t-orb1)}
.t-hero__content{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.t-eyebrow{display:flex;align-items:center;gap:8px;font-size:.68rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#d97706;margin-bottom:6px}
.t-eyebrow__dot{width:7px;height:7px;border-radius:50%;background:#d97706}
.t-hero__title{font-size:1.8rem;font-weight:800;color:var(--t-text);margin:0 0 4px;line-height:1.1}
.t-hero__accent{color:#d97706}
.t-hero__sub{font-size:.82rem;color:var(--t-muted);margin:0}

/* ── Wrapper ── */
.s-wrapper{padding:24px 32px 40px;display:flex;flex-direction:column;gap:20px}

/* ── Infobar ── */
.s-infobar{display:flex;align-items:center;gap:14px;padding:16px 20px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;flex-wrap:wrap}
.s-infobar__avatar{width:48px;height:48px;border-radius:12px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;flex-shrink:0}
.s-infobar__data{flex:1;min-width:180px}
.s-infobar__nombre{font-size:.92rem;font-weight:700;color:#111827;margin-bottom:5px}
.s-infobar__meta{display:flex;align-items:center;gap:6px;font-size:.75rem;color:#9ca3af;flex-wrap:wrap}
.s-infobar__meta i{color:#d97706;font-size:.7rem}
.s-infobar__sep{color:#d1d5db}
.s-infobar__badges{display:flex;gap:7px;flex-wrap:wrap}

/* ── KPI row ── */
.s-kpi-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
@media(max-width:900px){.s-kpi-row{grid-template-columns:repeat(2,1fr)}}
@media(max-width:500px){.s-kpi-row{grid-template-columns:1fr 1fr}}

.s-kpi{display:flex;align-items:center;gap:12px;padding:16px 18px;background:#fff;border:1px solid #e5e7eb;border-radius:12px}
.s-kpi__icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0}
.s-kpi__val{font-size:1.3rem;font-weight:800;color:#111827;line-height:1.1}
.s-kpi__lbl{font-size:.62rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:#9ca3af;margin-top:3px}

.s-kpi--green .s-kpi__icon{background:#f0fdf4;color:#15803d}
.s-kpi--blue  .s-kpi__icon{background:#eff6ff;color:#1d4ed8}
.s-kpi--red   .s-kpi__icon{background:#fff1f2;color:#be123c}
.s-kpi--amber .s-kpi__icon{background:#fffbeb;color:#b45309}
.s-kpi--slate .s-kpi__icon{background:#f1f5f9;color:#475569}

/* ── Callout ── */
.s-callout{display:flex;align-items:flex-start;gap:12px;padding:14px 18px;border-radius:10px;font-size:.84rem;line-height:1.5}
.s-callout--green{background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d}
.s-callout--red{background:#fff1f2;border:1px solid #fecdd3;color:#be123c}
.s-callout i{font-size:1.1rem;margin-top:1px;flex-shrink:0}

/* ── Cards ── */
.s-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
.s-card__header{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid #f3f4f6}
.s-card__title{display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:700;color:#111827}
.s-card__icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.78rem}
.s-card__icon--amber{background:#fef3c7;color:#d97706}
.s-card__icon--green{background:#f0fdf4;color:#15803d}
.s-card__icon--blue{background:#eff6ff;color:#1d4ed8}
.s-card__icon--rose{background:#fff1f2;color:#be123c}
.s-card__icon--slate{background:#f1f5f9;color:#475569}
.s-card__tag{font-size:.73rem;font-weight:600;color:#9ca3af;background:#f9fafb;border:1px solid #e5e7eb;padding:3px 11px;border-radius:100px}
.s-card__body{padding:18px;overflow-x:auto}

/* ── Asistencias grid ── */
.s-asist-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
@media(max-width:700px){.s-asist-grid{grid-template-columns:repeat(2,1fr)}}
.s-asist{padding:16px;border-radius:10px;text-align:center}
.s-asist__val{font-size:1.8rem;font-weight:800;line-height:1}
.s-asist__lbl{font-size:.71rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;margin-top:5px;display:flex;align-items:center;justify-content:center;gap:5px}
.s-asist--blue{background:#eff6ff;color:#1d4ed8}
.s-asist--green{background:#f0fdf4;color:#15803d}
.s-asist--red{background:#fff1f2;color:#be123c}
.s-asist--amber{background:#fffbeb;color:#b45309}

/* ── Tabla ── */
.s-table{width:100%;border-collapse:collapse}
.s-table thead th{padding:10px 16px;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;background:#f9fafb;border-bottom:1px solid #f3f4f6;text-align:left;white-space:nowrap}
.s-table tbody tr{border-bottom:1px solid #f9fafb;transition:background .15s}
.s-table tbody tr:last-child{border-bottom:none}
.s-table tbody tr:hover{background:#fafafa}
.s-table td{padding:11px 16px;vertical-align:middle}
.s-num{font-size:.78rem;color:#9ca3af;font-weight:600}
.s-muted{font-size:.78rem;color:#9ca3af}
.s-fecha{font-size:.82rem;font-weight:600;color:#374151;white-space:nowrap}
.s-desc{font-size:.81rem;color:#374151}

/* ── Curso ── */
.s-curso{display:flex;align-items:center;gap:9px}
.s-curso__icon{width:26px;height:26px;border-radius:7px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:.68rem;flex-shrink:0}
.s-curso__nombre{font-weight:700;font-size:.83rem;color:#111827}

/* ── Nota final ── */
.s-final{display:inline-flex;align-items:center;justify-content:center;min-width:36px;padding:4px 10px;border-radius:7px;font-size:.95rem;font-weight:800}
.s-final--green{background:#f0fdf4;color:#15803d}
.s-final--blue{background:#eff6ff;color:#1d4ed8}
.s-final--red{background:#fff1f2;color:#be123c}

/* ── Badges ── */
.s-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
.s-badge--amber{background:#fffbeb;color:#b45309}
.s-badge--blue{background:#eff6ff;color:#1d4ed8}
.s-badge--green{background:#f0fdf4;color:#15803d}
.s-badge--red{background:#fff1f2;color:#be123c}

/* ── Meta info ── */
.s-meta-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
@media(max-width:800px){.s-meta-grid{grid-template-columns:repeat(2,1fr)}}
.s-meta-item{}
.s-meta-label{font-size:.68rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#9ca3af;margin-bottom:4px}
.s-meta-val{font-size:.84rem;font-weight:600;color:#111827}

/* ── Comentario ── */
.s-comentario{background:#f9fafb;border-radius:9px;padding:14px 16px;font-size:.84rem;color:#374151;line-height:1.6;white-space:pre-line;border:1px solid #f3f4f6}

/* ── Botones ── */
.s-btn{display:inline-flex;align-items:center;gap:6px;padding:7px 16px;border-radius:8px;font-size:.8rem;font-weight:700;text-decoration:none;transition:all .15s;white-space:nowrap}
.s-btn--slate{background:#f1f5f9;color:#475569;border:1px solid #e2e8f0}
.s-btn--slate:hover{background:#475569;color:#fff;border-color:#475569}
.s-btn--red{background:#fff1f2;color:#be123c;border:1px solid #fecdd3}
.s-btn--red:hover{background:#be123c;color:#fff;border-color:#be123c}
.s-footer-btns{display:flex;gap:10px;flex-wrap:wrap}

/* ── Empty ── */
.s-empty{display:flex;align-items:center;justify-content:center;gap:10px;padding:32px 20px;font-size:.84rem;color:#9ca3af}
.s-empty i{font-size:1.1rem}

@media(max-width:700px){
    .t-hero{padding:20px 16px 18px}
    .s-wrapper{padding:16px 16px 32px}
}
</style>@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('mensaje'))
Swal.fire({
    icon: '{{ session('icono') }}',
    title: '{{ session('mensaje') }}',
    showConfirmButton: true,
    timer: 3000
});
@endif
</script>
@endsection