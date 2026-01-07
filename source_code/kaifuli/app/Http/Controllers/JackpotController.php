<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use
    App\Http\Controllers\LogController;
use App\Models\Log;
use Illuminate\Support\Facades\Cache;

class JackpotController extends Controller
{
    public function play(Request $request)
    {
        $messages = [
            'bet.min' => 'Минимальная ставка - :min',
            'bet.max' => 'Максимальная ставка - :max',
        ];
        $validator = \Validator::make($request->all(), [
            'bet' => 'required|numeric|min:1|max:1000',
        ], $messages);

        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->first()
            ];
        }
        $bet = $request->bet;
        $user = Auth::user();
        //return response(['error' => '5sec']);
        $status = DB::table('jackpot_status')->where(['id' => 1])->first();
        if ($status->status == 1) {
            return response(['error' => 'Игра уже началась!']);
        }
        if (Cache::has('user.id-' . $user->id)) {
            return response(['error' => 'Не спешите!']);
        }
        if ($user->balance < $bet) {
            return response()->json(['error' => 'Недостаточно средств']);
        }
        Cache::put('user.id-' . $user->id, '', 1);
        try {
            DB::beginTransaction();
            $user =
                User::lockForUpdate()->find($user->id);
            $oldBalance = $user->balance;

            $user->balance -= $bet;
            $user->save();

            $lastBet = DB::table('jackpot_bet')->lockForUpdate()->orderBy('id', 'desc')->first();

            $fromTicket = 1;
            $toTicket = 1;
            if ($lastBet) $fromTicket = $lastBet->toTicket;
            $toTicket = $fromTicket + floor($bet * 10);

            DB::table('jackpot_bet')->sharedLock()->insert([
                'user_id' => $user->id,
                'name' => $user->name,
                'img' => $user->img,
                'bet' => $bet,
                'fromTicket' => $fromTicket,
                'toTicket' => $toTicket,
            ]);
            $bank = DB::table('jackpot_bet')->sum('bet');
            $infos = DB::table('jackpot_bet')->distinct()->get(['user_id']);
            $user_chance = [];
            foreach ($infos as $info) {
                $sumBet = DB::table('jackpot_bet')->where(['user_id' => $info->user_id])->sum('bet');
                $chance = $sumBet / $bank * 100;
                $user_chance[] = ['user_id' => $info->user_id, 'chance' => Number_format($chance, 2)];
                DB::table('jackpot_bet')->lockForUpdate()->where(['user_id' => $info->user_id])->update(['chance' => $chance]);
            }
            Redis::publish('newBetJackpot', json_encode([
                'user_id' => $user->id,
                'name' => $user->name,
                'img' => $user->img,
                'bet' => $bet,
                'fromTicket' => $fromTicket,
                'toTicket' => $toTicket,
                'chance' => 0,
                'user_chance' => $user_chance
            ]));
            $info = "[play] Игрок поставил $bet в джекпот";
            LogController::create(['type' => 'jackpot', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        return response()->json(['success' => [
            'balance' => $user->balance,
        ]]);
    }
    public function getSlider()
    {
        $tickets = DB::table('jackpot_bet')->orderBy('id', 'desc')->first();
        $tickets = $tickets->toTicket;

        $jackpots = DB::table('jackpot_bet')->orderBy('id', 'desc')->get();

        $winner = [];
        $bank = 0;
        $winTicket = mt_rand(1, $tickets);
        foreach ($jackpots as $jackpot) {
            if ($jackpot->fromTicket <= $winTicket && $jackpot->toTicket >= $winTicket) {
                $winner = $jackpot;
            }
        }
        $winner->bank = DB::table('jackpot_bet')->sum('bet');
        $bets_user = DB::table('jackpot_bet')->where(['user_id' => $winner->user_id])->sum('bet');

        $winner->bank = ($winner->bank - $bets_user) * 0.9 + $bets_user;

        $jackpotTop = DB::table('jackpot_status')->where(['id' => 1])->first();
        $jackpotTop->comissia += ($winner->bank - $bets_user) * 0.1;

        DB::table('jackpot_status')->where(['id' => 1])->update(['comissia' => $jackpotTop->comissia]);

        $slider = [];
        $gets =
            DB::table('jackpot_bet')->distinct()->get(['user_id']);

        foreach ($gets as $get) {
            $lounted = DB::table('jackpot_bet')->where(['user_id' => $get->user_id])->first();
            for ($i = 0; $i < ceil($lounted->chance); $i++) {
                $slider[] = [
                    'img' => $lounted->img
                ];
            }
        }
        shuffle($slider);

        $win = [
            'img' => $winner->img
        ];

        $slider[79] = $win;
        DB::table('jackpot_bet')->truncate();
        return response()->json(
            [
                'slider' => $slider,
                'winUser' => $winner,
                'rand' => mt_rand(15, 80),
            ]
        );
    }
    public function startGame()
    {
        $result = DB::table('jackpot_bet')->distinct()->get(['user_id'])->count();
        if ($result >= 2) {
            return response()->json(['game' => true]);
        }
        return response()->json(['game' => false]);
    }
    public function addCash(Request $request)
    {
        $winUser = $request->winUser;
        try {
            DB::beginTransaction();
            $user =
                User::lockForUpdate()->find($winUser['user_id']);
            $oldBalance = $user->balance;
            $user->balance += $winUser['bank'];
            $user->save();
            DB::table('jackpot_win')->sharedLock()->insert([
                'user_id' => $user->id,
                'chance' => $winUser['chance'],
                'win' => $winUser['bank']
            ]);
            Log::create([
                'user_id' => $user->id,
                'type' => 'jackpot',
                'info' => "[win] Игрок выиграл в джекпоте " . $winUser['bank'] . " с шансом " . $winUser['chance'],
                'oldBalance' => $oldBalance,
                'newBalance' => $user->balance
            ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }

        return response()->json(true);
    }
}
