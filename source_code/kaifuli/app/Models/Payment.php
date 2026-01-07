<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];
}
