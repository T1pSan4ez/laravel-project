@extends('layouts.main')
@section('title', 'Generate PDF Report')
@section('content')

    <div class="container mt-3">
        <form id="pdf-form" action="{{ route('pdf.generate') }}" method="POST" target="_blank">
            @csrf
            <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
            <div class="mb-3">
                <label for="is_user" class="form-label">User Type</label>
                <select class="form-control" id="is_user" name="is_user">
                    <option value="">All</option>
                    <option value="user">Users</option>
                    <option value="guest">Guests</option>
                </select>
            </div>

            <button type="button" class="btn btn-secondary" onclick="previewPDF()">Preview</button>
            <button type="submit" class="btn btn-primary">Generate PDF</button>
        </form>


        <div class="mt-5">
            <h3>Preview:</h3>
            <table class="table table-bordered" id="preview-table" style="display: none;">
                <thead>
                <tr>
                    <th>Purchase Code</th>
                    <th>User ID</th>
                    <th>Total Items</th>
                    <th>Total Cost</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        function previewPDF() {
            const formData = new FormData(document.getElementById('pdf-form'));
            fetch('{{ route('pdf.preview') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    const table = document.getElementById('preview-table');
                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';
                    data.purchases.forEach(purchase => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${purchase.purchase_code || 'N/A'}</td>
                        <td>${purchase.user_id || 'Guest'}</td>
                        <td>${purchase.items.length}</td>
                        <td>${purchase.items.reduce((sum, item) => sum + (item.quantity * item.price), 0).toFixed(2)} UAH</td>
                    `;
                        tbody.appendChild(row);
                    });
                    table.style.display = 'table';
                });
        }
    </script>

@endsection
