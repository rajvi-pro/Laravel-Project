<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'generic_name',
        'manufacturer',
        'description',
        'category',
        'unit_price',
        'stock_quantity',
        'expiry_date',
        'staff_id'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'expiry_date' => 'date'
    ];

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff::class);
    }
}