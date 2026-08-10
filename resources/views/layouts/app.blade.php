<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestor de condominios | Panel de Control</title>

    <!-- Script anti-parpadeo: Revisa el tema antes de cargar la página -->
    <script>
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>

    <!-- Scripts Base -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts y Tipografía Moderna (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- DataTables CSS (Bootstrap 5) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">

    <!-- Styles base de Laravel -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- ESTILOS PERSONALIZADOS CON VARIABLES DE MODO OSCURO -->
    <style>
        /* Variables Globales (Modo Claro por defecto) */
        :root {
            --bg-color: #f4f7fb;
            --text-main: #334155;
            --text-muted: #64748b;
            --heading-color: #1e293b;
            --navbar-bg: #ffffff;
            --card-bg: #ffffff;
            --border-color: #f1f5f9;
            --hover-bg: #f1f5f9;
            --table-border: #e2e8f0;
            --shadow-color: rgba(0, 0, 0, 0.03);
            --shadow-hover: rgba(0, 0, 0, 0.07);
        }

        /* Variables Modo Oscuro */
        [data-theme="dark"] {
            --bg-color: #0f172a;
            --text-main: #cbd5e1;
            --text-muted: #94a3b8;
            --heading-color: #f8fafc;
            --navbar-bg: #1e293b;
            --card-bg: #1e293b;
            --border-color: #334155;
            --hover-bg: #334155;
            --table-border: #334155;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --shadow-hover: rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Adaptaciones de colores Bootstrap al tema */
        .bg-white { background-color: var(--card-bg) !important; }
        .text-dark { color: var(--heading-color) !important; }
        .text-muted { color: var(--text-muted) !important; }
        h1, h2, h3, h4, h5, h6 { color: var(--heading-color); }

        .navbar {
            background-color: var(--navbar-bg) !important;
            box-shadow: 0 4px 20px var(--shadow-color) !important;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .navbar-brand span {
            font-weight: 700;
            color: var(--heading-color) !important; /* Reemplaza el azul fijo por variable */
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .navbar-nav {
            align-items: center;
        }

        .nav-link {
            font-weight: 500 !important;
            color: var(--text-muted) !important;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link:hover,
        .nav-link.show {
            color: #4f46e5 !important;
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 25px var(--shadow-color);
            padding: 8px;
        }

        .dropdown-item {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-main);
            border-radius: 8px;
            padding: 8px 14px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: var(--hover-bg);
            color: #4f46e5;
        }

        .dropdown-header {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            padding: 6px 14px 2px;
        }

        .card {
            background-color: var(--card-bg);
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px var(--shadow-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px var(--shadow-hover);
        }

        .card-header {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 20px 25px;
            font-size: 1.1rem;
            color: var(--heading-color);
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
            color: var(--text-main);
        }

        .table thead th {
            border-bottom: 2px solid var(--table-border);
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.8px;
            padding-bottom: 15px;
            background-color: transparent !important;
        }

        .table tbody td, .table tbody tr {
            padding: 15px 10px;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
            background-color: transparent !important;
        }

        /* Ajustes para DataTables y buscador */
        .dt-container .dt-search input, .dt-container .dt-length select {
            background-color: var(--card-bg);
            color: var(--text-main);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 6px 12px;
        }

        /* Botón de alternar tema */
        #theme-toggle {
            cursor: pointer;
            border: none;
            background: transparent;
            font-size: 1.2rem;
            padding: 0;
            margin-right: 15px;
        }
        
        #theme-toggle i {
            transition: color 0.3s ease;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <!-- Logo: Puedes tener uno claro y otro oscuro si quisieras, pero dejamos el actual -->
                    <img src="{{ asset('images/condominio_reverse.png') }}" alt="Logo CondoGest" style="height: 35px; width: auto;" class="me-2">
                    <span>Gestor de condominios</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Menú principal -->
                    <ul class="navbar-nav me-auto ps-4">
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <!-- 1. Administración -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-building-gear"></i> Administración
                                    </a>
                                    <ul class="dropdown-menu shadow-sm">
                                        <li><h6 class="dropdown-header">Comunidad y Espacios</h6></li>
                                        <li><a class="dropdown-item" href="{{ route('condominiums.index') }}"><i class="bi bi-building me-2 text-primary"></i> Comunidades</a></li>
                                        <li><a class="dropdown-item" href="{{ route('units.index') }}"><i class="bi bi-door-open me-2 text-primary"></i> Unidades / Deptos</a></li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li><h6 class="dropdown-header">Personas</h6></li>
                                        <li><a class="dropdown-item" href="{{ route('residents.index') }}"><i class="bi bi-people-fill me-2 text-primary"></i> Directorio de Residentes</a></li>
                                    </ul>
                                </li>

                                <!-- 2. Finanzas & Reportes -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="financeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-wallet2"></i> Finanzas & Reportes
                                    </a>
                                    <ul class="dropdown-menu shadow-sm">
                                        <li><a class="dropdown-item" href="{{ route('common_expenses.index') }}"><i class="bi bi-cash-stack me-2 text-success"></i> Gastos Comunes</a></li>
                                        <li><a class="dropdown-item" href="{{ route('reports.index') }}"><i class="bi bi-file-earmark-bar-graph me-2 text-danger"></i> Centro de Reportes</a></li>
                                    </ul>
                                </li>

                                <!-- 3. Mantenimiento -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="maintenanceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-wrench-adjustable-circle"></i> Mantenimiento
                                    </a>
                                    <ul class="dropdown-menu shadow-sm">
                                        <li><a class="dropdown-item" href="{{ route('tickets.index') }}"><i class="bi bi-tools me-2 text-warning"></i> Incidencias / Tickets</a></li>
                                    </ul>
                                </li>
                            @endif

                            @if (Auth::user()->role === 'residente')
                                <li class="nav-item">
                                    <a class="nav-link fw-bold text-primary" href="{{ route('home') }}">
                                        <i class="bi bi-house-door-fill"></i> Mi Departamento
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Perfil y Tema -->
                    <ul class="navbar-nav ms-auto">
                        <!-- BOTÓN MODO OSCURO/CLARO -->
                        <li class="nav-item border-end pe-3 me-2 border-secondary border-opacity-25 d-none d-md-flex align-items-center">
                            <button id="theme-toggle" class="nav-link" title="Alternar modo claro/oscuro">
                                <i class="bi bi-moon-stars-fill" id="theme-icon"></i>
                            </button>
                        </li>

                        @guest
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2 text-danger"></i> Cerrar Sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
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

    <!-- jQuery y DataTables Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- Script de Inicialización y Tema -->
    <script>
        // DataTables
        document.addEventListener('DOMContentLoaded', function() {
            $('#datatable, .datatable').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    new DataTable(this, {
                        language: { url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-CL.json' },
                        pageLength: 10,
                        responsive: true
                    });
                }
            });

            // LÓGICA DEL BOTÓN MODO OSCURO
            const themeToggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            function updateIcon(theme) {
                if (theme === 'dark') {
                    themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                    themeIcon.style.color = '#fbbf24'; // Sol amarillo
                } else {
                    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
                    themeIcon.style.color = ''; // Color original (gris azulado)
                }
            }

            // Aplicar icono inicial
            const currentTheme = document.documentElement.getAttribute('data-theme');
            updateIcon(currentTheme);

            // Evento al hacer clic en el botón
            themeToggleBtn.addEventListener('click', () => {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                const newTheme = isDark ? 'light' : 'dark';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateIcon(newTheme);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>