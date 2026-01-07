<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Log;

class LogController extends Controller
{
    static function create($arr)
    {
        $user = Auth::user();

        Log::create([
            'user_id' => $user->id,
            'type' => $arr['type'],
            'info' => $arr['info'],
            'oldBalance' => $arr['oldBalance'],
            'newBalance' => $arr['newBalance']
        ]);
    }
}
