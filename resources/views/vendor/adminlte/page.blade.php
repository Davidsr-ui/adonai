@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')

    <style type="text/css">
        /* ========== ESTILOS EXISTENTES ========== */
        .zoomP{
            border: 1px solid #c0c0c0;
            box-shadow: #c0c0c0 0px 5px 5px 0px;
        }

        /* ========================================
           SIDEBAR ESTILO TECSUP - SISTEMA ADONAI
           Colores: Naranja/Amarillo Suave
           ======================================== */

        /* ========== SIDEBAR PRINCIPAL ========== */
        .main-sidebar {
            background: linear-gradient(180deg, #f5b27b 0%, #f3b66f 100%) !important;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1) !important;
            transition: width 0.3s ease !important;
            position: relative;
        }

        /* Logo/Brand mejorado */
        .brand-link {
            background: rgba(255, 255, 255, 0.1) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
            padding: 18px 15px !important;
            transition: all 0.3s ease;
            text-align: center;
        }

        .brand-link:hover {
            background: rgba(255, 255, 255, 0.15) !important;
        }

        .brand-image {
            margin-top: -3px;
            opacity: 1;
            width: 40px !important;
            height: 40px !important;
            object-fit: cover;
        }

        .brand-text {
            font-weight: 700 !important;
            font-size: 18px !important;
            color: #fff !important;
            letter-spacing: 0.5px;
            display: block;
            margin-top: 8px;
        }

        /* ========== BOTÓN TOGGLE (FLECHITA) ========== */
        .sidebar-toggle-custom {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 45px;
            height: 45px;
            background: rgba(0, 0, 0, 0.15) !important;
            border: none;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .sidebar-toggle-custom:hover {
            background: rgba(0, 0, 0, 0.25) !important;
            transform: translateX(-50%) scale(1.08);
        }

        .sidebar-toggle-custom i {
            color: rgba(0, 0, 0, 0.7);
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        /* Rotar flecha cuando está colapsado */
        .sidebar-collapse .sidebar-toggle-custom i {
            transform: rotate(180deg);
        }

        /* Ajustar sidebar para dejar espacio al botón */
        .main-sidebar .sidebar {
            padding-bottom: 80px !important;
        }

        /* ========== USER PANEL ========== */
        .user-panel {
            border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
            padding: 20px 15px !important;
            margin-bottom: 15px !important;
            text-align: center;
        }

        .user-panel .image {
            text-align: center;
            margin-bottom: 10px;
        }

        .user-panel .image img {
            border: 3px solid rgba(255, 255, 255, 0.3) !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            width: 55px !important;
            height: 55px !important;
        }

        .user-panel .info {
            text-align: center;
        }

        .user-panel .info a {
            color: rgba(0, 0, 0, 0.85) !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            display: block;
        }

        /* Ocultar nombre cuando está colapsado */
        .sidebar-collapse .user-panel .info {
            display: none;
        }

        /* ========== NAVEGACIÓN ========== */
        .nav-sidebar {
            padding: 5px 0 80px 0 !important;
        }

        .nav-sidebar .nav-item {
            margin-bottom: 3px;
        }

        /* Enlaces del menú */
        .nav-sidebar > .nav-item > .nav-link {
            padding: 14px 20px !important;
            margin: 2px 8px !important;
            border-radius: 10px !important;
            transition: all 0.3s ease !important;
            color: rgba(0, 0, 0, 0.75) !important;
            font-weight: 500 !important;
            font-size: 14px !important;
            position: relative;
            display: flex;
            align-items: center;
        }

        /* Efecto hover elegante */
        .nav-sidebar > .nav-item > .nav-link:hover {
            background: rgba(0, 0, 0, 0.1) !important;
            color: rgba(0, 0, 0, 0.9) !important;
            transform: translateX(3px);
        }

        /* Item activo */
        .nav-sidebar > .nav-item > .nav-link.active {
            background: rgba(0, 0, 0, 0.15) !important;
            color: rgba(0, 0, 0, 0.95) !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* Iconos del menú */
        .nav-sidebar .nav-link .nav-icon {
            font-size: 20px !important;
            margin-right: 15px !important;
            width: 24px !important;
            text-align: center !important;
            transition: all 0.3s ease;
            color: rgba(0, 0, 0, 0.7) !important;
            flex-shrink: 0;
        }

        .nav-sidebar > .nav-item > .nav-link:hover .nav-icon {
            transform: scale(1.1);
            color: rgba(0, 0, 0, 0.9) !important;
        }

        .nav-sidebar > .nav-item > .nav-link.active .nav-icon {
            color: rgba(0, 0, 0, 0.95) !important;
        }

        /* Texto del menú */
        .nav-sidebar .nav-link p {
            margin: 0 !important;
            line-height: 1.4 !important;
            letter-spacing: 0.3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ========== SIDEBAR COLAPSADO ========== */
        .sidebar-collapse .main-sidebar {
            width: 80px !important;
        }

        .sidebar-collapse .brand-text,
        .sidebar-collapse .nav-link p {
            display: none !important;
        }

        .sidebar-collapse .nav-sidebar > .nav-item > .nav-link {
            text-align: center;
            padding: 14px 10px !important;
            justify-content: center;
        }

        .sidebar-collapse .nav-sidebar .nav-link .nav-icon {
            margin-right: 0 !important;
            font-size: 24px !important;
        }

        .sidebar-collapse .brand-link {
            padding: 15px 10px !important;
        }

        /* Tooltip para items cuando está colapsado */
        .sidebar-collapse .nav-sidebar > .nav-item > .nav-link {
            position: relative;
        }

        .sidebar-collapse .nav-sidebar > .nav-item > .nav-link::after {
            content: attr(data-title);
            position: absolute;
            left: 80px;
            top: 50%;
            transform: translateY(-50%);
            background: #2c3e50;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            font-size: 13px;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .sidebar-collapse .nav-sidebar > .nav-item > .nav-link:hover::after {
            opacity: 1;
        }

        /* ========== SUBMENÚ ========== */
        .nav-treeview {
            background: rgba(0, 0, 0, 0.1) !important;
            border-radius: 8px;
            margin: 3px 8px 5px 8px !important;
            padding: 5px 0 !important;
        }

        .nav-treeview > .nav-item > .nav-link {
            padding: 10px 15px 10px 50px !important;
            color: rgba(0, 0, 0, 0.7) !important;
            font-size: 13px !important;
            border-radius: 6px !important;
            margin: 2px 5px !important;
            transition: all 0.3s ease;
        }

        .nav-treeview > .nav-item > .nav-link:hover {
            background: rgba(0, 0, 0, 0.08) !important;
            color: rgba(0, 0, 0, 0.9) !important;
            padding-left: 55px !important;
        }

        .nav-treeview > .nav-item > .nav-link.active {
            background: rgba(0, 0, 0, 0.12) !important;
            color: rgba(0, 0, 0, 0.95) !important;
            font-weight: 600 !important;
        }

        /* Ocultar submenús cuando está colapsado */
        .sidebar-collapse .nav-treeview {
            display: none !important;
        }

        /* ========== SCROLLBAR ========== */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .main-sidebar {
                box-shadow: 3px 0 15px rgba(0, 0, 0, 0.2) !important;
            }
            
            .sidebar-toggle-btn {
                display: none;
            }
        }

        /* ========== ANIMACIONES ========== */
        .nav-sidebar > .nav-item {
            animation: fadeInLeft 0.4s ease-out backwards;
        }

        .nav-sidebar > .nav-item:nth-child(1) { animation-delay: 0.05s; }
        .nav-sidebar > .nav-item:nth-child(2) { animation-delay: 0.08s; }
        .nav-sidebar > .nav-item:nth-child(3) { animation-delay: 0.11s; }
        .nav-sidebar > .nav-item:nth-child(4) { animation-delay: 0.14s; }
        .nav-sidebar > .nav-item:nth-child(5) { animation-delay: 0.17s; }
        .nav-sidebar > .nav-item:nth-child(6) { animation-delay: 0.20s; }
        .nav-sidebar > .nav-item:nth-child(7) { animation-delay: 0.23s; }
        .nav-sidebar > .nav-item:nth-child(8) { animation-delay: 0.26s; }
        .nav-sidebar > .nav-item:nth-child(9) { animation-delay: 0.29s; }
        .nav-sidebar > .nav-item:nth-child(10) { animation-delay: 0.32s; }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-15px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* ========== AJUSTES ADICIONALES ========== */
        .content-wrapper {
            transition: margin-left 0.3s ease !important;
        }

        /* Flecha de submenú */
        .nav-sidebar .right {
            transition: transform 0.3s ease !important;
            color: rgba(0, 0, 0, 0.5) !important;
        }

        /* ========================================
           AVATAR DE USUARIO EN NAVBAR
           ======================================== */
        
        /* Contenedor del usuario en navbar */
        .navbar-nav .nav-item.dropdown {
            display: flex;
            align-items: center;
        }

        .navbar-nav .dropdown-toggle {
            display: flex !important;
            align-items: center !important;
            gap: 10px;
            padding: 8px 15px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .navbar-nav .dropdown-toggle:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        /* Avatar circular con inicial */
        .user-avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        /* Colores aleatorios para avatares */
        .user-avatar-circle.color-1 { background: #3498db; }
        .user-avatar-circle.color-2 { background: #2ecc71; }
        .user-avatar-circle.color-3 { background: #e74c3c; }
        .user-avatar-circle.color-4 { background: #f39c12; }
        .user-avatar-circle.color-5 { background: #9b59b6; }
        .user-avatar-circle.color-6 { background: #1abc9c; }
        .user-avatar-circle.color-7 { background: #34495e; }

        /* Nombre del usuario */
        .user-name-text {
            font-weight: 500;
            font-size: 15px;
            color: #2c3e50;
        }

        /* Flecha del dropdown */
        .navbar-nav .dropdown-toggle::after {
            margin-left: 5px;
        }

        /* Dropdown menu mejorado */
        .navbar-nav .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
            border: none;
            margin-top: 8px;
            min-width: 200px;
        }

        .navbar-nav .dropdown-item {
            padding: 10px 20px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .navbar-nav .dropdown-item:hover {
            background: #f8f9fa;
            padding-left: 25px;
        }

        .navbar-nav .dropdown-item i {
            margin-right: 10px;
            width: 16px;
            text-align: center;
        }

        .nav-sidebar .nav-item.menu-open > .nav-link .right {
            transform: rotate(90deg);
        }
    </style>
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation (fullscreen mode) --}}
        @if($preloaderHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader') 
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                @include('adminlte::partials.sidebar.left-sidebar')
                
                {{-- Botón Toggle Sidebar dentro del sidebar --}}
                <button class="sidebar-toggle-custom" onclick="toggleSidebar()" title="Contraer/Expandir menú">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </aside>
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    
    {{-- Script para Toggle Sidebar --}}
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapse');
            
            // Guardar estado en localStorage
            if (document.body.classList.contains('sidebar-collapse')) {
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                localStorage.setItem('sidebar-collapsed', 'false');
            }
        }

        // Restaurar estado al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                document.body.classList.add('sidebar-collapse');
            }

            // Agregar data-title a los links para tooltips
            document.querySelectorAll('.nav-sidebar > .nav-item > .nav-link').forEach(link => {
                const text = link.querySelector('p');
                if (text) {
                    link.setAttribute('data-title', text.textContent.trim());
                }
            });

            // ========== CREAR AVATAR CON INICIAL ==========
            createUserAvatar();
        });

        function createUserAvatar() {
            // Buscar el link del dropdown del usuario
            const userLink = document.querySelector('.user-menu .nav-link.dropdown-toggle');
            
            if (userLink) {
                // Obtener el span con el nombre
                const nameSpan = userLink.querySelector('span');
                
                if (nameSpan) {
                    const userName = nameSpan.textContent.trim();
                    const initial = userName.charAt(0).toUpperCase();
                    
                    // Generar color basado en la inicial
                    const colors = ['color-1', 'color-2', 'color-3', 'color-4', 'color-5', 'color-6', 'color-7'];
                    const colorIndex = initial.charCodeAt(0) % colors.length;
                    const colorClass = colors[colorIndex];
                    
                    // Crear el HTML del avatar
                    const avatarHTML = `
                        <div class="user-avatar-circle ${colorClass}">
                            ${initial}
                        </div>
                        <span class="user-name-text">${userName}</span>
                    `;
                    
                    // Reemplazar el contenido del link
                    userLink.innerHTML = avatarHTML;
                }
            }
        }
    </script>

    @if((($mensaje=Session::get('mensaje')) && ($icono=Session::get('icono'))) )
        <script>
            Swal.fire({
                position: 'top-end',
                icon: '{{ $icono }}',
                title: '{{ $mensaje }}',
                showConfirmButton: false,
                timer: 4000
            });
        </script>
    @endif
@stop