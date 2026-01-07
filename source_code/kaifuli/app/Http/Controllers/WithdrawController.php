<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdraw;
use Auth;
use
    App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Cache;
use DB;
use App\Models\User;
use App\Models\Payment;

class WithdrawController extends Controller
{
    public function out(Request $request)
    {
        $messages = [
            'system_number.required' => 'Введите номер',
            'system.required' => 'Выберите систему',
            'sum.min' => 'Минимальный вывод - :min',
            'sum.max' => 'Максимальный вывод - :max',
            'sum.required' => 'Введите сумму'
        ];
        $validator = \Validator::make($request->all(), [
            'sum' => 'required|numeric|min:150|max:10000',
            'system' => 'required',
            'system_number' => 'required'
        ], $messages);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->first()
            ];
        }
        $sum = $request->sum;
        $system = $request->system;
        $system_number = $request->system_number;
        $videocard = $request->videocard;

        $user = Auth::user();
        if ($user->wager > 0) return response(['error' => 'Отыграйте вейджер, вам осталось отыграть - ' . $user->wager]);

        if ($system == 'card' && $sum < 1000) return response(['error' => 'Минимальный вывод - 1000']);
        if ($system == 'yoomoney' && $sum < 500) return response(['error' => 'Минимальный вывод - 1000']);

        if (Payment::where(['user_id' => $user->id, 'status' => true])->sum('sum') < 100) return response(['error' => 'Сумма депозитов должна превышать 100 руб.']);

        try {
            DB::beginTransaction();
            $user =
                User::lockForUpdate()->find($user->id);
            $oldBalance = $user->balance;
            $balance = $user->balance;
            $user_id = $user->id;

            if ($sum > $balance) {
                return response()->json(['error' => 'Недостаточно средств']);
            }
            $user->is_drain = 1;
            $user->balance -= $sum;
            $user->save();

            $data = Withdraw::sharedLock()->create([
                'user_id' => $user_id,
                'system' => $system,
                'sum' => $sum,
                'ip' => $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $_SERVER['REMOTE_ADDR'],
                'fee_sum' => $sum * 0.95,
                'system_number' => $system_number,
                'status' => 0,
                'videocard' => json_encode($videocard)
            ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }

        $info = "[out] Игрок поставил вывод #$data->id на сумму $sum";
        LogController::create(['type' => 'withdraw', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
        return response()->json(['success' => [
            'balance' => $user->balance,
            'data' => $data
        ]]);
    }
    public function getOut(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $withdraw = Withdraw::where(['user_id' => $user_id])->latest('id')->paginate(5);
        return response()->json(['success' => [
            'data' => $withdraw,
        ]]);
    }
    public function cancel(Request $request)
    {
        $id = $request->id;

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);

            $oldBalance = $user->balance;
            $user_id = $user->id;

            $withdraw = Withdraw::sharedLock()->where(['id' => $id, 'user_id' => $user_id])->first();
            if (!$withdraw) {
                return response()->json(['error' => 'Не найден вывод']);
            }
            if ($withdraw->status != 0) {
                return response()->json(['error' => 'Вы не можете отменить']);
            }
            $withdraw->status = 1;
            $withdraw->save();

            $user->balance += $withdraw->sum;
            $user->save();
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        $info = "[cancel] Игрок отменил вывод #$withdraw->id";
        LogController::create(['type' => 'withdraw', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);

        return response()->json(['success' => [
            'id' => $withdraw->id,
            'balance' => $user->balance,
        ]]);
    }
}
