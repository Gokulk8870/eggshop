@extends('layouts.master')
@section('title', 'Create Tray')
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('trays.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-4">
                        <label>Tray Color</label>
                        <input type="text" name="tcolor" class="form-control" value="{{ old('tcolor') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label>Entry Type</label>
                        <select name="type" class="form-control" required>
                            <option value="">Select</option>
                            <option value="return">Stock In</option>
                            <option value="damage">Damage</option>
                        </select>
                    </div>

                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Save Tray</button>
                    <a href="{{ route('trays.index') }}" class="btn btn-secondary">Back</a>
                </div>

            </form>

        </div>
    </div>

@endsection
