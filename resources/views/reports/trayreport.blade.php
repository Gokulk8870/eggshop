@extends('layouts.master')

@section('title', 'Stock Report')

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="stockreport">
                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Tray Color</th>
                        <th>Tray In</th>
                        <th>Tray Out</th>
                        <th>Opening Tray</th>
                        <th>Close Tray</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trays as $tray)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tray->tcolor }}</td>
                            <td>{{ $tray->tray_in ?? 0 }}</td>
                            <td>{{ $tray->tray_out ?? 0 }}</td>
                            <td>{{ $tray->opening_tray ?? 0 }}</td>
                            <td>{{ $tray->closing_tray ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
