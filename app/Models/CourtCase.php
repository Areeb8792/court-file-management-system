<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'case_number',
    'title',
    'description',
    'category',
    'fir_number',
    'petitioner',
    'respondent',
    'priority',
    'status',
    'judge_id',
    'lawyer_id',
    'clerk_id',
    'filed_at',
    'closed_at',
    'judgment'
])]
class CourtCase extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'filed_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    // Relationships
    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function clerk()
    {
        return $this->belongsTo(User::class, 'clerk_id');
    }

    public function hearings()
    {
        return $this->hasMany(Hearing::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
