@extends('layouts.main')
@section('title', 'Manage Products')
@section('content')

    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="add-tab" href="#add" data-bs-toggle="tab">Add Product</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="list-tab" href="#list" data-bs-toggle="tab">Product List</a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="add">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" step="0.01" value="{{ old('price') }}">
                        @error('price') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>

            <div class="tab-pane fade" id="list">
                @if($products->isEmpty())
                    <div class="alert alert-warning mt-4">No products available.</div>
                @else
                    <table class="table table-bordered mt-3">
                        <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->price }} UAH</td>
                                <td>
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @endif
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
