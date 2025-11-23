<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Colegio Adonai</title>

    @vite(['resources/css/style.css', 'resources/css/blog.css'])

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Spline Runtime -->
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.40/build/spline-viewer.js"></script>
    
    <style>
        /* Estilos anteriores se mantienen igual */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }

        .blog-card {
            animation: fadeInUp 0.6s ease both;
        }

        /* 🔥 SPLINES INTEGRADOS EN AMBOS LADOS */
        .blog-header {
            position: relative;
            padding: 0 200px; /* Espacio para ambos Splines */
            text-align: center;
        }

        .spline-right {
            position: absolute;
            top: -30px;
            right: 0;
            width: 220px;
            height: 220px;
            z-index: 5;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(211, 84, 0, 0.2);
            border: 4px solid rgba(255, 255, 255, 0.95);
            background: linear-gradient(135deg, #fdfbf7 0%, #fef5e7 100%);
            transition: all 0.4s ease;
        }

        .spline-left {
            position: absolute;
            top: -30px;
            left: 0;
            width: 220px;
            height: 220px;
            z-index: 5;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(211, 84, 0, 0.2);
            border: 4px solid rgba(255, 255, 255, 0.95);
            background: linear-gradient(135deg, #fef5e7 0%, #fdfbf7 100%);
            transition: all 0.4s ease;
        }

        .spline-right:hover {
            transform: scale(1.08) rotate(3deg);
            box-shadow: 0 16px 50px rgba(211, 84, 0, 0.3);
        }

        .spline-left:hover {
            transform: scale(1.08) rotate(-3deg);
            box-shadow: 0 16px 50px rgba(211, 84, 0, 0.3);
        }

        /* Ocultar marca de agua de Spline */
        .spline-right::part(logo),
        .spline-left::part(logo) {
            display: none !important;
        }

        .spline-right spline-viewer,
        .spline-left spline-viewer {
            border-radius: 22px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .blog-header {
                padding: 0 180px;
            }
            
            .spline-right,
            .spline-left {
                width: 180px;
                height: 180px;
                top: -20px;
            }
        }

        @media (max-width: 768px) {
            .blog-header {
                padding: 0 20px;
                text-align: center;
            }
            
            .spline-right,
            .spline-left {
                position: relative;
                top: 0;
                right: 0;
                left: 0;
                width: 140px;
                height: 140px;
                margin: 20px auto;
                display: inline-block;
            }
            
            .spline-left {
                margin-right: 10px;
            }
            
            .spline-right {
                margin-left: 10px;
            }
        }

        @media (max-width: 480px) {
            .spline-right,
            .spline-left {
                width: 120px;
                height: 120px;
            }
        }

        /* Asegurar que no tape contenido importante */
        .container {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
    <div class="nav-container">
        <div class="logo">
            <a href="/" class="logo-link">
                <img src="/img/logoad.png" alt="Colegio Adonai" class="logo-img">
            </a>
        </div>
        <ul class="nav-menu" id="nav-menu">
            <li class="nav-item"><a href="/" class="nav-link">Inicio</a></li>
            <li class="nav-item dropdown">
                <a href="#nosotros" class="nav-link dropdown-toggle">
                    Sobre Nosotros <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#mision" class="dropdown-link">Misión</a></li>
                    <li><a href="#vision" class="dropdown-link">Visión</a></li>
                    <li><a href="#valores" class="dropdown-link">Valores</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="{{ route('cursos') }}" class="nav-link">Cursos</a></li>
            <li class="nav-item"><a href="{{ route('talleres') }}" class="nav-link">Talleres</a></li>
            <li class="nav-item"><a href="{{ route('docentes') }}" class="nav-link">Profesores</a></li>
            <li class="nav-item"><a href="{{ route('blog') }}" class="nav-link active">Blog</a></li>
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

<!-- SECCIÓN BLOG -->
<section class="blog-section">
    <div class="container">
        <div class="blog-header">
            <!-- 🔥 SPLINE IZQUIERDO -->
            <div class="spline-left">
                <spline-viewer 
                    loading-anim 
                    url="https://prod.spline.design/YF-R-r2xjmGz-cCc/scene.splinecode"
                    style="width: 100%; height: 100%;"
                ></spline-viewer>
            </div>

            <!-- 🔥 SPLINE DERECHO -->
            <div class="spline-right">
                <spline-viewer 
                    loading-anim 
                    url="https://prod.spline.design/xmZ7i9phOY2dN8Bi/scene.splinecode"
                    style="width: 100%; height: 100%;"
                ></spline-viewer>
            </div>

            <span class="section-tag">Blog Escolar</span>
            <h1>Noticias y Eventos</h1>
            <p>Mantente informado sobre actividades, concursos, premios y eventos.</p>
        </div>

        <!-- BUSCADOR -->
        <div class="blog-search">
            <input type="text" id="searchInput" placeholder="Buscar noticias, eventos, concursos...">
            <i class="bi bi-search"></i>
        </div>

        <!-- FILTROS -->
        <div class="blog-filters">
            <span class="filter-label"><i class="bi bi-funnel-fill"></i> Filtrar por:</span>
            <button class="filter-btn active" data-filter="todos"><i class="bi bi-grid-fill"></i> Todos</button>
            <button class="filter-btn" data-filter="concursos"><i class="bi bi-megaphone-fill"></i> Concursos</button>
            <button class="filter-btn" data-filter="premios"><i class="bi bi-trophy-fill"></i> Premios</button>
            <button class="filter-btn" data-filter="académico"><i class="bi bi-book-fill"></i> Académico</button>
            <button class="filter-btn" data-filter="eventos"><i class="bi bi-calendar-event-fill"></i> Eventos</button>
            <button class="filter-btn" data-filter="comunidad"><i class="bi bi-people-fill"></i> Comunidad</button>
        </div>

        <!-- GRID BLOG -->
        <div class="blog-grid" id="blogGrid">
            @forelse ($posts as $post)
                <div class="blog-card" data-category="{{ strtolower($post->categoria) }}">
                    <div class="blog-card-image">
                        <img src="{{ asset('storage/' . $post->portada) }}" alt="{{ $post->titulo }}">
                        <span class="blog-card-badge">
                            <i class="bi bi-bookmark-fill"></i> {{ $post->categoria }}
                        </span>
                    </div>
                    <div class="blog-card-content">
                        <h3 class="blog-card-title">{{ $post->titulo }}</h3>
                        <p class="blog-card-excerpt">{{ $post->descripcion_corta }}</p>
                        <div class="blog-card-footer">
                            <span class="blog-card-date">
                                <i class="bi bi-calendar-event"></i>
                                {{ $post->fecha->format('d M Y') }}
                            </span>
                            <span class="blog-card-author">
                                <i class="bi bi-person-fill"></i>
                                {{ $post->autor ?? 'Administrador' }}
                            </span>
                            <a href="{{ route('blog.detalle', $post->id) }}" class="blog-card-read">
                                Leer más <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted" style="grid-column: 1 / -1;">
                    No hay publicaciones aún.
                </p>
            @endforelse
        </div>

        @if(isset($posts) && method_exists($posts, 'links'))
            <div class="blog-pagination">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>

<!-- FOOTER -->
<footer class="footer" id="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-column">
                <div class="footer-logo">
                    <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <span>Colegio Adonai</span>
                </div>
                <p>Formando vidas con propósito desde 2009</p>
                <h4>Ubícanos</h4>
                <div class="footer-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!..." loading="lazy"></iframe>
                </div>
            </div>
            <div class="footer-column">
                <h4>Contacto</h4>
                <p><i class="bi bi-geo-alt-fill"></i> Av. Principal 123, Lima</p>
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
                    <a href="https://www.facebook.com/colegiocristiano.mgsa?locale=es_LA" class="social-icon" target="_blank"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center footer-bottom">
            <p>&copy; 2025 Colegio Adonai. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

<a href="https://wa.me/51999999999" class="whatsapp-float" target="_blank">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".blog-card");
    const searchInput = document.getElementById("searchInput");
    const blogGrid = document.getElementById("blogGrid");

    function applyFilters() {
        const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        let hasVisibleCards = false;

        cards.forEach(card => {
            const category = card.dataset.category;
            const text = card.textContent.toLowerCase();
            
            const categoryMatch = activeFilter === 'todos' || category === activeFilter;
            const searchMatch = text.includes(searchTerm);
            
            if (categoryMatch && searchMatch) {
                card.style.display = 'block';
                hasVisibleCards = true;
            } else {
                card.style.display = 'none';
            }
        });

        if (!hasVisibleCards) {
            if (!document.querySelector('.no-results')) {
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.innerHTML = `
                    <i class="bi bi-search"></i>
                    <h3>No se encontraron resultados</h3>
                    <p>Intenta con otros términos de búsqueda o filtros</p>
                `;
                blogGrid.appendChild(noResults);
            }
        } else {
            const noResults = document.querySelector('.no-results');
            if (noResults) {
                noResults.remove();
            }
        }
    }

    filterButtons.forEach(btn => {
        btn.addEventListener("click", function() {
            filterButtons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            applyFilters();
        });
    });

    let searchTimeout;
    searchInput.addEventListener("input", function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 300);
    });

    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>

</body>
</html>