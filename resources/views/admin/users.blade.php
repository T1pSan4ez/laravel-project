@extends('layouts.main')
@section('title', 'Users')
@section('content')
    <div class="container mt-3">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($users->isNotEmpty())
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="alert alert-warning">No users found.</div>
        @endif
    </div>
@endsection
