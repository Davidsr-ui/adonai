<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos - Colegio Adonai</title>
    <!-- CSS y JS con Vite -->
    @vite(['resources/css/style.css', 'resources/js/script.js'])
    @vite(['resources/css/cursos.css', 'resources/js/script.js', 'resources/js/cursos.js'])
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <!-- Navbar Corregido -->
<nav class="navbar" id="navbar">
    <div class="nav-container">
        <!-- Logo como botón -->
        <div class="logo">
            <a href="/" class="logo-link">
                <img src="/img/logoad.png" alt="Colegio Adonai" class="logo-img">
            </a>
        </div>

        <ul class="nav-menu" id="nav-menu">
            <li class="nav-item"><a href="/" class="nav-link">Inicio</a></li>
            
            <!-- Dropdown corregido -->
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
            <!-- Botón Intranet -->
            <a href="{{ route('login') }}" class="btn-intranet">
                <span>Intranet</span>
                <i class="bi bi-person-circle"></i>
            </a>

            <!-- Hamburguesa -->
            <button class="menu-toggle" id="menu-toggle">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>

    <!-- Sección Hero -->
    <section class="seccion-hero-cursos">
        <div class="contenido-hero">
            <h1 class="titulo-hero">Nuestros Cursos Básicos</h1>
            <p class="subtitulo-hero">Formación integral para desarrollar tus habilidades al máximo</p>
        </div>
    </section>

    <!-- Sección Principal de Cursos -->
    <main class="contenedor-principal">
        <div class="contenedor-cursos">
            
            <!-- Tarjeta Curso 1: Comunicación -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Comunicación</h3>
                        <p class="descripcion-curso">Desarrollo de habilidades lingüísticas y expresivas</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Comprensión lectora y análisis de textos</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Expresión oral y oratoria</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Redacción y producción de textos</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Gramática y ortografía</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Literatura peruana y universal</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Comunicación efectiva oral y escrita</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Pensamiento crítico y análisis</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Creatividad literaria</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Argumentación coherente</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Curso 2: Matemática -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-square-root-alt"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Matemática</h3>
                        <p class="descripcion-curso">Desarrollo del pensamiento lógico y razonamiento</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Álgebra y ecuaciones</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Geometría y trigonometría</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Estadística y probabilidades</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Cálculo y funciones</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Resolución de problemas</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Razonamiento lógico-matemático</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Resolución de problemas complejos</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Pensamiento analítico</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Modelamiento matemático</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Curso 3: Ciencias Sociales -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Ciencias Sociales</h3>
                        <p class="descripcion-curso">Comprensión de la sociedad y ciudadanía</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Historia del Perú y universal</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Geografía y medio ambiente</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Economía y desarrollo sostenible</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Ciudadanía y derechos humanos</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Cultura y diversidad</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Conciencia ciudadana</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Pensamiento crítico social</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Convivencia democrática</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Gestión ambiental</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tarjeta Curso 3: Ciencias Sociales -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Ciencias Sociales</h3>
                        <p class="descripcion-curso">Comprensión de la sociedad y ciudadanía</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Historia del Perú y universal</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Geografía y medio ambiente</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Economía y desarrollo sostenible</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Ciudadanía y derechos humanos</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Cultura y diversidad</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Conciencia ciudadana</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Pensamiento crítico social</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Convivencia democrática</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Gestión ambiental</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Curso 4: Ciencia y Tecnología -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-atom"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Ciencia y Tecnología</h3>
                        <p class="descripcion-curso">Exploración científica y método experimental</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Biología y seres vivos</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Física y energía</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Química y materia</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Tecnología e innovación</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Método científico</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Indagación científica</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Pensamiento crítico experimental</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Diseño y construcción</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Explicación de fenómenos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Curso 5: Inglés -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-language"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Inglés</h3>
                        <p class="descripcion-curso">Comunicación en lengua extranjera</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Speaking y conversación</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Listening y comprensión auditiva</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Reading y comprensión lectora</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Writing y producción escrita</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Gramática y vocabulario</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Comunicación intercultural</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Fluidez oral</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Comprensión integral</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Producción textual en inglés</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Curso 6: Desarrollo Personal -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Desarrollo Personal</h3>
                        <p class="descripcion-curso">Formación integral y valores</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Autoconocimiento y autoestima</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Inteligencia emocional</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Habilidades sociales</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Proyecto de vida</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Valores y ética</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Autonomía y responsabilidad</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Gestión emocional</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Empatía y asertividad</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Toma de decisiones</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Curso 7: Educación Física -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-running"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Educación Física</h3>
                        <p class="descripcion-curso">Desarrollo motor y vida saludable</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Deportes individuales y colectivos</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Condición física y salud</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Expresión corporal</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Juegos y recreación</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Hábitos saludables</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Habilidades motrices</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Trabajo en equipo</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Disciplina deportiva</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Vida activa y saludable</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Curso 8: Arte y Cultura -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Arte y Cultura</h3>
                        <p class="descripcion-curso">Expresión artística y creatividad</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Artes visuales y plásticas</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Música y canto</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Teatro y expresión dramática</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Danza y movimiento</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Patrimonio cultural</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Creatividad y expresión</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Apreciación artística</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Sensibilidad estética</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Identidad cultural</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta Curso 9: Educación Religiosa -->
            <div class="tarjeta-curso" onclick="alternarCurso(this)">
                <div class="encabezado-curso">
                    <div class="icono-curso">
                        <i class="fas fa-pray"></i>
                    </div>
                    <div class="info-curso">
                        <h3 class="titulo-curso">Educación Religiosa</h3>
                        <p class="descripcion-curso">Formación espiritual y valores cristianos</p>
                    </div>
                    <div class="indicador-expansion">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#0891b2">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="contenido-curso">
                    <div class="grid-contenido">
                        <div class="columna-contenido">
                            <h4>Contenidos Principales</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Biblia y enseñanzas cristianas</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Valores y moral cristiana</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Historia de la salvación</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Sacramentos y liturgia</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Testimonio de fe</li>
                            </ul>
                        </div>
                        <div class="columna-contenido">
                            <h4>Competencias a Desarrollar</h4>
                            <ul class="lista-contenido">
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Formación espiritual</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Valores éticos cristianos</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Compromiso social</li>
                                <li><svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>Testimonio de vida</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="footer" id="footer">
  <div class="container">
    <div class="footer-content">
      
      <!-- COLUMNA 1 -->
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

      <!-- COLUMNA 2 -->
      <div class="footer-column">
        <h4>Contacto</h4>
        <p><i class="bi bi-geo-alt-fill"></i> Av. Principal 123, Lima</p>
        <p><i class="bi bi-telephone-fill"></i> (01) 234-5678</p>
        <p><i class="bi bi-envelope-fill"></i> info@colegioadonai.edu.pe</p>
      </div>

      <!-- COLUMNA 3 -->
      <div class="footer-column">
        <h4>Enlaces</h4>
        <a href="/#nosotros"><i class="bi bi-chevron-right"></i> Sobre Nosotros</a>
        <a href="/#cursos"><i class="bi bi-chevron-right"></i> Cursos</a>
        <a href="/blog"><i class="bi bi-chevron-right"></i> Blog</a>
        <a href="/#visita"><i class="bi bi-chevron-right"></i> Visita Guiada</a>
      </div>

      <!-- COLUMNA 4 -->
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


    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/51999999999" class="whatsapp-float" target="_blank" aria-label="Contactar por WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
</body>
</html>