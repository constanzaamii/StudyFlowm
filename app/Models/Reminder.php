<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'title',
        'message',
        'reminder_date',
        'is_sent',
    ];

    protected $casts = [
        'reminder_date' => 'datetime',
        'is_sent' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('is_sent', false);
    }

    public function scopeOverdue($query)
    {
        return $query->where('reminder_date', '<', now())
                    ->where('is_sent', false);
    }
}
