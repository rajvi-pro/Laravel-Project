<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillItem extends Model
{
    use SoftDeletes;

    protected $table = 'bill_items';

    protected $fillable = [
        'billing_id',
        'type',
        'description',
        'amount',
        'quantity',
        'total'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer'
    ];

    /**
     * Get the billing associated with this item.
     */
    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
