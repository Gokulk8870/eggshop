@extends('layouts.master')
@section('title', 'Manage Purchase Control Page')
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
        <div class="alert">
            <div class="alert-danger">
                @foreach ($errors->any() as $error)
                    <ul>
                        <li>{{ $error }}</li>
                    </ul>
                @endforeach
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                @if (auth()->user()->role == 'admin')
                    <div class="col-md-12 d-flex justify-content-end mb-2">
                        <a href="{{ route('purchasecon.create') }}" class="btn btn-primary">
                            Create
                        </a>
                    </div>
                @endif

            </div>
            <table class="table table-borderd " id="purchasecon">
                <thead class="table table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Prefix</th>
                        <th>Suffix</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-borderd">
                    @foreach ($purchasecons as $purchasecon)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $purchasecon->prefix }}</td>
                            <td>{{ $purchasecon->suffix }}</td>
                            <td>{{ $purchasecon->status }}</td>
                            <td>
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('purchasecon.show', $purchasecon->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('purchasecon.edit', $purchasecon->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('purchasecon.destroy', $purchasecon->id) }}" method="get"
                                        class="d-inline">
                                        @method('DELETE')
                                        <button type="submit" class="btn"><i class="fas fa-trash"></i></button>
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
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#purchasecon').DataTable({
                pagging: true,
                searching: true,
                info: true,
                ordering: true
            });
        });
    </script>
@endpush
