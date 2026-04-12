<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrayTransaction extends Model
{
    protected $fillable = [
        'tray_id',
        'customer_id',
        'type',
        'quantity',
        'reference_id',
         'supplier_id',
    ];
    public function tray()
    {
        return $this->belongsTo(Tray::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
}
