<?php

namespace App\Http\Controllers;

use App\Models\purchasecon;
use Illuminate\Http\Request;

class PurchaseconController extends Controller
{
    public function index(){
        $purchasecons=purchasecon::all();
        return view('purchasecons.index',compact('purchasecons'));
    }

    public function create(){
        
        return view('purchasecons.create');
    }
    public function store(Request $request){
        $validated=$request->validate([
            'prefix'=>'required|max:255',
            'suffix'=>'required|max:255',
            'year'=>'nullable|max:255',
            'status'=>'required|in:active,inactive',
        ]);
        purchasecon::create($validated);
        return redirect()->route('purchasecon.index')->with('success','SalesInvoiceId created success');
    }
public function show(purchasecon $purchasecon)
{
    return view('purchasecons.show', compact('purchasecon'));
}
   public function edit(purchasecon $purchasecon){
        return view('purchasecons.edit',compact('purchasecon'));
    }

   public function update(Request $request, purchasecon $purchasecon){
          $validated=$request->validate([
            'prefix'=>'required|max:255',
            'suffix'=>'required|max:255',
            'year'=>'nullable|max:255',
            'status'=>'required|in:active,inactive',
        ]);
        $purchasecon->update($validated);
        return redirect()->route('purchasecons.index')->with('success','SalesInvoiceId updated success');
    }

   public function destroy(purchasecon $purchasecon)
{
    $purchasecon->delete();

    return redirect()->route('purchasecons.index')
        ->with('success','SalesInvoiceId deleted success');
}   

}
