<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'court_case_id',
    'hearing_date',
    'courtroom',
    'judge_id',
    'status',
    'remarks'
])]
class Hearing extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'hearing_date' => 'datetime',
        ];
    }

    // Relationships
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class);
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }
}
