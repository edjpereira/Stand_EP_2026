<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Stand Eduardo Pereira 2026</title>

    <link rel="dns-prefetch" href="//fonts.造型.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')

    <style>
        .admin-link {
            color: #00D4FF !important;
            text-shadow: 0px 0px 8px rgba(0, 212, 255, 0.3);
        }
    </style>
    @livewireStyles
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Stand Eduardo Pereira
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('home') ? 'active' : '' }}"
                                    href="{{ url('/home') }}">Painel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('clients*') ? 'active' : '' }}"
                                    href="{{ route('clients.index') }}">Clientes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('vehicles*') ? 'active' : '' }}"
                                    href="{{ route('vehicles.index') }}">Viaturas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('sales*') ? 'active' : '' }}"
                                    href="{{ route('sales.index') }}">Vendas</a>
                            </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Registar</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    v-pre>
                                    {{ Auth::user()->name }} <span
                                        class="badge bg-secondary ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-menu-item dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Sair / Logout
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

        @if (auth()->check() && auth()->user()->role === 'admin')
            <nav class="navbar navbar-expand-md navbar-light border-bottom" style="background-color: #f8f9fa;">
                <div class="container">
                    <div class="navbar-nav">
                        <span class="navbar-text me-4"
                            style="font-family: monospace; font-size: 0.85rem; color: #007BFF;">
                            <i class="bi bi-shield-lock me-1"></i> ADMIN_PANEL
                        </span>

                        <a class="nav-link" href="{{ route('users.index') }}" style="color: #0065d0">
                            <i class="bi bi-people me-1"></i> Utilizadores
                        </a>

                        <a class="nav-link" href="{{ route('admin.trash') }}" style="color: #0065d0">
                            <i class="bi bi-trash me-1"></i> Reciclagem
                        </a>
                        <a class="nav-link" href="{{ route('admin.reports') }}"
                            style="color: #00D4FF !important; font-weight: 500;">
                            <i class="bi bi-graph-up me-1"></i> Relatórios
                        </a>

                        <a class="nav-link" href="{{ route('admin.audit') }}"
                            style="color: #00D4FF !important; font-weight: 500;">
                            <i class="bi bi-journal-check me-1"></i> Auditoria
                        </a>
                    </div>
                </div>
            </nav>
        @endif

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
    @livewireScripts
</body>

</html>
