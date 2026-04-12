@extends('layouts.master')

@section('title', 'Expense Details')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>Expense Details</h4>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <strong>Date:</strong>
                <p>{{ $expense->expense_date }}</p>
            </div>

            <div class="mb-3">
                <strong>Type:</strong>
                <p>{{ $expense->expense_type }}</p>
            </div>

            <div class="mb-3">
                <strong>Description:</strong>
                <p>{{ $expense->description ?? 'N/A' }}</p>
            </div>

            <div class="mb-3">
                <strong>Amount:</strong>
                <p>₹ {{ number_format($expense->amount, 2) }}</p>
            </div>

            <div class="mb-3">
                <strong>Created At:</strong>
                <p>{{ $expense->created_at->format('d-m-Y H:i') }}</p>
            </div>

        </div>
    </div>
@endsection
