<?php
// app/Models/Vehicle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    protected $fillable = [
        'type',
        'color',
        'model',
        'user_owned_id'
    ];

    // Relationships
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_owned_id');
    }
}
