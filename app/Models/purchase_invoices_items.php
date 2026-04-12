<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchase_invoices_items extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'tray_use',
        'purchase_price',
        'quantity',
        'eggs',
        'per_egg_price',
        'total',
    ];
    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'invoice_id');
    }
    public function purchaseInvoice()
{
    return $this->belongsTo(PurchaseInvoice::class, 'invoice_id');
}

    // 🔗 Relationship → belongs to Product
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
    
}
