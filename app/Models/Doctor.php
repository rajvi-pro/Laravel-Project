<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'qualification',
        'experience_years',
        'consultation_fee',
        'password',
        'is_available_today',
        'unavailable_message',
        'availability_date'
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'consultation_fee' => 'decimal:2',
        'is_available_today' => 'boolean',
        'availability_date' => 'date'
    ];

    protected $hidden = [
        'password'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalReports()
    {
        return $this->hasMany(MedicalReport::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }

    public function unavailabilities()
    {
        return $this->hasMany(DoctorUnavailability::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // Check if doctor is available today
    public function isAvailableToday()
    {
        if ($this->availability_date && $this->availability_date->toDateString() === now()->toDateString()) {
            return $this->is_available_today;
        }
        return true; // Default to available if no date set
    }

    // Get availability message for today
    public function getUnavailabilityMessage()
    {
        if ($this->availability_date && $this->availability_date->toDateString() === now()->toDateString()) {
            return $this->unavailable_message;
        }
        return null;
    }
}