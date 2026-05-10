@extends('layouts.master')
@section('title', 'Manage Purchase Invoice')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css"> --}}
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
                <div class="col-md-12 d-flex justify-content-end align-items-end">
                    <a href="{{ route('purchaseinvoices.create') }}" class="btn btn-primary fw-bold">
                        Create Purchase Invoice </a>
                </div>
            </div>

            <form action="{{ route('purchaseinvoices.index') }}" method="GET">
                <div class="row align-items-end mb-3">

                    <div class="col-md-3">
                        <label>Supplier Name</label>
                        <input type="text" name="supplier_name" list="supplier_list" autocomplete="off"
                            class="form-control">

                        <datalist id="supplier_list">
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_name }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="col-md-3">
                        <label>Phone Number</label>
                        <input type="text" name="phno" list="phno_list" class="form-control">

                        <datalist id="phno_list">
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->phno }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="col-md-3">
                        <label>Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="">Select</option>
                            @foreach ($paymentMethods as $paymethod)
                                <option value="{{ $paymethod }}">{{ $paymethod }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>
                    </div>
                </div>

            </form>



            <table class="table table-bordered" id="purchaseinvoice">
                <thead class=" table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Invoice No</th>
                        <th>Supplier Name</th>
                        <th>Phone</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($purchaseInvoices as $purchaseinvoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $purchaseinvoice->inv_number }}</td>
                            <td>{{ $purchaseinvoice->supplier_name }}</td>
                            <td>{{ $purchaseinvoice->phno }}</td>
                            <td>{{ $purchaseinvoice->total_price }}</td>
                            <td>
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('purchaseinvoices.show', $purchaseinvoice->id) }}"><i
                                            class="fas fa-eye"></i></a>
                                    <a href="{{ route('purchaseinvoice.bill', $purchaseinvoice->id) }}">
                                        <i class="fas fa-receipt"></i></a>
                                    <a href="{{ route('purchaseinvoices.edit', $purchaseinvoice->id) }}"><i
                                            class="fas fa-edit"></i></a>
                                    <form action="{{ route('purchaseinvoices.destroy', $purchaseinvoice->id) }}"
                                        method="POST" onsubmit="return confirm('Delete this invoice?')" class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger">
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
    <!-- 1. jQuery -->
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
            $('#purchaseinvoice').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthMenu: [5, 10, 25, 50],

                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print',
                    'colvis'
                ],

                // ✅ CORRECT PLACE

            });
        });
    </script>
@endpush
