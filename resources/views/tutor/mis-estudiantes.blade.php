@extends('layouts.tutor')
@section('title', 'Mis Estudiantes')
@section('page_title')Mis <span>Estudiantes</span>@endsection
@section('content')

<div class="t-hero">
    <div class="t-hero__orb t-hero__orb--1"></div>
    <div class="t-hero__content">
        <div class="t-hero__left">
            <div class="t-eyebrow"><span class="t-eyebrow__dot"></span>Tutoria Academica</div>
            <h1 class="t-hero__title">Mis <span class="t-hero__accent">Estudiantes</span></h1>
            <p class="t-hero__sub">Listado de estudiantes bajo tu tutoria.</p>
        </div>
    </div>
</div>

<div class="est-wrapper">
@if(Auth::user()->persona && Auth::user()->persona->tutor)
    @php
        $estudiantes = Auth::user()->persona->tutor->estudiantes()->with(['persona','grado'])->get();
    @endphp

    <div class="est-card">
        <div class="est-card__header">
            <div class="est-card__title">
                <span class="est-card__icon"><i class="fas fa-users"></i></span>
                Estudiantes Bajo Mi Tutoria
            </div>
            <span class="est-card__tag">{{ $estudiantes->count() }} registrados</span>
        </div>

        <div class="est-card__body">
            @if($estudiantes->count() > 0)
            <table class="est-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th>DNI</th>
                        <th>Grado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($estudiantes as $i => $est)
                    <tr>
                        <td class="est-num">{{ $i+1 }}</td>
                        <td>
                            <div class="est-alumno">
                                <div class="est-avatar">{{ strtoupper(substr($est->persona->nombres,0,1)) }}</div>
                                <div>
                                    <div class="est-nombre">{{ $est->persona->apellidos }}, {{ $est->persona->nombres }}</div>
                                    <div class="est-codigo">{{ $est->codigo_estudiante }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="est-muted">{{ $est->persona->dni }}</td>
                        <td>
                            @if($est->grado)
                                <span class="est-badge est-badge--amber">{{ $est->grado->nombre_completo }}</span>
                            @else
                                <span class="est-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="est-badge {{ $est->persona->estado=='Activo' ? 'est-badge--green' : 'est-badge--red' }}">
                                {{ $est->persona->estado }}
                            </span>
                        </td>
                        <td>
                            <div class="est-acciones">
                                <a href="{{ route('tutor.notas') }}" class="est-btn est-btn--amber" title="Ver notas">
                                    <i class="fas fa-star"></i> Notas
                                </a>
                                <a href="{{ route('tutor.asistencias') }}" class="est-btn est-btn--slate" title="Ver asistencias">
                                    <i class="fas fa-clipboard-check"></i> Asistencias
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="est-empty">
                <i class="fas fa-user-graduate"></i>
                <span>No tienes estudiantes asignados aun</span>
            </div>
            @endif
        </div>
    </div>

@else
    <div class="est-alerta">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Perfil incompleto</strong>
            <p>Contacta al administrador para completar tu perfil.</p>
        </div>
    </div>
@endif
</div>

@endsection

@section('css')<style>
/* Hero */
.t-hero{position:relative;overflow:hidden;padding:28px 32px 24px;background:var(--t-hero-bg);border-bottom:1px solid var(--t-border);margin-bottom:0}
.t-hero__orb{position:absolute;border-radius:50%;filter:blur(50px);pointer-events:none}
.t-hero__orb--1{width:250px;height:250px;top:-60px;right:5%;background:var(--t-orb1)}
.t-hero__content{position:relative;z-index:1}
.t-eyebrow{display:flex;align-items:center;gap:8px;font-size:.68rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:#d97706;margin-bottom:6px}
.t-eyebrow__dot{width:7px;height:7px;border-radius:50%;background:#d97706}
.t-hero__title{font-size:1.8rem;font-weight:800;color:var(--t-text);margin:0 0 4px;line-height:1.1}
.t-hero__accent{color:#d97706}
.t-hero__sub{font-size:.82rem;color:var(--t-muted);margin:0}

/* Wrapper */
.est-wrapper{padding:24px 32px 40px}

/* Card */
.est-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden}
.est-card__header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid #f3f4f6}
.est-card__title{display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:700;color:#111827}
.est-card__icon{width:32px;height:32px;border-radius:8px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:.8rem}
.est-card__tag{font-size:.75rem;font-weight:600;color:#9ca3af;background:#f9fafb;border:1px solid #e5e7eb;padding:4px 12px;border-radius:100px}
.est-card__body{padding:0}

/* Tabla */
.est-table{width:100%;border-collapse:collapse}
.est-table thead th{padding:10px 16px;font-size:.71rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af;background:#f9fafb;border-bottom:1px solid #f3f4f6;text-align:left;white-space:nowrap}
.est-table tbody tr{border-bottom:1px solid #f9fafb;transition:background .15s}
.est-table tbody tr:last-child{border-bottom:none}
.est-table tbody tr:hover{background:#fafafa}
.est-table td{padding:13px 16px;vertical-align:middle}
.est-num{font-size:.78rem;color:#9ca3af;font-weight:600}
.est-muted{font-size:.8rem;color:#9ca3af}

/* Alumno */
.est-alumno{display:flex;align-items:center;gap:10px}
.est-avatar{width:36px;height:36px;border-radius:9px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0}
.est-nombre{font-weight:700;font-size:.85rem;color:#111827}
.est-codigo{font-size:.7rem;color:#9ca3af;margin-top:2px}

/* Badges */
.est-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:6px;font-size:.75rem;font-weight:600}
.est-badge--amber{background:#fffbeb;color:#b45309}
.est-badge--green{background:#f0fdf4;color:#15803d}
.est-badge--red{background:#fff1f2;color:#be123c}

/* Botones accion */
.est-acciones{display:flex;gap:6px}
.est-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:7px;font-size:.75rem;font-weight:600;text-decoration:none;transition:all .15s;white-space:nowrap}
.est-btn--amber{background:#fef3c7;color:#b45309;border:1px solid #fde68a}
.est-btn--amber:hover{background:#d97706;color:#fff;border-color:#d97706}
.est-btn--slate{background:#f1f5f9;color:#475569;border:1px solid #e2e8f0}
.est-btn--slate:hover{background:#475569;color:#fff;border-color:#475569}

/* Vacio */
.est-empty{display:flex;align-items:center;justify-content:center;gap:10px;padding:40px 20px;font-size:.85rem;color:#9ca3af}
.est-empty i{font-size:1.2rem}

/* Alerta */
.est-alerta{display:flex;align-items:flex-start;gap:14px;padding:16px 20px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:.83rem;color:#92400e}
.est-alerta i{color:#d97706;margin-top:2px}
.est-alerta p{margin:4px 0 0;color:#b45309;font-size:.78rem}
</style>@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
@if(session('mensaje'))
Swal.fire({icon:'{{ session('icono') }}',title:'{{ session('mensaje') }}',timer:2500,showConfirmButton:false});
@endif
</script>
@endsection