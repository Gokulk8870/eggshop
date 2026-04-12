<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query=customer::query();
       if($request->name){
        $query->where('name','like','%'.$request->name.'%');
       }
       if($request->phno){
        $query->where('phno','like','%'.$request->phno.'%');
       }
       if($request->status){
        $query->where('status',$request->status);
       }
       $customers=$query->orderBy('id','desc')->get();
        return view('customers.index',compact('customers'));
        
    }
    

    public function search(Request $request){
        if($request->phno){
$customers=customer::where('phno','like','%'.$request->phno.'%')->get();
        }
        if($request->name){
$customers=customer::where('name','like','%'.$request->name.'%')->get();
        }
        
        return response()->json($customers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Customer::create([
            'name'=> $request->cname,
            'phno'=>$request->phno,
            'addr'=>$request->address,
            'status'=>$request->status,
        ]);
        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
{
    return view('customers.show', compact('customer'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit',compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->update([
        'name'   => $request->cname,
        'phno'   => $request->phno,
        'addr'   => $request->addr,
        'status' => $request->status,
    ]);
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully');;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $name=$customer->name;
        $customer->delete();
        return redirect()->route('customers.index')->with('success','$name customer is deleted');
    }
}
