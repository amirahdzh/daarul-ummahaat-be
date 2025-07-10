<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    const UPDATED_AT = null; // Only has created_at

    protected $fillable = [
        'user_id',
        'action',
        'target_table',
        'target_id',
        'note'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
