<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','Panel Docente') - Adonai</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
{{-- Bootstrap 4 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
:root {
    --ff:'Plus Jakarta Sans',sans-serif;
    --bg:#f0f7ff; --surface:#fff; --surface2:#f0f7ff;
    --border:#e1eaf8; --border2:#c8d8f0;
    --text:#0d1b2e; --text2:#3d5068; --muted:#8899b0;
    --shadow-sm:0 1px 3px rgba(0,0,0,.06);
    --shadow:0 4px 16px rgba(0,0,0,.08);
    --shadow-lg:0 12px 40px rgba(0,0,0,.13);
    --brand:#0ea5e9; --brand-d:#0284c7; --brand-dd:#0369a1;
    --brand-bg:rgba(14,165,233,.09); --brand-border:rgba(14,165,233,.22);
    --green:#10b981; --green-bg:rgba(16,185,129,.09);
    --amber:#f59e0b; --amber-bg:rgba(245,158,11,.09);
    --rose:#f43f5e;  --rose-bg:rgba(244,63,94,.09);
    --violet:#8b5cf6; --violet-bg:rgba(139,92,246,.09);
    --slate:#64748b;  --slate-bg:rgba(100,116,139,.09);
    --sb-w:250px; --sb-bg:#071628; --sb-text:#7a9bb5;
    --top-h:58px; --radius:12px; --radius-sm:8px;
}
[data-theme="dark"] {
    --bg:#071223; --surface:#0d1f35; --surface2:#112240;
    --border:rgba(255,255,255,.07); --border2:rgba(255,255,255,.13);
    --text:#e2f0ff; --text2:#7a9bb5; --muted:#4a6580;
    --shadow-sm:0 1px 3px rgba(0,0,0,.3);
    --shadow:0 4px 16px rgba(0,0,0,.4);
    --shadow-lg:0 12px 40px rgba(0,0,0,.5);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:var(--ff);background:var(--bg);color:var(--text);min-height:100vh;display:flex;transition:background .3s,color .3s}
a{text-decoration:none;color:inherit}
button{font-family:var(--ff);cursor:pointer;border:none;background:none}

.d-sidebar{width:var(--sb-w);min-height:100vh;background:var(--sb-bg);display:flex;flex-direction:column;position:fixed;left:0;top:0;bottom:0;z-index:100;transition:transform .3s;overflow:hidden}
.d-sidebar::before{content:'';position:absolute;top:-80px;left:-40px;width:220px;height:220px;background:radial-gradient(circle,rgba(14,165,233,.18) 0%,transparent 70%);pointer-events:none}

.d-sb-logo{display:flex;align-items:center;gap:11px;padding:22px 20px 18px;border-bottom:1px solid rgba(255,255,255,.06)}
.d-sb-logo__icon{width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#0ea5e9,#0369a1);display:flex;align-items:center;justify-content:center;font-size:1rem;color:#fff;box-shadow:0 4px 12px rgba(14,165,233,.35);flex-shrink:0}
.d-sb-logo__name{font-size:.95rem;font-weight:800;color:#fff;letter-spacing:-.01em}
.d-sb-logo__sub{font-size:.65rem;color:var(--sb-text);margin-top:1px}

.d-sb-user{display:flex;align-items:center;gap:10px;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06)}
.d-sb-user__avatar{width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.82rem;flex-shrink:0}
.d-sb-user__name{font-size:.78rem;font-weight:700;color:#e2f0ff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.d-sb-user__role{font-size:.65rem;color:var(--sb-text);margin-top:1px}

.d-sb-nav{flex:1;overflow-y:auto;padding:12px 0}
.d-sb-nav::-webkit-scrollbar{width:3px}
.d-sb-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:10px}
.d-sb-section{padding:10px 20px 4px;font-size:.6rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:rgba(122,155,181,.5)}
.d-sb-link{display:flex;align-items:center;gap:10px;padding:9px 20px;font-size:.8rem;font-weight:600;color:var(--sb-text);transition:all .2s;position:relative;margin:1px 0}
.d-sb-link i{width:16px;text-align:center;font-size:.82rem;flex-shrink:0}
.d-sb-link:hover{background:rgba(255,255,255,.06);color:#e2f0ff}
.d-sb-link.active{background:rgba(14,165,233,.15);color:#fff}
.d-sb-link.active::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:#0ea5e9;border-radius:0 3px 3px 0}
.d-sb-link.active i{color:#0ea5e9}

.d-sb-footer{padding:12px 20px 20px;border-top:1px solid rgba(255,255,255,.06)}
.d-sb-logout{display:flex;align-items:center;gap:10px;width:100%;padding:9px 12px;border-radius:8px;font-size:.78rem;font-weight:600;color:#f87171;background:rgba(248,113,113,.08);transition:all .2s;border:1px solid rgba(248,113,113,.15)}
.d-sb-logout:hover{background:rgba(248,113,113,.15)}

.d-main{margin-left:var(--sb-w);flex:1;display:flex;flex-direction:column;min-height:100vh}

.d-topbar{height:var(--top-h);background:var(--surface);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:90;box-shadow:var(--shadow-sm);transition:background .3s,border-color .3s}
.d-topbar__title{font-size:1.05rem;font-weight:800;color:var(--text);letter-spacing:-.02em}
.d-topbar__title span{color:var(--brand)}
.d-topbar__right{display:flex;align-items:center;gap:10px}

.d-theme-btn{width:36px;height:36px;border-radius:9px;background:var(--surface2);border:1px solid var(--border);color:var(--text2);display:flex;align-items:center;justify-content:center;font-size:.85rem;transition:all .2s}
.d-theme-btn:hover{border-color:var(--brand);color:var(--brand)}

.d-top-user{display:flex;align-items:center;gap:8px;padding:5px 10px 5px 5px;border-radius:10px;border:1px solid var(--border);background:var(--surface2);cursor:pointer;transition:all .2s;position:relative}
.d-top-user:hover{border-color:var(--brand)}
.d-top-user__avatar{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.72rem}
.d-top-user__name{font-size:.78rem;font-weight:700;color:var(--text)}
.d-top-user__chevron{font-size:.65rem;color:var(--muted)}
.d-top-user__menu{display:none;position:absolute;top:calc(100% + 8px);right:0;background:var(--surface);border:1px solid var(--border);border-radius:10px;box-shadow:var(--shadow-lg);min-width:180px;overflow:hidden;z-index:200}
.d-top-user:hover .d-top-user__menu,.d-top-user__menu:hover{display:block}
.d-top-user__menu a,.d-top-user__menu button{display:flex;align-items:center;gap:9px;width:100%;padding:10px 14px;font-size:.78rem;font-weight:600;color:var(--text2);text-align:left;transition:background .15s}
.d-top-user__menu a:hover,.d-top-user__menu button:hover{background:var(--surface2);color:var(--text)}
.d-top-user__menu i{width:14px;text-align:center;color:var(--brand)}
.d-top-user__menu .sep{height:1px;background:var(--border);margin:4px 0}
.d-top-user__menu .danger{color:#f43f5e}
.d-top-user__menu .danger i{color:#f43f5e}

.d-content{flex:1}

.d-menu-toggle{display:none;width:36px;height:36px;border-radius:9px;background:var(--surface2);border:1px solid var(--border);color:var(--text);align-items:center;justify-content:center;font-size:.9rem}
.d-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:99}

@media(max-width:900px){
    .d-sidebar{transform:translateX(-100%)}
    .d-sidebar.open{transform:translateX(0)}
    .d-main{margin-left:0}
    .d-menu-toggle{display:flex}
    .d-overlay.open{display:block}
}
</style>
@yield('css')
</head>
<body>

<aside class="d-sidebar" id="sidebar">
    <div class="d-sb-logo">
        <div class="d-sb-logo__icon"><i class="fas fa-graduation-cap"></i></div>
        <div>
            <div class="d-sb-logo__name">Adonai</div>
            <div class="d-sb-logo__sub">Panel Docente</div>
        </div>
    </div>

    @auth
    <div class="d-sb-user">
        <div class="d-sb-user__avatar">{{ strtoupper(substr(Auth::user()->nombre_completo ?? Auth::user()->name, 0, 1)) }}</div>
        <div style="overflow:hidden">
            <div class="d-sb-user__name">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</div>
            <div class="d-sb-user__role">Docente</div>
        </div>
    </div>
    @endauth

    <nav class="d-sb-nav">
        <div class="d-sb-section">Principal</div>
        <a href="{{ route('docente.dashboard') }}" class="d-sb-link {{ request()->routeIs('docente.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Inicio
        </a>
        <div class="d-sb-section">Gestion</div>
        <a href="{{ route('docente.mis-cursos') }}" class="d-sb-link {{ request()->routeIs('docente.mis-cursos') ? 'active' : '' }}">
            <i class="fas fa-book"></i> Mis Cursos
        </a>
        <a href="{{ route('docente.mis-alumnos') }}" class="d-sb-link {{ request()->routeIs('docente.mis-alumnos') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i> Mis Alumnos
        </a>
        <a href="{{ route('docente.asistencias.index') }}" class="d-sb-link {{ request()->routeIs('docente.asistencias.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i> Asistencias
        </a>
        <a href="{{ route('docente.notas.index') }}" class="d-sb-link {{ request()->routeIs('docente.notas.*') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Notas
        </a>
        <div class="d-sb-section">Seguimiento</div>
        <a href="{{ route('docente.comportamientos.index') }}" class="d-sb-link {{ request()->routeIs('docente.comportamientos.*') ? 'active' : '' }}">
            <i class="fas fa-user-check"></i> Comportamientos
        </a>
        <a href="{{ route('docente.reportes.index') }}" class="d-sb-link {{ request()->routeIs('docente.reportes.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i> Reportes
        </a>
        <div class="d-sb-section">Comunicacion</div>
        <a href="{{ route('docente.mensajeria') }}" class="d-sb-link {{ request()->routeIs('docente.mensajeria') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i> Mensajeria
        </a>
    </nav>

    <div class="d-sb-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="d-sb-logout">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesion
            </button>
        </form>
    </div>
</aside>

<div class="d-overlay" id="overlay" onclick="closeSidebar()"></div>

<div class="d-main">
    <header class="d-topbar">
        <div style="display:flex;align-items:center;gap:12px">
            <button class="d-menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div class="d-topbar__title">@yield('page_title','Panel <span>Docente</span>')</div>
        </div>
        <div class="d-topbar__right">
            <button class="d-theme-btn" onclick="toggleTheme()" title="Modo claro/oscuro">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>
            @auth
            <div class="d-top-user">
                <div class="d-top-user__avatar">{{ strtoupper(substr(Auth::user()->nombre_completo ?? Auth::user()->name, 0, 1)) }}</div>
                <span class="d-top-user__name">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <i class="fas fa-chevron-down d-top-user__chevron"></i>
                <div class="d-top-user__menu">
                    <div class="sep"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="danger"><i class="fas fa-sign-out-alt"></i> Cerrar Sesion</button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </header>
    <main class="d-content">
        @yield('content')
    </main>
</div>

{{-- jQuery primero, luego Bootstrap JS, luego Popper --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

@yield('js')

<script>
const html = document.documentElement;
const icon = document.getElementById('themeIcon');
const saved = localStorage.getItem('d_theme') || 'light';
html.setAttribute('data-theme', saved);
icon.className = saved === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
function toggleTheme() {
    const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', next);
    localStorage.setItem('d_theme', next);
    icon.className = next === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
}
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
}
</script>
</body>
</html>