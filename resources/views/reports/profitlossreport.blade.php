@extends('layouts.master')

@section('title', 'Profit & Loss Report')

@section('content')

    <div class="card">
        <div class="card-body">

            <h4 class="mb-3">Monthly Profit & Loss Report</h4>

            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Month</th>
                        <th>Sales</th>
                        <th>Purchase</th>
                        <th>Expenses</th>
                        <th>Profit</th>
                        <th>Profit Percentage</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($report as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($row->month . '-01')->format('F Y') }}
                            </td>

                            <td>₹ {{ number_format($row->total_sales, 2) }}</td>
                            <td>₹ {{ number_format($row->total_purchase, 2) }}</td>
                            <td>₹ {{ number_format($row->total_expenses, 2) }}</td>

                            <td>
                                <b class="{{ $row->profit < 0 ? 'text-danger' : 'text-success' }}">
                                    ₹ {{ number_format($row->profit, 2) }}
                                </b>
                            </td>
                            <td>
                                {{ $row->total_sales > 0 ? number_format(($row->profit / $row->total_sales) * 100, 2) : 0 }}
                                %
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

@endsection
