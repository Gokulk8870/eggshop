@extends('layouts.master')
@section('title', 'Edit User Page')
@section('content')
    <div class="card">
        <div class="container">
            <h3>Edit User</h3>

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>
                            Employee
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    Update User
                </button>

                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    Back
                </a>
            </form>
        </div>
    </div>
@endsection
