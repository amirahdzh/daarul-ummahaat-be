<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'donation_package_id',
        'fundraiser_id',
        'title',
        'name',
        'email',
        'phone',
        'category',
        'amount',
        'status',
        'proof_image',
        'confirmation_note',
        'confirmed_at'
    ];

    protected $casts = [
        'amount' => 'integer',
        'confirmed_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donationPackage()
    {
        return $this->belongsTo(DonationPackage::class);
    }

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}
