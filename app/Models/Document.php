<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'court_case_id',
    'user_id',
    'name',
    'file_path',
    'file_size',
    'document_type',
    'version',
    'parent_id'
])]
class Document extends Model
{
    use HasFactory;

    // Relationships
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    public function versions()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }
}
