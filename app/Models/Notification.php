<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'type',
        'title',
        'message',
        'related_type',
        'related_id',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime'
    ];

    // Polymorphic relationship
    public function notifiable()
    {
        return $this->morphTo();
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
        return $this;
    }

    // Check if unread
    public function isUnread()
    {
        return is_null($this->read_at);
    }
}