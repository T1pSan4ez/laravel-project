@extends('layouts.main')
@section('title', 'Manage Movies')
@section('content')

    <div class="container mt-3">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" id="add-tab" href="#add" data-bs-toggle="tab">Add Movie</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="search-tab" href="#search" data-bs-toggle="tab">Search Movies</a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="add">
                <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control  @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                        @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control  @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration</label>
                        <input type="number" class="form-control  @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}">
                        @error('duration') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="release_date" class="form-label">Release Date</label>
                        <input type="date" class="form-control  @error('release_date') is-invalid @enderror" id="release_date" name="release_date" value="{{ old('release_date') }}">
                        @error('release_date') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="age_rating" class="form-label">Age Rating</label>
                        <input type="text" class="form-control @error('age_rating') is-invalid @enderror" id="age_rating" name="age_rating" value="{{ old('age_rating') }}">
                        @error('age_rating') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="genres" class="form-label">Genres</label>
                        <select class="form-control @error('genres') is-invalid @enderror" id="genres" name="genres[]" multiple>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" {{ collect(old('genres'))->contains($genre->id) ? 'selected' : '' }}>{{ $genre->name }}</option>
                            @endforeach
                        </select>
                        @error('genres') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="poster" class="form-label">Poster</label>
                        <input type="file" class="form-control  @error('poster') is-invalid @enderror" id="poster" name="poster" accept="image/*">
                        @error('poster') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Add Movie</button>
                </form>
            </div>

            <div class="tab-pane fade" id="search">
                <div>
                    <form action="{{ route('movies.search') }}" method="GET" class="mb-4">
                        <div class="mb-3">
                            <label for="search" class="form-label">Search Movie by Title</label>
                            <input type="text" class="form-control" id="search" name="query" placeholder="Enter movie title" value="{{ $query ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>

                <div>
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
                                    <td class="px-3 py-2">{{ Str::limit($movie->description, 75, '...') }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('movies.destroy', $movie->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this movie?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $movies->links('pagination::bootstrap-5') }}
                        </div>
                    @elseif(isset($query))
                        <div class="alert alert-warning mt-4">No movies found for "{{ $query }}"</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.nav-link');
        let activeTab = localStorage.getItem('activeTab');

        if (!activeTab) {
            activeTab = 'add-tab';
        }

        const activeTabLink = document.querySelector(`#${activeTab}`);
        const activeTabPane = document.querySelector(activeTabLink.getAttribute('href'));

        tabs.forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(tabPane => tabPane.classList.remove('show', 'active'));

        activeTabLink.classList.add('active');
        activeTabPane.classList.add('show', 'active');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                localStorage.setItem('activeTab', this.id);
            });
        });
    });
</script>

<style>
    table {
        table-layout: fixed;
        width: 100%;
    }
    th, td {
        word-wrap: break-word;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
    th {
        width: 33.33%;
    }
    td {
        max-width: 75px;
    }
</style>
