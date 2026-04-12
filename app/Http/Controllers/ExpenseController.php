<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense; 

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required',
            'expense_type' => 'required',
            'amount' => 'required|numeric'
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense Added Successfully');
    }

    public function edit(Expense $expense)
    {
        
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_date' => 'required',
            'expense_type' => 'required',
            'amount' => 'required|numeric'
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense Updated Successfully');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense Deleted Successfully');
    }
}
