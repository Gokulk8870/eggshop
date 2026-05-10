@extends('layouts.master')
@section('title', 'Manage Supplier')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
                @if (auth()->user()->role == 'admin')
                    <div class="col-md-12 d-flex justify-content-end align-items-end">
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary fw-bold">
                            Add Supplier
                        </a>
                    </div>
                @endif

            </div>
            <form action="{{ route('suppliers.index') }}" method="get">
                <div class="row">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sname">Supplier Name</label>
                            <input type="text" id="sup_search" name="name" class="form-control"
                                value="{{ request('name') }}" list="supplier_list">
                            <ul class="list-group supplier_name_list  position-absolute w-100"></ul>
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="phno">Phone Number</label>
                            <input type="text" id="phno_search" name="phno" class="form-control"
                                value="{{ request('phno') }}" list="phno_list">
                            <ul class="list-group supplier_phno_list position-absolute w-100"></ul>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="staus">status</label>
                            <select name="status" id="status" class="form-control">

                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive"{{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i>Filter</button>
                            @if (request()->has('name') || request()->has('phno'))
                                <a href="{{ route('suppliers.index') }}" class="btn btn-danger">Clear Filter</a>
                            @endif
                        </div>
                    </div>



                </div>
            </form>

            <table class="table" id="supplier_table">
                <thead class="table table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Supplier Name</th>
                        <th>Phone Number</th>
                        <th>Staus</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table table-bordered">
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phno }}</td>
                            <td>{{ $supplier->status }}</td>
                            <td>
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('suppliers.show', $supplier->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="border-0 bg-transparent p-0"
                                            onclick="return confirm('Are you Sure?')">

                                            <i class="fas fa-trash text-primary"></i>

                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
                {{-- <div class="d-flex justify-content-end">
                    {{ $suppliers->links() }}
                </div> --}}

            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#supplier_table").DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });

            $('#sup_search').keyup(function() {
                let supplier = $(this).val();
                if (supplier != "") {
                    $.ajax({
                        url: "{{ route('suppliers.search') }}",
                        method: "GET",
                        data: {
                            name: supplier
                        },
                        success: function(data) {
                            let html = '';
                            data.forEach(function(supplier) {
                                html += '<li class="list-group-item">' + supplier.name +
                                    '</li>';
                            });
                            $('.supplier_name_list').html(html);
                        }
                    });
                } else {
                    $('.supplier_name_list').html('');
                }
            });
            $('#phno_search').keyup(function() {
                let phno = $(this).val();
                if (phno != "") {
                    $.ajax({
                        url: '{{ route('suppliers.search') }}',
                        method: 'GET',
                        data: {
                            phno: phno
                        },
                        success: function(data) {
                            let html = "";
                            data.forEach(function(supplier) {
                                html += '<li class="list-group-item">' + supplier.phno +
                                    '</li>';
                            });
                            $('.supplier_phno_list').html(html);
                        }

                    });
                } else {
                    $('.supplier_phno_list').html('');
                }
            });
        });
    </script>
@endpush
