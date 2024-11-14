@extends('layouts.main')
@section('title', 'Edit Hall')
@section('content')

    <div class="container mt-3">
        <h3>Edit Hall - {{ $hall->name }}</h3>

        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" action="{{ route('halls.edit', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}">
                    <div class="row g-3">
                        <div class="col-auto">
                            <label for="rows" class="form-label">Rows:</label>
                            <input type="number" class="form-control" id="rows" name="rows" value="{{ $rows }}" min="1" max="20">
                        </div>
                        <div class="col-auto">
                            <label for="columns" class="form-label">Columns:</label>
                            <input type="number" class="form-control" id="columns" name="columns" value="{{ $columns }}" min="1" max="20">
                        </div>
                        <div class="col-auto align-self-end">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <form method="POST" action="{{ route('halls.clearSeats', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}" onsubmit="return confirmDelete()">
                    @csrf
                    <button type="submit" class="btn btn-danger mt-3">Clear hall (all seats)</button>
                </form>
            </div>
        </div>

        <div class="screen text-center mb-4">
            <hr>
            <h4>Screen</h4>
        </div>

        <div class="seating-container">
            <div class="grid-container" style="grid-template-columns: repeat({{ $columns }}, 40px); grid-template-rows: repeat({{ $rows }}, 40px);">
                @foreach($seats as $seat)
                    <div class="seat {{ $seat->is_available ? 'available' : 'unavailable' }}"
                         onclick="toggleSelection(this)">
                        {{ $seat->row }}-{{ $seat->number }}
                    </div>
                @endforeach
            </div>
        </div>

    </div>

@endsection

<script>
    function toggleSelection(element) {
        element.classList.toggle('selected');
    }

    function confirmDelete() {
        return confirm("Are you sure you want to clear all seats? This action is irreversible.");
    }
</script>

<style>
    .seating-container {
        display: flex;
        justify-content: center;
        margin: 20px auto;
    }

    .grid-container {
        display: grid;
        gap: 5px;
        justify-content: center;
    }

    .seat {
        width: 40px;
        height: 40px;
        border: 2px solid #007bff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .seat.available {
        background-color: white;
    }

    .seat.unavailable {
        background-color: #ddd;
        border-color: #aaa;
        color: #aaa;
    }

    .seat.selected {
        background-color: #f00;
        color: white;
        border-color: #f00;
    }
</style>
