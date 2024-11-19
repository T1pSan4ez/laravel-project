@extends('layouts.main')
@section('title', 'Dashboard')
@section('content')
    <div class="container mt-4">
        <div class="row">

            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Admins and Users</h5>
                        <p class="card-text">
                            <strong>Admins:</strong> {{ $adminCount }} <br>
                            <strong>Users:</strong> {{ $userCount }} <br>
                            <strong>Total:</strong> {{ $adminCount + $userCount }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Cities, Cinemas, and Halls</h5>
                        <p class="card-text">
                            <strong>Cities:</strong> {{ $cityCount }} <br>
                            <strong>Cinemas:</strong> {{ $cinemaCount }} <br>
                            <strong>Halls:</strong> {{ $hallCount }} <br>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Movies</h5>
                        <p class="card-text">
                            <strong>Total Movies:</strong> {{ $movieCount }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Sessions</h5>
                        <p class="card-text">
                            <strong>Total Sessions:</strong> {{ $sessionCount }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
