<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function createdPrograms()
    {
        return $this->hasMany(Program::class, 'created_by');
    }

    public function createdFundraisers()
    {
        return $this->hasMany(Fundraiser::class, 'created_by');
    }

    public function createdActivities()
    {
        return $this->hasMany(Activity::class, 'created_by');
    }

    public function adminLogs()
    {
        return $this->hasMany(AdminLog::class);
    }

    // Helper methods for role checking
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isUser()
    {
        return $this->hasRole('user');
    }

    public function isEditor()
    {
        return $this->hasRole('editor');
    }
}
