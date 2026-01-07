<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Mines;
use App\Models\Bank;
use DB;
use App\Http\Controllers\BonusController;
use Illuminate\Support\Facades\Redis;
use
    App\Http\Controllers\LogController;
use App\Models\User;

class MinesController extends Controller
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
                    'mines' => $bank->mines - $num,
                ]);
            } elseif ($status == 'lose') {
                Bank::lockForUpdate()->whereId(1)->update([
                    'mines' => $bank->mines + ($num * (1 - ($bank->fee_mines / 100))),
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
            'bomb.min' => 'Минимальное кол-во бомб - :min',
            'bomb.max' => 'Максимальное кол-во бомб - :max',
        ];
        $validator = \Validator::make($request->all(), [
            'bet' => 'required|numeric|min:1|max:1000',
            'bomb' => 'required|integer|min:2|max:24',
        ], $messages);

        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->first()
            ];
        }

        $bet = $request->bet;
        $bomb = $request->bomb;

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);
            //if ($user->is_admin != 1) return response(['error' => 'Я БЛЯДЬ ЧИНЮ МИНЫ ИДИ НАХУЙ']);
            $last = Mines::where(['user_id' => $user->id, 'active' => true])->count();

            if ($last > 0) {
                return response()->json(['error' => 'У вас есть активная игра']);
            }
            if ($user->balance < $bet) {
                return response()->json(['error' => 'Недостаточно средств']);
            }
            $oldBalance = $user->balance;
            $user->balance -= $bet;
            if ($user->wager > 0) $user->wager -= $bet;
            $user->save();

            $arr_mines = collect()->range(1, 25)->shuffle()->splice(25 - $bomb);
            $create_mines = Mines::sharedLock()->create([
                'user_id' => $user->id,
                'bet' => $bet,
                'num_bomb' => $bomb,
                'clicked' => [],
                'mines' => $arr_mines,
                'active' => true
            ]);
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        $info = "[create] Игра ($create_mines->id) создана - ставка - $bet с количеством бомб $bomb";
        LogController::create(['type' => 'mines', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);

        return response()->json(['success' => [
            'balance' => $user->balance,
        ]]);
    }
    static function getCoff($t, $e)
    {
        for ($n = 1, $a = 0; $a < 25 - $t && $e > $a; $a++)
            $n *= (25 - $a) / (25 - $t - $a);

        return $n;
    }
    public function press(Request $request)
    {
        $messages = [
            'cell.min' => 'Минимальное кол-во бомб - :min',
            'cell.max' => 'Максимальное кол-во бомб - :max',
        ];
        $validator = \Validator::make($request->all(), [
            'cell' => 'required|integer|min:1|max:25',
        ], $messages);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->first()
            ];
        }
        $user = Auth::user();
        $cell = $request->cell;
        $oldBalance = $user->balance;

        $mines = Mines::lockForUpdate()->where(['user_id' => $user->id, 'active' => true])->first();
        if (!$mines) {
            return response()->json(['error' => 'У вас нет активных игр']);
        }

        if (in_array($cell, $mines->clicked)) {
            return response()->json(['error' => 'Вы уже нажали на эту кнопку']);
        }

        $bank = Bank::whereId(1)->first();

        $vozm_win = $this->getCoff($mines->num_bomb, count($mines->clicked)) - $mines->bet;
        if (($bank->mines - $vozm_win) / $bank->normal_mines < mt_rand(1, 100) / 100 && !$user->is_youtuber
            || $user->is_drain && $user->is_drain_chance > mt_rand(1, 100)  && !$user->is_youtuber
        ) {

            $norm = true;
            while ($norm) {
                $newMines = collect()->range(1, 25)->diff($mines->clicked)->shuffle()->take($mines->num_bomb)->toArray();
                if (in_array($cell, $newMines)) {
                    try {
                        DB::beginTransaction();
                        $user = Auth::user();
                        $user =
                            User::lockForUpdate()->find($user->id);
                        $norm = false;
                        $mines->clicked = collect($mines->clicked)->push($cell);
                        $mines->save();
                        $result = collect()->range(1, 25)->diff($newMines)->diff($mines->clicked)->toArray();
                        $winMines = [];
                        foreach ($result as $key) {
                            $winMines[] = $key;
                        }
                        $mines->active = false;
                        $tek_win = $mines->win;
                        $mines->win = 0;
                        $mines->save();

                        $tek_win == 0 ? $this->updateBank('lose', $mines->bet) : $this->updateBank('lose', $tek_win);
                        Redis::publish('newGame', json_encode([
                            'id' => mt_rand(1, 1000),
                            'game' => 'mines',
                            'name' => $user->name,
                            'bet' => number_format(
                                $mines->bet,
                                2
                            ),
                            'coff' => number_format($vozm_win / $mines->bet, 2),
                            'result' => 'lose',
                            'win' => number_format(
                                $vozm_win,
                                2
                            )
                        ]));
                        DB::commit();
                    } catch (\PDOException $e) {
                        DB::connection()->getPdo()->rollBack();
                        return response(['error' => 'Ошибка сервера!']);
                    }
                    $info = "[lose] Игрок проиграл в игре #$mines->id ставка " . number_format($mines->bet, 2);
                    LogController::create(['type' => 'mines', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
                    BonusController::raceBackCalc('mines', $mines->bet);
                    return response()->json(['success' => [
                        'status' => 'lose',
                        'loseCell' => $cell,
                        'mines' => $newMines,
                        'winMines' => $winMines,
                    ]]);
                }
            }
        }
        $bank->normal_mines == 0 ? $bank->normal_mines = 1 : null;
        $mines->clicked = collect($mines->clicked)->push($cell);
        $mines->save();
        if (!in_array($cell, $mines->mines)) {
            // win
            try {
                DB::beginTransaction();
                $user = Auth::user();
                $user =
                    User::lockForUpdate()->find($user->id);
                $count = collect($mines->clicked)->count();
                $coff = $this->getCoff($mines->num_bomb, $count);
                $win = $coff * $mines->bet;

                $mines->win == 0 ? $this->updateBank('win', $win - $mines->bet) : $this->updateBank('win', $win - $mines->win);

                $mines->win = $win;
                $mines->save();
                DB::commit();
            } catch (\PDOException $e) {
                DB::connection()->getPdo()->rollBack();
                return response(['error' => 'Ошибка сервера!']);
            }
            if ((25 - count($mines->clicked)) != $mines->num_bomb) {
                return response()->json(['success' => [
                    'status' => 'win',
                    'win' => $mines->win,
                    'clicked' => $mines->clicked
                ]]);
            } else {
                // finish
                try {
                    DB::beginTransaction();
                    $user = Auth::user();
                    $user =
                        User::lockForUpdate()->find($user->id);
                    $mines->active = false;
                    $mines->save();

                    $user->balance += $win;
                    $user->save();

                    $result = collect()->range(1, 25)->diff($mines->mines)->diff($mines->clicked);
                    $winMines = [];
                    foreach ($result as $key) {
                        $winMines[] = $key;
                    }
                    Redis::publish('newGame', json_encode([
                        'id' => mt_rand(1, 1000),
                        'game' => 'mines',
                        'name' => $user->name,
                        'bet' => number_format(
                            $mines->bet,
                            2
                        ),
                        'coff' => number_format($mines->win / $mines->bet, 2),
                        'result' => 'win',
                        'win' => number_format(
                            $win,
                            2
                        )
                    ]));
                    DB::commit();
                } catch (\PDOException $e) {
                    DB::connection()->getPdo()->rollBack();
                    return response(['error' => 'Ошибка сервера!']);
                }
                $info = "[finish] Игрок угадал все в игре #$mines->id выигрыш " . number_format($mines->win, 2);
                LogController::create(['type' => 'mines', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
                return response()->json(['success' => [
                    'status' => 'finish',
                    'win' => $mines->win,
                    'coff' => $mines->win / $mines->bet, // lol
                    'balance' => $user->balance,
                    'mines' => $mines->mines,
                    'winMines' => $winMines,
                    'clicked' => $mines->clicked,
                    'bet' => $mines->bet,
                ]]);
            }
        } else {
            // lose
            try {
                DB::beginTransaction();
                $user = Auth::user();
                $user =
                    User::lockForUpdate()->find($user->id);
                $mines->win == 0 ? $this->updateBank('lose', $mines->bet) : $this->updateBank('lose', $mines->win);
                $arr = collect()->range(1, 25);
                $result = collect($arr)->diff($mines->mines)->diff($mines->clicked);
                $winMines = [];
                foreach ($result as $key) {
                    $winMines[] = $key;
                }
                $mines->active = false;
                $mines->save();
                BonusController::raceBackCalc('mines', $mines->bet);
                Redis::publish('newGame', json_encode([
                    'id' => mt_rand(1, 1000),
                    'game' => 'mines',
                    'name' => $user->name,
                    'bet' => number_format(
                        $mines->bet,
                        2
                    ),
                    'coff' => number_format($mines->win / $mines->bet, 2),
                    'result' => 'lose',
                    'win' => number_format(
                        0,
                        2
                    )
                ]));
                DB::commit();
            } catch (\PDOException $e) {
                DB::connection()->getPdo()->rollBack();
                return response(['error' => 'Ошибка сервера!']);
            }
            $info = "[lose] Игрок проиграл в игре #$mines->id ставка " . number_format($mines->bet, 2);
            LogController::create(['type' => 'mines', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
            return response()->json(['success' => [
                'status' => 'lose',
                'loseCell' => $cell,
                'mines' => $mines->mines,
                'winMines' => $winMines
            ]]);
        }
    }
    public function get(Request $request)
    {

        $user = Auth::user();
        $mines = Mines::where(['user_id' => $user->id, 'active' => true])->first();

        if ($mines) {
            return response()->json(['success' => [
                'active' => true,
                'win' => $mines->win,
                'clicked' => $mines->clicked,
                'bet' => $mines->bet,
                'num_bomb' => $mines->num_bomb
            ]]);
        } else {
            return response()->json(['error' => []]);
        }
    }
    public function take(Request $request)
    {

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);
            $oldBalance = $user->balance;
            $mines = Mines::lockForUpdate()->where(['user_id' => $user->id, 'active' => true])->first();
            if ($mines == null) return response()->json(['error' => 'Вам нечего забирать']);

            if ($mines->promo_type && $mines->promo_step > count($mines->clicked)) {
                return response()->json(['error' => 'Сделайте ' . ($mines->promo_step - count($mines->clicked)) . ' ходов']);
            }
            if (!count($mines->clicked) > 0)
                return response()->json(['error' => 'Сделайте хоть 1 клик...']);

            $mines->active = false;
            $mines->save();
            $user->balance += $mines->win;
            $user->save();

            $arr = collect()->range(1, 25);
            $result = collect($arr)->diff($mines->mines)->diff($mines->clicked);
            $winMines = [];
            foreach ($result as $key) {
                $winMines[] = $key;
            }
            Redis::publish('newGame', json_encode([
                'id' => mt_rand(1, 1000),
                'game' => 'mines',
                'name' => $user->name,
                'bet' => number_format(
                    $mines->bet,
                    2
                ),
                'coff' => number_format($mines->win / $mines->bet, 2),
                'result' => 'win',
                'win' => number_format(
                    $mines->win,
                    2
                )
            ]));
            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        $info = "[take] Игрок завершил игру #$mines->id выигрыш " . number_format($mines->win, 2);
        LogController::create(['type' => 'mines', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);
        return response()->json(['success' => [
            'win' => $mines->win,
            'coff' => $mines->win / $mines->bet,
            'balance' => $user->balance,
            'mines' => $mines->mines,
            'winMines' => $winMines,
            'bet' => $mines->bet
        ]]);
    }
}
