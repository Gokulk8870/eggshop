<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInvoice extends Model
{
    protected $table = 'purchase_invoices';

    protected $fillable = [
        'inv_number',
        'supplier_name',
        'phno',
        'purchase_price',
        'invoice_date',
        'total_price',
        'payment_method',
        'tray_need',
        'tray_id'
    ];

    public function tray()
    {
        return $this->belongsTo(Tray::class);
    }

    public function items()
{
    return $this->hasMany(purchase_invoices_items::class, 'invoice_id');
}
}