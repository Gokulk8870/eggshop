<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tray;
use App\Models\TrayTransaction;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class TrayController extends Controller
{
    public function index(Request $request)
    {
        $query = Tray::query();

        if ($request->tcolor) {
            $query->where('tcolor', 'like', '%' . $request->tcolor . '%');
        }

        $trays = $query->get();

        return view('trays.index', compact('trays'));
    }

    public function search(Request $request)
    {
        $trays = Tray::when($request->tcolor, fn($q) =>
            $q->where('tcolor', 'like', '%' . $request->tcolor . '%')
        )->limit(5)->get();

        return response()->json($trays);
    }

    public function create()
    {
        return view('trays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tcolor'   => 'required|max:255',
            'quantity' => 'required|integer|min:1',
            'type'     => 'required|in:return,damage',
        ]);

        $tray = Tray::firstOrCreate(
            ['tcolor' => $validated['tcolor']],
            ['quantity' => 0]
        );

        if ($validated['type'] === 'return') {
            $tray->increment('quantity', $validated['quantity']);
        } else {
            if ($tray->quantity < $validated['quantity']) {
                return back()->withErrors(['quantity' => 'Not enough stock to mark as damage.'])->withInput();
            }
            $tray->decrement('quantity', $validated['quantity']);
        }

        TrayTransaction::create([
            'tray_id'  => $tray->id,
            'type'     => $validated['type'],
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->route('trays.index')->with('success', 'Tray saved successfully');
    }

    public function show(Tray $tray)
    {
        return view('trays.show', compact('tray'));
    }

    public function edit($id)
    {
        $tray = Tray::findOrFail($id);
        return view('trays.edit', compact('tray'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tcolor'   => 'required|max:255',
            'quantity' => 'required|integer|min:0',
        ]);

        $tray    = Tray::findOrFail($id);
        $oldQty  = $tray->quantity;
        $newQty  = $validated['quantity'];
        $diff    = $newQty - $oldQty;

        $tray->update([
            'tcolor'   => $validated['tcolor'],
            'quantity' => $newQty,
        ]);

        if ($diff !== 0) {
            TrayTransaction::create([
                'tray_id'  => $tray->id,
                'type'     => $diff > 0 ? 'return' : 'damage',
                'quantity' => abs($diff),
            ]);
        }

        return redirect()->route('trays.index')->with('success', 'Tray updated successfully');
    }

    public function destroy($id)
    {
        $tray = Tray::findOrFail($id);
        TrayTransaction::where('tray_id', $tray->id)->delete();
        $tray->delete();

        return redirect()->route('trays.index')->with('success', 'Tray deleted successfully');
    }

    public function returnForm()
    {
        $customers     = Customer::all();
        $trays         = Tray::where('quantity', '>', 0)->get();
        $customer_list = TrayTransaction::with('customer', 'tray')
            ->selectRaw('customer_id, tray_id, SUM(quantity) as total_quantity')
            ->where('type', 'out')
            ->whereNotNull('customer_id')
            ->groupBy('customer_id', 'tray_id')
            ->get();

        return view('trays.return', compact('customers', 'trays', 'customer_list'));
    }

            public function storeReturn(Request $request)
        {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'tray_id'     => 'required|exists:trays,id',
                'quantity'    => 'required|integer|min:1',
            ]);
            $tray = Tray::find($request->tray_id);
            $refund=$request->quantity*20;
            $out = TrayTransaction::where('customer_id', $request->customer_id)
                ->where('tray_id', $request->tray_id)
                ->where('type', 'out')
                ->sum('quantity');

            $returned = TrayTransaction::where('customer_id', $request->customer_id)
                ->where('tray_id', $request->tray_id)
                ->where('type', 'return')
                ->sum('quantity');

            $customer_list = TrayTransaction::with('customer')
                ->where('customer_id', $request->customer_id)
                ->where('tray_id', $request->tray_id)
                ->where('quantity', '>', 0)
                ->where('type', 'out')->get();


            $balance = $out - $returned;

            if ($request->quantity > $balance) {
                $trayColor = $tray->tcolor ?? 'Tray';
                return back()->withErrors(['quantity' => "Return exceeds balance. Customer owes {$balance} {$trayColor} trays."]);
            }

            $customer = Customer::find($request->customer_id);

            DB::transaction(function () use ($request) {
                $tray = Tray::findOrFail($request->tray_id);

                TrayTransaction::create([
                    'tray_id' => $tray->id,
                    'customer_id' => $request->customer_id,
                    'type' => 'return',
                    'quantity' => $request->quantity,
                ]);

                $tray->quantity += $request->quantity;
                $tray->save();
            });

            return redirect()->route('trays.return')
                ->with('success', "₹ $refund refunded to $customer, Tray returned successfully");
        }

}
