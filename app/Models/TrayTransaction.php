<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrayTransaction extends Model
{
    // Type constants — use these everywhere instead of raw strings
    const TYPE_IN     = 'in';      // Supplier delivers trays (purchase)
    const TYPE_OUT    = 'out';     // Trays sent to customer (sale)
    const TYPE_RETURN = 'return';  // Customer returns trays
    const TYPE_DAMAGE = 'damage';  // Trays written off as damaged
    const TYPE_LOST   = 'lost';    // Trays written off as lost

    const TYPES = [self::TYPE_IN, self::TYPE_OUT, self::TYPE_RETURN, self::TYPE_DAMAGE, self::TYPE_LOST];

    protected $fillable = [
        'tray_id',
        'customer_id',
        'supplier_id',
        'type',
        'quantity',
        'reference_id',
    ];

    // Prevent negative quantities at model level
    protected static function booted(): void
    {
        static::saving(function (self $transaction) {
            if ($transaction->quantity <= 0) {
                throw new \InvalidArgumentException('TrayTransaction quantity must be greater than zero.');
            }
            if (!in_array($transaction->type, self::TYPES, true)) {
                throw new \InvalidArgumentException("Invalid TrayTransaction type: {$transaction->type}");
            }
        });
    }

    public function tray()
    {
        return $this->belongsTo(Tray::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
