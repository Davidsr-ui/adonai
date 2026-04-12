<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema Adonai')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Sidebar collapsible */
        .navbar-vertical {
            transition: width 0.3s ease;
        }
        
        .navbar-vertical.navbar-collapsed {
            width: 72px !important;
        }
        
        .navbar-collapsed .navbar-brand .brand-text,
        .navbar-collapsed .nav-link-title,
        .navbar-collapsed .dropdown-toggle::after,
        .navbar-collapsed .logout-text,
        .navbar-collapsed .nav-section-title {
            display: none !important;
        }
        
        .navbar-collapsed .nav-link {
            justify-content: center;
            padding: 0.65rem 0;
        }
        
        .navbar-collapsed .sidebar-nav .dropdown-menu {
            display: none !important;
        }
        
        /* Secciones del menú */
        .nav-section-title {
            font-size: 0.65rem;
            padding: 0.75rem 0.75rem 0.25rem;
            margin-top: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--tblr-secondary);
            font-weight: 700;
            opacity: 0.7;
        }
        
        /* Dropdown flechas */
        .dropdown-toggle::after {
            transition: transform 0.3s;
            border: none !important;
            content: "\f078";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 9px;
            margin-left: auto;
            opacity: 0.6;
        }
        
        .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }
        
        /* Submenú */
        .sidebar-nav .dropdown-menu {
            padding-left: 0.5rem;
            background: transparent;
            border: none;
            box-shadow: none;
        }
        
        .sidebar-nav .dropdown-item {
            border-radius: 6px;
            padding: 0.45rem 0.75rem 0.45rem 2.5rem;
            font-size: 0.9rem;
        }
        
        /* Estilos de items */
        .sidebar-nav .nav-link {
            border-radius: 8px;
            margin: 1px 6px;
        }
        
        .sidebar-nav .nav-link.active {
            box-shadow: inset 3px 0 0 var(--tblr-primary);
        }
        
        /* Logo */
        .navbar-brand {
            padding: 1rem 0.75rem !important;
        }
        
        .navbar-brand img {
            height: 36px !important;
        }
        
        .navbar-brand .brand-text {
            font-size: 1.1rem !important;
            font-weight: 600 !important;
        }
        
        /* Botón hamburguesa en header */
        .sidebar-toggle-btn-header {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--tblr-secondary);
            margin-right: 0.5rem;
        }
        
        .sidebar-toggle-btn-header:hover {
            background: var(--tblr-bg-surface-secondary);
            color: var(--tblr-primary);
        }
        
        .sidebar-toggle-btn-header i {
            font-size: 1.25rem;
        }
        
        /* Page wrapper se adapta */
        .page-wrapper {
            transition: margin-left 0.3s ease;
        }
        
        /* ===== CORRECCIÓN PARA MODO OSCURO ===== */
        /* Header se adapta al tema */
        /* Header se adapta al tema */
.page-header-navbar {
    background: var(--tblr-bg-surface) !important;
    border-bottom: 1px solid var(--tblr-border-color) !important;
}

/* Asegurar que el texto también cambie */
[data-bs-theme="dark"] .page-header-navbar {
    background: #1a2234 !important;
    border-bottom-color: #2a3446 !important;
}

[data-bs-theme="dark"] .page-header-navbar .nav-link,
[data-bs-theme="dark"] .page-header-navbar .sidebar-toggle-btn-header {
    color: #e9ecef !important;
}

[data-bs-theme="light"] .page-header-navbar {
    background: #ffffff !important;
    border-bottom-color: #e9ecef !important;
}
        
        /* Cards en modo oscuro */
        .card {
            background: var(--tblr-bg-surface) !important;
            border-color: var(--tblr-border-color) !important;
        }
        
        /* Textos en modo oscuro */
        body[data-bs-theme="dark"] .text-muted {
            color: var(--tblr-secondary) !important;
        }
        
        body[data-bs-theme="dark"] .navbar-vertical {
            border-right-color: var(--tblr-border-color) !important;
        }
    </style>
    
    @yield('css')
</head>
<body data-bs-theme="{{ session('theme', 'light') }}">
<div class="wrapper">

  <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="{{ session('theme', 'light') }}">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <!-- Logo -->
      <h1 class="navbar-brand navbar-brand-autodark">
        <a href="{{ url('admin/dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
          <img src="{{ asset('vendor/adminlte/dist/img/logoad.png') }}" alt="Adonai" onerror="this.style.display='none'">
          <span class="brand-text">Sistema Adonai</span>
        </a>
      </h1>
      
      <div class="collapse navbar-collapse" id="sidebar-menu">
        <ul class="navbar-nav pt-lg-2 sidebar-nav">
          @php $role = Auth::user()->roles->first()->name ?? ''; @endphp

          @if($role === 'admin')
          <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('admin/dashboard') }}">
              <span class="nav-link-icon"><i class="fas fa-tachometer-alt"></i></span>
              <span class="nav-link-title">Dashboard</span>
            </a>
          </li>
          
          <!-- CONFIGURACIÓN -->
          <li class="nav-section-title">Configuración</li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->is('admin/gestiones*') || request()->is('admin/periodos*') || request()->is('admin/niveles*') || request()->is('admin/turnos*') || request()->is('admin/grados*') ? 'active' : '' }}" 
               href="#configMenu" 
               data-bs-toggle="collapse" 
               role="button"
               aria-expanded="{{ request()->is('admin/gestiones*') || request()->is('admin/periodos*') || request()->is('admin/niveles*') || request()->is('admin/turnos*') || request()->is('admin/grados*') ? 'true' : 'false' }}">
              <span class="nav-link-icon"><i class="fas fa-cog"></i></span>
              <span class="nav-link-title">Configuración</span>
            </a>
            <div class="collapse {{ request()->is('admin/gestiones*') || request()->is('admin/periodos*') || request()->is('admin/niveles*') || request()->is('admin/turnos*') || request()->is('admin/grados*') ? 'show' : '' }}" id="configMenu">
              <ul class="nav flex-column">
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/gestiones*') ? 'active' : '' }}" href="{{ url('admin/gestiones') }}"><i class="fas fa-tasks me-2 fa-fw"></i>Gestiones</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/periodos*') ? 'active' : '' }}" href="{{ url('admin/periodos') }}"><i class="fas fa-calendar-alt me-2 fa-fw"></i>Periodos</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/niveles*') ? 'active' : '' }}" href="{{ url('admin/niveles') }}"><i class="fas fa-layer-group me-2 fa-fw"></i>Niveles</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/turnos*') ? 'active' : '' }}" href="{{ url('admin/turnos') }}"><i class="fas fa-clock me-2 fa-fw"></i>Turnos</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/grados*') ? 'active' : '' }}" href="{{ url('admin/grados') }}"><i class="fas fa-graduation-cap me-2 fa-fw"></i>Grados</a></li>
              </ul>
            </div>
          </li>
          
          <!-- ACADÉMICO -->
          <li class="nav-section-title">Académico</li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->is('admin/cursos*') || request()->is('admin/horarios*') || request()->is('admin/asignaciones*') ? 'active' : '' }}" 
               href="#academicMenu" 
               data-bs-toggle="collapse" 
               role="button"
               aria-expanded="{{ request()->is('admin/cursos*') || request()->is('admin/horarios*') || request()->is('admin/asignaciones*') ? 'true' : 'false' }}">
              <span class="nav-link-icon"><i class="fas fa-graduation-cap"></i></span>
              <span class="nav-link-title">Académico</span>
            </a>
            <div class="collapse {{ request()->is('admin/cursos*') || request()->is('admin/horarios*') || request()->is('admin/asignaciones*') ? 'show' : '' }}" id="academicMenu">
              <ul class="nav flex-column">
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/cursos*') ? 'active' : '' }}" href="{{ url('admin/cursos') }}"><i class="fas fa-book me-2 fa-fw"></i>Cursos</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/horarios*') ? 'active' : '' }}" href="{{ url('admin/horarios') }}"><i class="fas fa-calendar-check me-2 fa-fw"></i>Horarios</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/asignaciones*') ? 'active' : '' }}" href="{{ url('admin/asignaciones') }}"><i class="fas fa-chalkboard-teacher me-2 fa-fw"></i>Asignación Docentes</a></li>
              </ul>
            </div>
          </li>
          
          <!-- PERSONAS -->
          <li class="nav-section-title">Personas</li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->is('admin/estudiantes*') || request()->is('admin/matriculas*') || request()->is('admin/docentes*') || request()->is('admin/tutores*') || request()->is('admin/tutor-estudiante*') ? 'active' : '' }}" 
               href="#peopleMenu" 
               data-bs-toggle="collapse" 
               role="button"
               aria-expanded="{{ request()->is('admin/estudiantes*') || request()->is('admin/matriculas*') || request()->is('admin/docentes*') || request()->is('admin/tutores*') || request()->is('admin/tutor-estudiante*') ? 'true' : 'false' }}">
              <span class="nav-link-icon"><i class="fas fa-users"></i></span>
              <span class="nav-link-title">Personas</span>
            </a>
            <div class="collapse {{ request()->is('admin/estudiantes*') || request()->is('admin/matriculas*') || request()->is('admin/docentes*') || request()->is('admin/tutores*') || request()->is('admin/tutor-estudiante*') ? 'show' : '' }}" id="peopleMenu">
              <ul class="nav flex-column">
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/estudiantes*') ? 'active' : '' }}" href="{{ url('admin/estudiantes') }}"><i class="fas fa-user-graduate me-2 fa-fw"></i>Estudiantes</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/matriculas*') ? 'active' : '' }}" href="{{ url('admin/matriculas') }}"><i class="fas fa-file-signature me-2 fa-fw"></i>Matrículas</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/docentes*') ? 'active' : '' }}" href="{{ url('admin/docentes') }}"><i class="fas fa-chalkboard-teacher me-2 fa-fw"></i>Docentes</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/tutores*') ? 'active' : '' }}" href="{{ url('admin/tutores') }}"><i class="fas fa-user-tie me-2 fa-fw"></i>Tutores</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/tutor-estudiante*') ? 'active' : '' }}" href="{{ url('admin/tutor-estudiante') }}"><i class="fas fa-user-friends me-2 fa-fw"></i>Tutor-Estudiante</a></li>
              </ul>
            </div>
          </li>
          
          <!-- CONTENIDO -->
          <li class="nav-section-title">Contenido</li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->is('admin/blog*') || request()->is('admin/talleres*') || request()->is('admin/usuarios*') ? 'active' : '' }}" 
               href="#contentMenu" 
               data-bs-toggle="collapse" 
               role="button"
               aria-expanded="{{ request()->is('admin/blog*') || request()->is('admin/talleres*') || request()->is('admin/usuarios*') ? 'true' : 'false' }}">
              <span class="nav-link-icon"><i class="fas fa-newspaper"></i></span>
              <span class="nav-link-title">Contenido</span>
            </a>
            <div class="collapse {{ request()->is('admin/blog*') || request()->is('admin/talleres*') || request()->is('admin/usuarios*') ? 'show' : '' }}" id="contentMenu">
              <ul class="nav flex-column">
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/blog*') ? 'active' : '' }}" href="{{ url('admin/blog') }}"><i class="fas fa-newspaper me-2 fa-fw"></i>Blog</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/talleres*') ? 'active' : '' }}" href="{{ url('admin/talleres') }}"><i class="fas fa-paint-brush me-2 fa-fw"></i>Talleres</a></li>
                <li class="nav-item"><a class="dropdown-item {{ request()->is('admin/usuarios*') ? 'active' : '' }}" href="{{ url('admin/usuarios') }}"><i class="fas fa-users me-2 fa-fw"></i>Usuarios</a></li>
              </ul>
            </div>
          </li>

          @elseif($role === 'docente')
          <li class="nav-item"><a class="nav-link {{ request()->is('docente/dashboard') ? 'active' : '' }}" href="{{ url('docente/dashboard') }}"><span class="nav-link-icon"><i class="fas fa-home"></i></span><span class="nav-link-title">Dashboard</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('docente/mis-cursos*') ? 'active' : '' }}" href="{{ url('docente/mis-cursos') }}"><span class="nav-link-icon"><i class="fas fa-book"></i></span><span class="nav-link-title">Mis Cursos</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('docente/estudiantes*') ? 'active' : '' }}" href="{{ url('docente/estudiantes') }}"><span class="nav-link-icon"><i class="fas fa-users"></i></span><span class="nav-link-title">Mis Estudiantes</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('docente/asistencias*') ? 'active' : '' }}" href="{{ url('docente/asistencias') }}"><span class="nav-link-icon"><i class="fas fa-clipboard-check"></i></span><span class="nav-link-title">Registrar Asistencias</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('docente/notas*') ? 'active' : '' }}" href="{{ url('docente/notas') }}"><span class="nav-link-icon"><i class="fas fa-star"></i></span><span class="nav-link-title">Registrar Notas</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('docente/comportamientos*') ? 'active' : '' }}" href="{{ url('docente/comportamientos') }}"><span class="nav-link-icon"><i class="fas fa-user-check"></i></span><span class="nav-link-title">Comportamientos</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('docente/reportes*') ? 'active' : '' }}" href="{{ url('docente/reportes') }}"><span class="nav-link-icon"><i class="fas fa-file-alt"></i></span><span class="nav-link-title">Reportes Académicos</span></a></li>

          @elseif($role === 'tutor')
          <li class="nav-item"><a class="nav-link {{ request()->is('tutor/dashboard') ? 'active' : '' }}" href="{{ url('tutor/dashboard') }}"><span class="nav-link-icon"><i class="fas fa-home"></i></span><span class="nav-link-title">Dashboard</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('tutor/mis-estudiantes*') ? 'active' : '' }}" href="{{ url('tutor/mis-estudiantes') }}"><span class="nav-link-icon"><i class="fas fa-user-graduate"></i></span><span class="nav-link-title">Mis Estudiantes</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('tutor/notas*') ? 'active' : '' }}" href="{{ url('tutor/notas') }}"><span class="nav-link-icon"><i class="fas fa-star"></i></span><span class="nav-link-title">Ver Notas</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('tutor/asistencias*') ? 'active' : '' }}" href="{{ url('tutor/asistencias') }}"><span class="nav-link-icon"><i class="fas fa-clipboard-check"></i></span><span class="nav-link-title">Ver Asistencias</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('tutor/horarios*') ? 'active' : '' }}" href="{{ url('tutor/horarios') }}"><span class="nav-link-icon"><i class="fas fa-calendar-week"></i></span><span class="nav-link-title">Horario de Clases</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('tutor/cursos-matriculados*') ? 'active' : '' }}" href="{{ url('tutor/cursos-matriculados') }}"><span class="nav-link-icon"><i class="fas fa-book-open"></i></span><span class="nav-link-title">Cursos Matriculados</span></a></li>
          <li class="nav-item"><a class="nav-link {{ request()->is('tutor/reportes*') ? 'active' : '' }}" href="{{ route('tutor.reportes.index') }}"><span class="nav-link-icon"><i class="fas fa-file-alt"></i></span><span class="nav-link-title">Reportes Académicos</span></a></li>
          @endif
        </ul>
        
        <!-- Logout -->
        <div class="mt-auto p-3 border-top">
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
          <a href="#" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span class="logout-text">Cerrar sesión</span>
          </a>
        </div>
      </div>
    </div>
  </aside>

  <div class="page-wrapper">
    <!-- HEADER CORREGIDO - Ahora usa clases de Tabler correctamente -->
    <header class="navbar navbar-expand-md d-none d-lg-flex sticky-top page-header-navbar">
      <div class="container-fluid">
        <!-- BOTÓN HAMBURGUESA -->
        <div class="d-flex align-items-center">
          <div class="sidebar-toggle-btn-header" onclick="toggleSidebar()" title="Colapsar menú">
            <i class="fas fa-bars"></i>
          </div>
          <div class="me-auto">@yield('content_header')</div>
        </div>
        
        <div class="d-flex align-items-center gap-3">
          <!-- Toggle Theme -->
          <a href="{{ route('admin.theme.toggle') }}" class="btn btn-sm btn-ghost-secondary" title="Cambiar tema">
            @if(session('theme')==='dark')
              <i class="fas fa-sun"></i>
            @else
              <i class="fas fa-moon"></i>
            @endif
          </a>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="dropdown">
              <span class="avatar avatar-sm rounded-circle bg-primary text-white">{{ strtoupper(substr(Auth::user()->name??'U',0,1)) }}</span>
              <span class="fw-semibold">{{ Auth::user()->name??'Usuario' }}</span>
              <i class="fas fa-chevron-down fa-xs"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow">
              <div class="dropdown-header"><strong>{{ Auth::user()->name??'' }}</strong><br><small class="text-muted">{{ Auth::user()->email??'' }}</small></div>
              <div class="dropdown-divider"></div>
              <a href="{{ route('logout') }}" class="dropdown-item text-danger" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="page-body">
      <div class="container-xl py-3">
        @if(session('success'))<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
        @if(session('error'))<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
        @yield('content')
      </div>
    </div>

    <footer class="footer footer-transparent d-print-none">
      <div class="container-xl">
        <p class="mb-0 text-muted text-center py-2">&copy; {{ date('Y') }} <strong>Colegio Adonai</strong> — Sistema de Gestión Académica</p>
      </div>
    </footer>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.navbar-vertical');
        
        sidebar.classList.toggle('navbar-collapsed');
        
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('navbar-collapsed'));
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarState = localStorage.getItem('sidebarCollapsed');
        if (sidebarState === 'true') {
            toggleSidebar();
        }
    });
</script>

@yield('js')
</body>
</html>