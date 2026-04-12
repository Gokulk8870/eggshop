<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
     protected $fillable = [
        'name',
        'phno',
        'email',
        'addr',
        'company_name',
        'status'
    ];
}
