@extends('layouts.tutor')
@section('title', 'Horarios')
@section('page_title')Horario de <span>Clases</span>@endsection
@section('content')

<div class="t-hero">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Horario Semanal</div>
            <h1 class="t-hero__title">Horario de <span class="t-hero__accent">Clases</span></h1>
            <p class="t-hero__sub">Consulta el horario semanal de tu hijo.</p>
        </div>
        <div class="t-hero__right">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                @if($tutor->estudiantes->count() > 1)
                <select id="estudianteSelect" onchange="cambiarEstudiante()" class="h-select">
                    @foreach($tutor->estudiantes as $est)
                    <option value="{{ $est->id }}" {{ $estudiante && $estudiante->id==$est->id ? 'selected' : '' }}>
                        {{ $est->persona->apellidos }} {{ $est->persona->nombres }}
                    </option>
                    @endforeach
                </select>
                @endif
                <button onclick="window.print()" class="r-btn r-btn--amber">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<div class="r-wrapper">
    @if($estudiante)

    {{-- Info bar --}}
    <div class="h-infobar">
        <div class="h-info-item">
            <span class="h-info-icon"><i class="fas fa-user"></i></span>
            <div>
                <div class="h-info-label">Estudiante</div>
                <div class="h-info-value">{{ $estudiante->persona->apellidos }} {{ $estudiante->persona->nombres }}</div>
            </div>
        </div>
        <div class="h-info-item">
            <span class="h-info-icon"><i class="fas fa-graduation-cap"></i></span>
            <div>
                <div class="h-info-label">Grado</div>
                <div class="h-info-value">{{ $estudiante->grado->nombre ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="h-info-item">
            <span class="h-info-icon"><i class="fas fa-layer-group"></i></span>
            <div>
                <div class="h-info-label">Nivel</div>
                <div class="h-info-value">{{ $estudiante->grado->nivel->nombre ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    {{-- Card de horario --}}
    <div class="r-card">
        <div class="r-card__header">
            <div class="r-card__title">
                <span class="r-card__icon"><i class="fas fa-calendar-alt"></i></span>
                Horario Semanal
            </div>
            <span class="r-card__tag">{{ count($horarioSemanal) }} bloques horarios</span>
        </div>
        <div class="r-card__body">
            @if(count($horarioSemanal) > 0)
            <table class="r-table h-table">
                <thead>
                    <tr>
                        <th style="min-width:110px">Hora</th>
                        @foreach(['Lunes','Martes','Miercoles','Jueves','Viernes'] as $dia)
                        <th>{{ $dia }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($horarioSemanal as $hora => $dias)
                    <tr>
                        <td>
                            <div class="h-hora">
                                <i class="fas fa-clock"></i>
                                {{ $hora }}
                            </div>
                        </td>
                        @foreach(['Lunes','Martes','Miercoles','Jueves','Viernes'] as $dia)
                        <td>
                            @if(isset($dias[$dia]))
                            <div class="h-clase">
                                <div class="h-clase__curso">{{ $dias[$dia]['curso'] }}</div>
                                <div class="h-clase__meta">
                                    <span><i class="fas fa-chalkboard-teacher"></i> {{ $dias[$dia]['docente'] }}</span>
                                    <span><i class="fas fa-door-open"></i> {{ $dias[$dia]['aula'] }}</span>
                                </div>
                            </div>
                            @else
                            <div class="h-vacio">—</div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="r-empty">
                <i class="fas fa-calendar"></i>
                <span>No hay horario registrado para este grado</span>
            </div>
            @endif
        </div>
    </div>

    @else
    <div class="r-empty" style="padding:60px 20px">
        <i class="fas fa-user-graduate"></i>
        <span>No tienes estudiantes asignados</span>
    </div>
    @endif
</div>

@endsection

@section('css')<style>
/* ── Hero (mismo del sistema) ── */
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
.h-select{padding:7px 13px;border-radius:9px;border:1px solid #e5e7eb;background:#fff;color:#111827;font-size:.82rem;font-weight:600;outline:none;cursor:pointer}
.h-select:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.1)}

/* ── Wrapper ── */
.r-wrapper{padding:24px 32px 40px}

/* ── Info bar ── */
.h-infobar{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:20px}
.h-info-item{display:flex;align-items:center;gap:12px;padding:12px 18px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;flex:1;min-width:160px}
.h-info-icon{width:34px;height:34px;border-radius:8px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:.82rem;flex-shrink:0}
.h-info-label{font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:2px}
.h-info-value{font-size:.84rem;font-weight:700;color:#111827}

/* ── Card (mismo del sistema) ── */
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
.r-table td{padding:10px 14px;vertical-align:middle}

/* ── Hora ── */
.h-hora{display:flex;align-items:center;gap:6px;font-size:.78rem;font-weight:700;color:#374151;white-space:nowrap}
.h-hora i{color:#d97706;font-size:.72rem}

/* ── Clase ── */
.h-clase{background:#fffbeb;border-left:3px solid #d97706;border-radius:7px;padding:8px 10px}
.h-clase__curso{font-weight:700;font-size:.78rem;color:#b45309;margin-bottom:4px}
.h-clase__meta{display:flex;flex-direction:column;gap:2px}
.h-clase__meta span{font-size:.68rem;color:#9ca3af;display:flex;align-items:center;gap:4px}
.h-clase__meta i{color:#d97706;width:10px}

/* ── Vacío ── */
.h-vacio{text-align:center;color:#d1d5db;font-size:.85rem;font-weight:500}
.r-empty{display:flex;align-items:center;justify-content:center;gap:10px;padding:40px 20px;font-size:.85rem;color:#9ca3af}
.r-empty i{font-size:1.2rem}

/* ── Botones ── */
.r-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:7px;font-size:.75rem;font-weight:600;text-decoration:none;transition:all .15s;white-space:nowrap;border:none;cursor:pointer}
.r-btn--amber{background:#fef3c7;color:#b45309;border:1px solid #fde68a}
.r-btn--amber:hover{background:#d97706;color:#fff;border-color:#d97706}

/* ── Print ── */
@media print{.sidebar,.topbar,.t-hero__right,.h-select{display:none!important}}

/* ── Responsive ── */
@media(max-width:700px){
    .t-hero{padding:20px 16px 18px}
    .r-wrapper{padding:16px 16px 32px}
    .h-infobar{gap:8px}
}
</style>@endsection

@section('js')<script>
function cambiarEstudiante(){
    window.location.href='{{ route("tutor.horarios") }}?estudiante_id='+document.getElementById('estudianteSelect').value;
}
</script>
@endsection