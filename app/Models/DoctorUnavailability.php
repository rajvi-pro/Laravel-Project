<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorUnavailability extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'doctor_id',
        'unavailable_date',
        'end_date',
        'reason'
    ];

    protected $casts = [
        'unavailable_date' => 'date',
        'end_date' => 'date'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Check if a given appointment falls within this unavailability range
    public function affectsAppointment($appointmentDate, $appointmentTime = null)
    {
        $appDate = $appointmentDate instanceof \Carbon\Carbon 
            ? $appointmentDate->toDateString() 
            : $appointmentDate;
        
        $startDate = $this->unavailable_date->toDateString();
        $endDate = ($this->end_date ?? $this->unavailable_date)->toDateString();

        // Check if appointment date falls within unavailable date range
        return strtotime($appDate) >= strtotime($startDate) && 
               strtotime($appDate) <= strtotime($endDate);
    }
}