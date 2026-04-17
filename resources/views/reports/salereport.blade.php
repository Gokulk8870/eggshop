@extends('layouts.master')

@section('title', 'Sale Report')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- <form action="{{ url('/reports/salereport') }}" method="get">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cnmae">Customer Name</label>
                            <input type="text" name="customer_name" id="cname" list="cnamelist"
                                value="{{ old('customer_name') }}" class="form-control">
                            <datalist id="cnamelist">
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->customer_name }}">{{ $customer->customer_name }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"> <i class="fas fa-filter"></i> Filter</button>
                        </div>
                    </div>
                </div>

            </form> --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Eggs Sold</h6>
                            <h4>{{ $sales->sum('eggscount') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Sales</h6>
                            <h4>₹ {{ $sales->sum('total_amount') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h6>Total Profit</h6>
                            <h4>₹ {{ $sales->sum('profit') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Avg Profit %</h6>
                            <h4>
                                {{ $sales->sum('total_amount') > 0
                                    ? number_format(($sales->sum('profit') / $sales->sum('total_amount')) * 100, 2)
                                    : 0 }}
                                %
                            </h4>
                        </div>
                    </div>
                </div>

            </div>
            <table class="table table-bordered"id="salereport">
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
            $('#salereport').DataTable({
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
