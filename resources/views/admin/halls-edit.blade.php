@extends('layouts.main')
@section('title', 'Edit Hall')
@section('content')

    <div class="container mt-3">
        <h3>Edit Hall - {{ $hall->name }}</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('bookedSlots'))
            <div class="alert alert-danger">
                <p>Some seats are booked and cannot be deleted:</p>
                <ul>
                    @foreach(session('bookedSlots') as $seat)
                        <li>Row {{ $seat['row'] }}, Seat {{ $seat['number'] }} (ID: {{ $seat['id'] }})</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if(session('confirm'))
            <div class="alert alert-warning">
                <p>Some seats are booked. Are you sure you want to remove all seats, including booked ones? This action
                    cannot be undone.</p>
                <form method="POST"
                      action="{{ route('halls.clearSeats', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}">
                    @csrf
                    <input type="hidden" name="confirm" value="true">
                    <button type="submit" class="btn btn-danger">Confirm and Clear All</button>
                    <a href="{{ route('halls.edit', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}"
                       class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-4">
                <h5>Hall parameters:</h5>
                <form method="GET"
                      action="{{ route('halls.edit', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}">
                    <div class="row g-3">
                        <div class="col-auto">
                            <label for="rows" class="form-label">Rows:</label>
                            <input type="number" class="form-control" id="rows" name="rows" value="{{ $rows }}"
                                   min="{{ $minRows }}" max="{{ $maxRows }}">
                        </div>
                        <div class="col-auto">
                            <label for="columns" class="form-label">Columns:</label>
                            <input type="number" class="form-control" id="columns" name="columns" value="{{ $columns }}"
                                   min="{{ $minColumns }}" max="{{ $maxColumns }}">
                        </div>
                        <div class="col-auto align-self-end">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <h5>Base prices:</h5>
                <form id="basePriceForm" class="form-inline d-flex align-items-center">
                    <div class="row g-3">
                        <div class="col-auto">
                            <label for="baseStandardPrice" class="form-label">Standard Price:</label>
                            <input type="number" class="form-control" id="baseStandardPrice" value="150" min="0"
                                   step="0.01" placeholder="Standard Price">
                        </div>
                        <div class="col-auto">
                            <label for="baseVipPrice" class="form-label">VIP Price:</label>
                            <input type="number" class="form-control" id="baseVipPrice" value="250" min="0" step="0.01"
                                   placeholder="VIP Price">
                        </div>
                        <div class="col-auto align-self-end">
                            <button type="button" id="vipModeButton" class="btn btn-secondary mr-2"
                                    onclick="toggleVipMode()">Enable VIP Mode
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-2">
                <br>
                <br>
                <form method="POST"
                      action="{{ route('halls.clearSeats', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger mt-3">
                        Clear hall (all seats)
                    </button>
                </form>
            </div>
        </div>

        <div class="screen text-center mb-4">
            <hr>
            <h4>Screen</h4>
        </div>

        <form method="POST"
              action="{{ route('halls.updateSeats', ['cinema_id' => $cinema->id, 'hall_id' => $hall->id]) }}">
            @csrf
            <div class="seating-container">
                <div class="grid-container"
                     style="grid-template-columns: repeat({{ $columns }}, 40px); grid-template-rows: repeat({{ $rows }}, 40px);">
                    @for ($row = 1; $row <= $rows; $row++)
                        @for ($number = 1; $number <= $columns; $number++)
                            <div
                                class="seat available {{ $seatStyles["{$row}-{$number}"] ?? '' }}"
                                onclick="toggleSelection(this, {{ $row }}, {{ $number }})"
                            >
                                {{ $row }}-{{ $number }}
                            </div>
                        @endfor
                    @endfor
                </div>
            </div>

            <input type="hidden" name="selected_seats" id="selectedSeats" value="{{ json_encode($selectedSeats) }}">

            <div class="text-center mt-4">
                <button type="button" class="btn btn-warning" onclick="selectAllSeats()">Select all seats</button>
                <button type="submit" class="btn btn-success">Save selected seats</button>
            </div>
        </form>
    </div>

@endsection

<script>
    let selectedSeats = @json($selectedSeats);
    let isVipMode = false;

    function toggleVipMode() {
        isVipMode = !isVipMode;

        const vipButton = document.getElementById("vipModeButton");
        vipButton.classList.toggle("active", isVipMode);

        if (isVipMode) {
            vipButton.textContent = "Disable VIP mode";
        } else {
            vipButton.textContent = "Enable VIP mode";
        }
    }

    function toggleSelection(element, row, number) {
        const seatIndex = selectedSeats.findIndex(seat => seat.row === row && seat.number === number);

        if (seatIndex > -1) {
            selectedSeats.splice(seatIndex, 1);
            element.classList.remove('selected', 'standard', 'vip');
        } else {
            const seatData = {
                row: row,
                number: number,
                type: isVipMode ? 'vip' : 'standard',
                price: isVipMode ? parseFloat(document.getElementById("baseVipPrice").value) || 250
                    : parseFloat(document.getElementById("baseStandardPrice").value) || 150
            };
            selectedSeats.push(seatData);

            element.classList.add('selected', seatData.type);
        }

        document.getElementById('selectedSeats').value = JSON.stringify(selectedSeats);
    }

    function selectAllSeats() {
        const rows = {{ $rows }};
        const columns = {{ $columns }};
        const allSeats = Array.from(document.querySelectorAll('.seat'));

        const allSelected = allSeats.every(seat => seat.classList.contains('selected'));

        if (allSelected) {
            selectedSeats = [];
            allSeats.forEach(seat => seat.classList.remove('selected', 'vip', 'standard'));
        } else {
            selectedSeats = [];
            for (let row = 1; row <= rows; row++) {
                for (let number = 1; number <= columns; number++) {
                    const seatElement = document.querySelector(`.seat[onclick="toggleSelection(this, ${row}, ${number})"]`);

                    if (seatElement && !seatElement.classList.contains('selected')) {
                        seatElement.classList.add('selected', isVipMode ? 'vip' : 'standard');
                    }

                    selectedSeats.push({
                        row: row,
                        number: number,
                        type: isVipMode ? 'vip' : 'standard',
                        price: isVipMode ? parseFloat(document.getElementById("baseVipPrice").value) || 250
                            : parseFloat(document.getElementById("baseStandardPrice").value) || 150
                    });
                }
            }
        }

        document.getElementById('selectedSeats').value = JSON.stringify(selectedSeats);
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

    .seat.standard {
        background-color: #2e8ef6;
        color: white;
    }

    .seat.vip {
        background-color: yellow;
    }
</style>
