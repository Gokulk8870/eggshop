@extends('layouts.master')
@section('title', 'Purchase Report')
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>S.no</th>
                        <th>Supplier Name</th>
                        <th>Inv Number</th>
                        <th>Invoice Date</th>
                        <th>Total Amount</th>
                        <th>Total Items</th>
                        <th>Eggs Count </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $purchase->supplier_name }}</td>
                            <td>{{ $purchase->inv_number }}</td>
                            <td>{{ $purchase->invoice_date }}</td>
                            <td>{{ $purchase->total_amount }}</td>
                            <td>{{ $purchase->total_items }}</td>
                            <td>{{ $purchase->eggscount }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
