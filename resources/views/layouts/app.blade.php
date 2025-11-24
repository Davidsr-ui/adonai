<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Colegio Adonai - Educación Cristiana de Excelencia')</title>

    <!-- CSS y JS con Vite -->
    @vite(['resources/css/style.css', 'resources/js/script.js', 'resources/css/blog.css', 'resources/css/talleres.css', 'resources/js/talleres.js', 'resources/css/docentes.css', 'resources/js/docentes.js', 'resources/css/cursos.css', 'resources/js/cursos.js'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <div class="logo">
                <a href="/" class="logo-link">
                    <img src="/img/logoad.png" alt="Colegio Adonai" class="logo-img">
                </a>
            </div>

            <ul class="nav-menu" id="nav-menu">
                <li class="nav-item"><a href="/" class="nav-link">Inicio</a></li>

                {{-- Aquí ya sin dropdown --}}
                <li class="nav-item">
                    <a href="/#nosotros" class="nav-link">Sobre Nosotros</a>
                </li>
                
                <li class="nav-item"><a href="{{ route('cursos') }}" class="nav-link">Cursos</a></li>
                <li class="nav-item"><a href="{{ route('talleres') }}" class="nav-link">Talleres</a></li>
                <li class="nav-item"><a href="{{ route('docentes') }}" class="nav-link">Profesores</a></li>
                <li class="nav-item"><a href="{{ route('blog') }}" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="{{ route('tour') }}" class="nav-link">Visita Guiada</a></li>
            </ul>

            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn-intranet">
                    <span>Intranet</span>
                    <i class="bi bi-person-circle"></i>
                </a>

                <button class="menu-toggle" id="menu-toggle">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    {{-- Contenido específico de cada página --}}
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer" id="footer">
        <div class="container">
            <div class="footer-content">
                
                <div class="footer-column">
                    <div class="footer-logo">
                        <div class="logo-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <span>Colegio Adonai</span>
                    </div>
                    <p>Formando vidas con propósito desde 2009</p>

                    <h4>Ubícanos</h4>
                    <div class="footer-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3893.1234567890123!2d-79.125678901234!3d-7.860123456789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9108abcdef12345%3A0xabcdef1234567890!2sChicama%2C%20Per%C3%BA!5e0!3m2!1ses!2spe!4v1695700000000!5m2!1ses!2spe"
                            loading="lazy"
                            allowfullscreen=""
                        ></iframe>
                    </div>
                </div>

                <div class="footer-column">
                    <h4>Contacto</h4>
                    <p><i class="bi bi-geo-alt-fill"></i> Av. Principal 123, Trujillo</p>
                    <p><i class="bi bi-telephone-fill"></i> (01) 234-5678</p>
                    <p><i class="bi bi-envelope-fill"></i> info@colegioadonai.edu.pe</p>
                </div>

                <div class="footer-column">
                    <h4>Enlaces</h4>
                    <a href="/#nosotros"><i class="bi bi-chevron-right"></i> Sobre Nosotros</a>
                    <a href="/#cursos"><i class="bi bi-chevron-right"></i> Cursos</a>
                    <a href="/blog"><i class="bi bi-chevron-right"></i> Blog</a>
                    <a href="/#visita"><i class="bi bi-chevron-right"></i> Visita Guiada</a>
                </div>

                <div class="footer-column">
                    <h4>Síguenos</h4>
                    <div class="social-links">
                        <a href="https://www.facebook.com/colegiocristiano.mgsa?locale=es_LA" target="_blank" class="social-icon" aria-label="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="YouTube">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="#" class="social-icon" aria-label="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center footer-bottom">
                <p>&copy; 2025 Colegio Adonai. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Botón WhatsApp -->
    <a href="https://wa.me/51999999999" class="whatsapp-float" target="_blank" aria-label="WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
</body>
</html>
