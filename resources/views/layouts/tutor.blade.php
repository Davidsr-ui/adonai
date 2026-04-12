<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Familiar') — Colegio Adonai</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Lora:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:    #2563EB;
            --primary-lt: #EFF6FF;
            --primary-dk: #1D4ED8;
            --accent:     #F59E0B;
            --accent-lt:  #FFFBEB;
            --success:    #10B981;
            --success-lt: #ECFDF5;
            --danger:     #EF4444;
            --danger-lt:  #FEF2F2;
            --purple:     #8B5CF6;
            --purple-lt:  #F5F3FF;
            --sidebar-w:  260px;
            --header-h:   68px;
            --bg:         #F0F4F8;
            --surface:    #FFFFFF;
            --border:     #E2E8F0;
            --text:       #1E293B;
            --text-muted: #64748B;
            --radius:     14px;
            --shadow:     0 2px 12px rgba(0,0,0,.07);
            --shadow-md:  0 4px 24px rgba(0,0,0,.10);
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), #60A5FA);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 20px;
            flex-shrink: 0;
        }
        .brand-text { line-height: 1.2; }
        .brand-text strong { display: block; font-size: 14px; font-weight: 800; color: var(--text); }
        .brand-text span   { font-size: 11px; color: var(--text-muted); font-weight: 500; }

        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-avatar {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), #FCD34D);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 16px; color: #fff;
            flex-shrink: 0;
        }
        .user-info strong { display: block; font-size: 13px; font-weight: 700; }
        .user-info span   { font-size: 11px; color: var(--text-muted); }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }

        .nav-label {
            font-size: 10px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1.2px;
            color: var(--text-muted);
            padding: 10px 8px 6px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px; font-weight: 600;
            transition: all .2s;
            margin-bottom: 2px;
            position: relative;
        }
        .nav-item:hover { background: var(--primary-lt); color: var(--primary); }
        .nav-item.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,.3);
        }
        .nav-item .nav-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
            background: rgba(0,0,0,.05);
            flex-shrink: 0;
        }
        .nav-item.active .nav-icon { background: rgba(255,255,255,.2); }
        .nav-item:hover:not(.active) .nav-icon { background: rgba(37,99,235,.1); }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }
        .btn-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            color: var(--danger);
            text-decoration: none;
            font-size: 14px; font-weight: 600;
            transition: all .2s;
            width: 100%;
            border: none; background: none; cursor: pointer;
        }
        .btn-logout:hover { background: var(--danger-lt); }

        /* ── MAIN ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── HEADER ── */
        .topbar {
            height: var(--header-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 28px;
            gap: 16px;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title {
            font-family: 'Lora', serif;
            font-size: 20px; font-weight: 600;
            color: var(--text);
            flex: 1;
        }
        .topbar-title span { color: var(--primary); }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .topbar-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); font-size: 16px;
            cursor: pointer; transition: all .2s; text-decoration: none;
        }
        .topbar-btn:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-lt); }

        .menu-toggle-btn {
            display: none;
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface);
            align-items: center; justify-content: center;
            color: var(--text); font-size: 18px;
            cursor: pointer;
        }

        /* ── PAGE ── */
        .page-body {
            flex: 1;
            padding: 28px;
        }

        /* ── ALERTS ── */
        .alert-flash {
            padding: 14px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 12px;
            font-size: 14px; font-weight: 600;
            animation: slideDown .3s ease;
        }
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px) } to { opacity:1; transform:translateY(0) } }
        .alert-success { background: var(--success-lt); color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error   { background: var(--danger-lt);  color: #991B1B; border: 1px solid #FECACA; }

        /* ── CARDS ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .card-header h3 { font-size: 15px; font-weight: 700; margin: 0; }
        .card-header .badge-count {
            margin-left: auto;
            background: var(--primary-lt);
            color: var(--primary);
            font-size: 12px; font-weight: 700;
            padding: 2px 8px; border-radius: 20px;
        }
        .card-body { padding: 22px; }
        .card-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border);
            background: #FAFBFC;
            border-radius: 0 0 var(--radius) var(--radius);
        }

        /* ── STAT CARDS ── */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex; align-items: center; gap: 16px;
            box-shadow: var(--shadow);
            transition: transform .2s, box-shadow .2s;
            text-decoration: none; color: inherit;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }
        .stat-icon.blue   { background: var(--primary-lt); color: var(--primary); }
        .stat-icon.amber  { background: var(--accent-lt);  color: var(--accent); }
        .stat-icon.green  { background: var(--success-lt); color: var(--success); }
        .stat-icon.purple { background: var(--purple-lt);  color: var(--purple); }
        .stat-number { font-size: 28px; font-weight: 800; line-height: 1; }
        .stat-label  { font-size: 12px; color: var(--text-muted); font-weight: 600; margin-top: 4px; }

        /* ── QUICK ACCESS ── */
        .quick-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .quick-btn {
            display: flex; flex-direction: column; align-items: center;
            gap: 8px; padding: 18px 10px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 12px; font-weight: 700;
            transition: all .2s; text-align: center;
            border: 2px solid transparent;
        }
        .quick-btn i { font-size: 22px; }
        .quick-btn.blue   { background: var(--primary-lt);  color: var(--primary); }
        .quick-btn.amber  { background: var(--accent-lt);   color: #D97706; }
        .quick-btn.green  { background: var(--success-lt);  color: #047857; }
        .quick-btn.red    { background: var(--danger-lt);   color: #DC2626; }
        .quick-btn.purple { background: var(--purple-lt);   color: #7C3AED; }
        .quick-btn.slate  { background: #F1F5F9;            color: #475569; }
        .quick-btn:hover  { border-color: currentColor; transform: translateY(-2px); box-shadow: var(--shadow); }

        /* ── TABLE ── */
        .t-table { width: 100%; border-collapse: collapse; }
        .t-table th { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--text-muted); padding: 10px 14px; border-bottom: 2px solid var(--border); text-align: left; }
        .t-table td { padding: 12px 14px; border-bottom: 1px solid var(--border); font-size: 14px; }
        .t-table tr:last-child td { border-bottom: none; }
        .t-table tr:hover td { background: var(--primary-lt); }

        /* ── BADGE ── */
        .badge {
            display: inline-block;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700;
        }
        .badge-blue   { background: var(--primary-lt);  color: var(--primary); }
        .badge-green  { background: var(--success-lt);  color: #047857; }
        .badge-red    { background: var(--danger-lt);   color: #DC2626; }
        .badge-amber  { background: var(--accent-lt);   color: #D97706; }
        .badge-purple { background: var(--purple-lt);   color: #7C3AED; }
        .badge-gray   { background: #F1F5F9;            color: #475569; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px; border-radius: 9px;
            font-size: 13px; font-weight: 700;
            border: none; cursor: pointer;
            text-decoration: none; transition: all .2s;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dk); box-shadow: 0 4px 12px rgba(37,99,235,.3); }
        .btn-outline { background: transparent; color: var(--primary); border: 2px solid var(--primary); }
        .btn-outline:hover { background: var(--primary-lt); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-block { width: 100%; justify-content: center; }

        /* ── EMPTY STATE ── */
        .empty-state { text-align: center; padding: 30px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 36px; margin-bottom: 10px; display: block; opacity: .4; }
        .empty-state p { font-size: 14px; font-weight: 600; }

        /* ── WELCOME BANNER ── */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, #3B82F6 60%, #60A5FA 100%);
            border-radius: var(--radius);
            padding: 24px 28px;
            color: #fff;
            margin-bottom: 24px;
            display: flex; align-items: center; gap: 20px;
            position: relative; overflow: hidden;
        }
        .welcome-banner::before {
            content: '';
            position: absolute; right: -20px; top: -20px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
        }
        .welcome-banner::after {
            content: '';
            position: absolute; right: 60px; bottom: -40px;
            width: 100px; height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        .welcome-icon { font-size: 40px; z-index: 1; }
        .welcome-text { z-index: 1; }
        .welcome-text h2 { font-family: 'Lora', serif; font-size: 20px; font-weight: 600; margin-bottom: 4px; }
        .welcome-text p  { font-size: 13px; opacity: .85; }

        /* ── ALERT WARNING ── */
        .alert-warning {
            background: #FFFBEB;
            border: 1px solid #FCD34D;
            border-radius: var(--radius);
            padding: 18px 22px;
            color: #92400E;
            display: flex; gap: 14px; align-items: flex-start;
            margin-bottom: 20px;
        }
        .alert-warning i { font-size: 20px; color: var(--accent); margin-top: 2px; }

        /* ── OVERLAY ── */
        .overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99; }

        /* ── RESPONSIVE ── */
        @media(max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-md); }
            .overlay.open { display: block; }
            .main-wrap { margin-left: 0; }
            .menu-toggle-btn { display: flex; }
            .page-body { padding: 16px; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .quick-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>

    @yield('css')
</head>
<body>

<!-- Overlay móvil -->
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- ── SIDEBAR ── -->
<aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
        <div class="brand-text">
            <strong>Colegio Adonai</strong>
            <span>Portal Familiar</span>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div class="user-info">
            <strong>{{ Auth::user()->nombre_completo }}</strong>
            <span>Padre / Tutor</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Principal</div>

        <a href="{{ route('tutor.dashboard') }}" class="nav-item {{ request()->routeIs('tutor.dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-home"></i></span>
            Inicio
        </a>

        <a href="{{ route('tutor.mis-estudiantes') }}" class="nav-item {{ request()->routeIs('tutor.mis-estudiantes') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-child"></i></span>
            Mis Estudiantes
        </a>

        <div class="nav-label" style="margin-top:8px">Seguimiento</div>

        <a href="{{ route('tutor.notas') }}" class="nav-item {{ request()->routeIs('tutor.notas') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-star"></i></span>
            Ver Notas
        </a>

        <a href="{{ route('tutor.asistencias') }}" class="nav-item {{ request()->routeIs('tutor.asistencias') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-clipboard-check"></i></span>
            Asistencias
        </a>

        <a href="{{ route('tutor.comportamientos') }}" class="nav-item {{ request()->routeIs('tutor.comportamientos') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-user-check"></i></span>
            Comportamiento
        </a>

        <a href="{{ route('tutor.reportes.index') }}" class="nav-item {{ request()->routeIs('tutor.reportes.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
            Reportes
        </a>

        <div class="nav-label" style="margin-top:8px">Información</div>

        <a href="{{ route('tutor.horarios') }}" class="nav-item {{ request()->routeIs('tutor.horarios') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
            Horarios
        </a>

        <a href="{{ route('tutor.cursos-matriculados') }}" class="nav-item {{ request()->routeIs('tutor.cursos-matriculados') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-book-open"></i></span>
            Cursos Matriculados
        </a>

        <a href="{{ route('tutor.mensajeria') }}" class="nav-item {{ request()->routeIs('tutor.mensajeria*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-comments"></i></span>
            Mensajería
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

<!-- ── MAIN ── -->
<div class="main-wrap">

    <header class="topbar">
        <button class="menu-toggle-btn" onclick="openSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-title">
            @yield('page_title', '<span>Portal</span> Familiar')
        </div>

        <div class="topbar-actions">
            <a href="{{ route('tutor.mensajeria') }}" class="topbar-btn" title="Mensajes">
                <i class="fas fa-envelope"></i>
            </a>
            <a href="{{ route('tutor.dashboard') }}" class="topbar-btn" title="Inicio">
                <i class="fas fa-home"></i>
            </a>
        </div>
    </header>

    <main class="page-body">

        @if(session('mensaje'))
            <div class="alert-flash {{ session('icono') === 'success' ? 'alert-success' : 'alert-error' }}">
                <i class="fas {{ session('icono') === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                {{ session('mensaje') }}
            </div>
        @endif

        @yield('content')

    </main>
</div>

<script>
    function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('overlay').classList.add('open'); }
    function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('open'); }
</script>

@yield('js')
</body>
</html>
