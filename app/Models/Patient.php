<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'pincode',
        'blood_group',
        'emergency_contact',
        'password'
    ];

    protected $casts = [
        'date_of_birth' => 'date'
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

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}