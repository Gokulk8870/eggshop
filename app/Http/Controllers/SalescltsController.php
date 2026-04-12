<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salesclt;

class SalescltsController extends Controller
{
    public function index(){
        $salecon=Salesclt::all();
        return view('salesclts.index',compact('salecon'));
    }

    public function create(){
        
        return view('salesclts.create');
    }
    public function store(Request $request){
        $validated=$request->validate([
            'prefix'=>'required|max:255',
            'suffix'=>'required|max:255',
            'year'=>'nullable|max:255',
            'status'=>'required|in:active,inactive',
        ]);
        Salesclt::create($validated);
        return redirect()->route('salesclts.index')->with('success','SalesInvoiceId created success');
    }
public function show(Salesclt $salesclt)
{
    return view('salesclts.show', compact('salesclt'));
}
   public function edit(Salesclt $salesclt){
        return view('salesclts.edit',compact('salesclt'));
    }

   public function update(Request $request, Salesclt $salesclt){
          $validated=$request->validate([
            'prefix'=>'required|max:255',
            'suffix'=>'required|max:255',
            'year'=>'nullable|max:255',
            'status'=>'required|in:active,inactive',
        ]);
        $salesclt->update($validated);
        return redirect()->route('salesclts.index')->with('success','SalesInvoiceId updated success');
    }

   public function destroy(Salesclt $salesclt)
{
    $salesclt->delete();

    return redirect()->route('salesclts.index')
        ->with('success','SalesInvoiceId deleted success');
}


    
}
