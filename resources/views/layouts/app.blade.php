<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestor de condominios | Panel de Control</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts y Tipografía Moderna (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Styles base de Laravel -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- ESTILOS PERSONALIZADOS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fb;
            color: #334155;
        }
        
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03) !important;
            padding: 15px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .navbar-brand {
            font-weight: 700;
            color: #4f46e5 !important;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }
        .nav-link {
            font-weight: 500 !important;
            color: #64748b !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #4f46e5 !important;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.07);
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 20px 25px;
            font-size: 1.1rem;
            color: #1e293b;
        }

        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
            transform: translateY(-1px);
        }

        .table {
            vertical-align: middle;
        }
        .table thead th {
            border-bottom: 2px solid #e2e8f0;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.8px;
            padding-bottom: 15px;
        }
        .table tbody td {
            padding: 15px 10px;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-buildings-fill me-2"></i>Gestor de condominios
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Menú principal -->
                    <ul class="navbar-nav me-auto ps-4">
                        @auth
                            <!-- ENLACES SOLO PARA ADMINISTRADORES -->
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('condominiums.index') }}"><i class="bi bi-building me-1"></i> Comunidades</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('units.index') }}"><i class="bi bi-door-open me-1"></i> Unidades</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('residents.index') }}"><i class="bi bi-people-fill me-1"></i> Residentes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('common_expenses.index') }}"><i class="bi bi-cash-stack me-1"></i> Finanzas</a>
                                </li>
                            @endif

                            <!-- ENLACES SOLO PARA RESIDENTES -->
                            @if(Auth::user()->role === 'residente')
                                <li class="nav-item">
                                    <a class="nav-link fw-bold text-primary" href="{{ route('home') }}"><i class="bi bi-house-door-fill me-1"></i> Mi Departamento</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Perfil -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            @yield('content')
        </main>
    </div>
</body>
</html>