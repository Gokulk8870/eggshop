<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tray extends Model
{
    protected $fillable = [
        'tcolor',
        'quantity',
    ];
    public function transactions()
{
    return $this->hasMany(TrayTransaction::class, 'tray_id');
}
}