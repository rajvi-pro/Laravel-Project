<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = [
        'patient_id',
        'member_name',
        'relationship',
        'date_of_birth',
        'gender',
        'phone',
        'blood_group',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
