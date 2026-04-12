@extends('layouts.master')
@section('title', 'Manage Products')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush
@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if (auth()->user()->role == 'admin')
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
                </div>
            @endif
            <table class="table table-bordered" id="products_table">
                <thead class="table table-info">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Sale Price</th>
                        <th>Quantity</th>
                        <th>Eggs</th>


                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->size }}</td>
                            <td>{{ $product->color }}</td>
                            <td>{{ $product->sale_price }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->totaleggs }}</td>



                            <td>
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm"><i
                                            class='fas fa-eye'></i></a>

                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm"><i
                                            class="fas fa-edit"></i></a>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>



@stop

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#products_table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });
        });
    </script>
@endpush
