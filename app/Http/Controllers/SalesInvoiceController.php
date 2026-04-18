<?php

namespace App\Http\Controllers;

use App\Models\SalesInvoice;
use App\Models\products;
use App\Models\Salesclt;
use App\Models\Tray;
use App\Models\Customer;
use App\Models\SalesInvoiceItem;
use App\Models\TrayTransaction;
use Doctrine\DBAL\Query;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $salesinvoices=SalesInvoice::orderBy('id','desc')->get();
        $query=SalesInvoice::query();
        if($request->customer_name){
            $query->where('customer_name', 'like', '%'.$request->customer_name.'%');
        }
        if($request->phno){
            $query->where('phno','like','%'.$request->phno.'%');
        }
        if($request->payment_method){
            $query->where('payment_method', $request->payment_method);
        }
        $salesinvoices=$query->orderBy('id','desc')->get();
        $customers = SalesInvoice::select('customer_name','phno')
            ->distinct()
            ->get();
       $paymentMethods = SalesInvoice::select('payment_method')
                    ->distinct()
                    ->pluck('payment_method');
        return view('salesinvoices.index',compact('salesinvoices','customers','paymentMethods'));
      }

    public function salescussearch(Request $request)
    {
            $query = SalesInvoice::query(); // ✅ IMPORTANT

            if ($request->customer_name) {
                $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
            }

            if ($request->phno) {
                $query->where('phno', 'like', '%' . $request->phno . '%');
            }

            $salesInvoices = $query->distinct()->get(); // ✅ single execution

            return response()->json($salesInvoices);
    }

      public function generateUpi(Request $request)
    {
        $amount = $request->total_price;

        $upi = "upi://pay?pa=" . env('UPI_ID') . "&pn=EggShop&am={$amount}&cu=INR";

        return response()->json(['upi' => $upi]);
    }




    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $trays = Tray::where('quantity', '>', 0)->get()
            ->each(fn($t) => $t->available = $t->quantity);
        $products = Products::all();
        $format = Salesclt::where('status','active')->first();
        $total_price = 0;
        $prefix = $format->prefix;
        $year = $format->year;
        $suffix = $format->suffix;
        $lastInvoice = SalesInvoice::latest()->first();
        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->inv_number, -2));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        $number = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

        $invoice_number = $prefix.'/'.$year.'/'.$suffix.'/'.$number;

        return view('salesinvoices.create', compact('invoice_number','trays','products','total_price'));
    }
    public function cussearch(Request $request)
    {
        $customer = Customer::where('phno', $request->phno)->first();

        return response()->json($customer);
    }

    public function getProducts(Request $request)
    {
        $product = products::find($request->id);

        return response()->json($product);
    }



public function store(Request $request)
{
    DB::beginTransaction();

    try {

        $request->validate([
            'inv_number'     => 'required',
            'customer_name'  => 'required',
            'phno'           => 'required',
            'product_id'     => 'required|exists:products,id',
            'quantity'       => 'required|numeric|min:1', // trays
            'sale_price'     => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'total_price'    => 'required|numeric|min:0',
        ]);

        $trayQty = (int) $request->quantity;   
        $eggs    = (int) $request->eggs;       

        $trayNeed = $request->tray_need === 'yes';
        $trayId   = $trayNeed ? $request->tray_id : null;

        
        $product = Products::findOrFail($request->product_id);

        if ($product->totaleggs < $eggs || $product->quantity < $trayQty) {
            return back()->with('error', 'Insufficient product stock');
        }

        if ($trayNeed && $trayId) {
            $selectedTray = Tray::findOrFail($trayId);
            $totalAvailable = Tray::where('tcolor', $selectedTray->tcolor)->sum('quantity');

            if ($totalAvailable < $trayQty) {
                return back()->with('error', "Only {$totalAvailable} trays available");
            }
        }

        $customer = Customer::firstOrCreate(
            ['phno' => $request->phno],
            ['name' => $request->customer_name]
        );

        $invoice = SalesInvoice::create([
            'inv_number'     => $request->inv_number,
            'customer_name'  => $request->customer_name,
            'phno'           => $request->phno,
            'invoice_date'   => $request->invoice_date,
            'total_price'    => $request->total_price,
            'sale_price'     => $request->sale_price,
            'payment_method' => $request->payment_method,
            'tray_need'      => $trayNeed ? 'yes' : 'no',
            'tray_id'        => $trayId,
        ]);

        // ===============================
        // ✅ STEP 7: PROFIT CALCULATION
        // ===============================
        $salePerEgg     = $request->sale_price / 30;
        $purchasePerEgg = $request->purchase_price / 30;

        $productProfit = ($salePerEgg - $purchasePerEgg) * $eggs;
        $trayProfit    = $trayNeed ? ($trayQty * 20) : 0;

        $totalProfit   = $productProfit + $trayProfit;

        // ===============================
        // ✅ STEP 8: SAVE ITEM
        // ===============================
        SalesInvoiceItem::create([
            'invoice_id'     => $invoice->id,
            'product_id'     => $product->id,
            'product_name'   => $product->product_name,
            'size'           => $product->size,
            'color'          => $product->color,

            'quantity'       => $trayQty,
            'eggs'           => $eggs,
            'tray_use'       => $trayQty,

            'sale_price'     => $request->sale_price,
            'purchase_price' => $request->purchase_price,
            'total'          => $request->total_price,
            'profit'         => $totalProfit,
        ]);

        // ===============================
        // ✅ STEP 9: UPDATE PRODUCT STOCK
        // ===============================
        $product->decrement('totaleggs', $eggs);
        $product->decrement('quantity', $trayQty);

        // ===============================
        // ✅ STEP 10: TRAY DEDUCTION (single update per tray, lockForUpdate)
        // ===============================
        if ($trayNeed && $trayId) {

            $selectedTray = Tray::findOrFail($trayId);
            $trays = Tray::where('tcolor', $selectedTray->tcolor)
                         ->where('quantity', '>', 0)
                         ->lockForUpdate()
                         ->get();

            $remaining = $trayQty;

            foreach ($trays as $tray) {
                if ($remaining <= 0) break;

                $used = min($tray->quantity, $remaining);
                $tray->decrement('quantity', $used);
                $remaining -= $used;

                TrayTransaction::create([
                    'tray_id'      => $tray->id,
                    'customer_id'  => $customer->id,
                    'type'         => TrayTransaction::TYPE_OUT,
                    'quantity'     => $used,
                    'reference_id' => $invoice->id,
                ]);
            }

            if ($remaining > 0) {
                throw new \Exception("Insufficient tray stock. Short by {$remaining} trays.");
            }
        }

        DB::commit();

        return redirect()
            ->route('salesinvoices.index')
            ->with('success', 'Sales Invoice Created Successfully');

    } catch (\Exception $e) {

        DB::rollback();

        return back()->with('error', $e->getMessage());
    }
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = SalesInvoice::with('items')->findOrFail($id);
        $item = $invoice->items->first();
        $trays = Tray::all();
        $products = Products::all();

        return view('salesinvoices.edit', compact('invoice','item','products','trays'));
    }
    

public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {

        // ✅ VALIDATION
        $request->validate([
            'inv_number'     => 'required',
            'customer_name'  => 'required',
            'phno'           => 'required',
            'product_id'     => 'required|exists:products,id',
            'tray_quantity'  => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric',
            'purchase_price' => 'required|numeric',
        ]);

        // ✅ FIND INVOICE
        $invoice = SalesInvoice::findOrFail($id);
        $oldItem = $invoice->items()->first();

        if (!$oldItem) {
            return back()->with('error', 'Invoice item not found');
        }

        // ======================================
        // 🔁 STEP 1: RESTORE OLD PRODUCT STOCK
        // ======================================
        $oldProduct = Products::find($oldItem->product_id);

        if ($oldProduct) {
            $oldProduct->increment('totaleggs', $oldItem->eggs);
            $oldProduct->increment('quantity', $oldItem->quantity);
        }

        // ======================================
        // 🔁 STEP 2: RESTORE OLD TRAY STOCK
        // ======================================
        $oldTransactions = TrayTransaction::where('reference_id', $invoice->id)->get();

        foreach ($oldTransactions as $transaction) {

            $tray = Tray::find($transaction->tray_id);

            if ($tray) {
                $tray->increment('quantity', $transaction->quantity);
            }

            $transaction->delete();
        }

        // ======================================
        // 🔹 STEP 3: NEW INPUT VALUES
        // ======================================
        $trayQty   = (int) $request->tray_quantity;
        $extraEggs = (int) ($request->eggs ?? 0);
        $eggs      = ($trayQty * 30) + $extraEggs;

        // ======================================
        // 🔹 STEP 4: FIND PRODUCT
        // ======================================
        $product = Products::findOrFail($request->product_id);

        // ======================================
        // 🔹 STEP 5: STOCK CHECK
        // ======================================
        if ($product->totaleggs < $eggs || $product->quantity < $trayQty) {
            return back()->with('error', 'Insufficient stock');
        }

        // ======================================
        // 🔹 STEP 6: CUSTOMER
        // ======================================
        $customer = Customer::firstOrCreate(
            ['phno' => $request->phno],
            ['name' => $request->customer_name]
        );

        // ======================================
        // 🔹 STEP 7: UPDATE INVOICE
        // ======================================
        $invoice->update([
            'inv_number'     => $request->inv_number,
            'customer_name'  => $request->customer_name,
            'phno'           => $request->phno,
            'invoice_date'   => $request->invoice_date,
            'total_price'    => $request->total_price,
            'sale_price'     => $request->sale_price,
            'payment_method' => $request->payment_method,
            'tray_need'      => $request->tray_need ?? 'no',
            'tray_id'        => $request->tray_need == 'yes'
                                ? $request->tray_id
                                : null,
        ]);

        // ======================================
        // 🔹 STEP 8: PROFIT CALCULATION
        // ======================================
        $sale_per_egg     = $request->sale_price / 30;
        $purchase_per_egg = $request->purchase_price / 30;

        $product_profit = ($sale_per_egg - $purchase_per_egg) * $eggs;
        $tray_profit    = ($request->tray_need == 'yes')
                            ? ($trayQty * 20)
                            : 0;

        $total_profit   = $product_profit + $tray_profit;

        // ======================================
        // 🔹 STEP 9: UPDATE ITEM
        // ======================================
        $oldItem->update([
            'product_id'     => $product->id,
            'product_name'   => $product->product_name,
            'size'           => $product->size,
            'color'          => $product->color,

            'quantity'       => $trayQty,
            'eggs'           => $eggs,
            'tray_use'       => $trayQty,

            'sale_price'     => $request->sale_price,
            'purchase_price' => $request->purchase_price,
            'total'          => $request->total_price,
            'profit'         => $total_profit,
        ]);

        // ======================================
        // 🔻 STEP 10: DEDUCT PRODUCT AGAIN
        // ======================================
        $product->decrement('totaleggs', $eggs);
        $product->decrement('quantity', $trayQty);

        // ======================================
        // 🔥 STEP 11: TRAY LOGIC AGAIN
        // ======================================
        if ($request->tray_need == 'yes' && $request->tray_id) {

            $selectedTray = Tray::findOrFail($request->tray_id);

            $trays = Tray::where('tcolor', $selectedTray->tcolor)->get();

            $totalAvailable = $trays->sum('quantity');

            if ($totalAvailable < $trayQty) {
                return back()->with(
                    'error',
                    'Only '.$totalAvailable.' trays available'
                );
            }

            $remaining = $trayQty;

            foreach ($trays as $tray) {

                if ($remaining <= 0) {
                    break;
                }

                if ($tray->quantity >= $remaining) {

                    $used = $remaining;

                    $tray->decrement('quantity', $remaining);

                    $remaining = 0;

                } else {

                    $used = $tray->quantity;

                    $remaining -= $used;

                    $tray->update([
                        'quantity' => 0
                    ]);
                }

                // ✅ SAVE NEW TRANSACTION
                TrayTransaction::create([
                    'tray_id'      => $tray->id,
                    'customer_id'  => $customer->id,
                    'type'         => TrayTransaction::TYPE_OUT,
                    'quantity'     => $used,
                    'reference_id' => $invoice->id,
                ]);
            }
        }

        DB::commit();

        return redirect()
            ->route('salesinvoices.index')
            ->with('success', 'Sales Invoice Updated Successfully');

    } catch (\Exception $e) {

        DB::rollback();

        dd($e->getMessage());
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoice = SalesInvoice::with('items')->findOrFail($id);
        $item = $invoice->items->first();

        if ($item) {
            // Restore product stock
            $product = Products::find($item->product_id);
            if ($product) {
                $product->increment('totaleggs', $item->eggs);
                $product->increment('quantity', $item->quantity);
            }

            // Restore tray stock
            $transactions = TrayTransaction::where('reference_id', $invoice->id)->get();
            foreach ($transactions as $t) {
                $tray = Tray::find($t->tray_id);
                if ($tray) {
                    $tray->increment('quantity', $t->quantity);
                }
                $t->delete();
            }
        }

        SalesInvoiceItem::where('invoice_id', $id)->delete();
        $invoice->delete();

        return redirect()->route('salesinvoices.index')
            ->with('success', 'Deleted successfully');
    }

    public function bill($id)
    {
        $salesInvoice = SalesInvoice::with('items.product')->findOrFail($id);
        $salesInvoiceItems = $salesInvoice->items;

        return view('salesinvoices.bill', compact('salesInvoice', 'salesInvoiceItems'));
    }

    public function show($id)
    {
        $salesInvoice = SalesInvoice::with('items.product')->findOrFail($id);
        $salesInvoiceItems = $salesInvoice->items;

        return view('salesinvoices.show', compact('salesInvoice','salesInvoiceItems'));
    }
}
