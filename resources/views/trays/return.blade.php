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
            <div class="mt-3">
                <table class="table table-bordered">
                    <thead class="table table-info">
                        <tr>
                            <th>S.no</th>
                            <th>Customer</th>
                            <th>Tray</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer_list as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->customer->name ?? 'N/A' }}</td>
                                <td>{{ $item->tray->tcolor ?? 'N/A' }}</td>
                                <td>{{ $item->total_quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
