@extends('layouts.master')
@section('title', 'Show Sales Control Page')
@section('content')

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Prefix </th>
                    <td>{{ $salesclt->prefix }}</td>

                </tr>
                <tr>
                    <th>Suffix</th>
                    <td>{{ $salesclt->suffix }}</td>
                </tr>
                <tr>
                    <th>Year</th>
                    <td>{{ $salesclt->year }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{{ $salesclt->status }}</td>
                </tr>

            </table>
        </div>
    </div>
@endsection
