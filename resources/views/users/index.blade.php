@extends('layouts.master')
@section('title', 'Manage User')
@section('content')
    <div class="card">


        <div class="card-body">
            <div class="container d-flex align-items-end justify-content-end">


                @if (auth()->user()->role == 'admin')
                    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
                        Add User
                    </a>
                @endif
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('users.edit', $user->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-primary" onclick="return confirm('Delete user?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
