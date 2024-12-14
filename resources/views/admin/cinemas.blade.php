@extends('layouts.main')
@section('title', 'Halls ')
@section('content')

    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="add-city mb-4">
            <h5>Add City</h5>
            <form action="{{ route('cinemas.addCity') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="city_name" class="form-label">City Name</label>
                    <input type="text" class="form-control @error('city_name') is-invalid @enderror" id="city_name" name="city_name" value="{{ old('city_name') }}">
                    @error('city_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Add City</button>
            </form>
        </div>

        <div class="add-cinema mb-4">
            <h5>Add Cinema</h5>
            <form action="{{ route('cinemas.addCinema') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="city_id" class="form-label">Select City</label>
                    <select class="form-select @error('city_id') is-invalid @enderror" id="city_id" name="city_id">
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="cinema_name" class="form-label">Cinema Name</label>
                    <input type="text" class="form-control @error('cinema_name') is-invalid @enderror" id="cinema_name" name="cinema_name" value="{{ old('cinema_name') }}">
                    @error('cinema_name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="cinema_address" class="form-label">Cinema Address</label>
                    <input type="text" class="form-control @error('cinema_address') is-invalid @enderror" id="cinema_address" name="cinema_address" value="{{ old('cinema_address') }}">
                    @error('cinema_address')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Add Cinema</button>
            </form>
        </div>

        <div class="d-flex">
            <div class="city-list" style="width: 250px; background-color: #ffffff; color: #000000; padding: 20px;">
                <h4 style="color: #000000;">City:</h4>
                <select id="city-select" onchange="showCinemas(this.value)" style="width: 100%; padding: 5px; background-color: #ffffff; color: #000000; border: none;">
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" style="color: #000000;">
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="cinema-list" style="margin-left: 20px; flex-grow: 1;">
                <h4>Cinema:</h4>
                <div id="cinemas-container">
                    @foreach($cities as $city)
                        <div class="cinema-group" id="city-{{ $city->id }}" style="display: {{ $loop->first ? 'block' : 'none' }};">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 style="color: red;">{{ $city->name }}</h5>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-link text-danger p-0" data-bs-toggle="modal" data-bs-target="#confirmDeleteCityModal" data-city-id="{{ $city->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="cinemas">
                                @foreach($city->cinemas as $cinema)
                                    <div class="row align-items-center mb-3">
                                        <div class="col">
                                            <h6>{{ $cinema->name }}</h6>
                                            <p>{{ $cinema->address }}</p>
                                            <a href="{{ route('halls', ['cinema_id' => $cinema->id]) }}" style="text-decoration: none; color: gray;">
                                                <i class="bi bi-calendar"></i> Halls
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-link text-danger p-0" data-bs-toggle="modal" data-bs-target="#confirmDeleteCinemaModal" data-cinema-id="{{ $cinema->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteCityModal" tabindex="-1" aria-labelledby="confirmDeleteCityLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteCityLabel">Confirm City Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this city? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <form method="POST" id="deleteCityForm" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteCinemaModal" tabindex="-1" aria-labelledby="confirmDeleteCinemaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteCinemaLabel">Confirm Cinema Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this cinema? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <form method="POST" id="deleteCinemaForm" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    function showCinemas(cityId) {
        document.querySelectorAll('.cinema-group').forEach(group => {
            group.style.display = 'none';
        });

        document.getElementById('city-' + cityId).style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const deleteCityModal = document.getElementById('confirmDeleteCityModal');
        const deleteCityForm = document.getElementById('deleteCityForm');

        deleteCityModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const cityId = button.getAttribute('data-city-id');
            deleteCityForm.action = `/admin/halls/delete-city/${cityId}`;
        });

        const deleteCinemaModal = document.getElementById('confirmDeleteCinemaModal');
        const deleteCinemaForm = document.getElementById('deleteCinemaForm');

        deleteCinemaModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const cinemaId = button.getAttribute('data-cinema-id');
            deleteCinemaForm.action = `/admin/halls/delete-cinema/${cinemaId}`;
        });
    });
</script>
