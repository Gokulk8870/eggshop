@extends('layouts.master')

@section('title', 'Profit & Loss Report')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">

                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Sales</h6>
                            <h4>₹ {{ number_format(array_sum(array_column($report, 'total_sales')), 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Purchase</h6>
                            <h4>₹ {{ number_format(array_sum(array_column($report, 'total_purchase')), 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h6>Total Expenses</h6>
                            <h4>₹ {{ number_format(array_sum(array_column($report, 'total_expenses')), 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    @php
                        $totalProfit = array_sum(array_column($report, 'profit'));
                    @endphp

                    <div class="card {{ $totalProfit >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                        <div class="card-body">
                            <h6>Net Profit / Loss</h6>
                            <h4>₹ {{ number_format($totalProfit, 2) }}</h4>
                        </div>
                    </div>
                </div>

            </div>

            <h4 class="mb-3">Monthly Profit & Loss Report</h4>

            <table class="table table-bordered" id="profitlosstable">
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
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- 2. DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    {{-- <!-- 3. Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script> --}}

    <!-- 4. Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <!-- 5. Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script>
        $(document).ready(function() {
            $('#profitlosstable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print',
                    'colvis'
                ],
            });
        });
    </script>
@endpush
