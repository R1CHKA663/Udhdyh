<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/api/vkontakte/success',
        '/api/payment/fk',
        '/api/payment/linepay',
        '/api/payment/xmpay',
        '/api/jackpot/getSlider',
        '/api/jackpot/startGame',
        '/api/jackpot/addCash'
    ];
}
