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
            <form action="" method="get">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pname">Product Name</label>
                            <input type="text" list="productlist" name="product_name" class="form-control">
                            <datalist id="productlist">
                                @foreach ($products as $product)
                                    <option value="{{ $product->product_name }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="size">Size</label>
                            <select name="size" id="size" class="form-control">
                                <option value="">----</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="color">Color</label>
                            <select name="color" id="color" class="form-control">
                                <option value="">----</option>
                                @foreach ($colors as $color)
                                    <option value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>

                </div>
            </form>
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

                                        <button class="btn btn-primary btn-sm"><i class="fas fa-trash"></i></button>
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
