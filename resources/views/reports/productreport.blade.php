@extends('layouts.master')

@section('title', 'Product Report')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ url('/reports/product') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pname">Product Name</label>
                            <input type="text" name="product_name" value="{{ request('product_name') }}"
                                class="form-control" list="productlist">

                            <datalist id="productlist">
                                @foreach ($productnames as $name)
                                    <option value="{{ $name->product_name }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center mt-4">
                        <div class="form-group">
                            <button type="submit" class="from-control btn btn-primary"><i class="fas fa-filter"></i>
                                Filter</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-success text-white p-3">
                        <h6>Total Stock In</h6>
                        <h4>{{ $products->sum('stock_in') }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-danger text-white p-3">
                        <h6>Total Stock Out</h6>
                        <h4>{{ $products->sum('stock_out') }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white p-3">
                        <h6>Opening Stock</h6>
                        <h4>{{ $products->sum('opening_stock') }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white p-3">
                        <h6>Closing Stock</h6>
                        <h4>{{ $products->sum('closing_stock') }}</h4>
                    </div>
                </div>

            </div>
            <table class="table table-bordered" id="productreport">
                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Product Name</th>
                        <th>Stock In</th>
                        <th>Stock Out</th>
                        <th>Opening Stock</th>
                        <th>Close Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->stock_in ?? 0 }}</td>
                            <td>{{ $product->stock_out ?? 0 }}</td>
                            <td>{{ $product->opening_stock ?? 0 }}</td>
                            <td>{{ $product->closing_stock ?? 0 }}</td>

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
            $('#productreport').DataTable({
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
