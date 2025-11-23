@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        /* ======== RESET COMPLETO ======== */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            overflow: hidden !important;
        }

        /* ======== OCULTAR NAVBAR Y HEADERS DE ADMINLTE ======== */
        .login-page .main-header,
        .login-page .navbar,
        .login-page nav,
        .login-page header,
        .main-header,
        .navbar,
        nav,
        header {
            display: none !important;
            visibility: hidden !important;
        }

        .login-page .wrapper,
        .login-page > .wrapper {
            padding: 0 !important;
            margin: 0 !important;
        }

        .login-page::before,
        .login-page::after {
            display: none !important;
        }

        /* ======== LOGIN INTRANET ADONAI - PROFESIONAL ======== */
        body.login-page {
            margin: 0 !important;
            padding: 0 !important;
            height: 100vh !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden !important;
            background: #ffffff;
        }

        .login-wrapper {
            display: flex;
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
        }

        /* ========= SECCIÓN IZQUIERDA - CARRUSEL ========= */
        .login-carousel-section {
            flex: 1;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #c0392b 0%, #8e2a1f 100%);
        }

        .carousel-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .carousel-slide.active {
            opacity: 1;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Overlay oscuro sobre las imágenes */
        .carousel-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(192, 57, 43, 0.3);
            z-index: 1;
        }

        /* Contenido sobre el carrusel */
        .carousel-content {
            position: absolute;
            bottom: 60px;
            left: 60px;
            z-index: 2;
            color: white;
            max-width: 500px;
        }

        .carousel-content h1 {
            font-size: 42px;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        }

        .carousel-content p {
            font-size: 18px;
            line-height: 1.6;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
        }

        /* Indicadores del carrusel */
        .carousel-indicators {
            position: absolute;
            bottom: 30px;
            right: 60px;
            z-index: 2;
            display: flex;
            gap: 10px;
        }

        .carousel-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
        }

        .carousel-indicator.active {
            background: white;
            width: 35px;
            border-radius: 6px;
        }

        /* ========= SECCIÓN DERECHA - FORMULARIO ========= */
        .login-form-section {
            width: 480px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            box-shadow: -5px 0 25px rgba(0,0,0,0.1);
            position: relative;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        /* Botón Volver */
        .login-back-btn {
            position: absolute;
            top: 25px;
            left: 25px;
            font-size: 15px;
            color: #c0392b;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s;
            padding: 8px 15px;
            border-radius: 6px;
        }

        .login-back-btn:hover {
            background: #fff5f5;
            color: #a93226;
        }

        /* Header del formulario */
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header .logo-icon {
            font-size: 50px;
            color: #c0392b;
            margin-bottom: 15px;
            display: inline-block;
            animation: fadeInDown 0.6s ease;
        }

        .login-header h2 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            animation: fadeInDown 0.6s ease 0.1s backwards;
        }

        .login-header p {
            font-size: 15px;
            color: #7f8c8d;
            animation: fadeInDown 0.6s ease 0.2s backwards;
        }

        /* Formulario */
        .login-form {
            animation: fadeInUp 0.6s ease 0.3s backwards;
        }

        .login-form .form-group {
            margin-bottom: 22px;
        }

        .login-form .form-group label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .login-form .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 15px;
            border: 2px solid #e8e8e8;
            transition: all 0.3s;
        }

        .login-form .form-control:focus {
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1);
            outline: none;
        }

        .login-form .form-control.is-invalid {
            border-color: #e74c3c;
        }

        .invalid-feedback {
            font-size: 13px;
            margin-top: 6px;
        }

        /* Opciones del formulario */
        .form-options {
            margin: 20px 0 25px 0;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-options label {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #5a5a5a;
            font-weight: 500;
        }

        .form-options input[type="checkbox"] {
            cursor: pointer;
        }

        .form-options .forgot-password {
            color: #c0392b;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .form-options .forgot-password:hover {
            color: #a93226;
            text-decoration: underline;
        }

        /* Botón principal */
        .btn-login {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            border: none;
            color: #fff;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(192, 57, 43, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(192, 57, 43, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Info adicional */
        .login-info {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }

        .login-info a {
            color: #c0392b;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-info a:hover {
            color: #a93226;
            text-decoration: underline;
        }

        /* Animaciones */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 968px) {
            .login-carousel-section {
                display: none;
            }
            
            .login-form-section {
                width: 100%;
            }

            .carousel-content {
                left: 30px;
                bottom: 30px;
            }

            .carousel-content h1 {
                font-size: 32px;
            }
        }
    </style>
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

@section('auth_body')
<div class="login-wrapper">
    <!-- ====== SECCIÓN IZQUIERDA - CARRUSEL ====== -->
    <div class="login-carousel-section">
        <div class="carousel-container">
            <!-- Slides del carrusel -->
            <div class="carousel-slide active">
                <img src="{{ asset('img/carrusel2.png') }}" alt="Slide 1">
            </div>
            <div class="carousel-slide">
                <img src="{{ asset('img/carrusel3.png') }}" alt="Slide 2">
            </div>
            <div class="carousel-slide">
                <img src="{{ asset('img/carrusel4.png') }}" alt="Slide 3">
            </div>

            <!-- Overlay -->
            <div class="carousel-overlay"></div>

            <!-- Contenido sobre el carrusel -->
            <div class="carousel-content">
                <h1>Bienvenido a Intranet Adonai</h1>
                <p>Portal de Padres y Estudiantes. Accede a toda la información académica en un solo lugar.</p>
            </div>

            <!-- Indicadores -->
            <div class="carousel-indicators">
                <span class="carousel-indicator active" data-slide="0"></span>
                <span class="carousel-indicator" data-slide="1"></span>
                <span class="carousel-indicator" data-slide="2"></span>
            </div>
        </div>
    </div>

    <!-- ====== SECCIÓN DERECHA - FORMULARIO ====== -->
    <div class="login-form-section">
        <!-- Botón Volver -->
        <a href="{{ url('/') }}" class="login-back-btn">
            <i class="bi bi-arrow-left"></i> Volver
        </a>

        <div class="login-container">
            <div class="login-header">
                <div class="logo-icon">✞</div>
                <h2>Intranet Adonai</h2>
                <p>Inicia sesión en tu cuenta</p>
            </div>

            <form action="{{ $loginUrl }}" method="POST" class="login-form">
                @csrf

                {{-- Usuario --}}
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="usuario@ejemplo.com"
                        class="form-control @error('email') is-invalid @enderror"
                        required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password"
                        placeholder="Ingresa tu contraseña"
                        class="form-control @error('password') is-invalid @enderror"
                        required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Opciones --}}
                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Recordarme</span>
                    </label>
                    @if($passResetUrl)
                        <a href="{{ $passResetUrl }}" class="forgot-password">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                {{-- Botón --}}
                <button type="submit" class="btn btn-login">Iniciar Sesión</button>

                {{-- Registro --}}
                @if($registerUrl)
                    <div class="login-info">
                        <p>¿Primera vez? <a href="{{ $registerUrl }}">Solicita tu acceso aquí</a></p>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    // ========= CARRUSEL AUTOMÁTICO =========
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.carousel-indicator');
        let currentSlide = 0;
        const slideInterval = 3000; // 3 segundos

        function showSlide(index) {
            // Ocultar todos los slides
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));

            // Mostrar el slide actual
            slides[index].classList.add('active');
            indicators[index].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        // Cambiar slide automáticamente
        setInterval(nextSlide, slideInterval);

        // Click en indicadores
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                showSlide(currentSlide);
            });
        });
    });
</script>
@stop