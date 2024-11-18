@extends('layouts.main')
@section('title', 'Create Session')
@section('content')
    <div class="container mt-3">
        <h3>Create Session</h3>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('sessions.store') }}">
            @csrf
            <div class="d-flex">
                <div class="city-list" style="width: 300px; background-color: #333; color: white; padding: 20px;">
                    <h4 style="color: white;">City:</h4>
                    <select id="city-select" onchange="showCinemas(this.value)" style="width: 100%; padding: 5px; background-color: #333; color: white; border: none;">
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" style="color: white;">
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="cinema-list" style="margin-left: 20px; flex-grow: 1;">
                    <h4>Cinema and Halls:</h4>
                    <div id="cinemas-container">
                        @foreach($cities as $city)
                            <div class="cinema-group" id="city-{{ $city->id }}" style="display: {{ $loop->first ? 'block' : 'none' }};">
                                @foreach($city->cinemas as $cinema)
                                    <div class="row align-items-center mb-3">
                                        <div class="col-6">
                                            <h5>{{ $cinema->name }}</h5>
                                            <p>{{ $cinema->address }}</p>
                                        </div>
                                        <div class="col-6">
                                            <ul class="list-unstyled">
                                                @foreach($cinema->halls as $hall)
                                                    <li>
                                                        <label>
                                                            <input type="radio" name="hall_id" value="{{ $hall->id }}" {{ old('hall_id') == $hall->id ? 'checked' : '' }}>
                                                            {{ $hall->name }}
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @error('hall_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="mt-4">
                    <h4>Search Movie by Title</h4>
                    <form action="{{ route('sessions.search') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                id="search"
                                name="query"
                                placeholder="Enter movie title"
                                value="{{ request('query') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>

                    @if(isset($movies) && $movies->isNotEmpty())
                        <table class="table table-bordered mt-3">
                            <thead class="table-light">
                            <tr>
                                <th class="px-3 py-2">Title</th>
                                <th class="px-3 py-2">Description</th>
                                <th class="px-3 py-2 text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($movies as $movie)
                                <tr>
                                    <td class="px-3 py-2">{{ $movie->title }}</td>
                                    <td class="px-3 py-2">{{ Str::limit($movie->description, 100) }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm"
                                            onclick="selectMovieForSession('{{ $movie->id }}', '{{ $movie->title }}')">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $movies->links('pagination::bootstrap-5') }}
                        </div>
                    @elseif(request('query'))
                        <div class="alert alert-warning mt-4">No movies found for "{{ request('query') }}"</div>
                    @endif

                    <div class="mt-3">
                        <strong>Selected Movie:</strong> <span id="selectedMovieTitle" style="font-weight: bold;">None</span>
                        <input type="hidden" id="selectedMovieId" name="movie_id" value="{{ old('movie_id') }}">
                        @error('movie_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}">
                    @error('start_time')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create Session</button>
            </div>
        </form>
    </div>
@endsection

<script>
    function showCinemas(cityId) {
        document.querySelectorAll('.cinema-group').forEach(group => {
            group.style.display = 'none';
        });
        document.getElementById(`city-${cityId}`).style.display = 'block';
    }

    function selectMovieForSession(movieId, movieTitle) {
        document.getElementById('selectedMovieId').value = movieId;
        document.getElementById('selectedMovieTitle').textContent = movieTitle;
    }
</script>
