@extends('layouts.master')

@section('title', 'Tray Shown Page')
@section('content')

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th>Tray Color</th>
                    <td>{{ $tray->tcolor }}</td>
                </tr>

                <tr>
                    <th>Quantity</th>
                    <td>{{ $tray->quantity }}</td>
                </tr>

                <tr>
                    <th>Created At</th>
                    <td>{{ $tray->created_at }}</td>
                </tr>

                <tr>
                    <th>Updated At</th>
                    <td>{{ $tray->updated_at }}</td>
                </tr>

            </table>

            <div class="mt-3">
                <a href="{{ route('trays.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('trays.edit', $tray->id) }}" class="btn btn-primary">Edit</a>
            </div>

        </div>
    </div>

@endsection
