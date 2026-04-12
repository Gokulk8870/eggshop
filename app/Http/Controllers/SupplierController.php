<?php

namespace App\Http\Controllers;

use App\Models\supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query=supplier::query();
        if($request->name){
            $query->where('name','like','%'.$request->name.'%');
        }
        if($request->phno){
             $query->where('phno','like','%'.$request->phno.'%');
        }
         if($request->status){
            $query->where('status',$request->status);
        }

        $suppliers=$query->orderBy('id','desc')->get();
        // $suppliers=Supplier::paginate(10);   //select * from suppliers 
        return view('suppliers.index',compact('suppliers'));

    }

    public function search(Request $request){
        if($request->name){
            $suppliers=supplier::where('name','like','%'.$request->name.'%')->get();
        }
        if($request->phno){
            $suppliers=supplier::where('phno','like','%'.$request->phno.'%')->get();
        }
        if($request->status){
            $supplier=supplier::where('status',$request->status)->get();
        }
        return response()->json($suppliers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated=$request->validate([
            'name'=>'required|string|max:255',
            'phno'=>'required|string| max:15',
            'email'=>'nullable|email|max:255',
            'addr'=>'nullable|max:50',
            'company_name'=>'nullable|max:255',
            'status'=>'required|in:active,inactive',
        ]);
        Supplier::create($validated);
        return redirect()->route('suppliers.index')->with('success',"supplier created");
    }

    /**
     * Display the specified resource.
     */
    public function show(supplier $supplier)
    {
        return view('suppliers.show',compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(supplier $supplier)
    {
        return view('suppliers.edit',compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, supplier $supplier)
    {
         $validated=$request->validate([
            'name'=>'required|string|max:255',
            'phno'=>'required|string|max:15',
            'email'=>'nullable|email|max:255',
            'addr'=>'nullable|max:50',
            'company_name'=>'nullable|max:255',
            'status'=>'required|in:active,inactive',
        ]);
        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success','suppliers updates successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(supplier $supplier)
    {
        $name=$supplier->name;
        $supplier->delete();
        return redirect('suppliers.index')->with('success',"$name deleted succesfully");

    }
}
