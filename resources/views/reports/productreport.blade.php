@extends('layouts.master')

@section('title', 'Product Report')

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

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
