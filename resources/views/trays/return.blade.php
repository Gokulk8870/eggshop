@extends('layouts.master')
@section('title', 'Tray Returns Page')
@section('content')
    <div class="card">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('tray.return.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Customer</label>
                            <select name="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tray Color</label>
                            <select name="tray_id" class="form-control" required>
                                <option value="">Select Tray</option>
                                @foreach ($trays as $tray)
                                    <option value="{{ $tray->id }}" {{ old('tray_id') == $tray->id ? 'selected' : '' }}>
                                        {{ $tray->tcolor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}"
                                min="1" required>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-success">Submit Return</button>
                    <a href="{{ route('trays.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </div>
@endsection
