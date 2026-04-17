<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TrayController;
use App\Http\Controllers\SalescltsController;
use App\Http\Controllers\PurchaseconController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Redirect based on role
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    if (!Auth::check()) {
        return redirect('/login');
    }

    return match (Auth::user()->role) {
        'admin' => redirect('/admin/dashboard'),
        'employee' => redirect('/employee/dashboard'),
        default => abort(403),
    };
});


// =====================================================
// COMMON ACCESS (Admin + Employee)
// =====================================================
Route::middleware(['auth'])->group(function () {

    Route::get('/employee/dashboard', [HomeController::class, 'dashboard'])
        ->name('employee.dashboard');

    // Customers
    Route::get('/customers/search',[CustomerController::class,'search'])
        ->name('customers.search');
    Route::resource('customers', CustomerController::class);

    // Sales
    Route::get('/cussearch', [SalesInvoiceController::class,'cussearch'])
        ->name('salesinvoices.cussearch');

    Route::get('/generate-upi', [SalesInvoiceController::class, 'generateUpi']);

    Route::get('salesinvoices/bill/{id}', [SalesInvoiceController::class, 'bill'])
        ->name('salesinvoice.bill');

    Route::get('salesinvoices/search',[SalesInvoiceController::class,'salescussearch'])
        ->name('salescus.search');

    Route::resource('salesinvoices', SalesInvoiceController::class);

    // Logout
    Route::get('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});


// =====================================================
// ADMIN ONLY
// =====================================================
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [HomeController::class, 'dashboard'])
        ->name('admin.dashboard');

    // Purchase
    Route::get('/supsearch',[PurchaseInvoiceController::class,'supsearch'])
        ->name('suppliersearch');

    Route::get('/get-price/{id}', [PurchaseInvoiceController::class, 'getPrice']);

    Route::get('purchaseinvoices/bill/{id}', [PurchaseInvoiceController::class, 'bill'])
        ->name('purchaseinvoice.bill');

    Route::resource('purchaseinvoices', PurchaseInvoiceController::class);

    // Suppliers
    Route::get('suppliers/search',[SupplierController::class,'search'])
        ->name('suppliers.search');
    Route::resource('suppliers', SupplierController::class);

    // Tray
    Route::get('/tray/search',[TrayController::class,'search'])
        ->name('trays.search');

    Route::get('/tray-return', [TrayController::class, 'returnForm'])
        ->name('tray.return.form');

    Route::post('/tray-return', [TrayController::class, 'storeReturn'])
        ->name('tray.return.store');

    Route::resource('trays', TrayController::class);

    // Products
    Route::get('/products/search', [SalesInvoiceController::class, 'getProducts'])
        ->name('products.getproducts');
    
    Route::get('/product/search',[ProductsController::class,'productsearch'])->name('products.search');
    Route::resource('products', ProductsController::class);

    // Other modules
    Route::resource('salesclts', SalescltsController::class);
    Route::resource('purchasecon', PurchaseconController::class);
    Route::resource('expenses', ExpenseController::class);

    // Users
    Route::resource('users', UserController::class);

    // Reports
    Route::prefix('reports')->group(function(){
        Route::get('/stock',[ReportController::class,"stockreport"])->name('report.stock');
        Route::get('/product',[ReportController::class,'productreport'])->name('report.product');
        // Route::get('/products/report/search',[ReportController::class,'productsearch'])->name('proname.report');
        Route::get('/tray',[ReportController::class,'trayreport'])->name('report.tray');
        Route::get('/sales',[ReportController::class, 'salesreport'])->name('report.sales');
        Route::get('/purchases',[ReportController::class,'purchasereport'])->name('report.purchase');
        Route::get('/profitloss',[ReportController::class,'profitlossreport'])->name('report.profitloss');
    });

});


Route::get('/migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration completed';
});

// Auth routes
Auth::routes();