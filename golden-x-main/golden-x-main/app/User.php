<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getLastMonthDepsAttribute() {
        return Payment::where('user_id', $this->id)
        ->where('status', 1)
        ->where('updated_at', '>=', Carbon::now()->subDays(30)->toDateTimeString())
        ->sum('sum');
    }
    public function getLastSevenDaysDepsAttribute() {
        return Payment::where('user_id', $this->id)
        ->where('status', 1)
        ->where('updated_at', '>=', Carbon::now()->subDays(7)->toDateTimeString())
        ->sum('sum');
    }


    public function getIsYoutuberAttribute() {
        return $this->admin == 3;
    }
}
