@extends('layouts.master')

@section('title', 'Sale Report')

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>S.no</th>
                        <th>Customer Name</th>
                        <th>Invoice No</th>
                        <th>Invoice Date(Recent)</th>
                        <th>Total Items</th>
                        <th>Total Eggs</th>
                        <th>Total Amount</th>
                        <th>Profit</th>
                        <th>Profit (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sale->customer_name }}</td>
                            <td>{{ $sale->inv_number }}</td>
                            <td>{{ $sale->invoice_date }}</td>
                            <td>{{ $sale->total_items }}</td>
                            <td>{{ $sale->eggscount }}</td>
                            <td>{{ $sale->total_amount }}</td>
                            <td>{{ $sale->profit }}</td>
                            <td>
                                {{ $sale->total_amount != 0 ? round(($sale->profit / $sale->total_amount) * 100, 2) : 0 }}
                                %
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
