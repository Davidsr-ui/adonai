@extends('layouts.app')

@section('title', 'Colegio Adonai - Educación Cristiana de Excelencia')

@section('content')

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
                <!-- INICIAL -->
                <div class="level-card course-card">
                    <div class="level-image course-image">
                        <img src="{{ asset('img/inicial.jpg') }}" alt="Nivel Inicial - Colegio Adonai">
                    </div>
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

                <!-- PRIMARIA -->
                <div class="level-card course-card">
                    <div class="level-image course-image">
                        <img src="{{ asset('img/primaria.jpg') }}" alt="Nivel Primaria - Colegio Adonai">
                    </div>
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

                <!-- SECUNDARIA -->
                <div class="level-card course-card">
                    <div class="level-image course-image">
                        <img src="{{ asset('img/secundaria.jpg') }}" alt="Nivel Secundaria - Colegio Adonai">
                    </div>
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

    <!-- Botón WhatsApp -->
    <a href="https://wa.me/51999999999" class="whatsapp-float" target="_blank" aria-label="WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- Script específico de esta página (hero + contadores) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            /* ------------------------------------------
               🖼️ CARRUSEL HERO (cambio directo, sin gris)
            ------------------------------------------- */
            const heroBg = document.querySelector(".hero-background");
            const images = [
                "/img/carrusel2.png",
                "/img/carrusel3.png",
                "/img/carrusel4.png"
            ];
            let current = 0;

            if (heroBg) {
                heroBg.style.transition = "none";
                heroBg.style.opacity = "1";
                heroBg.style.backgroundSize = "cover";
                heroBg.style.backgroundPosition = "center center";

                const preloaded = images.map(src => {
                    const img = new Image();
                    img.src = src;
                    return img;
                });

                heroBg.style.backgroundImage = `url(${images[current]})`;

                setInterval(() => {
                    const nextIndex = (current + 1) % images.length;
                    const nextImg = preloaded[nextIndex];

                    if (nextImg.complete) {
                        current = nextIndex;
                        heroBg.style.backgroundImage = `url(${images[current]})`;
                    } else {
                        nextImg.onload = () => {
                            current = nextIndex;
                            heroBg.style.backgroundImage = `url(${images[current]})`;
                        };
                    }
                }, 6000);
            }

            /* ------------------------------------------
               🔢 CONTADORES (con anti-reinicio)
            ------------------------------------------- */
            function animateCounter(element, target, suffix, duration = 1500) {

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
                        element.dataset.done = "true";
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

    {{-- Estilos específicos para que las imágenes de niveles se vean completas --}}
    <style>
        .levels-section .level-card .level-image {
            width: 100%;
            height: 190px;
            border-radius: 24px 24px 0 0;   /* respeta el estilo redondeado */
            overflow: hidden;
            background: #000000ff;             /* fondo claro detrás de la foto */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;                      /* por si .course-image tenía padding */
        }

        .levels-section .level-card .level-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;             /* 👉 muestra la imagen completa */
            display: block;
        }
    </style>

@endsection
