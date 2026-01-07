<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\Promo;
use App\Models\User;
use DB;

class PromoController extends Controller
{
    public function create()
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);
            $promo_time = $user->promo_time;
            if (!$user->is_promocoder) return response(['error' => 'Нет доступа']);
            if (($user->promo_time > Carbon::now()->valueOf())) return response(['error' => 'Попробуйте позже!']);

            $name = $this->getRandomWord(7);
            $reward = $user->promo_reward;
            $limit = $user->promo_limit;
            $date = Carbon::now()->addHours($user->promo_hours)->valueOf();

            $user->promo_time = $date;
            $user->save();

            Promo::sharedLock()->create([
                'user_id' => $user->id,
                'name' => $name,
                'reward' => $reward,
                'limit' => $limit,
                'type' => 0,
                'deposit' => 0
            ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        return response(['success' => 'Промокод успешно создан', 'time' => $date]);
    }
    public function get()
    {
        $user = Auth::user();

        $promo = Promo::where(['user_id' => $user->id])->get();

        return response(['success' => [
            'user' => [
                'limit' => $user->promo_limit,
                'reward' => number_format($user->promo_reward, 2),
                'time' => $user->promo_time
            ],
            'promo' => $promo
        ]]);
    }
    static function getRandomWord($len = 10)
    {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }
}
