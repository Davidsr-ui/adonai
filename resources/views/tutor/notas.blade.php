@extends('layouts.tutor')
@section('title', 'Notas de Mis Estudiantes')
@section('page_title')Ver <span>Notas</span>@endsection
@section('content')

<div class="t-hero">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Rendimiento Académico</div>
            <h1 class="t-hero__title">Notas de Mis <span class="t-hero__accent">Estudiantes</span></h1>
            <p class="t-hero__sub">Consulta las calificaciones registradas por los docentes.</p>
        </div>
        <div class="t-hero__right">
            @if($tutor->estudiantes->count() > 1)
            <select id="estudianteSelect" onchange="cambiarEstudiante()" class="n-select">
                @foreach($tutor->estudiantes as $est)
                <option value="{{ $est->id }}" {{ $estudiante && $estudiante->id==$est->id ? 'selected' : '' }}>
                    {{ $est->persona->apellidos }} {{ $est->persona->nombres }}
                </option>
                @endforeach
            </select>
            @endif
        </div>
    </div>
</div>

<div class="n-wrapper">
@if($estudiante)

    {{-- Info del estudiante --}}
    <div class="n-infobar">
        <div class="n-infobar__avatar">{{ strtoupper(substr($estudiante->persona->nombres,0,1)) }}</div>
        <div class="n-infobar__data">
            <div class="n-infobar__nombre">{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</div>
            <div class="n-infobar__meta">
                <span><i class="fas fa-graduation-cap"></i> {{ $estudiante->grado->nombre ?? 'N/A' }}</span>
                <span class="n-infobar__sep">·</span>
                <span><i class="fas fa-layer-group"></i> {{ $estudiante->grado->nivel->nombre ?? 'N/A' }}</span>
                <span class="n-infobar__sep">·</span>
                <span><i class="fas fa-id-card"></i> {{ $estudiante->persona->dni }}</span>
            </div>
        </div>
        <div class="n-infobar__kpis">
            <div class="n-kpi n-kpi--green"><div class="n-kpi__val">{{ $aprobados }}</div><div class="n-kpi__lbl">Aprobados</div></div>
            <div class="n-kpi n-kpi--red"><div class="n-kpi__val">{{ $desaprobados }}</div><div class="n-kpi__lbl">Desaprobados</div></div>
            <div class="n-kpi n-kpi--amber"><div class="n-kpi__val">{{ number_format($promedio,1) }}</div><div class="n-kpi__lbl">Promedio</div></div>
            <div class="n-kpi n-kpi--slate"><div class="n-kpi__val">{{ $totalNotas }}</div><div class="n-kpi__lbl">Total</div></div>
        </div>
    </div>

    {{-- Tabla de notas --}}
    <div class="n-card">
        <div class="n-card__header">
            <div class="n-card__title">
                <span class="n-card__icon"><i class="fas fa-star"></i></span>
                Calificaciones Registradas
            </div>
            <span class="n-card__tag">{{ $notas->count() }} registros</span>
        </div>
        <div class="n-card__body">
            @if($notas->count() > 0)
            <table class="n-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Curso</th>
                        <th>Periodo</th>
                        <th>Tipo</th>
                        <th style="text-align:center">Práctica</th>
                        <th style="text-align:center">Teoría</th>
                        <th style="text-align:center">Final</th>
                        <th>Estado</th>
                        <th>Docente</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notas as $i => $nota)
                    <tr>
                        <td class="n-num">{{ $i+1 }}</td>
                        <td>
                            <div class="n-curso">
                                <div class="n-curso__icon"><i class="fas fa-book"></i></div>
                                <span class="n-curso__nombre">{{ $nota->matricula->curso->nombre }}</span>
                            </div>
                        </td>
                        <td><span class="n-badge n-badge--amber">{{ $nota->periodo->nombre }}</span></td>
                        <td><span class="n-badge n-badge--blue">{{ $nota->tipo_evaluacion }}</span></td>
                        <td style="text-align:center" class="n-muted">{{ $nota->nota_practica ?? '–' }}</td>
                        <td style="text-align:center" class="n-muted">{{ $nota->nota_teoria ?? '–' }}</td>
                        <td style="text-align:center">
                            <span class="n-final n-final--{{ $nota->nota_final >= 14 ? 'green' : ($nota->nota_final >= 11 ? 'blue' : 'red') }}">
                                {{ $nota->nota_final }}
                            </span>
                        </td>
                        <td>
                            <span class="n-badge {{ $nota->nota_final >= 11 ? 'n-badge--green' : 'n-badge--red' }}">
                                {{ $nota->estado_nota_texto }}
                            </span>
                        </td>
                        <td class="n-muted">{{ $nota->docente?->persona->apellidos ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="n-empty">
                <i class="fas fa-star"></i>
                <span>No hay notas publicadas para este estudiante</span>
            </div>
            @endif
        </div>
    </div>

@else
    <div class="n-alerta">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Sin estudiantes asignados</strong>
            <p>No tienes estudiantes vinculados a tu perfil de tutor.</p>
        </div>
    </div>
@endif
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

/* ── Select ── */
.n-select{padding:7px 13px;border-radius:9px;border:1px solid var(--t-border);background:var(--t-surface);color:var(--t-text);font-size:.82rem;font-weight:600;cursor:pointer;min-width:200px}
.n-select:focus{outline:none;border-color:#d97706}

/* ── Wrapper ── */
.n-wrapper{padding:24px 32px 40px;display:flex;flex-direction:column;gap:20px}

/* ── Infobar ── */
.n-infobar{display:flex;align-items:center;gap:14px;padding:16px 20px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;flex-wrap:wrap}
.n-infobar__avatar{width:44px;height:44px;border-radius:11px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0}
.n-infobar__data{flex:1;min-width:160px}
.n-infobar__nombre{font-size:.9rem;font-weight:700;color:#111827;margin-bottom:4px}
.n-infobar__meta{display:flex;align-items:center;gap:6px;font-size:.75rem;color:#9ca3af;flex-wrap:wrap}
.n-infobar__meta i{color:#d97706;font-size:.7rem}
.n-infobar__sep{color:#d1d5db}
.n-infobar__kpis{display:flex;gap:8px;flex-wrap:wrap}

/* ── Mini KPIs ── */
.n-kpi{padding:8px 16px;border-radius:9px;text-align:center;min-width:68px}
.n-kpi__val{font-size:1.2rem;font-weight:800;line-height:1}
.n-kpi__lbl{font-size:.62rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;margin-top:3px}
.n-kpi--green{background:#f0fdf4;color:#15803d}
.n-kpi--red{background:#fff1f2;color:#be123c}
.n-kpi--amber{background:#fffbeb;color:#b45309}
.n-kpi--slate{background:#f1f5f9;color:#475569}

/* ── Card ── */
.n-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
.n-card__header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f3f4f6}
.n-card__title{display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:700;color:#111827}
.n-card__icon{width:32px;height:32px;border-radius:8px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:.8rem}
.n-card__tag{font-size:.75rem;font-weight:600;color:#9ca3af;background:#f9fafb;border:1px solid #e5e7eb;padding:4px 12px;border-radius:100px}
.n-card__body{padding:0;overflow-x:auto}

/* ── Tabla ── */
.n-table{width:100%;border-collapse:collapse}
.n-table thead th{padding:10px 16px;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;background:#f9fafb;border-bottom:1px solid #f3f4f6;text-align:left;white-space:nowrap}
.n-table tbody tr{border-bottom:1px solid #f9fafb;transition:background .15s}
.n-table tbody tr:last-child{border-bottom:none}
.n-table tbody tr:hover{background:#fafafa}
.n-table td{padding:12px 16px;vertical-align:middle}
.n-num{font-size:.78rem;color:#9ca3af;font-weight:600}
.n-muted{font-size:.8rem;color:#9ca3af}

/* ── Curso ── */
.n-curso{display:flex;align-items:center;gap:9px}
.n-curso__icon{width:28px;height:28px;border-radius:7px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:.7rem;flex-shrink:0}
.n-curso__nombre{font-weight:700;font-size:.84rem;color:#111827}

/* ── Nota final ── */
.n-final{display:inline-flex;align-items:center;justify-content:center;min-width:36px;padding:4px 10px;border-radius:7px;font-size:.95rem;font-weight:800}
.n-final--green{background:#f0fdf4;color:#15803d}
.n-final--blue{background:#eff6ff;color:#1d4ed8}
.n-final--red{background:#fff1f2;color:#be123c}

/* ── Badges ── */
.n-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
.n-badge--amber{background:#fffbeb;color:#b45309}
.n-badge--blue{background:#eff6ff;color:#1d4ed8}
.n-badge--green{background:#f0fdf4;color:#15803d}
.n-badge--red{background:#fff1f2;color:#be123c}

/* ── Empty ── */
.n-empty{display:flex;align-items:center;justify-content:center;gap:10px;padding:40px 20px;font-size:.85rem;color:#9ca3af}
.n-empty i{font-size:1.2rem}

/* ── Alerta ── */
.n-alerta{display:flex;align-items:flex-start;gap:14px;padding:16px 20px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:.83rem;color:#92400e}
.n-alerta i{color:#d97706;margin-top:2px}
.n-alerta p{margin:4px 0 0;color:#b45309;font-size:.78rem}

@media(max-width:700px){
    .t-hero{padding:20px 16px 18px}
    .n-wrapper{padding:16px 16px 32px}
    .n-infobar__kpis{width:100%}
}
</style>@endsection

@section('js')<script>
function cambiarEstudiante(){
    window.location.href='{{ route("tutor.notas") }}?estudiante_id='+document.getElementById('estudianteSelect').value;
}
</script>@endsection