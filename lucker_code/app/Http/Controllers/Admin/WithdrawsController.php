<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Withdraw;
use App\User;
use Illuminate\Http\Request;

class WithdrawsController extends Controller
{
    public function index()
    {
        return view('admin.withdraws.index');
    }

    public function decline(Request $request)
    {
        $withdraw = Withdraw::query()->find($request->id);

        if(!$withdraw) {
            return [
                'error' => 'Выплата отменена пользователем'
            ];
        }

        if($withdraw->status > 0) {
            return [
                'error' => 'Статус выплаты уже изменен ранее'
            ];
        }

        if($request->status == 2) {

            if($request->returnBalance == 1) {
                $user = User::where('id', $withdraw->user_id)->lockForUpdate()->first();
                $user->balance += $withdraw->sum;
                $user->save();
            }

            $withdraw->update([
                'status' => $request->status,
                'reason' => $request->reason
            ]);
        }
    }

    public function send(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $withdraw = Withdraw::where('id', $id)->lockForUpdate()->first();

        if(!$withdraw) {
            return [
                'error' => true,
                'message' => 'Выплата отменена пользователем',
                'reload' => true
            ];
        }

        if($withdraw->status > 0) {
            return [
                'error' => true,
                'message' => 'Статус выплаты уже изменен ранее',
                'reload' => true
            ];
        }

        $withdraw->update([
            'status' => 3
        ]);

        return [
            'message' => 'Выплата отправлена',
            'status' => $withdraw->status
        ];
    }

    public function waitingSend(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        $withdraw = Withdraw::where('id', $id)->lockForUpdate()->first();

        if(!$withdraw) {
            return [
                'error' => true,
                'message' => 'Выплата отменена пользователем',
                'reload' => true
            ];
        }

        if($withdraw->status != 3) {
            return [
                'error' => true,
                'message' => 'Статус выплаты уже изменен ранее',
                'reload' => true
            ];
        }

        $withdraw->update([
            'status' => 1
        ]);

        return [
            'message' => 'Выплата завершена',
            'status' => $withdraw->status
        ];
    }

    public function getById(Request $request)
    {
    
        $withdraw = Withdraw::where('withdraws.id', $request->id)
            ->join('users', 'users.id', '=', 'withdraws.user_id')
            ->where('users.is_youtuber', '<', 1)
            ->select('users.username as username', 'withdraws.*')
            ->first();
        return $withdraw;
    }
}
