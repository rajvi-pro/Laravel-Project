<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabResult extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'test_name',
        'test_date',
        'result',
        'normal_range',
        'status',
        'notes',
        'charge'
    ];

    protected $casts = [
        'test_date' => 'date'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}