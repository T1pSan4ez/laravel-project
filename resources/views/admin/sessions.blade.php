@extends('layouts.main')
@section('title', 'Manage Sessions')
@section('content')
    <div class="container mt-3">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" id="create-session-tab" href="#create-session" data-bs-toggle="tab">Create Session</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="view-sessions-tab" href="#view-sessions" data-bs-toggle="tab">View Sessions</a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="create-session">
                <div class="mt-4">
                    <strong>Search Movie by Title</strong>
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
                                <th class="px-3 py-2">Duration</th>
                                <th class="px-3 py-2 text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($movies as $movie)
                                <tr>
                                    <td class="px-3 py-2">{{ $movie->title }}</td>
                                    <td class="px-3 py-2">{{ $movie->duration }} minutes</td>
                                    <td class="px-3 py-2 text-center">
                                        <button
                                            type="button"
                                            class="btn btn-success btn-sm"
                                            onclick="selectMovieForSession('{{ $movie->id }}', '{{ $movie->title }}', '{{ $movie->duration }}')">
                                            Select
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $movies->appends(['sessions_page' => request('sessions_page')])->links('pagination::bootstrap-5') }}
                        </div>
                    @elseif(request('query'))
                        <div class="alert alert-warning mt-4">No movies found for "{{ request('query') }}"</div>
                    @endif
                </div>

                <form method="POST" action="{{ route('sessions.store') }}">
                    @csrf
                    <div class="d-flex">
                        <div class="city-list" style="width: 300px; background-color: #ffffff; color: #000000; padding: 20px;">
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
                        <div class="mt-3">
                            <strong>Selected Movie:</strong>
                            <span id="selectedMovieTitle" style="font-weight: bold;">None</span>
                            <span id="selectedMovieDuration" style="font-weight: bold;"></span>
                            <input type="hidden" id="selectedMovieId" name="movie_id" value="{{ old('movie_id') }}">
                            @error('movie_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="start_time" class="form-label">Start Time</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}">
                            @error('start_time')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="technical_break" class="form-label">Technical Break (minutes)</label>
                            <input type="number" class="form-control" id="technical_break" name="technical_break" value="{{ old('technical_break', 10) }}" placeholder="Optional">
                            @error('technical_break')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Create Session</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="view-sessions">
                @if(isset($sessions) && $sessions->isNotEmpty())
                    <table class="table table-bordered mt-3">
                        <thead class="table-light">
                        <tr>
                            <th class="px-3 py-2">ID</th>
                            <th class="px-3 py-2">City</th>
                            <th class="px-3 py-2">Cinema</th>
                            <th class="px-3 py-2">Hall</th>
                            <th class="px-3 py-2">Movie</th>
                            <th class="px-3 py-2">Start Time</th>
                            <th class="px-3 py-2">End Time</th>
                            <th class="px-3 py-2 text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sessions as $session)
                            <tr>
                                <td class="px-3 py-2">{{ $session->id }}</td>
                                <td class="px-3 py-2">{{ $session->hall->cinema->city->name }}</td>
                                <td class="px-3 py-2">{{ $session->hall->cinema->name }}</td>
                                <td class="px-3 py-2">{{ $session->hall->name }}</td>
                                <td class="px-3 py-2">{{ $session->movie->title }}</td>
                                <td class="px-3 py-2">{{ $session->start_time }}</td>
                                <td class="px-3 py-2">{{ $session->end_time }}</td>
                                <td class="px-3 py-2 text-center">
                                    <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal"
                                            data-session-id="{{ $session->id }}"
                                            data-session-movie="{{ $session->movie->title }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                    <div class="mt-3">
                        {{ $sessions->appends(['movies_page' => request('movies_page')])->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="alert alert-warning mt-4">No sessions found</div>
                @endif
            </div>
        </div>
    </div>


    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the session for <strong id="sessionMovie"></strong>? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteSessionForm" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Confirm and Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.nav-link');
        const activeTabKey = 'sessionManagementTab';
        const defaultTabId = 'create-session-tab';
        let activeTab = localStorage.getItem(activeTabKey) || defaultTabId;

        const activeTabLink = document.querySelector(`#${activeTab}`);
        const activeTabPane = activeTabLink ? document.querySelector(activeTabLink.getAttribute('href')) : null;

        if (activeTabLink && activeTabPane) {
            tabs.forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(tabPane => tabPane.classList.remove('show', 'active'));

            activeTabLink.classList.add('active');
            activeTabPane.classList.add('show', 'active');
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {

                localStorage.setItem(activeTabKey, this.id);

                tabs.forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(tabPane => tabPane.classList.remove('show', 'active'));

                this.classList.add('active');
                const targetPane = document.querySelector(this.getAttribute('href'));
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });

        const confirmDeleteModal = document.getElementById('confirmDeleteModal');
        const deleteSessionForm = document.getElementById('deleteSessionForm');
        const sessionMovie = document.getElementById('sessionMovie');

        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const sessionId = button.getAttribute('data-session-id');
            const movieTitle = button.getAttribute('data-session-movie');

            deleteSessionForm.action = `/admin-panel/sessions/${sessionId}`;
            sessionMovie.textContent = movieTitle;
        });


    });

    function showCinemas(cityId) {
        document.querySelectorAll('.cinema-group').forEach(group => {
            group.style.display = 'none';
        });
        document.getElementById(`city-${cityId}`).style.display = 'block';
    }

    function selectMovieForSession(movieId, movieTitle, movieDuration) {
        document.getElementById('selectedMovieId').value = movieId;
        document.getElementById('selectedMovieTitle').textContent = movieTitle;
        document.getElementById('selectedMovieDuration').textContent = `|  ${movieDuration} minutes`;
    }
</script>
