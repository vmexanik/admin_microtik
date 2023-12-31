<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    @vite('resources/sass/app.scss')

    @if(Request::is('/'))
        @vite('resources/js/check_router_status.js')
    @endif

    @if(Request::is('update_router'))
        @vite('resources/js/update_router.js')
    @endif

    @if(Request::is('user_router'))
        @vite('resources/js/user_router.js')
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Слава Україні!!</title>

</head>
<body class="d-flex flex-column h-100">
<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Doker Corp.</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ (Request::is('/') ? 'active' : '') }}" aria-current="page"
                                   href="/">Main</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (Request::is('router') ? 'active' : '') }}" aria-current="page"
                                   href="/router">Routers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (Request::is('update_router') ? 'active' : '') }}" aria-current="page"
                                   href="/update_router">Update RoS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (Request::is('user_router') ? 'active' : '') }}"
                                   aria-current="page"
                                   href="/user_router">Change passwords</a>
                            </li>
                            @if (Auth::user()->user_type=='admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ (Request::is('register') ? 'active' : '') }}"
                                       href="/register">Register new admin</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
                <div class="d-flex">
                    @if (Route::has('login'))
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="btn btn-outline-secondary" href="{{ route('logout') }}" onclick="event.preventDefault();
                                        this.closest('form').submit();">Logout</a>
                            </form>
                        @else
                            <a class="btn btn-outline-secondary" href="{{ route('login') }}">Login</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>
</header>
