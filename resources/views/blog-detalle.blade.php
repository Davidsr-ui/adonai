@extends('layouts.app')

@section('title', 'Colegio Adonai - Educación Cristiana de Excelencia')

@section('content')

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #F8F9FA;
        }

        .post-detail {
            max-width: 900px;
            margin: 120px auto 50px;
            padding: 0 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #D35400;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 30px;
            transition: gap 0.3s ease;
            font-size: 16px;
        }

        .back-link:hover {
            gap: 15px;
        }

        .post-header {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .post-category {
            display: inline-block;
            background: linear-gradient(135deg, #D35400 0%, #E67E22 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .post-title {
            font-size: 42px;
            font-weight: 900;
            color: #2C3E50;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .post-meta {
            display: flex;
            gap: 25px;
            color: #7F8C8D;
            font-size: 16px;
            flex-wrap: wrap;
        }

        .post-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .post-meta i {
            color: #D35400;
        }

        .post-image-container {
            margin-bottom: 40px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .post-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            display: block;
        }

        .post-content {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            font-size: 18px;
            line-height: 1.8;
            color: #34495E;
            margin-bottom: 50px;
        }

        .post-content p {
            margin-bottom: 20px;
        }

        .related-posts {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid #ECF0F1;
        }

        .related-title {
            font-size: 28px;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .related-title i {
            color: #D35400;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .related-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .related-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .related-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .related-card-content {
            padding: 20px;
        }

        .related-card-category {
            display: inline-block;
            background: #FEF5E7;
            color: #D35400;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .related-card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2C3E50;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .related-card-date {
            font-size: 14px;
            color: #95A5A6;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .related-card-link {
            color: #D35400;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.3s ease;
        }

        .related-card-link:hover {
            gap: 12px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .post-detail {
                margin-top: 100px;
            }

            .post-title {
                font-size: 32px;
            }

            .post-header,
            .post-content {
                padding: 25px;
            }

            .post-image {
                height: 300px;
            }

            .post-content {
                font-size: 16px;
            }

            .related-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

<!-- DETALLE DEL POST -->
<section class="post-detail">
    <a href="{{ route('blog') }}" class="back-link">
        <i class="bi bi-arrow-left-circle-fill"></i>
        Volver al Blog
    </a>

    <article>
        <div class="post-header">
            <span class="post-category">
                <i class="bi bi-bookmark-fill"></i> {{ $post->categoria }}
            </span>
            
            <h1 class="post-title">{{ $post->titulo }}</h1>
            
            <div class="post-meta">
                <span>
                    <i class="bi bi-calendar-event"></i>
                    {{ $post->fecha->format('d \d\e F, Y') }}
                </span>
                <span>
                    <i class="bi bi-person-fill"></i>
                    {{ $post->autor ?? 'Administrador' }}
                </span>
            </div>
        </div>

        @if($post->portada)
            <div class="post-image-container">
                <img src="{{ asset('storage/' . $post->portada) }}" 
                     alt="{{ $post->titulo }}" 
                     class="post-image">
            </div>
        @endif

        <div class="post-content">
            {!! nl2br(e($post->contenido)) !!}
        </div>
    </article>

    <!-- POSTS RELACIONADOS -->
    @if($relacionados->count() > 0)
        <div class="related-posts">
            <h2 class="related-title">
                <i class="bi bi-grid-fill"></i> 
                Publicaciones Relacionadas
            </h2>

            <div class="related-grid">
                @foreach($relacionados as $relacionado)
                    <div class="related-card">
                        @if($relacionado->portada)
                            <img src="{{ asset('storage/' . $relacionado->portada) }}" 
                                 alt="{{ $relacionado->titulo }}"
                                 class="related-card-image">
                        @endif

                        <div class="related-card-content">
                            <span class="related-card-category">
                                {{ $relacionado->categoria }}
                            </span>

                            <h3 class="related-card-title">
                                {{ $relacionado->titulo }}
                            </h3>

                            <p class="related-card-date">
                                <i class="bi bi-calendar-event"></i>
                                {{ $relacionado->fecha->format('d M Y') }}
                            </p>

                            <a href="{{ route('blog.detalle', $relacionado->id) }}" 
                               class="related-card-link">
                                Leer más <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
@endsection
