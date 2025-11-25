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
                    <a href="#niveles" class="btn btn-secondary">Niveles Académicos</a>
                </div>
            </div>

            <!-- Contadores -->
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
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Movilidad Escolar (reemplazo de talleres del home) -->
    <section class="movilidad-section" id="movilidad">
        <div class="container">
            <div class="movilidad-wrapper">
                <div class="movilidad-content">
                    <span class="section-tag">Servicio adicional</span>
                    <h2 class="movilidad-title">Tenemos Movilidad Escolar</h2>
                    <p class="movilidad-desc">
                        Brindamos un servicio de transporte seguro y confiable para nuestros estudiantes,
                        con rutas organizadas y personal capacitado para acompañarlos en cada trayecto.
                    </p>
                    <ul class="movilidad-list">
                        <li>
                            <i class="bi bi-shield-check"></i>
                            <span>Choferes y asistentes con vocación de servicio y responsabilidad.</span>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Rutas estratégicas y planificación según la ubicación de los estudiantes.</span>
                        </li>
                        <li>
                            <i class="bi bi-clock-history"></i>
                            <span>Horarios coordinados con las jornadas de ingreso y salida escolar.</span>
                        </li>
                    </ul>
                </div>

                <div class="movilidad-illustration">
                    <svg class="movilidad-bus" viewBox="0 0 500 300" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <!-- Cuerpo del bus -->
                        <rect x="50" y="80" width="400" height="150" rx="20" fill="#FDB813" />
                        <!-- Techo -->
                        <rect x="80" y="60" width="340" height="25" rx="10" fill="#E5A50A" />
                        <!-- Ventanas -->
                        <rect x="80" y="100" width="70" height="50" rx="5" fill="#87CEEB" />
                        <rect x="170" y="100" width="70" height="50" rx="5" fill="#87CEEB" />
                        <rect x="260" y="100" width="70" height="50" rx="5" fill="#87CEEB" />
                        <rect x="350" y="100" width="70" height="50" rx="5" fill="#87CEEB" />
                        <!-- Franja negra inferior -->
                        <rect x="50" y="210" width="400" height="10" fill="#2d3748" />
                        <!-- Luces -->
                        <circle cx="70" cy="195" r="8" fill="#FFE5B4" />
                        <circle cx="430" cy="195" r="8" fill="#FF6B6B" />
                        <!-- Ruedas -->
                        <circle cx="120" cy="230" r="35" fill="#2d3748" />
                        <circle cx="120" cy="230" r="20" fill="#4a5568" />
                        <circle cx="380" cy="230" r="35" fill="#2d3748" />
                        <circle cx="380" cy="230" r="20" fill="#4a5568" />
                        <!-- Centros de ruedas -->
                        <circle cx="120" cy="230" r="8" fill="#cbd5e0" />
                        <circle cx="380" cy="230" r="8" fill="#cbd5e0" />
                        <!-- Puerta -->
                        <rect x="70" y="160" width="50" height="60" rx="5" fill="#E5A50A" />
                        <rect x="75" y="165" width="40" height="50" rx="3" fill="#2d3748" />
                        <!-- Parachoques -->
                        <rect x="40" y="220" width="420" height="8" rx="4" fill="#2d3748" />
                        <!-- STOP -->
                        <rect x="30" y="120" width="15" height="40" fill="#2d3748" />
                        <polygon points="37.5,100 52.5,115 22.5,115" fill="#FF0000" />
                        <text x="37.5" y="113" font-size="10" fill="white" text-anchor="middle" font-weight="bold">
                            STOP
                        </text>
                        <!-- Letrero -->
                        <rect x="180" y="75" width="140" height="15" fill="#2d3748" />
                        <text x="250" y="86" font-size="12" fill="white" text-anchor="middle" font-weight="bold">
                            SCHOOL BUS
                        </text>
                    </svg>
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

    {{-- Estilos específicos para esta página --}}
    <style>
        /* 📌 Ajuste de imágenes de niveles: llenan el cuadro, sin franjas negras */
        .levels-section .level-card .level-image {
            width: 100%;
            height: 220px;
            border-radius: 20px 20px 0 0;
            overflow: hidden;
            background: var(--gradiente-primario);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .levels-section .level-card .level-image img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* 👉 llena el cuadro, sin fondo negro */
            display: block;
        }

        /* 🚍 Sección Movilidad Escolar */
        .movilidad-section {
            padding: 5rem 15px;
            background: var(--color-gris-claro);
        }

        .movilidad-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            background: var(--color-blanco);
            border-radius: 24px;
            box-shadow: var(--sombra-intensa);
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
            gap: 2.5rem;
            overflow: hidden;
        }

        .movilidad-content {
            padding: 3rem 2.5rem;
        }

        .movilidad-title {
            font-size: 2.4rem;
            font-weight: 900;
            color: var(--color-negro);
            margin-bottom: 1rem;
        }

        .movilidad-desc {
            font-size: 1rem;
            color: var(--color-gris);
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .movilidad-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .movilidad-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: var(--color-gris);
        }

        .movilidad-list i {
            color: var(--color-rojo);
            font-size: 1.3rem;
            margin-top: 0.15rem;
        }

        .movilidad-illustration {
            background: linear-gradient(180deg, #e0f2fe 0%, #bae6fd 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
        }

        .movilidad-bus {
            width: 100%;
            max-width: 420px;
            height: auto;
            display: block;
        }

        @media (max-width: 992px) {
            .movilidad-wrapper {
                grid-template-columns: 1fr;
            }
            .movilidad-illustration {
                order: -1;
            }
            .movilidad-content {
                padding: 2.5rem 2rem;
            }
        }

        @media (max-width: 576px) {
            .movilidad-title {
                font-size: 2rem;
            }
            .movilidad-content {
                padding: 2rem 1.5rem;
            }
        }
    </style>

@endsection


