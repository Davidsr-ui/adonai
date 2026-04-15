@extends('layouts.tutor')
@section('title', 'Reportes Académicos')
@section('page_title')Reportes <span>Académicos</span>@endsection
@section('content')

<div class="t-hero">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Informes Escolares</div>
            <h1 class="t-hero__title">Reportes <span class="t-hero__accent">Académicos</span></h1>
            <p class="t-hero__sub">Consulta los informes académicos de tus hijos.</p>
        </div>
        <div class="t-hero__right">
            <div class="r-pill"><i class="fas fa-file-alt"></i> {{ $reportes->count() }} reportes disponibles</div>
        </div>
    </div>
</div>

<div class="r-wrapper">
    <div class="r-card">
        <div class="r-card__header">
            <div class="r-card__title">
                <span class="r-card__icon"><i class="fas fa-file-alt"></i></span>
                Mis Reportes
            </div>
            <span class="r-card__tag">{{ $reportes->count() }} registros</span>
        </div>
        <div class="r-card__body">
            @if($reportes->count() > 0)
            <table class="r-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th>Periodo</th>
                        <th>Tipo</th>
                        <th style="text-align:center">Promedio</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportes as $i => $r)
                    <tr>
                        <td class="r-num">{{ $i+1 }}</td>
                        <td>
                            <div class="r-alumno">
                                <div class="r-avatar">{{ strtoupper(substr($r->estudiante->persona->nombres,0,1)) }}</div>
                                <div>
                                    <div class="r-nombre">{{ $r->estudiante->persona->apellidos }}</div>
                                    <div class="r-muted">{{ $r->estudiante->persona->nombres }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="r-badge r-badge--amber">{{ $r->periodo->nombre }}</span></td>
                        <td><span class="r-badge r-badge--blue">{{ $r->tipo }}</span></td>
                        <td style="text-align:center">
                            @if($r->promedio_general)
                            <span class="r-promedio r-promedio--{{ $r->promedio_general>=14 ? 'green' : ($r->promedio_general>=11 ? 'blue' : 'red') }}">
                                {{ number_format($r->promedio_general,1) }}
                            </span>
                            @else
                            <span class="r-muted">N/A</span>
                            @endif
                        </td>
                        <td class="r-muted">{{ $r->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="r-acciones">
                                <a href="{{ route('tutor.reportes.show', $r->id) }}" class="r-btn r-btn--amber" title="Ver reporte">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('tutor.reportes.descargar-pdf', $r->id) }}" class="r-btn r-btn--red" title="Descargar PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="r-empty">
                <i class="fas fa-folder-open"></i>
                <span>No hay reportes disponibles aún</span>
            </div>
            @endif
        </div>
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

/* ── Pill ── */
.r-pill{display:inline-flex;align-items:center;gap:7px;padding:7px 15px;border-radius:100px;font-size:.78rem;font-weight:600;background:#fffbeb;border:1px solid #fde68a;color:#b45309}

/* ── Wrapper ── */
.r-wrapper{padding:24px 32px 40px}

/* ── Card ── */
.r-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
.r-card__header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f3f4f6}
.r-card__title{display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:700;color:#111827}
.r-card__icon{width:32px;height:32px;border-radius:8px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:.8rem}
.r-card__tag{font-size:.75rem;font-weight:600;color:#9ca3af;background:#f9fafb;border:1px solid #e5e7eb;padding:4px 12px;border-radius:100px}
.r-card__body{padding:0;overflow-x:auto}

/* ── Tabla ── */
.r-table{width:100%;border-collapse:collapse}
.r-table thead th{padding:10px 16px;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;background:#f9fafb;border-bottom:1px solid #f3f4f6;text-align:left;white-space:nowrap}
.r-table tbody tr{border-bottom:1px solid #f9fafb;transition:background .15s}
.r-table tbody tr:last-child{border-bottom:none}
.r-table tbody tr:hover{background:#fafafa}
.r-table td{padding:12px 16px;vertical-align:middle}
.r-num{font-size:.78rem;color:#9ca3af;font-weight:600}
.r-muted{font-size:.78rem;color:#9ca3af}

/* ── Alumno ── */
.r-alumno{display:flex;align-items:center;gap:10px}
.r-avatar{width:36px;height:36px;border-radius:9px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0}
.r-nombre{font-weight:700;font-size:.84rem;color:#111827}

/* ── Promedio ── */
.r-promedio{display:inline-flex;align-items:center;justify-content:center;min-width:38px;padding:4px 10px;border-radius:7px;font-size:.95rem;font-weight:800}
.r-promedio--green{background:#f0fdf4;color:#15803d}
.r-promedio--blue{background:#eff6ff;color:#1d4ed8}
.r-promedio--red{background:#fff1f2;color:#be123c}

/* ── Badges ── */
.r-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
.r-badge--amber{background:#fffbeb;color:#b45309}
.r-badge--blue{background:#eff6ff;color:#1d4ed8}

/* ── Botones ── */
.r-acciones{display:flex;gap:6px}
.r-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:7px;font-size:.75rem;font-weight:600;text-decoration:none;transition:all .15s;white-space:nowrap}
.r-btn--amber{background:#fef3c7;color:#b45309;border:1px solid #fde68a}
.r-btn--amber:hover{background:#d97706;color:#fff;border-color:#d97706}
.r-btn--red{background:#fff1f2;color:#be123c;border:1px solid #fecdd3}
.r-btn--red:hover{background:#be123c;color:#fff;border-color:#be123c}

/* ── Empty ── */
.r-empty{display:flex;align-items:center;justify-content:center;gap:10px;padding:40px 20px;font-size:.85rem;color:#9ca3af}
.r-empty i{font-size:1.2rem}

@media(max-width:700px){
    .t-hero{padding:20px 16px 18px}
    .r-wrapper{padding:16px 16px 32px}
}
</style>@endsection