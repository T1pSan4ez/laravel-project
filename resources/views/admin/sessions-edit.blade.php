@extends('layouts.main')
@section('title', 'Edit Session')
@section('content')
    <div class="container mt-3">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('sessions.update', $session->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="movie_id" class="form-label">Select Movie</label>
                <select class="form-control @error('movie_id') is-invalid @enderror" id="movie_id" name="movie_id">
                    @foreach($movies as $movie)
                        <option value="{{ $movie->id }}" {{ $movie->id == old('movie_id', $session->movie_id) ? 'selected' : '' }}>
                            {{ $movie->title }} ({{ $movie->duration }} minutes)
                        </option>
                    @endforeach
                </select>
                @error('movie_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="city_id" class="form-label">Select City</label>
                <select class="form-control @error('city_id') is-invalid @enderror" id="city_id" name="city_id" onchange="updateHalls(this.value)">
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ $city->id == old('city_id', $session->hall->cinema->city_id) ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
                @error('city_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="hall_id" class="form-label">Select Hall</label>
                <select class="form-control @error('hall_id') is-invalid @enderror" id="hall_id" name="hall_id">
                    @foreach($cities as $city)
                        @foreach($city->cinemas as $cinema)
                            @foreach($cinema->halls as $hall)
                                <option value="{{ $hall->id }}" data-city="{{ $city->id }}" {{ $hall->id == old('hall_id', $session->hall_id) ? 'selected' : '' }}>
                                    {{ $hall->name }} (Cinema: {{ $cinema->name }})
                                </option>
                            @endforeach
                        @endforeach
                    @endforeach
                </select>
                @error('hall_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', $session->start_time->format('Y-m-d\TH:i')) }}">
                @error('start_time')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="technical_break" class="form-label">Technical Break (minutes)</label>
                <input type="number" class="form-control @error('technical_break') is-invalid @enderror" id="technical_break" name="technical_break" value="{{ old('technical_break', $session->technical_break) }}" placeholder="Optional">
                @error('technical_break')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Session</button>
            <a href="{{ route('sessions') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateHalls(document.getElementById('city_id').value);
        });

        function updateHalls(cityId) {
            const hallSelect = document.getElementById('hall_id');
            const options = hallSelect.querySelectorAll('option');

            options.forEach(option => {
                option.style.display = option.getAttribute('data-city') === cityId ? 'block' : 'none';
            });

            if (!hallSelect.querySelector('option:checked')?.getAttribute('data-city') === cityId) {
                hallSelect.value = '';
            }
        }
    </script>
@endsection
