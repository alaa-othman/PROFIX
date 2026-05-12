<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'address',
        'birth_date',
        'role',
        'registered_at',
        'out_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'registered_at' => 'datetime',
        'out_at' => 'datetime',
    ];

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Check role methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isMechanic()
    {
        return $this->role === 'mechanic';
    }

    public function isCallCenter()
    {
        return $this->role === 'call_center';
    }

    public function isAccountant()
    {
        return $this->role === 'accountant';
    }
}
