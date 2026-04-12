@extends('layouts.master')
@section('title', 'Show Supplier')
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
            <table class="table table-bordered">
                <tr>
                    <th>Supplier ID</th>
                    <td>{{ $supplier->id }}</td>

                </tr>
                <tr>
                    <th>Supplier Name</th>
                    <td>{{ $supplier->name }}</td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>{{ $supplier->phno }}</td>
                </tr>
                <tr>
                    <th>E-mail</th>
                    <td>{{ $supplier->email }}</td>
                </tr>
                <tr>
                    <th>address</th>
                    <td>{{ $supplier->addr }}</td>
                </tr>

                <tr>
                    <th>Company Name</th>
                    <td>{{ $supplier->company_name }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $supplier->status }}</td>
                </tr>

            </table>
        </div>
    </div>
@endsection
