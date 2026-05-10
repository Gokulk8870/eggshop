@extends('layouts.master')
@section('title', 'Manage SalesInvoice')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush


@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <ul>
                    <li>{{ $error }}</li>
                </ul>
            @endforeach
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                @if (auth()->user()->role == 'employee' || auth()->user()->role == 'admin')
                    <div class="col-md-12 d-flex justify-content-end align-items-end">
                        <a href="{{ route('salesinvoices.create') }}" class="btn btn-primary fw-bold">
                            Create Sales Invoice
                        </a>
                    </div>
                @endif
            </div>
            <form action="{{ route('salesinvoices.index') }}" method="GET">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Customer Name</label>

                            <input type="text" name="customer_name" id="customer_name" class="form-control"
                                autocomplete="off" list="customer_list">

                            <datalist id="customer_list">
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->customer_name }}"></option>
                                @endforeach
                            </datalist>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Phno">phone Number</label>
                            <input type="text" list="phnolist" name="phno" id="phno" class="form-control"
                                autocomplete="off">
                            <datalist id="phnolist">

                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->phno }}"></option>
                                @endforeach
                            </datalist>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="payment">Payment Method</label>
                        <select name="payment_method" id="payment" class="form-control">

                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method }}">{{ $method }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <table class="table" id="sales_table">
                <thead class="table table-bordered table-info">
                    <tr>
                        <th>S.no</th>
                        <th>Invoice No</th>
                        <th>Customer Name</th>
                        <th>Phno</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>

                </thead>
                <tbody class="table table-bordered">

                    @foreach ($salesinvoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $invoice->inv_number }}</td>
                            <td>{{ $invoice->customer_name }}</td>
                            <td>{{ $invoice->phno }}</td>
                            <td>{{ $invoice->total_price }}</td>
                            <td>
                                @if (auth()->user()->role == 'employee' || auth()->user()->role == 'admin')
                                    <a href="{{ route('salesinvoices.show', $invoice->id) }}">
                                        <i class="fas fa-eye"></i></a>

                                    <a href="{{ route('salesinvoices.edit', $invoice->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- "" --}}

                                    <a href="{{ route('salesinvoice.bill', $invoice->id) }}">
                                        <i class="fas fa-receipt"></i>
                                    </a>

                                    <form action="{{ route('salesinvoices.destroy', $invoice->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
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
            $('#sales_table').DataTable({
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
    <script></script>
@endpush
