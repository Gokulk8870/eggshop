@extends('layouts.master')
@section('title', 'View Sales Invoice')
@section('content')

    <div class="card">
        <div class="card-body">

            <!-- 🔹 salesInvoice INFO -->

            <div class="row mb-3">


                <div class="col-md-3">
                    <strong>salesInvoice No:</strong><br>
                    {{ $salesInvoice->inv_number }}
                </div>

                <div class="col-md-3">
                    <strong>Customer Name:</strong><br>
                    {{ $salesInvoice->customer_name }}
                </div>

                <div class="col-md-3">
                    <strong>Phone:</strong><br>
                    {{ $salesInvoice->phno }}
                </div>

                <div class="col-md-3">
                    <strong>Date:</strong><br>
                    {{ $salesInvoice->invoice_date }}
                </div>


            </div>

            <div class="row mb-3">


                <div class="col-md-3">
                    <strong>Payment:</strong><br>
                    {{ $salesInvoice->payment_method }}
                </div>

                <div class="col-md-3">
                    <strong>Total Amount:</strong><br>
                    <span class="text-primary fw-bold">₹ {{ $salesInvoice->total_price }}</span>
                </div>

                <div class="col-md-3">
                    <strong>Tray Used:</strong><br>
                    {{ $salesInvoice->tray_need == 'yes' ? 'Yes' : 'No' }}
                </div>

                <div class="col-md-3">
                    <strong>Tray Color:</strong><br>
                    {{ $salesInvoice->tray ? $salesInvoice->tray->tcolor : '-' }}
                </div>


            </div>

            <hr>

            <!-- 🔹 ITEMS TABLE -->

            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Tray Qty</th>
                        <th>Eggs</th>
                        <th>Sale Price</th>
                        <th>Total</th>
                        <th>Profit</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $totalProfit = 0;
                    @endphp

                    @foreach ($salesInvoice->items as $index => $item)
                        @php
                            $totalProfit += $item->profit;
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->size }}</td>
                            <td>{{ $item->color }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->eggs }}</td>
                            <td>₹ {{ number_format($item->sale_price, 2) }}</td>
                            <td>₹ {{ number_format($item->total, 2) }}</td>
                            <td class="text-success">₹ {{ number_format($item->profit, 2) }}</td>
                        </tr>
                    @endforeach

                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end">Grand Total</th>
                        <th>₹ {{ number_format($salesInvoice->total_price, 2) }}</th>
                        <th class="text-success">₹ {{ number_format($totalProfit, 2) }}</th>
                    </tr>
                </tfoot>

            </table>

            <!-- 🔹 ACTIONS -->

            {{-- <div class="mt-3">


                <a href="{{ route('salessalesInvoices.index') }}" class="btn btn-secondary">
                    Back
                </a>

                <a href="{{ route('salessalesInvoices.edit', $salesInvoice->id) }}" class="btn btn-warning">
                    Edit
                </a>

                <button onclick="window.print()" class="btn btn-success">
                    Print
                </button> --}}


        </div>

    </div>
    </div>

@endsection
