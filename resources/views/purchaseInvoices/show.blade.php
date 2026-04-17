@extends('layouts.master')

@section('title', 'View Purchase Invoice')
@section('page_title', 'View Purchase Invoice')

@section('content')

    <div class="card">
        <div class="card-body">

            <!-- 🔹 INVOICE INFO -->
            <div class="row mb-3">

                <div class="col-md-3">
                    <strong>Invoice No:</strong><br>
                    {{ $invoice->inv_number }}
                </div>

                <div class="col-md-3">
                    <strong>Supplier Name:</strong><br>
                    {{ $invoice->supplier_name }}
                </div>

                <div class="col-md-3">
                    <strong>Phone:</strong><br>
                    {{ $invoice->phno }}
                </div>

                <div class="col-md-3">
                    <strong>Date:</strong><br>
                    {{ $invoice->invoice_date }}
                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-3">
                    <strong>Payment:</strong><br>
                    {{ $invoice->payment_method }}
                </div>

                <div class="col-md-3">
                    <strong>Total Amount:</strong><br>
                    <span class="text-primary fw-bold">
                        ₹ {{ number_format($invoice->total_price, 2) }}
                    </span>
                </div>

                <div class="col-md-3">
                    <strong>Tray Used:</strong><br>
                    {{ $invoice->tray_need == 'yes' ? 'Yes' : 'No' }}
                </div>

                <div class="col-md-3">
                    <strong>Tray Color:</strong><br>
                    {{ $invoice->tray->tcolor ?? '-' }}
                </div>

            </div>

            <hr>

            <!-- 🔹 ITEMS TABLE -->
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Tray Qty</th>
                        <th>Eggs</th>
                        <th>Purchase Price</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $grandTotal = 0;
                    @endphp

                    @forelse ($invoice->items as $index => $item)
                        @php
                            $grandTotal += $item->total;
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                {{ $item->product->product_name ?? 'N/A' }}
                            </td>

                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->eggs }}</td>

                            <td>₹ {{ number_format($item->purchase_price, 2) }}</td>
                            <td>₹ {{ number_format($item->total, 2) }}</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">
                                No Items Found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Grand Total</th>
                        <th>₹ {{ number_format($grandTotal, 2) }}</th>
                    </tr>
                </tfoot>

            </table>

            <!-- 🔹 ACTIONS -->


        </div>
    </div>

@endsection
