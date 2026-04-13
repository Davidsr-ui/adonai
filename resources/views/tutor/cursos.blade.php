@extends('layouts.tutor')
@section('title', 'Cursos Matriculados')
@section('page_title')Cursos <span>Matriculados</span>@endsection
@section('content')

<div class="t-hero">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Malla Curricular</div>
            <h1 class="t-hero__title">Cursos <span class="t-hero__accent">Matriculados</span></h1>
            <p class="t-hero__sub">Cursos en los que está inscrito tu hijo este periodo.</p>
        </div>
        <div class="t-hero__right">
            @if($tutor->estudiantes->count() > 1)
            <select id="estudianteSelect" onchange="cambiarEstudiante()" class="h-select">
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
            <span class="h-info-icon"><i class="fas fa-book-open"></i></span>
            <div>
                <div class="h-info-label">Total Cursos</div>
                <div class="h-info-value">{{ count($cursos) }} cursos</div>
            </div>
        </div>
    </div>

    {{-- Grid de cursos --}}
    @if(count($cursos) > 0)
    <div class="c-grid">
        @foreach($cursos as $curso)

        <div class="c-card">
            <div class="c-card__header">
                <div class="c-card__avatar">{{ strtoupper(substr($curso->curso_nombre, 0, 1)) }}</div>
                <div class="c-card__info">
                    <div class="c-card__nombre">{{ $curso->curso_nombre }}</div>
                    <div class="c-card__docente">
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ $curso->nombres }} {{ $curso->apellidos }}
                    </div>
                </div>
            </div>
            <div class="c-card__badges">
                @if($curso->codigo)
                <span class="r-badge r-badge--amber"><i class="fas fa-barcode"></i> {{ $curso->codigo }}</span>
                @endif
                @if($curso->horas_semanales)
                <span class="r-badge r-badge--blue"><i class="fas fa-clock"></i> {{ $curso->horas_semanales }}h/sem</span>
                @endif
                @if($curso->area_curricular)
                <span class="r-badge r-badge--green">{{ $curso->area_curricular }}</span>
                @endif
            </div>
            <div class="c-card__actions">
                <button onclick="document.getElementById('det-{{ $curso->id }}').style.display='flex'" class="r-btn r-btn--gray">
                    <i class="fas fa-info-circle"></i> Detalles
                </button>
                <a href="{{ route('tutor.mensajeria') }}?docente_user_id={{ $curso->user_id }}&estudiante_id={{ $estudiante->id }}" class="r-btn r-btn--amber">
                    <i class="fas fa-envelope"></i> Mensaje
                </a>
            </div>
        </div>

        {{-- Modal de detalles --}}
        <div id="det-{{ $curso->id }}" class="c-modal-overlay" onclick="if(event.target===this)this.style.display='none'">
            <div class="c-modal">
                <button onclick="document.getElementById('det-{{ $curso->id }}').style.display='none'" class="c-modal__close">
                    <i class="fas fa-times"></i>
                </button>
                <div class="c-modal__header">
                    <div class="c-modal__avatar">{{ strtoupper(substr($curso->curso_nombre, 0, 1)) }}</div>
                    <h3 class="c-modal__title">{{ $curso->curso_nombre }}</h3>
                </div>
                <div class="c-modal__body">
                    <div class="c-modal__row">
                        <span class="c-modal__label"><i class="fas fa-chalkboard-teacher"></i> Docente</span>
                        <span class="c-modal__val">{{ $curso->nombres }} {{ $curso->apellidos }}</span>
                    </div>
                    @if($curso->codigo)
                    <div class="c-modal__row">
                        <span class="c-modal__label"><i class="fas fa-barcode"></i> Código</span>
                        <span class="c-modal__val">{{ $curso->codigo }}</span>
                    </div>
                    @endif
                    @if($curso->horas_semanales)
                    <div class="c-modal__row">
                        <span class="c-modal__label"><i class="fas fa-clock"></i> Horas/Semana</span>
                        <span class="c-modal__val">{{ $curso->horas_semanales }}</span>
                    </div>
                    @endif
                    @if($curso->area_curricular)
                    <div class="c-modal__row">
                        <span class="c-modal__label"><i class="fas fa-layer-group"></i> Área Curricular</span>
                        <span class="c-modal__val">{{ $curso->area_curricular }}</span>
                    </div>
                    @endif
                    <div class="c-modal__row">
                        <span class="c-modal__label"><i class="fas fa-graduation-cap"></i> Grado</span>
                        <span class="c-modal__val">{{ $estudiante->grado->nombre ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="c-modal__footer">
                    <a href="{{ route('tutor.mensajeria') }}?docente_user_id={{ $curso->user_id }}&estudiante_id={{ $estudiante->id }}" class="r-btn r-btn--amber" style="width:100%;justify-content:center">
                        <i class="fas fa-envelope"></i> Enviar Mensaje al Docente
                    </a>
                </div>
            </div>
        </div>

        @endforeach
    </div>
    @else
    <div class="r-empty">
        <i class="fas fa-book-open"></i>
        <span>No hay cursos registrados para este grado</span>
    </div>
    @endif

@else
<div class="r-empty" style="padding:60px 20px">
    <i class="fas fa-user-graduate"></i>
    <span>No tienes estudiantes asignados</span>
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
.h-select{padding:7px 13px;border-radius:9px;border:1px solid #e5e7eb;background:#fff;color:#111827;font-size:.82rem;font-weight:600;outline:none;cursor:pointer}
.h-select:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(217,119,6,.1)}

/* ── Wrapper ── */
.r-wrapper{padding:24px 32px 40px}

/* ── Info bar ── */
.h-infobar{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:24px}
.h-info-item{display:flex;align-items:center;gap:12px;padding:12px 18px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;flex:1;min-width:160px}
.h-info-icon{width:34px;height:34px;border-radius:8px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:.82rem;flex-shrink:0}
.h-info-label{font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;margin-bottom:2px}
.h-info-value{font-size:.84rem;font-weight:700;color:#111827}

/* ── Grid de cursos ── */
.c-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:16px}

/* ── Card de curso ── */
.c-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;display:flex;flex-direction:column;transition:box-shadow .2s,transform .2s}
.c-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.08);transform:translateY(-2px)}
.c-card__header{display:flex;align-items:flex-start;gap:12px;padding:18px 18px 14px;border-bottom:1px solid #f3f4f6}
.c-card__avatar{width:42px;height:42px;border-radius:10px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0}
.c-card__info{flex:1;min-width:0}
.c-card__nombre{font-weight:800;font-size:.88rem;color:#111827;margin-bottom:4px;line-height:1.3}
.c-card__docente{font-size:.74rem;color:#9ca3af;display:flex;align-items:center;gap:5px}
.c-card__docente i{color:#d97706;font-size:.68rem}
.c-card__badges{display:flex;flex-wrap:wrap;gap:6px;padding:12px 18px;border-bottom:1px solid #f3f4f6;min-height:44px}
.c-card__actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px 18px}

/* ── Badges ── */
.r-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:6px;font-size:.72rem;font-weight:600}
.r-badge--amber{background:#fffbeb;color:#b45309}
.r-badge--blue{background:#eff6ff;color:#1d4ed8}
.r-badge--green{background:#f0fdf4;color:#15803d}

/* ── Botones ── */
.r-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border-radius:7px;font-size:.75rem;font-weight:600;text-decoration:none;transition:all .15s;white-space:nowrap;border:none;cursor:pointer;justify-content:center}
.r-btn--amber{background:#fef3c7;color:#b45309;border:1px solid #fde68a}
.r-btn--amber:hover{background:#d97706;color:#fff;border-color:#d97706}
.r-btn--gray{background:#f9fafb;color:#374151;border:1px solid #e5e7eb}
.r-btn--gray:hover{background:#f3f4f6;border-color:#d1d5db}

/* ── Modal ── */
.c-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:999;align-items:center;justify-content:center;backdrop-filter:blur(2px)}
.c-modal{background:#fff;border-radius:16px;padding:0;max-width:440px;width:90%;position:relative;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.15)}
.c-modal__close{position:absolute;top:14px;right:14px;background:#f3f4f6;border:none;border-radius:7px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#6b7280;font-size:.85rem;transition:all .15s;z-index:1}
.c-modal__close:hover{background:#e5e7eb;color:#111827}
.c-modal__header{display:flex;align-items:center;gap:14px;padding:24px 24px 18px;background:#fffbeb;border-bottom:1px solid #fde68a}
.c-modal__avatar{width:46px;height:46px;border-radius:11px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;flex-shrink:0;border:2px solid #fde68a}
.c-modal__title{font-size:1rem;font-weight:800;color:#b45309;margin:0;line-height:1.3}
.c-modal__body{padding:18px 24px;display:grid;gap:10px}
.c-modal__row{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid #f9fafb}
.c-modal__row:last-child{border-bottom:none}
.c-modal__label{font-size:.78rem;font-weight:600;color:#9ca3af;display:flex;align-items:center;gap:6px;white-space:nowrap}
.c-modal__label i{color:#d97706;width:12px}
.c-modal__val{font-size:.82rem;font-weight:700;color:#111827;text-align:right}
.c-modal__footer{padding:0 24px 20px}

/* ── Empty ── */
.r-empty{display:flex;align-items:center;justify-content:center;gap:10px;padding:40px 20px;font-size:.85rem;color:#9ca3af}
.r-empty i{font-size:1.2rem}

/* ── Responsive ── */
@media(max-width:700px){
    .t-hero{padding:20px 16px 18px}
    .r-wrapper{padding:16px 16px 32px}
    .c-grid{grid-template-columns:1fr}
}
</style>@endsection

@section('js')<script>
function cambiarEstudiante(){
    window.location.href='{{ route("tutor.cursos-matriculados") }}?estudiante_id='+document.getElementById('estudianteSelect').value;
}
</script>@endsection