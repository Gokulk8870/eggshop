<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'product_name',
        'size',
        'color',
        'sale_price',
        'quantity',
        'tray_color',
        'status',
        'purchase_price',
        'eggprice',
        'totaleggs'
    ];


    public function tray()
    {
        return $this->belongsTo(Tray::class);
    }

}