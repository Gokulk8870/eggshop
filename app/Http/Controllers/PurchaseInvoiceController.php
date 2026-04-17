<?php

namespace App\Http\Controllers;
use App\Models\supplier;
use App\Models\Tray;
use App\Models\TrayTransaction;
use App\Models\purchasecon;
use App\Models\Products;
use App\Models\purchase_invoices_items;
use App\Models\PurchaseInvoice;

use Illuminate\Http\Request;
use Nette\Utils\Json;

class PurchaseInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query=PurchaseInvoice::query();
        if($request->supplier_name){
            $query->where('supplier_name','LIKE','%'.$request->supplier_name.'%');
        }
        if($request->phno){
            $query->where('phno','LIKE','%'.$request->phno.'%');
        }
        if($request->payment_method){
            $query->where('payment_method',$request->payment_method);
        }
        $purchaseInvoices=$query->orderBy('id', 'DESC')->get();
        $suppliers=PurchaseInvoice::select('supplier_name')
                    ->distinct()->get();
        $paymentMethods=PurchaseInvoice::select('payment_method')
                    ->distinct()->pluck('payment_method');
        return view('purchaseinvoices.index',compact('purchaseInvoices','suppliers','paymentMethods'));
    }

    public function supsearch(Request $request){
        $supplier_name=supplier::where('phno',$request->phno)->first();
        return response()->json($supplier_name);
    }

    public function purchasesupserarch(Request $request){
        $query=supplier::query();
        if($request->supplier_name){
            $query->where('name', 'LIKE', '%'.$request->supplier_name.'%');
        }
        if($request->phno){
            $query->where('phno', 'LIKE', '%'.$request->phno.'%');
        }
        $purchaseInvoices = $query->distinct()->get();
        return response()->json($purchaseInvoices);
    }
      public function generateUpi(Request $request)
{
    $amount = $request->amount;

    $upi = "upi://pay?pa=" . env('UPI_ID') . "&pn=EggShop&am={$amount}&cu=INR";

    return response()->json(['upi' => $upi]);
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trays = Tray::all();
        $products = Products::all();
        $format = purchasecon::where('status','active')->first();
        $total_price = 0;
        $prefix = $format->prefix;
        $year = $format->year;
        $suffix = $format->suffix;
        $lastInvoice = PurchaseInvoice::latest()->first();
        if($lastInvoice){
            $lastNumber = intval(substr($lastInvoice->inv_number,-2));
            $nextNumber = $lastNumber+1;
        }
        else{
            $nextNumber = 1;
        }
        $number = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        $invoice_number = $prefix.'/'.$year.'/'.$suffix.'/'.$number;
        return view('purchaseinvoices.create',compact('invoice_number','trays','products','total_price'));
    }

    /**
     * Store a newly created resource in storage.
     */
            public function store(Request $request)
        {
            // ✅ VALIDATION
            $request->validate([
                'inv_number'     => 'required',
                'supplier_name'  => 'required',
                'phno'           => 'required',
                'product_id'     => 'required',
                'quantity'       => 'required|numeric|min:1',
                'purchase_price' => 'required|numeric',
            ]);

            // ✅ BASIC CALCULATION
            $trayQty   = $request->quantity;
            $extraEggs = $request->eggs ?? 0;
            $eggs      = ($trayQty * 30) + $extraEggs;

            // ✅ CREATE INVOICE
            $invoice = PurchaseInvoice::create([
                'inv_number'     => $request->inv_number,
                'supplier_name'  => $request->supplier_name,
                'phno'           => $request->phno,
                'invoice_date'   => $request->invoice_date,
                'purchase_price' => $request->purchase_price,
                'total_price'    => $request->total_price,
                'payment_method' => $request->payment_method,
                'tray_need'      => $request->tray_need ?? 'no',
                'tray_id'        => $request->tray_need == 'yes' ? $request->tray_id : null,
            ]);

            // ✅ PRODUCT FIND
            $product = Products::find($request->product_id);

            if (!$product) {
                return back()->with('error', 'Product not found');
            }

            // ✅ SAVE ITEM
            purchase_invoices_items::create([
                'invoice_id'     => $invoice->id,
                'product_id'     => $request->product_id,
                'quantity'       => $trayQty,
                'tray_use'       => $request->tray_quantity ?? 0,
                'eggs'           => $eggs,
                'purchase_price' => $request->purchase_price,
                'total'          => $request->total_price,
                'per_egg_price'=>$request->eggprice,
            ]);

            // ✅ INCREASE PRODUCT STOCK
            $product->increment('totaleggs', $eggs);
            $product->increment('quantity', $trayQty);

            // ✅ TRAY LOGIC (RETURN = IN)
            if ($request->tray_need == 'yes' && $request->tray_id) {

                $trayQtyUsed = $request->tray_quantity ?? 0;

                $tray = Tray::find($request->tray_id);

                if ($tray && $trayQtyUsed > 0) {

                    // ✅ increase tray stock
                    $tray->increment('quantity', $trayQtyUsed);

                    // ✅ record transaction
                    TrayTransaction::create([
                        'tray_id'      => $tray->id,
                        'supplier_id'  => supplier::where('phno', $request->phno)->value('id'),
                        'type'         => 'return',
                        'quantity'     => $trayQtyUsed,
                        'reference_id' => $invoice->id,
                    ]);
                }
            }

            return redirect()->route('purchaseinvoices.index')
                ->with('success', 'Purchase Invoice Created Successfully');
        }

    /**
     * Display the specified resource.
     */
  public function show($id)
{
    $invoice = PurchaseInvoice::with(['items.product', 'tray'])
        ->findOrFail($id);

    return view('purchaseinvoices.show', compact('invoice'));
}

    /**
     * Show the form for editing the specified resource.
     */
  public function edit($id)
{
    $purchaseInvoice = PurchaseInvoice::findOrFail($id);

    $purchaseInvoice->load('items.product');
    $products = Products::all();
    $trays = Tray::all();
    $item = $purchaseInvoice->items->first();

    return view('purchaseinvoices.edit', compact(
        'purchaseInvoice',
        'products',
        'trays',
        'item'
    ));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'inv_number'     => 'required',
            'supplier_name'  => 'required',
            'phno'           => 'required',
            'product_id'     => 'required',
            'quantity'       => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric',
        ]);

        $purchaseInvoice = PurchaseInvoice::findOrFail($id);
        $oldItem = $purchaseInvoice->items->first();

        if (!$oldItem) {
            return back()->with('error', 'Invoice item not found');
        }

        // ✅ OLD PRODUCT REVERT
        $oldProduct = Products::find($oldItem->product_id);
        if ($oldProduct) {
            $oldProduct->increment('totaleggs', $oldItem->eggs);
            $oldProduct->increment('quantity', $oldItem->quantity);
        }

        // ✅ OLD TRAY REVERT
        $oldTransactions = TrayTransaction::where('reference_id', $purchaseInvoice->id)->get();
        foreach ($oldTransactions as $t) {
            $tray = Tray::find($t->tray_id);
            if ($tray) {
                $tray->decrement('quantity', $t->quantity);
            }
            $t->delete();
        }

        // ✅ NEW VALUES
        $trayQty = $request->quantity;
        $eggs    = $trayQty * 30;

        $product = Products::find($request->product_id);
        if (!$product) {
            return back()->with('error', 'Product not found');
        }

        // ✅ PRICE CALC
        $total = $trayQty * $request->purchase_price;
        $perEgg = $request->purchase_price / 30;

        // ✅ UPDATE INVOICE
        $purchaseInvoice->update([
            'inv_number'     => $request->inv_number,
            'supplier_name'  => $request->supplier_name,
            'phno'           => $request->phno,
            'invoice_date'   => $request->invoice_date,
            'purchase_price' => $request->purchase_price,
            'total_price'    => $request->total_price,
            'payment_method' => $request->payment_method,
            'tray_need'      => $request->tray_need ?? 'no',
            'tray_id'        => $request->tray_need == 'yes' ? $request->tray_id : null,
        ]);

        // ✅ UPDATE ITEM
        $oldItem->update([
            'product_id'     => $request->product_id,
            'quantity'       => $trayQty,
            'tray_use'       => $trayQty,
            'eggs'           => $eggs,
            'purchase_price' => $request->purchase_price,
            'total'          => $total,
            'per_egg_price'  => $perEgg,
        ]);

        // ✅ ADD NEW STOCK
        $product->increment('totaleggs', $eggs);
        $product->increment('quantity', $trayQty);

        // ✅ TRAY UPDATE
        if ($request->tray_need == 'yes' && $request->tray_id) {

            $trayQtyUsed = $request->tray_quantity ?? 0;

            $tray = Tray::find($request->tray_id);

            if ($tray && $trayQtyUsed > 0) {

                $tray->increment('quantity', $trayQtyUsed);

                TrayTransaction::create([
                    'tray_id'      => $tray->id,
                    'supplier_id'  => supplier::where('phno', $request->phno)->value('id'),
                    'type'         => 'return',
                    'quantity'     => $trayQtyUsed,
                    'reference_id' => $purchaseInvoice->id,
                ]);
            }
        }

        return redirect()->route('purchaseinvoices.index')
            ->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = PurchaseInvoice::with('items')->findOrFail($id);
        $item = $invoice->items->first();

        if ($item) {
            // Revert product stock (purchase adds stock, so delete removes it)
            $product = Products::find($item->product_id);
            if ($product) {
                $product->decrement('totaleggs', $item->eggs);
                $product->decrement('quantity', $item->quantity);
            }

            // Revert tray stock
            $transactions = TrayTransaction::where('reference_id', $invoice->id)->get();
            foreach ($transactions as $t) {
                $tray = Tray::find($t->tray_id);
                if ($tray) {
                    $tray->decrement('quantity', $t->quantity);
                }
                $t->delete();
            }
        }

        purchase_invoices_items::where('invoice_id', $id)->delete();
        $invoice->delete();

        return redirect()->route('purchaseinvoices.index')
            ->with('success', 'Deleted successfully');
    }
     public function bill($id){
        $purchaseinvoice = PurchaseInvoice::with('items.product')->findOrFail($id);
        $item = $purchaseinvoice->items->first();
        return view('purchaseinvoices.bill', compact('purchaseinvoice', 'item'));
     }

}
