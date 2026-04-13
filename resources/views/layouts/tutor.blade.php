<!DOCTYPE html>
<html lang="es" data-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Familiar') â€” Colegio Adonai</title>
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
            --header-h:   64px;
            --bg:         #F0F4F8;
            --surface:    #FFFFFF;
            --border:     #E2E8F0;
            --text:       #1E293B;
            --text-muted: #64748B;
            --radius:     14px;
            --shadow:     0 2px 12px rgba(0,0,0,.07);
            --shadow-md:  0 4px 24px rgba(0,0,0,.10);
        }

        /* â”€â”€ MODO OSCURO â”€â”€ */
        [data-theme="dark"] {
            --bg:         #0F172A;
            --surface:    #1E293B;
            --border:     #334155;
            --text:       #F1F5F9;
            --text-muted: #94A3B8;
            --primary-lt: rgba(37,99,235,.15);
            --accent-lt:  rgba(245,158,11,.15);
            --success-lt: rgba(16,185,129,.15);
            --danger-lt:  rgba(239,68,68,.15);
            --purple-lt:  rgba(139,92,246,.15);
            --shadow:     0 2px 12px rgba(0,0,0,.3);
            --shadow-md:  0 4px 24px rgba(0,0,0,.4);
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            transition: background .3s, color .3s;
        }

        /* â”€â”€ SIDEBAR â”€â”€ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform .3s ease, background .3s, border-color .3s;
        }

        .sidebar-brand {
            height: var(--header-h);
            padding: 0 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), #60A5FA);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 18px;
            flex-shrink: 0;
        }
        .brand-text strong { display: block; font-size: 13px; font-weight: 800; color: var(--text); line-height: 1.3; }
        .brand-text span   { font-size: 11px; color: var(--text-muted); font-weight: 500; }

        .sidebar-user {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), #FCD34D);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 15px; color: #fff;
            flex-shrink: 0;
        }
        .user-info strong { display: block; font-size: 13px; font-weight: 700; color: var(--text); }
        .user-info span   { font-size: 11px; color: var(--text-muted); }

        .sidebar-nav { flex: 1; padding: 14px 12px; overflow-y: auto; }

        .nav-label {
            font-size: 10px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 1.2px;
            color: var(--text-muted);
            padding: 10px 8px 5px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px; font-weight: 600;
            transition: all .2s;
            margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--primary-lt); color: var(--primary); }
        .nav-item.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,.3);
        }
        .nav-item .nav-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            background: rgba(100,116,139,.1);
            flex-shrink: 0;
        }
        .nav-item.active .nav-icon { background: rgba(255,255,255,.2); }
        .nav-item:hover:not(.active) .nav-icon { background: rgba(37,99,235,.12); }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border);
        }
        .btn-logout {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--danger);
            text-decoration: none;
            font-size: 13.5px; font-weight: 600;
            transition: all .2s;
            width: 100%;
            border: none; background: none; cursor: pointer;
        }
        .btn-logout:hover { background: var(--danger-lt); }

        /* â”€â”€ MAIN â”€â”€ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* â”€â”€ TOPBAR â€” alineado con sidebar â”€â”€ */
        .topbar {
            height: var(--header-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 14px;
            position: sticky; top: 0; z-index: 50;
            transition: background .3s, border-color .3s;
        }
        .topbar-title {
            font-family: 'Lora', serif;
            font-size: 18px; font-weight: 600;
            color: var(--text);
            flex: 1;
        }
        .topbar-title span { color: var(--primary); }

        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .topbar-btn {
            width: 36px; height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: var(--surface);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); font-size: 15px;
            cursor: pointer; transition: all .2s;
            text-decoration: none;
        }
        .topbar-btn:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-lt); }

        .menu-toggle-btn {
            display: none;
            width: 36px; height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: var(--surface);
            align-items: center; justify-content: center;
            color: var(--text); font-size: 17px;
            cursor: pointer;
        }

        /* â”€â”€ PAGE â”€â”€ */
        .page-body { flex: 1; padding: 24px; }

        /* â”€â”€ ALERTS â”€â”€ */
        .alert-flash {
            padding: 13px 18px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 12px;
            font-size: 14px; font-weight: 600;
            animation: slideDown .3s ease;
        }
        @keyframes slideDown { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
        .alert-success { background: var(--success-lt); color: #065F46; border: 1px solid rgba(16,185,129,.3); }
        .alert-error   { background: var(--danger-lt);  color: #991B1B; border: 1px solid rgba(239,68,68,.3); }

        /* â”€â”€ CARDS â”€â”€ */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); transition: background .3s, border-color .3s; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 10px; }
        .card-header h3 { font-size: 14px; font-weight: 700; margin: 0; color: var(--text); }
        .card-header .badge-count { margin-left: auto; background: var(--primary-lt); color: var(--primary); font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
        .card-body { padding: 20px; }
        .card-footer { padding: 13px 20px; border-top: 1px solid var(--border); border-radius: 0 0 var(--radius) var(--radius); }

        /* â”€â”€ STAT CARDS â”€â”€ */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 22px; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 18px; display: flex; align-items: center; gap: 14px;
            box-shadow: var(--shadow); transition: transform .2s, box-shadow .2s, background .3s;
            text-decoration: none; color: inherit;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .stat-icon.blue   { background: var(--primary-lt); color: var(--primary); }
        .stat-icon.amber  { background: var(--accent-lt);  color: var(--accent); }
        .stat-icon.green  { background: var(--success-lt); color: var(--success); }
        .stat-icon.purple { background: var(--purple-lt);  color: var(--purple); }
        .stat-number { font-size: 26px; font-weight: 800; line-height: 1; color: var(--text); }
        .stat-label  { font-size: 11px; color: var(--text-muted); font-weight: 600; margin-top: 4px; }

        /* â”€â”€ QUICK ACCESS â”€â”€ */
        .quick-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .quick-btn {
            display: flex; flex-direction: column; align-items: center;
            gap: 7px; padding: 16px 8px;
            border-radius: 11px; text-decoration: none;
            font-size: 11.5px; font-weight: 700;
            transition: all .2s; text-align: center;
            border: 2px solid transparent;
        }
        .quick-btn i { font-size: 20px; }
        .quick-btn.blue   { background: var(--primary-lt);  color: var(--primary); }
        .quick-btn.amber  { background: var(--accent-lt);   color: #D97706; }
        .quick-btn.green  { background: var(--success-lt);  color: #047857; }
        .quick-btn.red    { background: var(--danger-lt);   color: #DC2626; }
        .quick-btn.purple { background: var(--purple-lt);   color: #7C3AED; }
        .quick-btn.slate  { background: rgba(100,116,139,.1); color: var(--text-muted); }
        .quick-btn:hover  { border-color: currentColor; transform: translateY(-2px); box-shadow: var(--shadow); }

        /* â”€â”€ TABLE â”€â”€ */
        .t-table { width: 100%; border-collapse: collapse; }
        .t-table th { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--text-muted); padding: 10px 14px; border-bottom: 2px solid var(--border); text-align: left; }
        .t-table td { padding: 11px 14px; border-bottom: 1px solid var(--border); font-size: 13.5px; color: var(--text); }
        .t-table tr:last-child td { border-bottom: none; }
        .t-table tr:hover td { background: var(--primary-lt); }

        /* â”€â”€ BADGE â”€â”€ */
        .badge { display: inline-block; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .badge-blue   { background: var(--primary-lt); color: var(--primary); }
        .badge-green  { background: var(--success-lt); color: #047857; }
        .badge-red    { background: var(--danger-lt);  color: #DC2626; }
        .badge-amber  { background: var(--accent-lt);  color: #D97706; }
        .badge-purple { background: var(--purple-lt);  color: #7C3AED; }
        .badge-gray   { background: rgba(100,116,139,.12); color: var(--text-muted); }

        /* â”€â”€ BUTTONS â”€â”€ */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; border: none; cursor: pointer; text-decoration: none; transition: all .2s; font-family: 'Nunito', sans-serif; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dk); box-shadow: 0 4px 12px rgba(37,99,235,.3); color: #fff; }
        .btn-outline { background: transparent; color: var(--primary); border: 2px solid var(--primary); }
        .btn-outline:hover { background: var(--primary-lt); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .btn-block { width: 100%; justify-content: center; }

        /* â”€â”€ EMPTY STATE â”€â”€ */
        .empty-state { text-align: center; padding: 28px 20px; color: var(--text-muted); }
        .empty-state i { font-size: 32px; margin-bottom: 8px; display: block; opacity: .35; }
        .empty-state p { font-size: 13px; font-weight: 600; }

        /* â”€â”€ WELCOME BANNER â”€â”€ */
        .welcome-banner {
            background: linear-gradient(135deg, var(--primary) 0%, #3B82F6 60%, #60A5FA 100%);
            border-radius: var(--radius); padding: 22px 26px; color: #fff;
            margin-bottom: 22px; display: flex; align-items: center; gap: 18px;
            position: relative; overflow: hidden;
        }
        .welcome-banner::before { content:''; position:absolute; right:-20px; top:-20px; width:150px; height:150px; border-radius:50%; background:rgba(255,255,255,.08); }
        .welcome-banner::after  { content:''; position:absolute; right:60px; bottom:-40px; width:90px; height:90px; border-radius:50%; background:rgba(255,255,255,.06); }
        .welcome-icon { font-size: 36px; z-index: 1; }
        .welcome-text { z-index: 1; }
        .welcome-text h2 { font-family: 'Lora', serif; font-size: 19px; font-weight: 600; margin-bottom: 3px; }
        .welcome-text p  { font-size: 13px; opacity: .85; }

        .alert-warning { background: var(--accent-lt); border: 1px solid rgba(245,158,11,.3); border-radius: var(--radius); padding: 16px 20px; color: #92400E; display: flex; gap: 12px; align-items: flex-start; margin-bottom: 20px; }
        .alert-warning i { font-size: 18px; color: var(--accent); margin-top: 2px; }

        /* â”€â”€ OVERLAY â”€â”€ */
        .overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:99; }

        /* â”€â”€ RESPONSIVE â”€â”€ */
        @media(max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-md); }
            .overlay.open { display: block; }
            .main-wrap { margin-left: 0; }
            .menu-toggle-btn { display: flex; }
            .page-body { padding: 14px; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .quick-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
    @yield('css')
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- â”€â”€ SIDEBAR â”€â”€ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><img src="{{ asset('img/logoad.png') }}" alt="Adonai" style="width:38px;height:38px;border-radius:10px;object-fit:cover;"></div>
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
            <span class="nav-icon"><i class="fas fa-home"></i></span> Inicio
        </a>
        <a href="{{ route('tutor.mis-estudiantes') }}" class="nav-item {{ request()->routeIs('tutor.mis-estudiantes') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-child"></i></span> Mis Estudiantes
        </a>

        <div class="nav-label" style="margin-top:8px">Seguimiento</div>
        <a href="{{ route('tutor.notas') }}" class="nav-item {{ request()->routeIs('tutor.notas') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-star"></i></span> Ver Notas
        </a>
        <a href="{{ route('tutor.asistencias') }}" class="nav-item {{ request()->routeIs('tutor.asistencias') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-clipboard-check"></i></span> Asistencias
        </a>
        <a href="{{ route('tutor.comportamientos') }}" class="nav-item {{ request()->routeIs('tutor.comportamientos') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-user-check"></i></span> Comportamiento
        </a>
        <a href="{{ route('tutor.reportes.index') }}" class="nav-item {{ request()->routeIs('tutor.reportes.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-file-alt"></i></span> Reportes
        </a>

        <div class="nav-label" style="margin-top:8px">Información</div>
        <a href="{{ route('tutor.horarios') }}" class="nav-item {{ request()->routeIs('tutor.horarios') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span> Horarios
        </a>
        <a href="{{ route('tutor.cursos-matriculados') }}" class="nav-item {{ request()->routeIs('tutor.cursos-matriculados') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-book-open"></i></span> Cursos Matriculados
        </a>
        <a href="{{ route('tutor.mensajeria') }}" class="nav-item {{ request()->routeIs('tutor.mensajeria*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="fas fa-comments"></i></span> Mensajería
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

<!-- â”€â”€ MAIN â”€â”€ -->
<div class="main-wrap">

    <!-- TOPBAR igual al admin -->
    <header class="topbar">
        <button class="menu-toggle-btn" onclick="openSidebar()">
            <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-title">
            @yield('page_title', '<span>Portal</span> Familiar')
        </div>

        <div class="topbar-actions">
            <!-- Toggle tema oscuro/claro -->
            <a href="{{ route('admin.theme.toggle') }}" class="topbar-btn" title="Cambiar tema">
                @if(session('theme') === 'dark')
                    <i class="fas fa-sun"></i>
                @else
                    <i class="fas fa-moon"></i>
                @endif
            </a>

            <!-- Avatar + nombre + dropdown -->
            <div style="position:relative" id="userDropdownWrap">
                <button onclick="toggleDropdown()" style="display:flex;align-items:center;gap:8px;padding:5px 10px;border-radius:9px;border:1px solid var(--border);background:var(--surface);cursor:pointer;font-family:'Nunito',sans-serif;font-weight:700;font-size:13px;color:var(--text);">
                    <span style="width:30px;height:30px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;flex-shrink:0;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </span>
                    {{ Auth::user()->name ?? 'Tutor' }}
                    <i class="fas fa-chevron-down" style="font-size:10px;color:var(--text-muted)"></i>
                </button>
                <div id="userDropdown" style="display:none;position:absolute;right:0;top:calc(100% + 8px);background:var(--surface);border:1px solid var(--border);border-radius:10px;box-shadow:var(--shadow-md);min-width:200px;z-index:200;overflow:hidden;">
                    <div style="padding:12px 16px;border-bottom:1px solid var(--border);">
                        <div style="font-weight:700;font-size:13px;">{{ Auth::user()->nombre_completo }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ Auth::user()->email }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="width:100%;padding:11px 16px;border:none;background:none;text-align:left;cursor:pointer;font-family:'Nunito',sans-serif;font-size:13px;font-weight:600;color:var(--danger);display:flex;align-items:center;gap:8px;">
                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
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
    function toggleDropdown() {
        const d = document.getElementById('userDropdown');
        d.style.display = d.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', function(e) {
        if (!document.getElementById('userDropdownWrap').contains(e.target)) {
            document.getElementById('userDropdown').style.display = 'none';
        }
    });
</script>
@yield('js')
</body>
</html>




