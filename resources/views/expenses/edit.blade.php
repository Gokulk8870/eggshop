@extends('layouts.master')

@section('title', 'Expense Edit page')
@section('content')
    <div class="card">

        <div class="card-body">
            <form action="{{ route('expenses.update', $expense) }}" method="post">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Date</label>
                    <input type="date" name="expense_date" value="{{ $expense->expense_date }}" class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label>Expense Type</label>
                    <select name="expense_type" class="form-control" required>
                        <option value="Electricity" {{ $expense->expense_type == 'Electricity' ? 'selected' : '' }}>
                            Electricity</option>
                        <option value="Rent" {{ $expense->expense_type == 'Rent' ? 'selected' : '' }}>Rent</option>
                        <option value="Salary" {{ $expense->expense_type == 'Salary' ? 'selected' : '' }}>Salary</option>
                        <option value="Transport" {{ $expense->expense_type == 'Transport' ? 'selected' : '' }}>Transport
                        </option>
                        <option value="Water Bill" {{ $expense->expense_type == 'Water Bill' ? 'selected' : '' }}>Water Bill
                        </option>
                        <option value="Maintenance" {{ $expense->expense_type == 'Maintenance' ? 'selected' : '' }}>
                            Maintenance</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" value="{{ $expense->description }}">
                </div>

                <div class="mb-3">
                    <label>Amount</label>
                    <input type="number" value="{{ $expense->amount }}" step="0.01" name="amount" class="form-control"
                        required>
                </div>

                <button class="btn btn-success">Update Expense</button>
            </form>
        </div>
    </div>
@endsection
