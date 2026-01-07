<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Bank;
use App\Http\Controllers\BonusController;
use Illuminate\Support\Facades\Redis;
use
    App\Http\Controllers\LogController;

use DB;
use App\Models\User;

class BubblesController extends Controller
{
    //
    public function newPurple()
    {
        return
            1000000 / (intval(mt_rand(1, 1000000) / 1000000 * 1000000) + 1);
    }
    public function updateBank($status, $num)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);
            if ($user->is_youtuber) {
                DB::commit();
                return;
            }
            $bank = Bank::lockForUpdate()->whereId(1)->first();

            if ($status == 'win') {
                Bank::whereId(1)->update([
                    'bubbles' => $bank->bubbles - $num,
                ]);
            } elseif ($status == 'lose') {
                Bank::lockForUpdate()->whereId(1)->update([
                    'bubbles' => $bank->bubbles + ($num * (1 - ($bank->fee_bubbles / 100))),
                ]);
            }
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
    }
    public function play(Request $request)
    {
        $messages = [
            'bet.min' => 'Минимальная ставка - :min',
            'bet.max' => 'Максимальная ставка - :max',
            'purple.min' => 'Минимальная цель - x:min',
            'purple.max' => 'Максимальный цель - x:max',
        ];
        $validator = \Validator::make($request->all(), [
            'bet' => 'required|numeric|min:1|max:1000',
            'purple' => 'required|numeric|min:1.05|max:1000000',
        ], $messages);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->first()
            ];
        }

        $bet = $request->bet;
        $purple = $request->purple;
        $user = Auth::user();
        // if ($user->is_admin == 0) return response(['error' => 'Упс! Игра недоступна!']);

        try {
            DB::beginTransaction();
            $user =
                User::lockForUpdate()->find($user->id);
            $oldBalance = $user->balance;

            if ($user->balance < $bet) {
                return response()->json(['error' => 'Недостаточно средств']);
            }
            $randPurple = $this->newPurple();

            $bank = Bank::lockForUpdate()->whereId(1)->first();
            $bank->normal_bubbles == 0 ? $bank->normal_bubbles = 1 : null;
            if ($randPurple >= $purple) {
                $win = $bet * $purple;
                $status = 'win';


                if (($bank->bubbles - ($win - $bet)) / $bank->normal_bubbles < mt_rand(1, 100) / 100 && !$user->is_youtuber
                    || $user->is_drain && $user->is_drain_chance > mt_rand(1, 100)  && !$user->is_youtuber
                ) {
                    $status = 'lose';
                    $status_lose = false;
                    while (!$status_lose) {
                        $randPurple =
                            $this->newPurple();
                        if ($randPurple < $purple) {
                            $status_lose = true;
                            $win = 0;
                        }
                    }
                }
            } else {
                $status = 'lose';
                $win = 0;
            }
            if ($status == 'lose') BonusController::raceBackCalc('bubbles', $bet);
            $status == 'win' ? $user->balance += $win - $bet : $user->balance -= $bet;
            $user->save();
            $status == 'win' ? $this->updateBank('win', $win - $bet) : $this->updateBank('lose', $bet);
            $status == 'win' ? $info = "[play] Победа в игре ставка $bet с целью $purple выигрыш $win" :

                $info = "[play] Проигрыш в игре ставка $bet с целью $purple";
            if ($status != 'win')
                DB::table('gameJackpotes')->increment('sum', $bet * 0.01);
            if (mt_rand(1, 1000) <= 1 && $purple >= 4 && $status != 'win') {
                $jackpotes = DB::table('gameJackpotes')->where('id', 1)->first();
                $jackpot_sum = $jackpotes->sum * ($bet * (mt_rand(1, 100) / 100));
                DB::table('gameJackpotes')->decrement('sum', $jackpot_sum);
                $user->balance += $jackpot_sum;
                $user->save();
                DB::table('gamejackpotWin')->insert([
                    'user_id' => $user->id,
                    'winSum' => $jackpot_sum,
                    'game' => 'bubbles'
                ]);
                Redis::publish('jackpotesWin', json_encode([
                    'user_id' => $user->id,
                    'game' => 'bubbles',
                    'name' => $user->name,
                    'jackpot_sum' => $jackpot_sum,
                ]));
            }

            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        LogController::create(['type' => 'bubbles', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
        Redis::publish('newGame', json_encode([
            'id' => mt_rand(1, 1000),
            'game' => 'bubbles',
            'name' => $user->name,
            'bet' => number_format(
                $bet,
                2
            ),
            'coff' => number_format($purple, 2),
            'result' => $status,
            'win' => number_format(
                $win,
                2
            )
        ]));
        \DB::table('bubbles')->sharedLock()->insert([
            'user_id' => $user->id,
            'bet' => $bet,
            'purple' => $purple,
            'win' => $win
        ]);
        return response()->json([
            'success' => [
                'balance' => $user->balance,
                'status' => $status,
                'win' => $win,
                'randPurple' => $randPurple,
                'bet' => $bet,
                'text' => $status == 'lose' ? 'Выпало x' . round($randPurple, 2) : null
            ]
        ]);
    }
}
