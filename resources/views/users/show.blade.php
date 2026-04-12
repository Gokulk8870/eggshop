@extends('layouts.master')

@section('content')
<div class="container">
    <h3>User Details</h3>

    <div class="card">
        <div class="card-body">

            <div class="mb-3">
                <strong>Name:</strong>
                <p>{{ $user->name }}</p>
            </div>

            <div class="mb-3">
                <strong>Email:</strong>
                <p>{{ $user->email }}</p>
            </div>

            <div class="mb-3">
                <strong>Role:</strong>
                <p>{{ ucfirst($user->role) }}</p>
            </div>

            <div class="mb-3">
                <strong>Created At:</strong>
                <p>{{ $user->created_at->format('d-m-Y h:i A') }}</p>
            </div>

            <a href="{{ route('users.edit', $user->id) }}"
               class="btn btn-warning">
                Edit User
            </a>

            <a href="{{ route('users.index') }}"
               class="btn btn-secondary">
                Back
            </a>

        </div>
    </div>
</div>
@endsection