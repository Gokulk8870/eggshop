<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceItem extends Model
{
    use HasFactory;

   protected $fillable = [
    'invoice_id',
    'product_id',
    'product_name',
    'size',
    'color',
    'quantity',
    'eggs',
    'sale_price',
    'purchase_price',
    'total',
    'profit',
    'tray_use'
        ];

    // 🔗 Relationship → belongs to Invoice
    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }

    // 🔗 Relationship → belongs to Product
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}