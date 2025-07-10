<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fundraiser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'target_amount',
        'current_amount',
        'deadline',
        'image',
        'status',
        'created_by',
        'is_published'
    ];

    protected $casts = [
        'target_amount' => 'integer',
        'current_amount' => 'integer',
        'deadline' => 'date',
        'is_published' => 'boolean'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
