<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        .navbar {
            max-height: 70px;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <nav class="d-flex flex-column bg-dark text-white p-3" style="width: 250px; height: 100vh; position: fixed;">
        <h3 class="text-center">Admin Panel</h3>
        <ul class="nav flex-column mt-4">

            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('movies') }}">Movies</a>
            </li>
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link text-white" href="{{ route('theater-plays') }}">Theater Plays</a>--}}
{{--            </li>--}}
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('cinemas') }}">Cinemas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('sessions') }}">Sessions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('products') }}">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('users') }}">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('pdf.generator') }}">PDFs</a>
            </li>
        </ul>
    </nav>

    <div class="main-content flex-grow-1" style="margin-left: 250px;">
        <nav class="navbar navbar-expand navbar-light bg-light shadow-sm">
            <div class="container-fluid d-flex justify-content-between align-content-center ">
                <span class="navbar-brand">@yield('title')</span>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                @endguest
                @auth()
                    <a href="#" class="btn btn-primary btn-sm"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                @endauth
            </div>
        </nav>

        @if(request()->is('/'))
            <div class="alert alert-success text-center my-3">
                <h4>Welcome to the Admin Panel!</h4>
                <p>We’re glad to have you here.</p>
            </div>
        @endif

        <div class="container py-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>


