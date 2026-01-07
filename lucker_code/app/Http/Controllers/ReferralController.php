<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\ReferralProfit;
use App\User;
use App\Action;

use DB;

class ReferralController extends Controller
{
    public function init()
    {
        $referral_lvl_1_list = User::where('referral_use', $this->user->id)
            ->select('id')
            ->get();

        $referral_lvl_2_list = User::whereIn('referral_use', $referral_lvl_1_list)
            ->select('id')
            ->get();

        $referral_lvl_3_list = User::whereIn('referral_use', $referral_lvl_2_list)
            ->select('id')
            ->get();

        return [
            'data' => [
                'lvl_1' => [
                    'count' => count($referral_lvl_1_list),
                    'income' => ReferralProfit::whereIn('from_id', $referral_lvl_1_list)->where('level', 1)->sum('amount')
                ],
                'lvl_2' => [
                    'count' => count($referral_lvl_2_list),
                    'income' => ReferralProfit::whereIn('from_id', $referral_lvl_2_list)->where('level', 2)->sum('amount')
                ],
                'lvl_3' => [
                    'count' => count($referral_lvl_3_list),
                    'income' => ReferralProfit::whereIn('from_id', $referral_lvl_3_list)->where('level', 3)->sum('amount')
                ],
            ],
            'ref_income' => $this->user->referral_balance,
            'ref_reward' => $this->config->referral_reward,
            'link' => $this->config->referral_domain . '/r/' . $this->user->unique_id
        ];
    }

    public function take()
    {
        DB::beginTransaction();

        $user = User::where('id', $this->user->id)->lockForUpdate()->first();

        if($user->referral_balance < 20) {
            return [
                'error' => true,
                'message' => 'Минимальный вывод 20₽'
            ];
        }

        $refBal = $user->referral_balance;

        $user->balance += $user->referral_balance;
        $user->wager += $user->referral_balance * 5;
        $user->referral_balance = 0;
        $user->save();

        $desc_bank = ($refBal / 100 * 30) / 5;
        $this->banking->decrement('bank_dice', $desc_bank);
        $this->banking->decrement('bank_mines', $desc_bank);
        $this->banking->decrement('bank_bubbles', $desc_bank);
        $this->banking->decrement('bank_allin', $desc_bank);
        $this->banking->decrement('bank_wheel', $desc_bank);

        if(!(\Cache::has('user.'.$this->user->id.'.historyBalance'))){ \Cache::put('user.'.$this->user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
            'user_id' => $this->user->id,
            'type' => 'Сбор рефки',
            'balance_before' => round($user->balance - $refBal, 2),
            'balance_after' => round($user->balance, 2),
            'date' => date('d.m.Y H:i:s')
        );

        $cashe_hist_user = \Cache::get('user.'.$this->user->id.'.historyBalance');

        $cashe_hist_user = json_decode($cashe_hist_user);
        $cashe_hist_user[] = $hist_balance;
        $cashe_hist_user = json_encode($cashe_hist_user);
        \Cache::put('user.'.$this->user->id.'.historyBalance', $cashe_hist_user);

        DB::commit();

        return [
            'balance' => $user->balance
        ];
    }

    public function setReferral($unique_id)
    {
        Session(['ref' => $unique_id]);
        return redirect('/');
    }
}
