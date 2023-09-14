<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion des tâches') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.2/tinymce.min.js" integrity="sha512-lLE5tUMZXmDmyGWI5KDlFemVusXiALcU1lPibL4xkPbPvuOXfXcdoeU3KBDxWp18/KOzrfKkgsscN1t9740ciA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Gestion des tâches') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <!-- Authentication Links -->
                    @guest
                        <ul class="navbar-nav ms-auto">
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        </ul>
                    @else
                        <ul class="navbar-nav mx-auto">
                            @if(Auth::user()->is_admin === 1)
                                <li class="nav-item ms-lg-3">
                                    <i class="fa-solid fa-gauge"></i>
                                    <a href="{{ route('home') }}" class="text-decoration-none text-dark">Dashboard</a>
                                </li>
                            @endif
                            <li class="nav-item mx-lg-3">
                                <i class="fa-solid fa-list-check"></i>
                                <a href="{{ route('space') }}" class="text-decoration-none text-dark">Mes espaces</a>
                            </li>
                            <li class="nav-item">
                                <i class="fa-solid fa-handshake"></i>
                                <a href="{{ route('meetings') }}" class="text-decoration-none text-dark">Réunions</a>
                            </li>
                            @if(Auth::user()->is_admin === 1)
                                <li class="nav-item ms-lg-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-file-zipper"></i>
                                        <a href="{{ route('show_archives') }}" class="dropdown-item ms-1">Archives</a>
                                    </div>
                                </li>
                            @endif
                        </ul>
                        <ul class="navbar-nav ms-auto">
                            @if(Auth::user()->is_admin === 1)
                                <li class="nav-item me-lg-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create_space">Créer un espace</button>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->first_name.' '. Auth::user()->last_name}}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <div class="d-flex align-items-center mx-3">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Se déconnecter') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        @include('spaces.add')
                    @endguest
                </div>
            </div>
        </nav>
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>