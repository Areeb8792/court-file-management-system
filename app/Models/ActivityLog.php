<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'court_case_id',
    'action',
    'details',
    'ip_address'
])]
class ActivityLog extends Model
{
    use HasFactory;

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class);
    }
}
