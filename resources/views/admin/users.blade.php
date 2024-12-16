@extends('layouts.main')
@section('title', 'Users')
@section('content')
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($users->isNotEmpty())
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 30%;">Name</th>
                    <th style="width: 40%;">Email</th>
                    <th style="width: 10%;">User Type</th>
                    <th style="width: 15%;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->user_type }}</td>
                        <td>
                            @if(auth()->user()->user_type === 'super_admin' || (auth()->user()->user_type === 'admin' && $user->user_type === 'user'))
                                <form action="{{ route('users.updateType', $user->id) }}" method="POST">
                                    @csrf
                                    <select name="user_type" class="form-select form-select-sm d-inline-block w-auto">
                                        <option value="user" {{ $user->user_type === 'user' ? 'selected' : '' }}>User</option>
                                        @if(auth()->user()->user_type === 'super_admin')
                                            <option value="admin" {{ $user->user_type === 'admin' ? 'selected' : '' }}>Admin</option>
                                        @endif
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            @endif
                        </td>
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

<style>
    .fixed-table th, .fixed-table td {
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
