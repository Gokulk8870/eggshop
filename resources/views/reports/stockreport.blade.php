@extends('layouts.master')

@section('title', 'Stock Report')

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="stockreport">
                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Product Name</th>
                        <th>Stock In</th>
                        <th>Stock Out</th>
                        <th>Opening Stock</th>
                        <th>Close Stock</th>
                        <th>Tray Color</th>
                        <th>Tray In</th>
                        <th>Tray Out</th>
                        <th>Opening Tray</th>
                        <th>Close Tray</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->stock_in ?? 0 }}</td>
                            <td>{{ $product->stock_out ?? 0 }}</td>
                            <td>{{ $product->opening_stock ?? 0 }}</td>
                            <td>{{ $product->closing_stock ?? 0 }}</td>
                    @endforeach
                    @foreach ($trays as $tray)
                        <td>{{ $tray->tcolor }}</td>
                        <td>{{ $tray->tray_in ?? 0 }}</td>
                        <td>{{ $tray->tray_out ?? 0 }}</td>
                        <td>{{ $tray->opening_tray ?? 0 }}</td>
                        <td>{{ $tray->closing_tray ?? 0 }}</td>
                    @endforeach
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
@endsection
