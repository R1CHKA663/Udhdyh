<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Action;
use App\Promocode;
use App\PromocodeActivation;
use App\User;
use App\Setting;
use App\Payment;
use DB;

class PromoController extends Controller
{
    public function activate(Request $request)
    {
        try {
            DB::beginTransaction();

            $code = $request->code;
            $promo = Promocode::where('name', $code)->first();

            if (!$promo) {
                return [
                    'error' => true,
                    'message' => 'Промокод не найден'
                ];
            }

            if($promo->type != 'balance') {
                return [
                    'error' => true,
                    'message' => 'Этот промокод нужно активировать во вкладке "Пополнить"'
                ];
            }

            $allUsed = PromocodeActivation::where('promo_id', $promo->id)->count('id');

            if ($allUsed >= $promo->activation) {
                return [
                    'error' => true,
                    'message' => 'Промокод закончился'
                ];
            }

            $used = PromocodeActivation::where([['promo_id', $promo->id], ['user_id', $this->user->id]])->first();

            if ($used) {
                return [
                    'error' => true,
                    'message' => 'Вы уже использовали этот код'
                ];
            }

            if(strtotime($promo->end_time) <= time())  {
                return [
                    'error' => true,
                    'message' => 'Время промокода закончилось'
                ];
            }

            if(!$this->user->tg_id) {
                return [
                    'error' => true,
                    'message' => 'Подпишитесь на наш Telegram-канал',
                    'showModal' => true
                ];
            }

            if(!$this->isChannelMember()) {
                return [
                    'error' => true,
                    'message' => 'Подпишитесь на наш Telegram-канал'
                ];
            }
            if(!$this->isGroupMember()) {
                return [
                    'error' => true,
                    'message' => 'Подпишитесь на нашу группу ВКонтакте'
                ];
            }

            $old_balance = $this->user->balance;
            $this->user->increment('balance', $promo->sum);
            $this->user->increment('wager', $promo->sum * $promo->wager);

                if($promo->name == "VIP65") {
                if(Payment::where([['user_id', $this->user->id], ['status', 1]])->sum('sum') < $this->config->min_dep_withdraw) {
                    DB::rollback();
                    return [
                        'error' => true,
                        'message' => 'Необходимо пополнить баланс на: ' . $this->config->min_dep_withdraw . ' руб'
                    ];
                }
            }

            $desc_bank = ($promo->sum / 100 * 30) / 5;
            $this->banking->decrement('bank_dice', $desc_bank);
            $this->banking->decrement('bank_mines', $desc_bank);
            $this->banking->decrement('bank_bubbles', $desc_bank);
            $this->banking->decrement('bank_allin', $desc_bank);
            $this->banking->decrement('bank_wheel', $desc_bank);


            PromocodeActivation::create([
                'promo_id' => $promo->id,
                'user_id' => $this->user->id
            ]);

            if(PromocodeActivation::where('promo_id', $promo->id)->where('user_id', $this->user->id)->count() !== 1) {
                DB::rollback();
                return [
                    'error' => true,
                    'message' => 'Вы уже использовали этот код'
                ];
            }


            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            return [
                'error' => true,
                'message' => 'Подождите'
            ];
        }

        if(!(\Cache::has('user.'.$this->user->id.'.historyBalance'))){ \Cache::put('user.'.$this->user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
            'user_id' => $this->user->id,
            'type' => 'Активация промокода ('.$promo->name.')',
            'balance_before' => round($old_balance, 2),
            'balance_after' => round($this->user->balance, 2),
            'date' => date('d.m.Y H:i:s')
        );

        $cashe_hist_user = \Cache::get('user.'.$this->user->id.'.historyBalance');

        $cashe_hist_user = json_decode($cashe_hist_user);
        $cashe_hist_user[] = $hist_balance;
        $cashe_hist_user = json_encode($cashe_hist_user);
        \Cache::put('user.'.$this->user->id.'.historyBalance', $cashe_hist_user);

        DB::commit();
        return [
            'balance' => $this->user->balance,
            'text' => 'Промокод активирован'
        ];
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|min:4|max:10',
            'sum' => 'required|numeric|min:1|max:5',
            'activate' => 'required|integer|min:5|max:100'
        ]);

        if($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->errors()->first()
            ];
        }


        if(Payment::where([['user_id', $this->user->id], ['status', 1]])->sum('sum') < 1000) {
            return [
                'error' => true,
                'message' => 'Необходимо пополнить баланс на 1000 руб'
            ];
        }

        DB::beginTransaction();

        $this->user = User::where('id', $this->user->id)
            ->lockForUpdate()
            ->first();

        $code = $request->code;
        $sum = $request->sum;
        $activate = $request->activate;

        $cost = $sum * $activate;

        if($this->user->balance < $cost) {
            DB::rollback();
            return [
                'error' => true,
                'message' => 'Недостаточно средств'
            ];
        }

        $this->user->decrement('balance', $cost);
        $isExists = Promocode::where('name', $code)->first();

        if($isExists) {
            DB::rollback();
            return [
                'error' => true,
                'message' => 'Промокод уже существует'
            ];
        }

        Promocode::create([
            'name' => $code,
            'sum' => $sum,
            'activation' => $activate,
            'type' => 'balance'
        ]);

        if(Promocode::where('name', $code)->count() !== 1) {
            DB::rollback();
            return [
                'error' => true,
                'message' => 'Промокод уже существует'
            ];
        }

        if(!(\Cache::has('user.'.$this->user->id.'.historyBalance'))){ \Cache::put('user.'.$this->user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
            'user_id' => $this->user->id,
            'type' => 'Создание промокода ('.$code.')',
            'balance_before' => round($this->user->balance + $cost, 2),
            'balance_after' => round($this->user->balance, 2),
            'date' => date('d.m.Y H:i:s')
        );

        $cashe_hist_user = \Cache::get('user.'.$this->user->id.'.historyBalance');

        $cashe_hist_user = json_decode($cashe_hist_user);
        $cashe_hist_user[] = $hist_balance;
        $cashe_hist_user = json_encode($cashe_hist_user);
        \Cache::put('user.'.$this->user->id.'.historyBalance', $cashe_hist_user);

        DB::commit();

        return [
            'success' => true,
            'balance' => $this->user->balance,
            'text' => 'Промокод создан'
        ];
    }
}
