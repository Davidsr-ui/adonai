<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Adonai - Educación Cristiana de Excelencia</title>

    <!-- CSS y JS con Vite -->
    @vite(['resources/css/style.css', 'resources/js/script.js'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Font Awesome (para el ícono de flecha del dropdown) -->
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
                <li class="nav-item"><a href="/" class="nav-link active">Inicio</a></li>
                
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

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Formando Vidas con Propósito</h1>
                <p class="hero-subtitle">Educación cristiana de excelencia que transforma corazones y mentes</p>
                <div class="hero-buttons">
                    <a href="#nosotros" class="btn btn-primary">Conoce más</a>
                    <a href="#niveles" class="btn btn-secondary">Admisiones 2025</a>
                </div>
            </div>

            <!-- 📊 Contadores -->
            <div class="hero-stats">
                <div class="stat-card">
                    <div class="stat-number home-stat-number" data-target="15" data-suffix="+">0</div>
                    <div class="stat-label">Años de experiencia</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number home-stat-number" data-target="500" data-suffix="+">0</div>
                    <div class="stat-label">Estudiantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number home-stat-number" data-target="98" data-suffix="%">0</div>
                    <div class="stat-label">Satisfacción</div>
                </div>
            </div>
        </div>

        <div class="scroll-indicator">
            <span>Explora</span>
            <div class="mouse-icon"></div>
        </div>
    </section>

    <!-- Sección Misión, Visión y Valores -->
    <section class="about-section" id="nosotros">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Quiénes Somos</span>
                <h2 class="section-title">Nuestra Identidad</h2>
            </div>

            <div class="about-grid">
                <div class="about-card" id="mision">
                    <div class="card-icon">
                        <img src="https://cdn-icons-png.flaticon.com/512/7198/7198217.png" alt="mision-img" width="56" height="56">
                    </div>
                    <h3>Misión</h3>
                    <p>Formar estudiantes íntegros con valores cristianos, excelencia académica y compromiso social, preparándolos para ser líderes transformadores en la sociedad.</p>
                    <div class="card-glow"></div>
                </div>

                <div class="about-card" id="vision">
                    <div class="card-icon">
                        <img src="https://cdn-icons-png.flaticon.com/512/7104/7104130.png" alt="vision-img" width="65" height="65">
                    </div>
                    <h3>Visión</h3>
                    <p>Ser reconocidos como la institución educativa cristiana líder en formación integral, innovación pedagógica y desarrollo del carácter cristiano.</p>
                    <div class="card-glow"></div>
                </div>

                <div class="about-card" id="valores">
                    <div class="card-icon">
                        <img src="https://cdn-icons-png.flaticon.com/512/5681/5681514.png" alt="valores-img" width="56" height="56">
                    </div>
                    <h3>Valores</h3>
                    <p>Fe, Amor, Excelencia, Integridad, Respeto, Servicio y Responsabilidad son los pilares que guían nuestra comunidad educativa.</p>
                    <div class="card-glow"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Niveles Educativos -->
    <section class="levels-section" id="niveles">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Educación Integral</span>
                <h2 class="section-title">Nuestros Niveles Educativos</h2>
            </div>

            <div class="levels-grid">
                <div class="level-card course-card">
                    <div class="level-image course-image"></div>
                    <div class="level-content">
                        <h3>Inicial</h3>
                        <p>Desarrollo integral en un ambiente seguro y estimulante con metodología lúdica.</p>
                        <ul class="level-features">
                            <li>Estimulación temprana</li>
                            <li>Desarrollo socioemocional</li>
                            <li>Inglés desde inicial</li>
                        </ul>
                        <button class="btn-learn-more">Más información</button>
                    </div>
                </div>

                <div class="level-card course-card">
                    <div class="level-image course-image"></div>
                    <div class="level-content">
                        <h3>Primaria</h3>
                        <p>Formación académica sólida con énfasis en valores y desarrollo de habilidades.</p>
                        <ul class="level-features">
                            <li>Programa bilingüe</li>
                            <li>Tecnología educativa</li>
                            <li>Deportes y artes</li>
                        </ul>
                        <button class="btn-learn-more">Más información</button>
                    </div>
                </div>

                <div class="level-card course-card">
                    <div class="level-image course-image"></div>
                    <div class="level-content">
                        <h3>Secundaria</h3>
                        <p>Preparación académica de excelencia con enfoque en liderazgo y proyecto de vida.</p>
                        <ul class="level-features">
                            <li>Preparación universitaria</li>
                            <li>Liderazgo cristiano</li>
                            <li>Orientación vocacional</li>
                        </ul>
                        <button class="btn-learn-more">Más información</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Talleres -->
    <section class="workshops-section" id="talleres">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Desarrollo Integral</span>
                <h2 class="section-title">Talleres Extracurriculares</h2>
            </div>

            <div class="workshops-carousel">
                <div class="workshop-item">
                    <div class="workshop-icon"><i class="bi bi-music-note-beamed"></i></div>
                    <h4>Música</h4>
                    <p>Piano, guitarra, canto</p>
                </div>
                <div class="workshop-item">
                    <div class="workshop-icon"><i class="bi bi-dribbble"></i></div>
                    <h4>Deportes</h4>
                    <p>Fútbol, vóley, básquet</p>
                </div>
                <div class="workshop-item">
                    <div class="workshop-icon"><i class="bi bi-palette"></i></div>
                    <h4>Arte</h4>
                    <p>Pintura, dibujo, manualidades</p>
                </div>
                <div class="workshop-item">
                    <div class="workshop-icon"><i class="bi bi-cpu"></i></div>
                    <h4>Robótica</h4>
                    <p>Programación y tecnología</p>
                </div>
                <div class="workshop-item">
                    <div class="workshop-icon"><i class="bi bi-mask"></i></div>
                    <h4>Teatro</h4>
                    <p>Expresión y dramatización</p>
                </div>
                <div class="workshop-item">
                    <div class="workshop-icon"><i class="bi bi-book"></i></div>
                    <h4>Oratoria</h4>
                    <p>Comunicación efectiva</p>
                </div>
            </div>
        </div>
    </section>

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

    <!-- Script específico de esta página (hero + contadores) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* ------------------------------------------
               🖼️ CARRUSEL HERO
            ------------------------------------------- */
            const heroBg = document.querySelector(".hero-background");
            const images = [
                "/img/carrusel2.png",
                "/img/carrusel3.png",
                "/img/carrusel4.png"
            ];
            let current = 0;

            // Pre-carga
            images.forEach(src => { new Image().src = src; });

            heroBg.style.backgroundImage = `url(${images[current]})`;

            setInterval(() => {
                current = (current + 1) % images.length;
                heroBg.style.opacity = 0;

                setTimeout(() => {
                    heroBg.style.backgroundImage = `url(${images[current]})`;
                    heroBg.style.opacity = 1;
                }, 400);

            }, 6000);

            /* ------------------------------------------
               🔢 CONTADORES (con anti-reinicio)
            ------------------------------------------- */
            function animateCounter(element, target, suffix, duration = 1500) {

                // Ya se animó → no repetir
                if (element.dataset.done === "true") return;

                let startTime = null;

                function step(timestamp) {
                    if (!startTime) startTime = timestamp;

                    const progress = timestamp - startTime;
                    const percent = Math.min(progress / duration, 1);
                    const value = Math.floor(percent * target);

                    element.textContent = value + suffix;

                    if (progress < duration) {
                        requestAnimationFrame(step);
                    } else {
                        element.textContent = target + suffix;
                        element.dataset.done = "true"; // marcar como terminado
                    }
                }

                requestAnimationFrame(step);
            }

            /* ------------------------------------------
               OBSERVER PARA ACTIVAR CONTADORES SOLO 1 VEZ
            ------------------------------------------- */
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {

                        document.querySelectorAll('.home-stat-number').forEach(stat => {
                            const target = parseInt(stat.dataset.target);
                            const suffix = stat.dataset.suffix || "";
                            animateCounter(stat, target, suffix);
                        });

                        observer.disconnect();
                    }
                });
            }, { threshold: 0.6 });

            const heroSection = document.getElementById("inicio");
            if (heroSection) {
                observer.observe(heroSection);
            }

            // Lucide
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>
