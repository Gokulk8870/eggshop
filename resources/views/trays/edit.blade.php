@extends('layouts.master')
@section('title', 'Edit Tray')
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

            <form action="{{ route('trays.update', $tray->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tcolor">Tray Color</label>
                            <input type="text" name="tcolor" class="form-control"
                                value="{{ old('tcolor', $tray->tcolor) }}" required>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="{{ $tray->quantity }}" required>
                        <small class="text-muted">Updating this will adjust stock automatically</small>
                    </div>



                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Tray</button>
                    <a href="{{ route('trays.index') }}" class="btn btn-secondary">Back</a>
                </div>

            </form>

        </div>
    </div>

@endsection
