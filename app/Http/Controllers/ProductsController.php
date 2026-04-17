<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Tray;
use App\Models\TrayTransaction;
use Doctrine\DBAL\Query;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Products::query();
        if($request->product_name){
            $query->where('product_name','like','%'.$request->product_name.'%');
        }
        if($request->size){
            $query->where('size','like','%'.$request->size.'%');
        }
        if($request->color){
            $query->where('color','like','%'.$request->color.'%');
        }
        $products=$query->orderBy('id','desc')->get();
        $sizes = Products::select('size')->distinct()->pluck('size');
        $colors = Products::select('color')->distinct()->pluck('color');;

        return view('products.index', compact('products','sizes','colors'));
    }

    public function productsearch(Request $request){
        $query=products::query();
        if($request->product_name){
             $query->where('product_name','like','%'.$request->product_name.'%');
        }
         if($request->size){
            $query->where('size','like','%'.$request->size.'%');
        }
         if($request->color){
            $query->where('color','like','%'.$request->color.'%');
        }
        $product=$query->distinct()->get();
        return response()->json($product);
    }

    public function create()
    {
        $trays = Tray::where('quantity', '>', 0)->get();
        return view('products.create', compact('trays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name'   => 'required|string|max:255',
            'size'           => 'required|in:small,medium,large',
            'color'          => 'required|in:white,brown',
            'quantity'       => 'required|integer|min:1',
            'tray_color'     => 'required|string',
            'status'         => 'required|in:active,inactive',
            'purchase_price' => 'required|numeric',
            'sale_price'     => 'required|numeric',
        ]);

        $validated['eggprice']  = $validated['sale_price'] / 30;
        $validated['totaleggs'] = $validated['quantity'] * 30;

        return DB::transaction(function () use ($validated) {
            // Check total available stock for this color
            $totalStock = Tray::where('tcolor', $validated['tray_color'])->sum('quantity');

            if ($validated['quantity'] > $totalStock) {
                return back()->withErrors([
                    'quantity' => 'Not enough trays available. Available: ' . $totalStock
                ])->withInput();
            }

            // Deduct from trays
            $remaining = $validated['quantity'];
            $trays = Tray::where('tcolor', $validated['tray_color'])->where('quantity', '>', 0)->get();

            foreach ($trays as $tray) {
                if ($remaining <= 0) break;

                $deduct = min($tray->quantity, $remaining);
                $tray->decrement('quantity', $deduct);
                $remaining -= $deduct;

                // Record transaction
                TrayTransaction::create([
                    'tray_id'  => $tray->id,
                    'type'     => 'out',
                    'quantity' => $deduct,
                ]);
            }

            Products::create($validated);

            return redirect()->route('products.index')->with('success', 'Product created successfully');
        });
    }

    public function show(Products $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Products $product)
    {
        $trays = Tray::all();
        return view('products.edit', compact('product', 'trays'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name'   => 'required|string|max:255',
            'size'           => 'required|in:small,medium,large',
            'color'          => 'required|in:white,brown',
            'quantity'       => 'required|integer|min:1',
            'tray_color'     => 'required|string',
            'status'         => 'required|in:active,inactive',
            'purchase_price' => 'required|numeric',
            'sale_price'     => 'required|numeric',
        ]);

        $validated['eggprice']  = $validated['sale_price'] / 30;
        $validated['totaleggs'] = $validated['quantity'] * 30;

        $product = Products::findOrFail($id);

        return DB::transaction(function () use ($product, $validated) {
            $oldColor = $product->tray_color;
            $oldQty   = $product->quantity;
            $newColor = $validated['tray_color'];
            $newQty   = $validated['quantity'];

            // Step 1: Return old trays
            if ($oldQty > 0) {
                $tray = Tray::firstOrCreate(['tcolor' => $oldColor], ['quantity' => 0]);
                $tray->increment('quantity', $oldQty);

                TrayTransaction::create([
                    'tray_id'  => $tray->id,
                    'type'     => 'return',
                    'quantity' => $oldQty,
                ]);
            }

            // Step 2: Check new stock
            $totalStock = Tray::where('tcolor', $newColor)->sum('quantity');

            if ($newQty > $totalStock) {
                return back()->withErrors([
                    'quantity' => 'Not enough trays available. Available: ' . $totalStock
                ])->withInput();
            }

            // Step 3: Deduct new trays
            $remaining = $newQty;
            $trays = Tray::where('tcolor', $newColor)->where('quantity', '>', 0)->get();

            foreach ($trays as $tray) {
                if ($remaining <= 0) break;

                $deduct = min($tray->quantity, $remaining);
                $tray->decrement('quantity', $deduct);
                $remaining -= $deduct;

                TrayTransaction::create([
                    'tray_id'  => $tray->id,
                    'type'     => 'out',
                    'quantity' => $deduct,
                ]);
            }

            // Step 4: Update product
            $product->update($validated);

            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        });
    }

    public function destroy(Products $product)
    {
        return DB::transaction(function () use ($product) {
            if ($product->tray_color && $product->quantity > 0) {
                $tray = Tray::firstOrCreate(['tcolor' => $product->tray_color], ['quantity' => 0]);
                $tray->increment('quantity', $product->quantity);

                TrayTransaction::create([
                    'tray_id'  => $tray->id,
                    'type'     => 'return',
                    'quantity' => $product->quantity,
                ]);
            }

            $product->delete();

            return redirect()->route('products.index')->with('success', 'Product deleted successfully');
        });
    }
}
