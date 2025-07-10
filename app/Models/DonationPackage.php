<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonationPackage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'amount',
        'category',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'integer',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
