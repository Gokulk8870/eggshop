@extends('layouts.app')

@push('styles')
    <style>
        body {
            overflow: hidden;
            background: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center">

        <div class="row w-100 justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">

                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="row g-0">

                        <!-- Left Image -->
                        <div class="col-md-5 d-none d-md-block">
                            <img src="{{ asset('images/egg-shop-login.webp') }}" class="img-fluid h-100 w-100"
                                style="object-fit: cover;">
                        </div>

                        <!-- Right Form -->
                        <div class="col-md-7 bg-white p-5 d-flex align-items-center">
                            <div class="w-100">

                                <h3 class="fw-bold mb-2 text-center">EGG SHOP</h3>
                                <p class="text-muted text-center mb-4">
                                    Welcome! Log in to your account.
                                </p>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror" required>

                                        @error('email')
                                            <span class="invalid-feedback d-block">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Password</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" required>

                                        @error('password')
                                            <span class="invalid-feedback d-block">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Remember -->
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" name="remember">
                                        <label class="form-check-label">Remember Me</label>
                                    </div>

                                    <!-- Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            Login
                                        </button>
                                    </div>

                                </form>

                                <div class="text-center mt-4 text-muted small">
                                    Powered By Egg Shop Management
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
