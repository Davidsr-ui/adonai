@extends('layouts.tutor')

@section('title', 'Inicio — Portal Familiar')

@section('page_title')
    <span>Bienvenido</span> al Portal
@endsection

@section('content')

    @if(!Auth::user()->persona || !Auth::user()->persona->tutor)
        <div class="alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Perfil incompleto</strong>
                <p style="margin-top:4px;font-size:13px;">Tu perfil de tutor no está asociado correctamente. Por favor contacta al administrador del colegio.</p>
            </div>
        </div>
    @else

        <div class="welcome-banner">
            <div class="welcome-icon">👨‍👩‍👧</div>
            <div class="welcome-text">
                <h2>¡Hola, {{ Auth::user()->nombre_completo }}!</h2>
                <p>Aquí puedes seguir el progreso académico y el bienestar de tus hijos en el Colegio Adonai.</p>
            </div>
        </div>

        @if(count($alertas) > 0)
        <div style="background:#FFFBEB;border:1px solid #FCD34D;border-radius:14px;padding:16px 20px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:8px;font-weight:700;color:#92400E;margin-bottom:10px;">
                <i class="fas fa-bell" style="color:#F59E0B"></i>
                Alertas — Requieren tu atención
            </div>
            @foreach($alertas as $alerta)
            <div style="display:flex;align-items:flex-start;gap:10px;padding:8px 0;border-bottom:1px solid #FDE68A;font-size:13px;color:#78350F;">
                <i class="{{ $alerta['icono'] }}" style="margin-top:2px;color:#D97706"></i>
                <span>{{ $alerta['mensaje'] }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <div class="stat-grid">
            <a href="{{ route('tutor.mis-estudiantes') }}" class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-child"></i></div>
                <div>
                    <div class="stat-number">{{ $estudiantes->count() }}</div>
                    <div class="stat-label">Mis Estudiantes</div>
                </div>
            </a>
            <a href="{{ route('tutor.reportes.index') }}" class="stat-card">
                <div class="stat-icon amber"><i class="fas fa-file-alt"></i></div>
                <div>
                    <div class="stat-number">{{ $totalReportes }}</div>
                    <div class="stat-label">Reportes Disponibles</div>
                </div>
            </a>
            <a href="{{ route('tutor.comportamientos') }}" class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-bell"></i></div>
                <div>
                    <div class="stat-number">{{ $ultimosComportamientos->count() }}</div>
                    <div class="stat-label">Notificaciones</div>
                </div>
            </a>
            <a href="{{ route('tutor.asistencias') }}" class="stat-card">
                <div class="stat-icon green"><i class="fas fa-clipboard-check"></i></div>
                <div>
                    <div class="stat-number">—</div>
                    <div class="stat-label">Asistencias</div>
                </div>
            </a>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-child" style="color:var(--primary)"></i>
                    <h3>Mis Estudiantes</h3>
                    <span class="badge-count">{{ $estudiantes->count() }}</span>
                </div>
                <div class="card-body" style="padding:0">
                    @if($estudiantes->count() > 0)
                        <table class="t-table">
                            <thead><tr><th>Nombre</th><th>DNI</th><th>Grado</th></tr></thead>
                            <tbody>
                                @foreach($estudiantes as $est)
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div style="width:32px;height:32px;border-radius:50%;background:var(--primary-lt);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;flex-shrink:0;">
                                                {{ strtoupper(substr($est->persona->nombres, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight:700;font-size:13px;">{{ $est->persona->apellidos }}</div>
                                                <div style="font-size:11px;color:var(--text-muted);">{{ $est->persona->nombres }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-size:12px;color:var(--text-muted);">{{ $est->persona->dni }}</td>
                                    <td>
                                        @if($est->grado)
                                            <span class="badge badge-blue">{{ $est->grado->nombre }}</span>
                                        @else
                                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-graduate"></i>
                            <p>No tienes estudiantes asignados aún</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('tutor.mis-estudiantes') }}" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-eye"></i> Ver todos los estudiantes
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bolt" style="color:var(--accent)"></i>
                    <h3>Accesos Rápidos</h3>
                </div>
                <div class="card-body">
                    <div class="quick-grid">
                        <a href="{{ route('tutor.mis-estudiantes') }}" class="quick-btn blue"><i class="fas fa-child"></i>Mis Hijos</a>
                        <a href="{{ route('tutor.notas') }}" class="quick-btn amber"><i class="fas fa-star"></i>Ver Notas</a>
                        <a href="{{ route('tutor.asistencias') }}" class="quick-btn green"><i class="fas fa-clipboard-check"></i>Asistencias</a>
                        <a href="{{ route('tutor.comportamientos') }}" class="quick-btn red"><i class="fas fa-user-check"></i>Comportamiento</a>
                        <a href="{{ route('tutor.reportes.index') }}" class="quick-btn purple"><i class="fas fa-chart-line"></i>Reportes</a>
                        <a href="{{ route('tutor.horarios') }}" class="quick-btn slate"><i class="fas fa-calendar-alt"></i>Horarios</a>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bell" style="color:var(--accent)"></i>
                    <h3>Últimas Notificaciones</h3>
                    <span class="badge-count">{{ $ultimosComportamientos->count() }}</span>
                </div>
                <div class="card-body" style="padding:0">
                    @if($ultimosComportamientos->count() > 0)
                        @foreach($ultimosComportamientos as $comp)
                        <div style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;gap:12px;align-items:flex-start;">
                            <div style="width:36px;height:36px;border-radius:10px;background:var(--accent-lt);color:var(--accent);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="{{ $comp->tipo_icon ?? 'fas fa-flag' }}"></i>
                            </div>
                            <div style="flex:1">
                                <div style="font-weight:700;font-size:13px;">{{ $comp->estudiante->persona->apellidos }}, {{ $comp->estudiante->persona->nombres }}</div>
                                <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">{{ \Str::limit($comp->descripcion, 80) }}</div>
                                <div style="font-size:11px;color:var(--text-muted);margin-top:4px;"><i class="fas fa-clock"></i> {{ $comp->fecha_formateada ?? '' }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state"><i class="fas fa-bell-slash"></i><p>Sin notificaciones recientes</p></div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('tutor.comportamientos') }}" class="btn btn-outline btn-sm btn-block"><i class="fas fa-eye"></i> Ver todos</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-file-alt" style="color:var(--primary)"></i>
                    <h3>Últimos Reportes</h3>
                    <span class="badge-count">{{ $ultimosReportes->count() }}</span>
                </div>
                <div class="card-body" style="padding:0">
                    @if($ultimosReportes->count() > 0)
                        <table class="t-table">
                            <thead><tr><th>Estudiante</th><th>Periodo</th><th>Promedio</th><th></th></tr></thead>
                            <tbody>
                                @foreach($ultimosReportes as $rep)
                                <tr>
                                    <td style="font-weight:700;font-size:13px;">{{ $rep->estudiante->persona->apellidos }}</td>
                                    <td><span class="badge badge-gray">{{ $rep->periodo->nombre }}</span></td>
                                    <td>
                                        @if($rep->promedio_general)
                                            <span class="badge badge-blue">{{ number_format($rep->promedio_general, 1) }}</span>
                                        @else
                                            <span style="color:var(--text-muted);font-size:12px;">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('tutor.reportes.show', $rep->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state"><i class="fas fa-folder-open"></i><p>No hay reportes disponibles aún</p></div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('tutor.reportes.index') }}" class="btn btn-outline btn-sm btn-block"><i class="fas fa-eye"></i> Ver todos</a>
                </div>
            </div>
        </div>

    @endif

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('mensaje'))
    Swal.fire({ icon: '{{ session('icono') }}', title: '{{ session('mensaje') }}', timer: 3000, showConfirmButton: false });
    @endif
</script>
@endsection
