<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded  = [];
    protected $casts = [
        'bonus_vk' => 'boolean',
        'bonus_tg' => 'boolean',
        'hourly_bonus' => 'integer',
    ];
}
