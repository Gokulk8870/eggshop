<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\purchase_invoices_items;
use App\Models\SalesInvoiceItem;
use App\Models\SalesInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function stockreport(){
        
    $products=DB::table('products as p')
            ->select(
                'p.id',
                'p.product_name',
                'p.quantity as opening_stock',
                DB::raw('(select IFNULL(SUM(quantity),0) from purchase_invoices_items where product_id=p.id)as stock_in'),
                DB::raw('(select IFNULL(SUM(quantity),0) from sales_invoice_items where product_id=p.id)as stock_out'),
                DB::raw('(p.quantity+(select IFNULL(SUM(quantity),0) from purchase_invoices_items where product_id=p.id)-(SELECT IFNULL(SUM(quantity), 0) from sales_invoice_items where product_id=p.id)) as closing_stock')
                )->get();

    $trays = DB::table('trays as t')
    ->select(
        't.id',
        't.tcolor',
        't.quantity as opening_tray',
        DB::raw('(SELECT IFNULL(SUM(quantity),0) 
                  FROM tray_transactions 
                  WHERE tray_id = t.id AND type = "return") as tray_in'),
        DB::raw('(SELECT IFNULL(SUM(quantity),0) 
                  FROM tray_transactions 
                  WHERE tray_id = t.id AND type = "out") as tray_out'),
        DB::raw('(
            t.quantity
            +
            (SELECT IFNULL(SUM(quantity),0) 
             FROM tray_transactions 
             WHERE tray_id = t.id AND type = "return")
            -
            (SELECT IFNULL(SUM(quantity),0) 
             FROM tray_transactions 
             WHERE tray_id = t.id AND type IN ("out","damage","lost"))
        ) as closing_tray')
    )
    ->get();
    return view('reports.stockreport',compact('products','trays'));
    }
    public function productreport(Request $request){
        $query = DB::table('products as p')
            ->select(
                'p.id',
                'p.product_name',
                'p.quantity as opening_stock',
                DB::raw('(select IFNULL(SUM(quantity),0) from purchase_invoices_items where product_id=p.id)as stock_in'),
                DB::raw('(select IFNULL(SUM(quantity),0) from sales_invoice_items where product_id=p.id)as stock_out'),
                DB::raw('(p.quantity+(select IFNULL(SUM(quantity),0) from purchase_invoices_items where product_id=p.id)-(SELECT IFNULL(SUM(quantity), 0) from sales_invoice_items where product_id=p.id)) as closing_stock')
                );
         if ($request->product_name) {
            $query->where('p.product_name', 'like', '%' . $request->product_name . '%');
        }

         $products = $query->get();

        $productnames = products::all();
        return view('reports.productreport',compact('products','productnames'));
    }       
    public function trayreport(Request $request){
        $query = DB::table('trays as t')
            ->select(
                't.id',
                't.tcolor',
                't.quantity as opening_tray',
        DB::raw('(SELECT IFNULL(SUM(quantity),0) 
                  FROM tray_transactions 
                  WHERE tray_id = t.id AND type = "return") as tray_in'),
        DB::raw('(SELECT IFNULL(SUM(quantity),0) 
                  FROM tray_transactions 
                  WHERE tray_id = t.id AND type = "out") as tray_out'),
        DB::raw('(
            t.quantity
            +
            (SELECT IFNULL(SUM(quantity),0) 
             FROM tray_transactions 
             WHERE tray_id = t.id AND type = "return")
            -
            (SELECT IFNULL(SUM(quantity),0) 
             FROM tray_transactions 
             WHERE tray_id = t.id AND type IN ("out","damage","lost"))
        ) as closing_tray')
    );
    if($request->tcolor){
        $query->where('t.tcolor','like','%'.$request->tcolor.'%');
    }
    $trays=$query->get();
   
    return view('reports.trayreport',compact('trays'));
    }
    public function salesreport(Request $request){

        $query=DB::table('sales_invoices as s')
        ->select(
            's.id',
            's.customer_name',
            's.inv_number',
            's.invoice_date',
            's.total_price as total_amount',
            DB::raw('(select IFNULL(SUM(profit),0) from sales_invoice_items where invoice_id=s.id )as profit'),
            DB::raw('(select IFNULL(SUM(quantity),0) from sales_invoice_items where invoice_id=s.id )as total_items'),
            DB::raw('(SELECT IFNULL(SUM(eggs), 0) from sales_invoice_items where invoice_id=s.id )as eggscount'),
        );
        if($request->customer_name){
            $query->where('s.customer_name','like','%'.$request->customer_name.'%');
        }
        if($request->payment_method){
            $query->where('s.payment_method', 'like', '%'.$request->payment_method.'%');
        }

        $filter = $request->date_filter;
        if ($filter == 'today') {
            $query->whereDate('s.invoice_date', Carbon::today());
        }
        else if($filter=='yesterday'){
            $query->whereDate('s.invoice_date', Carbon::yesterday());
        
        }
        else if ($filter == 'this_week') {
            $query->whereBetween('s.invoice_date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        }
        else if($filter=='last_week'){
            $query->whereBetween('s.invoice_date',[
                Carbon::now()->subWeek()->startOfWeek(),
                Carbon::now()->subWeek()->endOfWeek()
            ]);
        }
        else if($filter=='this_month'){
            $query->whereBetween('s.invoice_date',[
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);
        }
        else if($filter=='last_month'){
            $query->whereBetween('s.invoice_date',[
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ]);
        }
        else if($filter=='this_year'){
            $query->whereBetween('s.invoice_date',[
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear()
            ]);
        }
        else if($filter=='last_year'){
            $query->whereBetween('s.invoice_date',[
                Carbon::now()->subYear()->startOfYear(),
                Carbon::now()->subYear()->endOfYear()
            ]);
        }
        $sales=$query->get();
        $customers=SalesInvoice::all();
        $paymentmethods=SalesInvoice::select('payment_method')->distinct()->get();
        return view('reports.salereport',compact('sales','customers','paymentmethods'));
    }
    public function purchasereport(){
        $purchases=DB::table('purchase_invoices as p')
        ->select(
            'p.id',
            'p.supplier_name',
            'p.inv_number',
            'p.invoice_date',
            'p.total_price as total_amount',
            DB::raw('(select IFNULL(SUM(quantity),0) from purchase_invoices_items where invoice_id=p.id )as total_items'),
            DB::raw('(SELECT IFNULL(SUM(eggs), 0) from purchase_invoices_items where invoice_id=p.id )as eggscount'),
        )->get();
        return view('reports.purchasereport',compact('purchases'));
    }
    public function profitlossreport()
{
   
    $sales = DB::table('sales_invoices')
        ->selectRaw('DATE_FORMAT(invoice_date, "%Y-%m") as month, SUM(total_price) as total_sales')
        ->groupBy('month')
        ->pluck('total_sales', 'month');

    $purchase = DB::table('purchase_invoices')
        ->selectRaw('DATE_FORMAT(invoice_date, "%Y-%m") as month, SUM(total_price) as total_purchase')
        ->groupBy('month')
        ->pluck('total_purchase', 'month');

    $expenses = DB::table('expenses')
        ->selectRaw('DATE_FORMAT(expense_date, "%Y-%m") as month, SUM(amount) as total_expenses')
        ->groupBy('month')
        ->pluck('total_expenses', 'month');

    
    $months = collect()
        ->merge($sales->keys())
        ->merge($purchase->keys())
        ->merge($expenses->keys())
        ->unique()
        ->sort();

    $report = [];

    foreach ($months as $month) {
        $totalSales = $sales[$month] ?? 0;
        $totalPurchase = $purchase[$month] ?? 0;
        $totalExpenses = $expenses[$month] ?? 0;

        $report[] = (object)[
            'month' => $month,
            'total_sales' => $totalSales,
            'total_purchase' => $totalPurchase,
            'total_expenses' => $totalExpenses,
            'profit' => $totalSales - $totalPurchase - $totalExpenses,
        ];

    }

    return view('reports.profitlossreport', compact('report'));
}
}
