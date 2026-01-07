<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redis;

use App\User;
use App\Profit;
use App\Action;
use DB;

class BubblesController extends Controller
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
            'bet' => 'required|numeric|min:1|max:1000000',
            'goal' => 'required|numeric|min:1.1|max:1000',
        ]);

        if($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->errors()->first()
            ];
        }

        $bet = $request->bet;
        $goal = $request->goal;

        $random = rand(0, 999999);
        $chance = 100 / $goal;

        $win = ($bet * $goal) - $bet;
        $isWin = false;
        $coef = round(1000000 / ($random + 1), 2);

        if($coef >= $goal) $isWin = true;

        try {
            DB::beginTransaction();

            $user = User::where('id', $this->user->id)->lockForUpdate()->first();

            if($user->balance < $bet) {
                return [
                    'error' => true,
                    'message' => 'Недостаточно средств'
                ];
            }

            if($this->config->antiminus == 1 && !$user->is_youtuber) {
                if($win > $this->profit->bank_bubbles) {
                    $randerr = rand(1, 100);
                    if($goal > 4) {
                        if($randerr > 30 && $randerr < 80) {
                            $coef = round(rand(100, 200) / 100, 2);
                        } elseif($randerr > 80 && $randerr < 90) {
                            $coef = round(rand(150, 300) / 100, 2);
                        } elseif($randerr > 90 && $randerr < 99) {
                            $coef = round(rand(150, 400) / 100, 2);
                        } elseif($randerr < 30) {
                            $coef = round(rand(100, 150) / 100, 2);
                        }
                    } else {
                        $coef = round(rand(100, $goal * 100 - 1) / 100, 2);
                    }
                    $isWin = false;
                }
            }

            $user->decrement('wager', $bet / 100 * 10);
            if($user->wager < 0) $user->update([
                'wager' => 0
            ]);

            if($isWin) {
                $user->increment('balance', $win);
                $user->increment('bubbles', $win);

                if($this->config->antiminus == 1 && !$user->is_youtuber) {
                    $this->profit->update([
                        'bank_bubbles' => $this->profit->bank_bubbles - $win,
                    ]);
                }

                $text = 'Выигрыш ' . number_format($win + $bet, 2, '.', '');
            } else {
                $user->decrement('balance', $bet);
                $user->decrement('bubbles', $bet);

                if(!$user->is_youtuber) {
                    $this->profit->update([
                        'bank_bubbles' => $this->profit->bank_bubbles + ($bet / 100) * (100 - $this->profit->comission),
                        'earn_bubbles' => $this->profit->earn_bubbles + ($bet / 100) * $this->profit->comission
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
                'type' => 'bubbles',
                'username' => $user->username,
                'amount' => $bet,
                'coeff' => round(($win + $bet) / $bet, 2),
                'result' => $isWin ? ($win + $bet) : 0
            ]));
        }

        if(!(\Cache::has('user.'.$this->user->id.'.historyBalance'))){ \Cache::put('user.'.$this->user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
			'user_id' => $this->user->id,
			'type' => 'Ставка в Bubbles',
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
