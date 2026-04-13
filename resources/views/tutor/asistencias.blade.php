@extends('layouts.tutor')
@section('title', 'Asistencias')
@section('page_title')Ver <span>Asistencias</span>@endsection
@section('content')
<div class="t-hero" style="margin-bottom:20px">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Control de Asistencia</div>
            <h1 class="t-hero__title">Asistencias de <span class="t-hero__accent">Mis Hijos</span></h1>
            <p class="t-hero__sub">Revisa el historial de asistencias registrado.</p>
        </div>
        <div class="t-hero__right">
            @if($tutor->estudiantes->count() > 1)
            <select id="estudianteSelect" onchange="cambiarEstudiante()" class="as-select">
                @foreach($tutor->estudiantes as $est)
                <option value="{{ $est->id }}" {{ $estudiante && $estudiante->id==$est->id ? 'selected' : '' }}>{{ $est->persona->apellidos }} {{ $est->persona->nombres }}</option>
                @endforeach
            </select>
            @endif
        </div>
    </div>
</div>

<div class="as-wrapper">
@if($estudiante)

    {{-- Info estudiante --}}
    <div class="as-info-bar">
        <div class="as-info-item"><i class="fas fa-user"></i><span><strong>{{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}</strong></span></div>
        <div class="as-info-item"><i class="fas fa-graduation-cap"></i><span>{{ $estudiante->grado->nombre ?? 'N/A' }}</span></div>
    </div>

    {{-- KPIs --}}
    <div class="as-kpi-grid">
        <div class="as-kpi">
            <div class="as-kpi__num as-kpi__num--green">{{ $presentes }}</div>
            <div class="as-kpi__label">Presentes</div>
        </div>
        <div class="as-kpi">
            <div class="as-kpi__num as-kpi__num--red">{{ $ausentes }}</div>
            <div class="as-kpi__label">Ausentes</div>
        </div>
        <div class="as-kpi">
            <div class="as-kpi__num as-kpi__num--amber">{{ $tardanzas }}</div>
            <div class="as-kpi__label">Tardanzas</div>
        </div>
        <div class="as-kpi as-kpi--destacado">
            <div class="as-kpi__num">{{ number_format($porcentaje,1) }}%</div>
            <div class="as-kpi__label">Asistencia</div>
        </div>
    </div>

    {{-- Barra de progreso --}}
    <div class="as-progress-wrap">
        <div class="as-progress-top">
            <span>Porcentaje de asistencia general</span>
            <span class="as-pct {{ $porcentaje>=85 ? 'as-pct--green' : ($porcentaje>=70 ? 'as-pct--amber' : 'as-pct--red') }}">{{ number_format($porcentaje,1) }}%</span>
        </div>
        <div class="as-progress-bar">
            <div class="as-progress-fill {{ $porcentaje>=85 ? 'as-fill--green' : ($porcentaje>=70 ? 'as-fill--amber' : 'as-fill--red') }}" style="width:{{ $porcentaje }}%"></div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="as-card">
        <div class="as-card__header">
            <span class="as-card__title"><i class="fas fa-clipboard-check"></i> Historial de Asistencias</span>
        </div>
        <div class="as-card__body">
            @if($asistencias->count() > 0)
            <table class="as-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Curso</th>
                        <th>Estado</th>
                        <th>Docente</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asistencias as $a)
                    <tr>
                        <td>
                            <div class="as-fecha">{{ $a->fecha_formateada }}</div>
                            <div class="as-dia">{{ $a->dia_semana }}</div>
                        </td>
                        <td>{{ $a->curso->nombre }}</td>
                        <td>
                            <span class="as-badge as-badge--{{ $a->estado=='Presente' ? 'green' : ($a->estado=='Ausente' ? 'red' : 'amber') }}">
                                {{ $a->estado }}
                            </span>
                        </td>
                        <td class="as-muted">{{ $a->docente?->persona->apellidos ?? 'N/A' }}</td>
                        <td class="as-muted">{{ $a->observaciones ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="as-empty"><i class="fas fa-clipboard"></i><span>No hay registros de asistencias</span></div>
            @endif
        </div>
    </div>

@else
<div class="as-empty"><i class="fas fa-user-graduate"></i><span>No tienes estudiantes asignados</span></div>
@endif
</div>
@endsection

@section('css')<style>
/* Hero */
.t-hero{position:relative;overflow:hidden;padding:28px 32px 24px;background:var(--t-hero-bg);border-bottom:1px solid var(--t-border)}
.t-hero__orb{position:absolute;border-radius:50%;filter:blur(50px);pointer-events:none}
.t-hero__orb--1{width:250px;height:250px;top:-60px;right:5%;background:var(--t-orb1)}
.t-hero__content{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.t-eyebrow{display:flex;align-items:center;gap:8px;font-size:.68rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#d97706;margin-bottom:6px}
.t-eyebrow__dot{width:7px;height:7px;border-radius:50%;background:#d97706}
.t-hero__title{font-size:1.8rem;font-weight:800;color:var(--t-text);margin:0 0 4px;line-height:1.1}
.t-hero__accent{color:#d97706}
.t-hero__sub{font-size:.82rem;color:var(--t-muted);margin:0}

.as-select{padding:8px 14px;border-radius:8px;border:1.5px solid #d1d5db;background:#fff;color:#111827;font-size:.82rem;font-weight:600;font-family:inherit;cursor:pointer}
.as-select:focus{outline:none;border-color:#d97706}

.as-wrapper{padding:24px 32px 40px}

/* Info bar — muy sutil */
.as-info-bar{display:flex;flex-wrap:wrap;gap:20px;padding:12px 18px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;margin-bottom:20px}
.as-info-item{display:flex;align-items:center;gap:8px;font-size:.83rem;color:#374151}
.as-info-item i{color:#d97706;font-size:.8rem}

/* KPIs — solo numeros grandes, sin cajas de colores */
.as-kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px}
@media(max-width:640px){.as-kpi-grid{grid-template-columns:repeat(2,1fr)}}
.as-kpi{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:16px 18px;text-align:center}
.as-kpi--destacado{border-color:#d97706;background:#fffbeb}
.as-kpi__num{font-size:1.7rem;font-weight:800;color:#111827;line-height:1}
.as-kpi__num--green{color:#16a34a}
.as-kpi__num--red{color:#dc2626}
.as-kpi__num--amber{color:#d97706}
.as-kpi__label{font-size:.72rem;font-weight:600;color:#9ca3af;margin-top:5px}

/* Barra progreso */
.as-progress-wrap{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:16px 20px;margin-bottom:20px}
.as-progress-top{display:flex;justify-content:space-between;font-size:.8rem;font-weight:600;color:#6b7280;margin-bottom:10px}
.as-pct{font-weight:800}
.as-pct--green{color:#16a34a}
.as-pct--amber{color:#d97706}
.as-pct--red{color:#dc2626}
.as-progress-bar{height:8px;background:#f3f4f6;border-radius:100px;overflow:hidden}
.as-progress-fill{height:100%;border-radius:100px;transition:width .8s ease}
.as-fill--green{background:#22c55e}
.as-fill--amber{background:#f59e0b}
.as-fill--red{background:#f43f5e}

/* Card */
.as-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
.as-card__header{padding:14px 20px;border-bottom:1px solid #f3f4f6}
.as-card__title{font-size:.86rem;font-weight:700;color:#374151;display:flex;align-items:center;gap:8px}
.as-card__title i{color:#d97706}
.as-card__body{padding:0}

/* Tabla */
.as-table{width:100%;border-collapse:collapse}
.as-table thead th{padding:10px 16px;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;background:#f9fafb;border-bottom:1px solid #f3f4f6;text-align:left}
.as-table tbody tr{border-bottom:1px solid #f9fafb;transition:background .15s}
.as-table tbody tr:last-child{border-bottom:none}
.as-table tbody tr:hover{background:#fafafa}
.as-table td{padding:12px 16px;font-size:.83rem;color:#374151;vertical-align:middle}
.as-fecha{font-weight:700;font-size:.83rem;color:#111827}
.as-dia{font-size:.7rem;color:#9ca3af;margin-top:2px}
.as-muted{color:#9ca3af !important;font-size:.78rem !important}

/* Badges — solo texto con punto de color */
.as-badge{display:inline-flex;align-items:center;gap:5px;font-size:.78rem;font-weight:600;padding:3px 10px;border-radius:6px}
.as-badge--green{background:#f0fdf4;color:#15803d}
.as-badge--red{background:#fff1f2;color:#be123c}
.as-badge--amber{background:#fffbeb;color:#b45309}

/* Vacio */
.as-empty{display:flex;align-items:center;justify-content:center;gap:10px;padding:40px 20px;font-size:.85rem;color:#9ca3af}
.as-empty i{font-size:1.2rem}
</style>@endsection

@section('js')<script>
function cambiarEstudiante(){
    window.location.href='{{ route("tutor.asistencias") }}?estudiante_id='+document.getElementById('estudianteSelect').value;
}
</script>@endsection