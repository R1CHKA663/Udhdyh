<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redis;

use App\User;
use App\Profit;
use App\Action;
use DB;

class AllinController extends Controller
{
    protected $profit;

    public function __construct()
    {
        parent::__construct();
        $this->profit = Profit::first();
    }

    public function play(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal' => 'required|numeric|min:1.25|max:10',
        ]);

        if($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->errors()->first()
            ];
        }

        try {
            DB::beginTransaction();

            $user = User::where('id', $this->user->id)->lockForUpdate()->first();

            $bet = $user->balance;
            $goal = $request->goal;

            $random = rand(0, 999999);

            $perc = 20;

            //$psum = Payment::where([['created_at', '>=', \Carbon\Carbon::today()->subDays(1)], ['user_id', $user->id], ['status', 1]])->sum('sum');
            //
            //if($psum >= 50) {
            //    $perc = 40;
            //}

            $win_before = ($bet * $goal) - $bet;
            $win_perc = ($win_before + $bet) / 100 * $perc;
            $win = $win_before + $win_perc;
            $isWin = false;
            $coef = round(1000000 / ($random + 1), 2);

            if($coef >= $goal) $isWin = true;

            if($user->balance < $bet) {
                return [
                    'error' => true,
                    'message' => 'Недостаточно средств'
                ];
            }

            if($bet < 100) {
                return [
                    'error' => true,
                    'message' => 'Минимальная ставка 100р'
                ];
            }
            if($bet > 100000) {
                return [
                    'error' => true,
                    'message' => 'Максимальная ставка 100000р'
                ];
            }

            if($this->config->antiminus == 1 && !$user->is_youtuber) {
                if($win > $this->profit->bank_allin) {
                    $coef = round(rand(100, $goal * 100 - 1) / 100, 2);
                    $isWin = false;
                }
            }

            $user->decrement('wager', $bet / 100 * 10);
            if($user->wager < 0) $user->update([
                'wager' => 0
            ]);

            if($isWin) {
                $user->increment('balance', $win);
                $user->increment('allingame', $win);

                if($this->config->antiminus == 1 && !$user->is_youtuber) {
                    $this->profit->update([
                        'bank_allin' => $this->profit->bank_allin - $win,
                    ]);
                }

                $text = 'Выигрыш ' . number_format($win + $bet, 2, '.', '') . ' [' . number_format($win_before + $bet, 2, '.', '') . ' + ' . $perc . '%]';
            } else {
                $user->decrement('balance', $bet);
                $user->decrement('allingame', $bet);

                if(!$user->is_youtuber) {
                    $this->profit->update([
                        'bank_allin' => $this->profit->bank_allin + ($bet / 100) * (100 - $this->profit->comission)
                    ]);
                }

                $text = 'Выпало ' . number_format($coef, 2, '.', '');
            }

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

        if($isWin) {
            Redis::publish('newGame', json_encode([
                'id' => rand(10000, 99999999999),
                'type' => 'allingame',
                'username' => $user->username,
                'amount' => $bet,
                'coeff' => round(($win + $bet) / $bet, 2),
                'result' => $isWin ? ($win + $bet) : 0
            ]));
        }

        if(!(\Cache::has('user.'.$this->user->id.'.historyBalance'))){ \Cache::put('user.'.$this->user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
            'user_id' => $this->user->id,
            'type' => 'Ставка в ALL IN',
            'balance_before' => round($this->user->balance, 2),
            'balance_after' => round($isWin ? $this->user->balance + $win : $this->user->balance - $bet, 2),
            'date' => date('d.m.Y H:i:s')
        );

        $cashe_hist_user = \Cache::get('user.'.$this->user->id.'.historyBalance');

        $cashe_hist_user = json_decode($cashe_hist_user);
        $cashe_hist_user[] = $hist_balance;
        $cashe_hist_user = json_encode($cashe_hist_user);
        \Cache::put('user.'.$this->user->id.'.historyBalance', $cashe_hist_user);

        return [
            'text' => $text,
            'isWin' => $isWin,
            'balance' => $user->balance,
            'success' => true
        ];
    }
}
