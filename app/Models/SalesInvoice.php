<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
     protected $table = 'sales_invoices';
    protected $fillable = [
    'inv_number',
    'customer_name',
    'phno',
    'total_price',
    'payment_method',
    'invoice_date',
       'tray_id',
    'tray_need', 
    'eggs',
    'sale_price',// ✅
];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function tray(){
    return $this->belongsTo(Tray::class);
}
    public function salesclt(){
        return $this->belongsTo(Salesclt::class);
    }
  public function items()
{
    return $this->hasMany(SalesInvoiceItem::class, 'invoice_id', 'id');
}
}
