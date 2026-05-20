<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role Helper Methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClerk(): bool
    {
        return $this->role === 'clerk';
    }

    public function isJudge(): bool
    {
        return $this->role === 'judge';
    }

    public function isLawyer(): bool
    {
        return $this->role === 'lawyer';
    }

    public function isPublic(): bool
    {
        return $this->role === 'public';
    }

    // Relationships
    public function casesAsJudge()
    {
        return $this->hasMany(CourtCase::class, 'judge_id');
    }

    public function casesAsLawyer()
    {
        return $this->hasMany(CourtCase::class, 'lawyer_id');
    }

    public function casesAsClerk()
    {
        return $this->hasMany(CourtCase::class, 'clerk_id');
    }

    public function hearingsAsJudge()
    {
        return $this->hasMany(Hearing::class, 'judge_id');
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
