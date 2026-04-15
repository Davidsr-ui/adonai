@extends('layouts.tutor')
@section('title', 'Comportamientos')
@section('page_title')Ver <span>Comportamientos</span>@endsection
@section('content')

<div class="t-hero">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Seguimiento Conductual</div>
            <h1 class="t-hero__title">Comportamiento <span class="t-hero__accent">Escolar</span></h1>
            <p class="t-hero__sub">Registro de comportamientos notificados por los docentes.</p>
        </div>
        <div class="t-hero__right">
            @if($tutor->estudiantes->count() > 1)
            <select id="estudianteSelect" onchange="cambiarEstudiante()" class="c-select">
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

<div class="c-wrapper">
@if($estudiante)

    {{-- Info del estudiante --}}
    <div class="c-infobar">
        <div class="c-infobar__avatar">{{ strtoupper(substr($estudiante->persona->nombres,0,1)) }}</div>
        <div class="c-infobar__data">
            <div class="c-infobar__nombre">{{ $estudiante->persona->apellidos }}, {{ $estudiante->persona->nombres }}</div>
            <div class="c-infobar__meta">
                <span><i class="fas fa-graduation-cap"></i> {{ $estudiante->grado->nombre ?? 'N/A' }}</span>
                <span class="c-infobar__sep">·</span>
                <span><i class="fas fa-id-card"></i> {{ $estudiante->persona->dni }}</span>
            </div>
        </div>
        <div class="c-infobar__kpis">
            <div class="c-kpi c-kpi--amber"><div class="c-kpi__val">{{ $totalComportamientos }}</div><div class="c-kpi__lbl">Total</div></div>
            <div class="c-kpi c-kpi--green"><div class="c-kpi__val">{{ $positivos }}</div><div class="c-kpi__lbl">Positivos</div></div>
            <div class="c-kpi c-kpi--red"><div class="c-kpi__val">{{ $negativos }}</div><div class="c-kpi__lbl">Negativos</div></div>
            <div class="c-kpi c-kpi--blue"><div class="c-kpi__val">{{ $conSancion }}</div><div class="c-kpi__lbl">Con Sanción</div></div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="c-card">
        <div class="c-card__header">
            <div class="c-card__title">
                <span class="c-card__icon"><i class="fas fa-user-check"></i></span>
                Registro de Comportamientos
            </div>
            <span class="c-card__tag">{{ $comportamientos->count() }} registros</span>
        </div>
        <div class="c-card__body">
            @if($comportamientos->count() > 0)
            <table class="c-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Docente</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comportamientos as $i => $c)
                    <tr>
                        <td class="c-num">{{ $i+1 }}</td>
                        <td>
                            <div class="c-fecha">{{ $c->fecha_formateada }}</div>
                            <div class="c-muted">{{ $c->dia_semana }}</div>
                        </td>
                        <td>
                            <span class="c-badge {{ $c->tipo=='Positivo' ? 'c-badge--green' : ($c->tipo=='Negativo' ? 'c-badge--red' : 'c-badge--amber') }}">
                                <i class="fas {{ $c->tipo=='Positivo' ? 'fa-smile' : ($c->tipo=='Negativo' ? 'fa-frown' : 'fa-meh') }}"></i>
                                {{ $c->tipo }}
                            </span>
                        </td>
                        <td>
                            <div class="c-desc">{{ \Str::limit($c->descripcion, 60) }}</div>
                            @if($c->sancion)
                            <span class="c-badge c-badge--amber" style="margin-top:5px">
                                <i class="fas fa-exclamation-triangle"></i> Con sanción
                            </span>
                            @endif
                        </td>
                        <td class="c-muted">{{ $c->docente?->persona->apellidos ?? 'N/A' }}</td>
                        <td>
                            <button class="c-btn" onclick="verDetalle({{ $c->id }})" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="c-empty">
                <i class="fas fa-bell-slash"></i>
                <span>No hay comportamientos notificados</span>
            </div>
            @endif
        </div>
    </div>

@else
    <div class="c-alerta">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Sin estudiantes asignados</strong>
            <p>No tienes estudiantes vinculados a tu perfil de tutor.</p>
        </div>
    </div>
@endif
</div>

{{-- Modales --}}
@if($estudiante)
@foreach($comportamientos as $c)
<div id="modal-{{ $c->id }}" class="c-modal-overlay" onclick="if(event.target===this)this.style.display='none'">
    <div class="c-modal">
        <button class="c-modal__close" onclick="document.getElementById('modal-{{ $c->id }}').style.display='none'">
            <i class="fas fa-times"></i>
        </button>
        <div class="c-modal__header">
            <div class="c-modal__icon"><i class="fas fa-user-check"></i></div>
            <div>
                <div class="c-modal__title">Detalle del Comportamiento</div>
                <div class="c-modal__sub">{{ $c->fecha_formateada }} · {{ $c->dia_semana }}</div>
            </div>
        </div>
        <div class="c-modal__body">
            <div class="c-modal__row">
                <div class="c-modal__label">Estudiante</div>
                <div class="c-modal__val">{{ $c->estudiante->persona->apellidos }}, {{ $c->estudiante->persona->nombres }}</div>
            </div>
            <div class="c-modal__row">
                <div class="c-modal__label">Tipo</div>
                <div class="c-modal__val">
                    <span class="c-badge {{ $c->tipo=='Positivo' ? 'c-badge--green' : ($c->tipo=='Negativo' ? 'c-badge--red' : 'c-badge--amber') }}">
                        <i class="fas {{ $c->tipo=='Positivo' ? 'fa-smile' : ($c->tipo=='Negativo' ? 'fa-frown' : 'fa-meh') }}"></i>
                        {{ $c->tipo }}
                    </span>
                </div>
            </div>
            <div class="c-modal__row">
                <div class="c-modal__label">Descripción</div>
                <div class="c-modal__val c-modal__desc">{{ $c->descripcion }}</div>
            </div>
            @if($c->sancion)
            <div class="c-modal__row">
                <div class="c-modal__label">Sanción</div>
                <div class="c-modal__val c-modal__sancion">{{ $c->sancion }}</div>
            </div>
            @endif
            <div class="c-modal__row">
                <div class="c-modal__label">Docente</div>
                <div class="c-modal__val">{{ $c->docente?->persona->apellidos ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

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
.c-select{padding:7px 13px;border-radius:9px;border:1px solid var(--t-border);background:var(--t-surface);color:var(--t-text);font-size:.82rem;font-weight:600;cursor:pointer;min-width:200px}
.c-select:focus{outline:none;border-color:#d97706}

/* ── Wrapper ── */
.c-wrapper{padding:24px 32px 40px;display:flex;flex-direction:column;gap:20px}

/* ── Infobar ── */
.c-infobar{display:flex;align-items:center;gap:14px;padding:16px 20px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;flex-wrap:wrap}
.c-infobar__avatar{width:44px;height:44px;border-radius:11px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0}
.c-infobar__data{flex:1;min-width:160px}
.c-infobar__nombre{font-size:.9rem;font-weight:700;color:#111827;margin-bottom:4px}
.c-infobar__meta{display:flex;align-items:center;gap:6px;font-size:.75rem;color:#9ca3af;flex-wrap:wrap}
.c-infobar__meta i{color:#d97706;font-size:.7rem}
.c-infobar__sep{color:#d1d5db}
.c-infobar__kpis{display:flex;gap:8px;flex-wrap:wrap}

/* ── Mini KPIs ── */
.c-kpi{padding:8px 16px;border-radius:9px;text-align:center;min-width:68px}
.c-kpi__val{font-size:1.2rem;font-weight:800;line-height:1}
.c-kpi__lbl{font-size:.62rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;margin-top:3px}
.c-kpi--green{background:#f0fdf4;color:#15803d}
.c-kpi--red{background:#fff1f2;color:#be123c}
.c-kpi--amber{background:#fffbeb;color:#b45309}
.c-kpi--blue{background:#eff6ff;color:#1d4ed8}

/* ── Card ── */
.c-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
.c-card__header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f3f4f6}
.c-card__title{display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:700;color:#111827}
.c-card__icon{width:32px;height:32px;border-radius:8px;background:#fff1f2;color:#be123c;display:flex;align-items:center;justify-content:center;font-size:.8rem}
.c-card__tag{font-size:.75rem;font-weight:600;color:#9ca3af;background:#f9fafb;border:1px solid #e5e7eb;padding:4px 12px;border-radius:100px}
.c-card__body{padding:0;overflow-x:auto}

/* ── Tabla ── */
.c-table{width:100%;border-collapse:collapse}
.c-table thead th{padding:10px 16px;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;background:#f9fafb;border-bottom:1px solid #f3f4f6;text-align:left;white-space:nowrap}
.c-table tbody tr{border-bottom:1px solid #f9fafb;transition:background .15s}
.c-table tbody tr:last-child{border-bottom:none}
.c-table tbody tr:hover{background:#fafafa}
.c-table td{padding:12px 16px;vertical-align:middle}
.c-num{font-size:.78rem;color:#9ca3af;font-weight:600}
.c-muted{font-size:.78rem;color:#9ca3af}
.c-fecha{font-size:.84rem;font-weight:700;color:#111827}
.c-desc{font-size:.81rem;color:#374151}

/* ── Badges ── */
.c-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
.c-badge--amber{background:#fffbeb;color:#b45309}
.c-badge--green{background:#f0fdf4;color:#15803d}
.c-badge--red{background:#fff1f2;color:#be123c}
.c-badge--blue{background:#eff6ff;color:#1d4ed8}

/* ── Botón ver ── */
.c-btn{width:30px;height:30px;border-radius:7px;background:#fef3c7;color:#b45309;border:1px solid #fde68a;display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;cursor:pointer;transition:all .15s}
.c-btn:hover{background:#d97706;color:#fff;border-color:#d97706}

/* ── Empty / Alerta ── */
.c-empty{display:flex;align-items:center;justify-content:center;gap:10px;padding:40px 20px;font-size:.85rem;color:#9ca3af}
.c-empty i{font-size:1.2rem}
.c-alerta{display:flex;align-items:flex-start;gap:14px;padding:16px 20px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:.83rem;color:#92400e}
.c-alerta i{color:#d97706;margin-top:2px}
.c-alerta p{margin:4px 0 0;color:#b45309;font-size:.78rem}

/* ── Modal ── */
.c-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:999;align-items:center;justify-content:center}
.c-modal{background:#fff;border-radius:14px;padding:0;max-width:520px;width:90%;position:relative;max-height:85vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2)}
.c-modal__close{position:absolute;top:14px;right:14px;width:28px;height:28px;border-radius:7px;background:#f3f4f6;border:none;cursor:pointer;color:#6b7280;display:flex;align-items:center;justify-content:center;font-size:.8rem;transition:background .15s}
.c-modal__close:hover{background:#e5e7eb}
.c-modal__header{display:flex;align-items:center;gap:12px;padding:20px 22px 16px;border-bottom:1px solid #f3f4f6}
.c-modal__icon{width:38px;height:38px;border-radius:10px;background:#fff1f2;color:#be123c;display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0}
.c-modal__title{font-size:.95rem;font-weight:800;color:#111827}
.c-modal__sub{font-size:.74rem;color:#9ca3af;margin-top:2px}
.c-modal__body{padding:18px 22px;display:flex;flex-direction:column;gap:12px}
.c-modal__row{display:grid;grid-template-columns:100px 1fr;gap:10px;align-items:start}
.c-modal__label{font-size:.72rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#9ca3af;padding-top:2px}
.c-modal__val{font-size:.85rem;color:#111827;font-weight:500}
.c-modal__desc{background:#f9fafb;border-radius:8px;padding:10px 12px;white-space:pre-line;font-size:.82rem;color:#374151;line-height:1.5}
.c-modal__sancion{background:#fffbeb;border-radius:8px;padding:10px 12px;font-size:.82rem;color:#92400e;border:1px solid #fde68a}

@media(max-width:700px){
    .t-hero{padding:20px 16px 18px}
    .c-wrapper{padding:16px 16px 32px}
    .c-infobar__kpis{width:100%}
    .c-modal__row{grid-template-columns:1fr}
}
</style>@endsection

@section('js')<script>
function cambiarEstudiante(){
    window.location.href='{{ route("tutor.comportamientos") }}?estudiante_id='+document.getElementById('estudianteSelect').value;
}
function verDetalle(id){
    document.getElementById('modal-'+id).style.display='flex';
}
</script>@endsection