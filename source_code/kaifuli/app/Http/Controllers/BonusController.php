<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\PromoLog;
use App\Models\Admin;
use App\Models\Mines;
use App\Models\Withdraw;
use App\Models\User;
use Carbon\Carbon;
use
    App\Http\Controllers\LogController;
use Auth;
use DB;


class BonusController extends Controller
{
    public function getMinesStatus()
    {
        $user = Auth::user();

        $mines = Mines::where(['user_id' => $user->id, 'active' => true])->count();
        return $mines > 0 ? true : false;
    }
    public function getWithdrawStatus()
    {
        $user = Auth::user();

        $withdraw = Withdraw::where(['user_id' => $user->id, 'status' => 0])->count();
        return $withdraw > 0 ? true : false;
    }
    static function raceBackCalc($game, $bet)
    {
        $user = \Auth::user();
        $admin = Admin::where(['id' => 1])->first();
        $deposit = $user->deposit;
        $procent = 1;

        switch ($deposit) {
            case $deposit >= 500 && $deposit <= 5000:
                $procent = 1.25;
                break;
            case $deposit >= 5000 && $deposit <= 15000:
                $procent = 1.5;
                break;
            case $deposit >= 15000:
                $procent = 2;
                break;
        }
        if ($game == $admin->raceback_game && $bet >= 15) $procent *= $admin->raceback_procent;
        $user->raceback += $bet * ($procent / 100);
        $user->save();
    }
    public function activePromo(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->lockForUpdate()->find($user->id);
            $name = $request->name;

            if (!$user->bonus_tg) return response(['error' => 'Привяжите телеграм...']);
            if (!$user->bonus_vk) return response(['error' => 'Подпишитесь на группу вк']);

            $promo = Promo::where(['name' => $name])->first();
            if ($promo == null) {
                return response()->json(['error' => 'Промокод не найден']);
            }
            if ($promo->type == 1) {
                return response()->json(['error' => 'Этот промокод к депозиту']);
            }
            if ($promo->deposit == 1 && $user->deposit < 30) {
                return response()->json(['error' => 'У вас должен быть минимальный депозит, для активация этого промокода']);
            }
            if ($promo->status) {
                return response()->json(['error' => 'Активации закончились']);
            }
            if ($promo->limited >= $promo->limit) {
                return response()->json(['error' => 'Активации закончились']);
            }
            $PromoLog = PromoLog::where(['user_id' => $user->id, 'promo_id' => $promo->id])->first();
            if ($PromoLog != null) {
                return response()->json(['error' => 'Вы уже активировали данный промокод']);
            }
            $count = PromoLog::where([['created_at', '>=', \Carbon\Carbon::today()], ['user_id', $user->id]])->count();
            if ($user->balance > 10) {
                return response(['error' => 'Баланс должен быть меньше 10']);
            }
            if ($count >= 3) {
                return response()->json(['error' => 'Максимум 3 промокода в день!']);
            }
            // return;
            if ($promo->type == 2) {
                if ($this->getMinesStatus()) return response(['error' => 'У вас активная игра в минах']);

                $arr_mines = collect()->range(1, 25)->shuffle()->splice(25 - $promo->mines['bomb']);
                $sum_game = mt_rand($promo->mines['min'] * 100, $promo->mines['max'] * 100) / 100;
                $create_mines = Mines::sharedLock()->create([
                    'user_id' => $user->id,
                    'bet' => $sum_game,
                    'num_bomb' => $promo->mines['bomb'],
                    'clicked' => [],
                    'mines' => $arr_mines,
                    'active' => true,
                    'promo_type' => 1,
                    'promo_step' => $promo->mines['step']
                ]);
            }
            $promo->limited += 1;
            $promo->save();

            $oldBalance = $user->balance;
            $user->wager += $promo->reward * 3;
            $user->balance += $promo->reward;
            $user->save();
            PromoLog::sharedLock()->create([
                'user_id' => $user->id,
                'promo_id' => $promo->id,
                'type' => $promo->type,
                'reward' => $promo->reward
            ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        if ($promo->type < 2) {
            $info = "[promoActive] Игрок активировал промокод #$promo->id на сумму $promo->reward";
            LogController::create(['type' => 'bonus', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
            return response()->json([
                'success' => 'Вам начислено ' . $promo->reward . ' румбиков!',
                'balance' => $user->balance,
                'reward' => $promo->reward
            ]);
        } else {
            $info = "[promoActive] Игрок активировал промокод #$promo->id на сумму $sum_game на игру #$create_mines->id";
            LogController::create(['type' => 'bonus', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
            return response()->json([
                'success' => 'Игра создана на сумму ' . $sum_game . ' с количеством бомб ' . $promo->mines['bomb'],
                'balance' => $user->balance,
                'reward' => 0
            ]);
        }
    }
    public function getRaceBack()
    {
        $user = \Auth::user();
        $admin = Admin::where(['id' => 1])->first();

        return response([
            'success' => [
                'week' => [
                    'game' => $admin->raceback_game,
                    'procent' => $admin->raceback_procent,
                ],
                'user' => [
                    'raceback' => $user->raceback,
                    'deposit' => $user->deposit
                ]
            ]
        ]);
    }
    public function outRaceBack()
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);
            if ($user->balance >= 1) return response(['error' => 'Баланс должен быть меньше 1']);
            if ($user->raceback < 10) return response()->json(['error' => 'Минимум к снятию - 10']);
            if ($user->deposit < 30)
                return response()->json(['error' => 'Требуется минимальный депозит']);
            if ($user->referalov < 3)
                return response()->json(['error' => 'Требуется иметь минимум 3 реферала']);
            $raceback = $user->raceback;

            $oldBalance = $user->balance;
            $user->balance += 10;
            $user->raceback -= 10;
            $user->wager += 10 * 3;

            $user->save();
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        $info = "[raceback] Игрок забрал рэйкбэк 10 -- было $raceback стало $user->raceback";
        LogController::create(['type' => 'bonus', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);

        return response()->json(['success' => [
            'sum' => 10,
            'balance' => $user->balance,
            'raceback' => $user->raceback
        ]]);
    }
    static function getAdmin()
    {
    }
    static function checkSocial()
    {
        $user = Auth::user();
        $vk = new \VK\Client\VKApiClient('5.131');
        $admin =
            Admin::where(['id' => 1])->first();
        $vkontakte = $vk->groups()->isMember($admin->group_token, ['group_id' => $admin->group_id, 'user_id' => $user->vk_id]);
        $vkontakte ? $status = false : $status = [true, 'vk', 'Вы не подписаны на группу ВК'];
        if ($status) return $status;
        if ($user->tg_id == null) return [false, 'tg', 'Вы не привязывали телеграм'];
        $client = new \GuzzleHttp\Client();
        $telegram = $client->post('https://api.telegram.org/bot5431252048:AAExVlyP8bIAfdXeRTj62AE4aF7pnXG_dwA/getChatMember', [
            'chat_id' => '@kaifuli_play',
            'user_id' => $user->tg_id
        ]);
        return $status = ['tg', $user->tg_id];
    }
    const bonus_tg = 5;
    const bonus_vk = 5;
    public function freeBonusSocial(Request $request)
    {
        // $info = $this->checkSocial();
        // if ($info) return response(['error' => $info[2]]);
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);
            $type = $request->type;
            if (!in_array($type, ['vk', 'tg'])) return response(['error' => 'Произошла ошибка']);
            $oldBalance = $user->balance;


            if ($user->balance >= 1) return response(['error' => 'Баланс должен быть меньше 1']);
            $admin =
                Admin::where(['id' => 1])->first();
            if ($type == 'vk') {
                $vk = new \VK\Client\VKApiClient('5.131');
                $response = $vk->groups()->isMember($admin->group_token, ['group_id' => $admin->group_id, 'user_id' => $user->vk_id]);
                if (!$response) return response(['error' => 'Вы не подписаны на группу ВК']);
            }
            if ($type == 'tg' && !$user->tg_id) {
                return response()->json(['error' => 'Привяжите телеграм канал']);
            }
            if ($type == 'tg' && $user->bonus_tg || $type == 'vk' && $user->bonus_vk) return response()->json(['error' => 'Вы уже получали данный бонус']);


            if ($type == 'vk') {
                $user->bonus_vk = true;
                $user->balance += self::bonus_vk;
                $bonus =
                    self::bonus_vk;
            } elseif ($type == 'tg') {
                $user->bonus_tg = true;
                $user->balance += self::bonus_tg;
                $bonus =
                    self::bonus_tg;
            }
            $user->wager += 5 * 3;
            $user->save();
            if ($user->bonus_tg && $user->bonus_vk) $this->addMoneyRef($user->invited);
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        $info = "[$type] Игрок получил бонус за привязку tg - 10";
        LogController::create(['type' => 'bonus', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
        return response(['success' => ['sum' => $bonus, 'balance' => $user->balance]]);
    }
    static function addMoneyRef($invite)
    {
        try {
            DB::beginTransaction();
            $user = User::lockForUpdate()->where(['id' => $invite])->first();
            if ($user != null) {
                $user->income_all += 5;
                $user->income += 5;
                $user->save();
            }
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
    }
    public function getBonusSocial()
    {
        $user = \Auth::user();

        return response(['success' => [
            'user' => [
                'bonus_tg' => $user->bonus_tg,
                'bonus_vk' => $user->bonus_vk,
            ],
            'bonus' => [
                'vk' => self::bonus_vk,
                'tg' => self::bonus_tg,
            ]
        ]]);
    }

    public function freeBonusMore(Request $request)
    {
        $type = $request->type;
        $user = \Auth::user();
        $oldBalance = $user->balance;

        if ($user->balance >= 1) return response(['error' => 'Баланс должен быть меньше 1']);
        if (!$user->bonus_tg) return response(['error' => 'Получите сначала бонусы за ВК И TG']);
        if (!$user->bonus_vk) return response(['error' => 'Получите сначала бонусы за ВК И TG']);
        if ($this->getWithdrawStatus()) return response(['error' => 'У вас активные выплаты']);
        if ($this->getMinesStatus()) return response(['error' => 'У вас активная игра в минах']);
        if ($user->balance >= 1) return response(['error' => 'Баланс должен быть меньше 1']);
        if ($user->referalov < 3)
            return response()->json(['error' => 'Требуется иметь минимум 3 реферала']);
        if (
            $type == 'hourly' && (!$user->hourly_bonus || $user->hourly_bonus < Carbon::now()->valueOf())
            || $type == 'day' && (!$user->day_bonus || $user->day_bonus < Carbon::now()->valueOf())
        ) {
            try {
                DB::beginTransaction();
                $user = Auth::user();
                $user =
                    User::lockForUpdate()->find($user->id);
                if ($type == 'hourly') {
                    $bonus = mt_rand(1, 300) / 100;
                    $date = Carbon::now()->addMinutes(60)->valueOf();
                    $user->hourly_bonus = $date;
                    $info = "[$type] Игрок получил ежечасный бонус в размере $bonus";
                } elseif ($type == 'day') {
                    $bonus = mt_rand(1, 1000) / 100;
                    $date = Carbon::now()->addHours(24)->valueOf();
                    $user->day_bonus = $date;
                    $info = "[$type] Игрок получил ежедневный бонус в размере $bonus";
                }
                $user->wager += $bonus * 3;
                $user->balance += $bonus;
                $user->save();
                DB::commit();
            } catch (\PDOException $e) {
                DB::connection()->getPdo()->rollBack();
                return response(['error' => 'Ошибка сервера!']);
            }
            LogController::create(['type' => 'bonus', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
            return response(['success' => [
                'balance' => $user->balance,
                'sum' => $bonus,
                'date' => $date,
            ]]);
        } else {
            return response(['error' => 'Обновите страницу']);
        }
    }
    public function getBonusMore()
    {
        $user = \Auth::user();

        return response(['success' => [
            'user' => [
                'hourly_bonus' => $user->hourly_bonus,
                'day_bonus' => $user->day_bonus,
                'finished' => Carbon::now()->valueOf()
            ]
        ]]);
    }
    public function checkBonusRepost()
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);
            $oldBalance = $user->balance;

            $reposts = \DB::table('user_repost')->lockForUpdate()->where(['user_id' => $user->id, 'status' => 0])->latest('id')->take(15)->get();
            $repost_bonus = 0;
            for ($i = 0; $i < count($reposts); $i++) {
                switch ($user->repost) {
                    case $user->repost > 0 && $user->repost < 25:
                        $repost_bonus += 0.4;
                        break;
                    case $user->repost >= 25 && $user->repost < 100:
                        $repost_bonus += 0.6;
                        break;
                    case $user->repost >= 100 && $user->repost < 300:
                        $repost_bonus += 0.8;
                        break;
                    case $user->repost >= 300:
                        $repost_bonus += 1;
                        break;
                }
                \DB::table('user_repost')->lockForUpdate()->where(['user_id' => $user->id, 'status' => 0])->update(['status' => 1]);
            }
            //return response(['error' => 'Временно недоступно']);
            if (!count($reposts)) return response(['error' => 'Новых действий не обнаружено']);
            $user->income_repost += $repost_bonus;
            $user->repost += count($reposts);
            $user->save();
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        $info = "[checkRepost] Игроку было зачислено на репост-счет $repost_bonus";
        LogController::create(['type' => 'bonus', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
        return response(['success' => [
            'user' => [
                'repost' => $user->repost,
                'repost_bonus' => $repost_bonus,
                'income_repost' => $user->income_repost,
                'make_repost' => count($reposts)
            ]
        ]]);
    }
    public function getBonusRepost()
    {
        $user = \Auth::user();

        return response([
            'success' => [
                'user' => [
                    'repost' => $user->repost,
                    'income_repost' => $user->income_repost,
                ]
            ]
        ]);
    }
    public function transferBonusRepost()
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);

            $oldBalance = $user->balance;
            $income_repost = $user->income_repost;

            if ($user->balance >= 1) return response(['error' => 'Баланс должен быть меньше 1']);
            if (!$user->bonus_tg) return response(['error' => 'Привяжите телеграм...']);
            if (!$user->bonus_vk) return response(['error' => 'Подпишитесь на группу вк']);
            if ($this->getWithdrawStatus()) return response(['error' => 'У вас активные выплаты']);
            if ($this->getMinesStatus()) return response(['error' => 'У вас активная игра в минах']);
            if ($income_repost < 10) return response(['error' => 'Минимум к выводу 10']);
            $user->balance += 10;
            $user->wager += 10 * 3;
            $user->income_repost -= 10;
            $user->save();
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        $info = "[transferRepost] Игрок перевел на свой счет - 10";
        LogController::create(['type' => 'bonus', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);

        return response(['success' => [
            'user' => [
                'repost' => $user->repost,
                'income_repost' => $user->income_repost,
                'balance' => $user->balance,
                'transfer' => 10
            ]
        ]]);
    }
}
