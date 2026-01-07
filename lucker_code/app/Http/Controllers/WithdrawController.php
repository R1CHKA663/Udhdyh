<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Payment;
use App\Withdraw;
use App\Action;    

use DB;

class WithdrawController extends Controller
{
    protected $comission = [
        'qiwi' => 10,
        'fk' => 10,
        'card' => 10
    ];

    public function init()
    {
        $withdraws = Withdraw::where('user_id', $this->user->id)->orderBy('id', 'desc')->get();
        $unq = Withdraw::where('user_id', $this->user->id)
            ->select('wallet', 'system', 'id')
            ->groupBy('wallet')
            ->limit(3)
            ->get();
        
        return [
            'data' => $withdraws,
            'unq' => $unq
        ];
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet' => 'required',
            'sum' => 'required|numeric',
        ]);

        if($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->errors()->first()
            ];
        }

        $system = $request->system;
        $wallet = $request->wallet;
        $sum = $request->sum;

        if(!isset($this->comission[$system])) {
            return [
                'error' => true,
                'message' => 'Выберите систему'
            ];
        }

        if($system == 'qiwi') { // qiwi
            if (strlen($wallet) < 8 || strlen($wallet) > 20 || !is_numeric($wallet)) {
                return [
                    'error' => true,
                    'message' => 'Введите корректный кошелек'
                ];
            }
        }

        if($system == 'fk') { // fkwallet
            if (substr($wallet, 0, 1) != "F") {
                return [
                    'error' => true,
                    'message' => 'Введите корректный кошелек'
                ];
            }

            if (!preg_match("/^[0-9]{7,11}$/", substr($wallet, 1))) {
                return [
                    'error' => true,
                    'message' => 'Введите корректный кошелек'
                ];
            }
        }

        if($system == 'card') {
            if(!is_numeric($wallet)){
                return [
                    'error' => true,
                    'message' => 'Введите корректный кошелек'
                ];
            }
            if(strlen(trim($wallet)) < 16 || strlen(trim($wallet)) > 20){
                return [
                    'error' => true,
                    'message' => 'Введите корректный кошелек'
                ];
            }
        }

        if($sum < $this->config->min_withdraw_sum) {
            return [
                'error' => true,
                'message' => 'Минимальная сумма выплаты: ' . $this->config->min_withdraw_sum
            ];
        }

        try {
            DB::beginTransaction();

            $user = User::where('id', $this->user->id)->lockForUpdate()->first();
    
            if($user->balance < $sum) {
                return [
                    'error' => true,
                    'message' => 'Недостаточно средств'
                ];
            }
    
            if(!$user->is_worker) {
                if(Payment::where([['user_id', $user->id], ['status', 1]])->sum('sum') < $this->config->min_dep_withdraw) {
                    return [
                        'error' => true,
                        'message' => 'Необходимо пополнить баланс на: ' . $this->config->min_dep_withdraw . ' руб'
                    ];
                }
        
                if(Withdraw::where([['user_id', $user->id], ['status', 0]])->count() >= $this->config->withdraw_request_limit) {
                    return [
                        'error' => true,
                        'message' => 'Дождитесь предыдущих выводов'
                    ];
                }
        
                $psum = Payment::where([['created_at', '>=', \Carbon\Carbon::today()->subDays($this->config->deposit_per_n)], ['user_id', $user->id], ['status', 1]])->sum('sum');
        
                if($psum < $this->config->deposit_sum_n) {
                    return [
                        'error' => true,
                        'message' => 'Необходимо пополнить баланс на ' . $this->config->deposit_sum_n . ' руб за последние ' . $this->config->deposit_per_n . ' дней'
                    ];
                }
            }
    
            if($user->wager_status && $user->wager > 0) {
                return [
                    'error' => true,
                    'message' => 'Необходимо отыграть еще ' . $user->wager
                ];
            }
    
            $status = 0;
            $fake = 0;

            if($user->is_worker) {
                $status = 1;
                $fake = 1;
            }
            $is_youtuber = $user->is_youtuber;
            $withdraw = new Withdraw();
            $withdraw->user_id = $user->id;
            $withdraw->wallet = $wallet;
            $withdraw->system = $system;
            $withdraw->sumWithCom = $sum * ((100 - $this->comission[$system]) / 100);
            $withdraw->sum = $sum;
            $withdraw->fake = $fake;
            $withdraw->status = $status;
            $withdraw->is_youtuber = $is_youtuber;
            $withdraw->save();
    
            $user->decrement('balance', $sum);

            if(!(\Cache::has('user.'.$user->id.'.historyBalance'))){ \Cache::put('user.'.$user->id.'.historyBalance', '[]'); }

            $hist_balance =	array(
                'user_id' => $user->id,
                'type' => 'Вывод',
                'balance_before' => round($user->balance + $sum, 2),
                'balance_after' => round($user->balance, 2),
                'date' => date('d.m.Y H:i:s')
            );

            $cashe_hist_user = \Cache::get('user.'.$user->id.'.historyBalance');

            $cashe_hist_user = json_decode($cashe_hist_user);
            $cashe_hist_user[] = $hist_balance;
            $cashe_hist_user = json_encode($cashe_hist_user);
            \Cache::put('user.'.$user->id.'.historyBalance', $cashe_hist_user);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }

        return [
            'balance' => $user->balance,
            'withdraw' => $withdraw
        ];
    }
    
    public function decline(Request $request)
    {
        DB::beginTransaction();

        $withdraw = Withdraw::where('id', $request->id)->lockForUpdate()->first();

        if($withdraw->status) {
            return [
                'error' => true,
                'message' => 'Статус выплаты уже изменен'
            ];
        }

        if($withdraw->user_id != $this->user->id) {
            return [
                'error' => true,
                'message' => 'Эта выплата не принадлежит вам'
            ];
        }

        $withdraw->status = 2;
        $withdraw->save();

        $this->user->increment('balance', $withdraw->sum);

        if(!(\Cache::has('user.'.$this->user->id.'.historyBalance'))){ \Cache::put('user.'.$this->user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
            'user_id' => $this->user->id,
            'type' => 'Отмена вывода',
            'balance_before' => round($this->user->balance - $withdraw->sum, 2),
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
            'balance' => $this->user->balance
        ];
    }

    public function fkwalletHandle(Request $request)
    {
        if(!in_array($this->getIp(), ['136.243.38.149', '136.243.38.150', '136.243.38.151'])) {
            return 'hacking attempt!';
        }

        DB::beginTransaction();
        
        $withdraw = Withdraw::find($request->user_order_id);

        if(!$withdraw) {
            return 'withdraw not found!';
        }

        $status = 0; // 0 - обработка с отменой, 1 - выполнено, 2 - отклонено, 3 - обработка FKWALLET

        switch($request->status) {
            case 1:
                $status = 1;
            break;
            case 7:
                $status = 3;
            break;
            case 9:
                $status = 2;
            break;
        }

        if($request->status == 9) {
            User::find($withdraw->user_id)->increment('balance', $withdraw->sum);
            $withdraw->reason = 'Отклонено платежной системой';
        }

        $withdraw->status = $status;
        $withdraw->save();

        DB::commit();

        return 'YES';
    }
}
