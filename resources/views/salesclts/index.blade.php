@extends('layouts.master')
@section('title', 'Manage Sales Control Page')
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
                <div class="col-md-12 d-flex justify-content-end mb-2">
                    <a href="{{ route('salesclts.create') }}" class="btn btn-primary">
                        Create
                    </a>
                </div>
            </div>
            <table class="table table-bordered" id="salecon">
                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Prefix</th>
                        <th>Suffix</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salecon as $salesclt)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $salesclt->prefix }}</td>
                            <td>{{ $salesclt->suffix }}</td>
                            <td>{{ $salesclt->status }}</td>
                            <td>
                                <a href="{{ route('salesclts.show', $salesclt->id) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('salesclts.edit', $salesclt->id) }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('salesclts.destroy', $salesclt->id) }}" method="get"
                                    class="d-inline">
                                    @method('DELETE')
                                    <button type="submit" class="btn"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#salecon').DataTable({
                pagging: true,
                searching: true,
                info: true,
                ordering: true
            });
        });
    </script>
@endpush
