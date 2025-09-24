<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'evaluation_type',
        'grade',
        'weight',
        'evaluation_date',
        'comments',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'grade' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Scopes
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByEvaluationType($query, $type)
    {
        return $query->where('evaluation_type', $type);
    }
}
