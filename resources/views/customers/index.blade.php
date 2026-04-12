@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush
@section('title', 'Manage Customers')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                @if (auth()->user()->role == 'employee' || auth()->user()->role == 'admin')
                    <div class="col-md-12 d-flex justify-content-end align-items-end">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary fw-bold">
                            Add Customer
                        </a>
                    </div>
                @endif
            </div>
            <form action="{{ route('customers.index') }}" method="get">
                <div class="row">

                    <div class="col-md-2">
                        <form action="" method="get">
                            <div class="form-group">
                                <label for="name">Customer Name</label>
                                <input type="text" id="cus_search" name="name" class="form-control"
                                    list="customer_list" value="{{ request('name') }}">
                                <ul class="list-group customer_name_list"></ul>

                                {{-- <datalist id="customer_list">
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->name }}"></option>
                                @endforeach
                            </datalist> --}}
                            </div>

                    </div>
                    <div class="col-md-2">
                        <div class="form-group position-relative">
                            <label for="phno">Phone Number</label>
                            <input type="text" id="phno_search" name="phno" class="form-control"
                                value="{{ request('phno') }}">

                            <ul class="list-group customer_phno_list"></ul>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="" class="form-control">
                                <option value="">--Select--</option>
                                <option value="active" {{ request('status' == 'active' ? 'selected' : '') }}>Active</option>
                                <option value="inactive" {{ request('status' == 'inactive' ? 'selected' : '') }}>InActive
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-group">

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>

                            @if (request()->has('name') || request()->has('phno'))
                                <a href="{{ route('customers.index') }}" class="btn btn-danger">
                                    <i class="fas fa-times"></i> Clear Filter
                                </a>
                            @endif

                        </div>
                    </div>




                </div>
            </form>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <div class="form-group d-flex align-items-center">
                        {{-- <label for="search" class="me-2 mb-0 fw-primary"> </label> --}}

                    </div>
                </div>
            </div>


            <table class="table table-bordered" id="customer_table">

                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Customer Name</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table">
                    @foreach ($customers as $customer)
                        <tr>

                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phno }}</td>
                            <td>{{ $customer->status }}</td>
                            <td>
                                @if (auth()->check() && in_array(auth()->user()->role, ['employee', 'admin']))
                                    <a href="{{ route('customers.show', $customer->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('customers.edit', $customer->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-primary btn-sm"
                                            onclick="return confirm('Are you Sure?')"><i class="fas fa-trash ">
                                            </i></button>
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
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#customer_table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true

            });

            $('#cus_search').keyup(function() {
                var cus = $(this).val();
                if (cus != '') {
                    $.ajax({
                        url: "{{ route('customers.search') }}",
                        method: 'GET',
                        data: {
                            name: cus
                        },
                        success: function(data) {
                            let value = "";
                            data.forEach(function(customer) {
                                value += '<li class="list-group-item">' +
                                    customer.name + "</li>";
                            });
                            $('.customer_name_list').html(value);

                        }

                    });
                } else {
                    $('.customer_name_list').html('');
                }
            });

            $('#phno_search').keyup(function() {

                var phno = $(this).val();

                if (phno != '') {

                    $.ajax({
                        url: "{{ route('customers.search') }}",
                        method: "GET",
                        data: {
                            phno: phno
                        },
                        success: function(data) {

                            var html = '';

                            data.forEach(function(customer) {
                                html += '<li class="list-group-item">' +
                                    customer.phno +
                                    '</li>';
                            });

                            $('.customer_phno_list').html(html);
                        }
                    });

                }
            });
        });
    </script>
@endpush
