@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        /* ======== LOGIN INTRANET ADONAI ======== */
        body.login-page {
            background: #f4f6f9; /* color clarito */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            position: relative; /* para el botón de volver flotante */
        }

        /* Botón Volver */
        .login-back-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 16px;
            color: #c0392b;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.3s;
        }
        .login-back-btn:hover {
            color: #a93226;
        }

        .login-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .login-header .logo-icon {
            font-size: 40px;
            color: #c0392b;
            margin-bottom: 10px;
        }

        .login-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .login-header p {
            font-size: 14px;
            color: #777;
        }

        .login-form .form-group label {
            font-weight: 600;
            color: #444;
        }

        .login-form .form-control {
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
        }

        .form-options {
            margin: 15px 0;
            font-size: 14px;
        }

        .form-options label {
            cursor: pointer;
        }

        .form-options .forgot-password {
            color: #c0392b;
            text-decoration: none;
        }

        .form-options .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-login {
            background: #c0392b;
            border: none;
            color: #fff;
            padding: 12px;
            width: 100%;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #a93226;
        }

        .login-info {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .login-info a {
            color: #c0392b;
            font-weight: 600;
            text-decoration: none;
        }

        .login-info a:hover {
            text-decoration: underline;
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
<div class="login-container">
    <!-- Botón Volver -->
    <a href="{{ url('/') }}" class="login-back-btn">
        <i class="bi bi-arrow-left"></i> Volver
    </a>

    <div class="login-header">
        <div class="logo-icon">✞</div>
        <h2>Intranet Adonai</h2>
        <p>Portal de Padres y Estudiantes</p>
    </div>

    <form action="{{ $loginUrl }}" method="POST" class="login-form">
        @csrf

        {{-- Usuario --}}
        <div class="form-group">
            <label for="email">Usuario</label>
            <input type="email" id="email" name="email"
                value="{{ old('email') }}"
                placeholder="Ingrese su usuario"
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
                placeholder="Ingrese su contraseña"
                class="form-control @error('password') is-invalid @enderror"
                required>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Opciones --}}
        <div class="form-options d-flex justify-content-between align-items-center">
            <label class="checkbox">
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
@stop
