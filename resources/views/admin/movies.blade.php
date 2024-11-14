@extends('layouts.main')
@section('title', 'Movies')
@section('content')
    <div class="container mt-3">
        <h2>Add New Movie</h2>

        <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="duration" class="form-label">Duration (in minutes)</label>
                <input type="number" class="form-control" id="duration" name="duration" required>
            </div>

            <div class="mb-3">
                <label for="age_rating" class="form-label">Age Rating</label>
                <input type="text" class="form-control" id="age_rating" name="age_rating">
            </div>

            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre">
            </div>

            <div class="mb-3">
                <label for="release_date" class="form-label">Release Date</label>
                <input type="date" class="form-control" id="release_date" name="release_date">
            </div>

            <button type="submit" class="btn btn-primary">Add Movie</button>
        </form>
    </div>
@endsection

