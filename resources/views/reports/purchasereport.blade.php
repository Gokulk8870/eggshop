@extends('layouts.master')
@section('title', 'Purchase Report')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sname">Supplier Name</label>
                            <input type="text" id="sname" name="supplier_name" list="supplierlist"
                                class="form-control" autocomplete="off" value="{{ request('supplier_name') }}">
                            <datalist id="supplierlist">
                                @foreach ($supplierlist as $supplier)
                                    <option value="{{ $supplier->supplier_name }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pmethod">Payment Method</label>
                            <select name="payment_method" id="pmethod" class="form-control">
                                <option value="">---</option>
                                @foreach ($paymentmods as $paymenttype)
                                    <option value="{{ $paymenttype->payment_method }}">{{ $paymenttype->payment_method }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Date By Filter</label>
                            <select name="date_filter" class="form-control">
                                <option value="">-- Select --</option>

                                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today
                                </option>

                                <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>
                                    Yesterday</option>

                                <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This
                                    Week</option>

                                <option value="last_week" {{ request('date_filter') == 'last_week' ? 'selected' : '' }}>
                                    Last
                                    Week</option>

                                <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>
                                    This
                                    Year</option>

                                <option value="last_year" {{ request('date_filter') == 'last_year' ? 'selected' : '' }}>
                                    Last
                                    Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row mb-3">

                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Purchase</h6>
                            <h4>₹ {{ $purchases->sum('total_amount') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Items</h6>
                            <h4>{{ $purchases->sum('total_items') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h6>Total Eggs</h6>
                            <h4>{{ $purchases->sum('eggscount') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <h6>Total Invoices</h6>
                            <h4>{{ $purchases->count() }}</h4>
                        </div>
                    </div>
                </div>

            </div>
            <table class="table table-bordered" id="purchasereport">
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
            $('#purchasereport').DataTable({
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
