<?php

namespace App\Http\Controllers;

use App\Models\Customer; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
{
    return view('home');
}
        public function dashboard()
    {
        $totalSales = DB::table('sales_invoices')->sum('total_price');

        $totalPurchase = DB::table('purchase_invoices')->sum('total_price');

        $totalExpenses = DB::table('expenses')->sum('amount');

        $totalProfit = $totalSales - $totalPurchase - $totalExpenses;

       $chartData = DB::table('sales_invoices')
            ->selectRaw('DATE_FORMAT(invoice_date, "%Y-%m") as month, SUM(total_price) as sales')
            ->groupBy('month')
            ->pluck('sales', 'month');

        $purchaseData = DB::table('purchase_invoices')
            ->selectRaw('DATE_FORMAT(invoice_date, "%Y-%m") as month, SUM(total_price) as purchase')
            ->groupBy('month')
            ->pluck('purchase', 'month');

        $months = collect()
            ->merge($chartData->keys())
            ->merge($purchaseData->keys())
            ->unique()
            ->sort()
            ->values();

        return view('home', compact('chartData', 'purchaseData', 'months','totalSales','totalPurchase','totalExpenses','totalProfit'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   
}
