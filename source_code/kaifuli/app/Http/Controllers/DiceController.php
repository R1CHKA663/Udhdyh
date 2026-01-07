<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\Rule;
use App\Models\Bank;
use App\Http\Controllers\BonusController;
use Illuminate\Support\Facades\Redis;
use
    App\Http\Controllers\LogController;
use DB;
use App\Models\User;

class DiceController extends Controller
{
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
                Bank::lockForUpdate()->whereId(1)->update([
                    'dice' => $bank->dice - $num,
                ]);
            } elseif ($status == 'lose') {
                Bank::lockForUpdate()->whereId(1)->update([
                    'dice' => $bank->dice + ($num * (1 - ($bank->fee_dice / 100))),
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
            'chance.min' => 'Минимальная шанс - :min %',
            'chance.max' => 'Максимальный шанс - :max %',
        ];
        $validator = \Validator::make($request->all(), [
            'bet' => 'required|numeric|min:1|max:1000',
            'chance' => 'required|numeric|min:1|max:95',
            'btn' => [
                Rule::in(['down', 'up']),
                'required'
            ]
        ], $messages);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->first()
            ];
        }
        $bet = $request->bet;
        $chance = $request->chance;
        $btn = $request->btn;

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);
            $oldBalance = $user->balance;
            $bank = Bank::lockForUpdate()->whereId(1)->first();
            $bank->normal_dice == 0 ? $bank->normal_dice = 1 : null;

            if ($user->balance < $bet) {
                return response()->json(['error' => 'Недостаточно средств']);
            }
            $rand_num = mt_rand(1, 1000000);
            $min = intval(($chance / 100) * 999999);
            $max = intval(999999 - ($chance / 100) * 999999);
            if ($btn == 'down' && $rand_num < $min || $btn == 'up' && $rand_num > $max) {
                $status = 'win';
                $win = 100 / $chance * $bet;

                if (($bank->dice - ($win - $bet)) / $bank->normal_dice < mt_rand(1, 100) / 100 && !$user->is_youtuber
                    || $user->is_drain && $user->is_drain_chance > mt_rand(1, 100) && !$user->is_youtuber
                ) {

                    $status = 'lose';
                    $win = 0;
                    $btn == 'down' ? $rand_num = mt_rand($min, 1000000) : $rand_num = mt_rand(0, $max);
                }
            } else {
                $win = 0;
                $status = 'lose';
            }
            if ($status == 'lose') BonusController::raceBackCalc('dice', $bet);
            $status == 'win' ? $user->balance += $win - $bet : $user->balance -= $bet;
            if ($user->wager > 0) $user->wager -= $bet;
            $user->save();
            $status == 'win' ? $this->updateBank('win', $win - $bet) : $this->updateBank('lose', $bet);
            $status == 'win' ? $info = "[$btn] Победа в игре ставка $bet с шансом $chance выигрыш $win" :
                $info = "[$btn] Проигрыш в игре ставка $bet с шансом $chance";
            if ($status != 'win')
                DB::table('gameJackpotes')->increment('sum', $bet * 0.01);

            if (mt_rand(1, 1000) <= 1 && $chance <= 25 && $status != 'win') {
                $jackpotes = DB::table('gameJackpotes')->where('id', 1)->first();
                $jackpot_sum = $jackpotes->sum * ($bet * (mt_rand(30, 100) / 100));
                DB::table('gameJackpotes')->decrement('sum', $jackpot_sum);
                $user->balance += $jackpot_sum;
                $user->save();
                DB::table('gamejackpotWin')->insert([
                    'user_id' => $user->id,
                    'winSum' => $jackpot_sum,
                    'game' => 'dice'
                ]);
                Redis::publish('jackpotesWin', json_encode([
                    'user_id' => $user->id,
                    'game' => 'dice',
                    'name' => $user->name,
                    'jackpot_sum' => $jackpot_sum,
                ]));
            }

            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        LogController::create(['type' => 'dice', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
        Redis::publish('newGame', json_encode([
            'id' => mt_rand(1, 1000),
            'game' => 'dice',
            'name' => $user->name,
            'bet' => number_format(
                $bet,
                2
            ),
            'coff' => number_format((100 / $chance), 2),
            'result' => $status,
            'win' => number_format(
                $win,
                2
            )
        ]));
        DB::table('dice')->sharedLock()->insert([
            'user_id' => $user->id,
            'bet' => $bet,
            'chance' => $chance,
            'win' => $win
        ]);
        return response()->json([
            'success' => [
                'balance' => $user->balance,
                'status' => $status,
                'rand_num' => $rand_num,
                'win' => $win,
                'text' => $status == 'lose' ? 'Выпало ' . $rand_num : null,
                'bet' => $bet
            ]
        ]);
    }
}
