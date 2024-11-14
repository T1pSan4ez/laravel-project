<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('theater-plays') }}">Theater Plays</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('cinemas') }}">Cinemas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('users') }}">Users</a>
            </li>
        </ul>
    </nav>

    <div class="main-content flex-grow-1" style="margin-left: 250px;">
        <nav class="navbar navbar-expand navbar-light bg-light shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand">@yield('title')</span>

            </div>
        </nav>

        <div class="container py-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
