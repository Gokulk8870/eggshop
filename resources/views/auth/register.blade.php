@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="row g-0">

                    <!-- Left Side Image -->
                    <div class="col-md-6 d-none d-md-block">
                        <img src="{{ asset('images/egg-shop-register.jpg') }}"
                             alt="Egg Shop Register"
                             class="img-fluid h-100 w-100"
                             style="object-fit: cover;">
                    </div>

                    <!-- Right Side Register Form -->
                    <div class="col-md-6 bg-white p-5 d-flex align-items-center">
                        <div class="w-100">

                            <h2 class="fw-bold mb-2 text-dark">EGG SHOP ERP</h2>
                            <p class="text-muted mb-4">
                                Create your account.
                            </p>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Name
                                    </label>
                                    <input id="name"
                                           type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           autocomplete="name"
                                           autofocus>

                                    @error('name')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        Email Address
                                    </label>
                                    <input id="email"
                                           type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="role" class="form-label fw-semibold">
                                        Select Role
                                    </label>
                                    <select name="role"
                                            id="role"
                                            class="form-control @error('role') is-invalid @enderror"
                                            required>
                                        <option value="">Choose Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="employee">Employee</option>
                                    </select>

                                    @error('role')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        Password
                                    </label>
                                    <input id="password"
                                           type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           name="password"
                                           required
                                           autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="password-confirm" class="form-label fw-semibold">
                                        Confirm Password
                                    </label>
                                    <input id="password-confirm"
                                           type="password"
                                           class="form-control"
                                           name="password_confirmation"
                                           required
                                           autocomplete="new-password">
                                </div>

                                <!-- Register Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        Register
                                    </button>
                                </div>

                            </form>

                            <div class="text-center mt-4 text-muted">
                                Powered By Egg Shop Management
                            </div>

                        </div>
                    </div>
                    <!-- End Right Side -->

                </div>
            </div>
        </div>

    </div>
</div>
@endsection