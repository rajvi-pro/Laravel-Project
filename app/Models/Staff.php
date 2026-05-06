<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'department',
        'shift',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}