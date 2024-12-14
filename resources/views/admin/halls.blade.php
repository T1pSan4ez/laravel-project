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

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-4">
            <h5>Add new hall</h5>
            <form action="{{ route('halls.store', ['cinema_id' => $cinema->id]) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="hall_name" class="form-label">Hall name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="hall_name" name="name" value="{{ old('name') }}">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
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
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                            data-cinema-id="{{ $cinema->id }}" data-hall-id="{{ $hall->id }}" data-hall-name="{{ $hall->name }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the hall <strong id="hallName"></strong>? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" id="deleteHallForm" action="">
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
        const confirmDeleteModal = document.getElementById('confirmDeleteModal');
        const deleteHallForm = document.getElementById('deleteHallForm');
        const hallNameElement = document.getElementById('hallName');

        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const cinemaId = button.getAttribute('data-cinema-id');
            const hallId = button.getAttribute('data-hall-id');
            const hallName = button.getAttribute('data-hall-name');

            deleteHallForm.action = `/admin-panel/cinema/${cinemaId}/halls/${hallId}`;
            hallNameElement.textContent = hallName;
        });
    });
</script>
