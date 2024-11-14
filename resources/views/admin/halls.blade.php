@extends('layouts.main')
@section('title', 'Halls for ' . $cinema->name)
@section('content')

    <div class="container mt-3">
        <h3>{{ $cinema->name }} - Halls</h3>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <h5>Add New Hall</h5>
            <form action="{{ route('halls.store', ['cinema_id' => $cinema->id]) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="hall_name" class="form-label">Hall Name</label>
                    <input type="text" class="form-control" id="hall_name" name="name" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Hall</button>
            </form>
        </div>

        <ul>
            @foreach($halls as $hall)
                <li class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('halls.edit', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}" style="text-decoration: none; color: black;">
                        {{ $hall->name }}
                    </a>
                    <form action="{{ route('halls.destroy', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this hall?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>

@endsection
