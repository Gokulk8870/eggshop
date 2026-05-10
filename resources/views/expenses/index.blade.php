@extends('layouts.master')
@section('title', 'Manage Expense')
@section('content')
    <div class="card">
        <div class="card-body">
            @if (auth()->user()->role == 'admin')
                <div class="d-flex align-items-end justify-content-end">
                    <a href="{{ route('expenses.create') }}" class="btn btn-primary mb-3">
                        Add Expense
                    </a>
                </div>
            @endif


            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered ">
                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $expense->expense_date }}</td>
                            <td>{{ $expense->expense_type }}</td>
                            <td>{{ $expense->description }}</td>
                            <td>₹ {{ $expense->amount }}</td>
                            <td>
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-primary btn-sm" onclick="return confirm('Delete?')">
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
