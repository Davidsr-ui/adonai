@extends('layouts.app')

@section('title', 'Colegio Adonai - Educación Cristiana de Excelencia')

@section('content')
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

@endsection