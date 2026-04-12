@extends('layouts.master')
@section('title', 'Show Customer')
@section('content')

    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title">Customer Details</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <tr>
                    <th width="30%">Customer Name</th>
                    <td>{{ $customer->name }}</td>
                </tr>

                <tr>
                    <th>Phone Number</th>
                    <td>{{ $customer->phno }}</td>
                </tr>

                <tr>
                    <th>Address</th>
                    <td>{{ $customer->addr }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        @if ($customer->status == 'active')
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="text-right mt-3">
                <a href="{{ route('customers.index') }}" class="btn btn-primary"> Previous page

                </a>
            </div>

        </div>
    </div>

@endsection
