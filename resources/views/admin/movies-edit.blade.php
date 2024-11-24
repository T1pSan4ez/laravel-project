@extends('layouts.main')
@section('title', 'Edit Movie')
@section('content')
    <div class="container mt-3">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input
                    type="text"
                    class="form-control @error('title') is-invalid @enderror"
                    id="title"
                    name="title"
                    value="{{ old('title', $movie->title) }}"
                >
                @error('title')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea
                    class="form-control @error('description') is-invalid @enderror"
                    id="description"
                    name="description"
                >{{ old('description', $movie->description) }}</textarea>
                @error('description')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="duration" class="form-label">Duration (minutes)</label>
                <input
                    type="number"
                    class="form-control @error('duration') is-invalid @enderror"
                    id="duration"
                    name="duration"
                    value="{{ old('duration', $movie->duration) }}"
                >
                @error('duration')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="release_date" class="form-label">Release Date</label>
                <input
                    type="date"
                    class="form-control @error('release_date') is-invalid @enderror"
                    id="release_date"
                    name="release_date"
                    value="{{ old('release_date', $movie->release_date) }}"
                >
                @error('release_date')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="poster" class="form-label">Poster</label>
                <input
                    type="file"
                    class="form-control @error('poster') is-invalid @enderror"
                    id="poster"
                    name="poster"
                    accept="image/*"
                >
                @if($movie->poster)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $movie->poster) }}" alt="Movie Poster" style="width: 150px;">
                    </div>
                @endif
                @error('poster')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Movie</button>
            <a href="{{ route('movies') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
