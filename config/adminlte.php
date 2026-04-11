<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */

    'title' => 'Sistema Adonai',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    */

    'logo' => '<b>Sistema</b> Adonai',
    'logo_img' => 'vendor/adminlte/dist/img/logoad.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logoad.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/logoad.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items - MENÚ DINÁMICO POR ROLES
    |--------------------------------------------------------------------------
    */

    'menu' => [
        // ==========================================
        // MENÚ PARA ADMINISTRADOR
        // ==========================================
        [
            'text' => 'Dashboard',
            'url' => 'admin/dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
            'role' => 'admin',
        ],
        
        [
            'text' => 'Gestiones',
            'url' => 'admin/gestiones',
            'icon' => 'fas fa-fw fa-tasks',
            'role' => 'admin',
        ],

        [
            'text' => 'Periodos',
            'url' => 'admin/periodos',
            'icon' => 'fas fa-fw fa-calendar-alt',
            'role' => 'admin',
        ],

        [
            'text' => 'Niveles',
            'url' => 'admin/niveles',
            'icon' => 'fas fa-fw fa-layer-group',
            'role' => 'admin',
        ],

        [
            'text' => 'Turnos',
            'url' => 'admin/turnos',
            'icon' => 'fas fa-fw fa-clock',
            'role' => 'admin',
        ],

        [
            'text' => 'Cursos',
            'url' => 'admin/cursos',
            'icon' => 'fas fa-fw fa-book',
            'role' => 'admin',
        ],

        [
            'text' => 'Grados',
            'url' => 'admin/grados',
            'icon' => 'fas fa-fw fa-layer-group',
            'role' => 'admin',
        ],

        [
            'text' => 'Estudiantes',
            'url' => 'admin/estudiantes',
            'icon' => 'fas fa-fw fa-user-graduate',
            'role' => 'admin',
        ],

        [
            'text' => 'Matrículas',
            'url' => 'admin/matriculas',
            'icon' => 'fas fa-fw fa-file-signature',
            'role' => 'admin',
        ],

        [
            'text' => 'Tutores',
            'url' => 'admin/tutores',
            'icon' => 'fas fa-fw fa-user-tie',
            'role' => 'admin',
        ],

        [
            'text' => 'Tutor-Estudiante',
            'url' => 'admin/tutor-estudiante',
            'icon' => 'fas fa-fw fa-user-friends',
            'role' => 'admin',
        ],

        [
            'text' => 'Docentes',
            'url' => 'admin/docentes',
            'icon' => 'fas fa-fw fa-chalkboard-teacher',
            'role' => 'admin',
        ],

        [
            'text' => 'Asignación Docentes',
            'url' => 'admin/asignaciones',
            'icon' => 'fas fa-fw fa-chalkboard-teacher',
            'role' => 'admin',
        ],

        [
            'text' => 'Horarios',
            'url' => 'admin/horarios',
            'icon' => 'fas fa-fw fa-calendar-check',
            'role' => 'admin',
        ],

        [
            'text' => 'Blog',
            'url'  => 'admin/blog',
            'icon' => 'fas fa-fw fa-newspaper',
            'role' => 'admin',
        ],
        [
            'text' => 'Talleres',
            'url' => 'admin/talleres',
            'icon' => 'fas fa-fw fa-paint-brush',
            'role' => 'admin',
        ],


/*
        [
            'text' => 'Comportamientos',
            'url' => 'admin/comportamientos',
            'icon' => 'fas fa-fw fa-user-check',
            'role' => 'admin',
        ],

        [
            'text' => 'admines',
            'url' => 'admin/administradores',
            'icon' => 'fas fa-fw fa-user-tie',
            'role' => 'admin',
        ],

        [
            'text' => 'Permisos',
            'url' => 'admin/permissions',
            'icon' => 'fas fa-fw fa-key',
            'role' => 'admin',
        ],

        [
            'text' => 'Roles',
            'url' => 'admin/roles',
            'icon' => 'fas fa-fw fa-user-tag',
            'role' => 'admin',
        ],
*/
        [
            'text' => 'Usuarios',
            'url' => 'admin/usuarios',
            'icon' => 'fas fa-fw fa-users',
            'role' => 'admin',
        ],

        // ==========================================
        // MENÚ PARA DOCENTE - ACTUALIZADO
        // ==========================================
        [
            'text' => 'Dashboard Docente',
            'icon' => 'fas fa-home',
            'url'  => 'docente/dashboard',
            'role' => 'docente',
        ],
        [
            'text' => 'Mis Cursos',
            'icon' => 'fas fa-book',
            'url'  => 'docente/mis-cursos',
            'role' => 'docente',
        ],
        [
            'text' => 'Mis Estudiantes',
            'icon' => 'fas fa-users',
            'url'  => 'docente/estudiantes',
            'role' => 'docente',
        ],
        [
            'text' => 'Registrar Asistencias',
            'icon' => 'fas fa-clipboard-check',
            'url'  => 'docente/asistencias',
            'role' => 'docente',
        ],
        [
            'text' => 'Registrar Notas',
            'icon' => 'fas fa-star',
            'url'  => 'docente/notas',
            'role' => 'docente',
        ],
        [
            'text' => 'Comportamientos',
            'icon' => 'fas fa-user-check',
            'url'  => 'docente/comportamientos',
            'role' => 'docente',
        ],
        [
            'text' => 'Reportes Académicos',
            'icon' => 'fas fa-file-alt',
            'url'  => 'docente/reportes',
            'role' => 'docente',
        ],
        // 🆕 NUEVAS OPCIONES DOCENTE
        [
            'text' => 'Mis Alumnos',
            'icon' => 'fas fa-user-graduate',
            'url'  => 'docente/mis-alumnos',
            'role' => 'docente',
        ],
        [
            'text' => 'Mensajería',
            'icon' => 'fas fa-envelope',
            'url'  => 'docente/mensajeria',
            'role' => 'docente',
        ],

        // ==========================================
        // MENÚ PARA TUTOR - ACTUALIZADO
        // ==========================================
        [
            'text' => 'Dashboard Tutor',
            'icon' => 'fas fa-home',
            'url'  => 'tutor/dashboard',
            'role' => 'tutor',
        ],
        [
            'text' => 'Mis Estudiantes',
            'icon' => 'fas fa-user-graduate',
            'url'  => 'tutor/mis-estudiantes',
            'role' => 'tutor',
        ],
        [
            'text' => 'Ver Notas',
            'icon' => 'fas fa-star',
            'url'  => 'tutor/notas',
            'role' => 'tutor',
        ],
        [
            'text' => 'Ver Asistencias',
            'icon' => 'fas fa-clipboard-check',
            'url'  => 'tutor/asistencias',
            'role' => 'tutor',
        ],
        [
            'text' => 'Ver Comportamientos',
            'icon' => 'fas fa-user-check',
            'url'  => 'tutor/comportamientos',
            'role' => 'tutor',
        ],
        [
            'text' => 'Reportes Académicos',
            'route' => 'tutor.reportes.index',  // ✅ Así debe estar
            'icon' => 'fas fa-file-alt',
            'role' => 'tutor', 
        ],
        // 🆕 NUEVAS OPCIONES TUTOR
        [
            'text' => 'Horario de Clases',
            'icon' => 'fas fa-calendar-week',
            'url'  => 'tutor/horarios',
            'role' => 'tutor',
        ],
        [
            'text' => 'Cursos Matriculados',
            'icon' => 'fas fa-book-open',
            'url'  => 'tutor/cursos-matriculados',
            'role' => 'tutor',
        ],
        [
            'text' => 'Mensajería',
            'icon' => 'fas fa-envelope',
            'url'  => 'tutor/mensajeria',
            'role' => 'tutor',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters - CON FILTRO PERSONALIZADO
    |--------------------------------------------------------------------------
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,

        // ✅ FILTRO PERSONALIZADO PARA ROLES
        App\Http\MenuFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    */

    'livewire' => false,
];
