@extends('layouts.master')
@section('title', 'Create Expense Page')
@section('content')
    <div class="card">


        <div class="card-body">


            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Date</label>
                    <input type="date" name="expense_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Expense Type</label>
                    <select name="expense_type" class="form-control" required>
                        <option value="Electricity">Electricity</option>
                        <option value="Rent">Rent</option>
                        <option value="Salary">Salary</option>
                        <option value="Transport">Transport</option>
                        <option value="Water Bill">Water Bill</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>

                <button class="btn btn-success">Save Expense</button>
            </form>
        </div>
    </div>
@endsection
