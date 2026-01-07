<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Socialite;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Models\usersInfo;
use
    App\Http\Controllers\LogController;
use DB;

class AuthController extends Controller
{
    public function auth(Request $request)
    {
        try {
            $user = json_decode(json_encode(Socialite::driver('vkontakte')->stateless()->user()));
        } catch (\Exception $e) {

            return "Ошибка на стороне ВК";
        }
        $user = $user->user;
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"] ?? $_SERVER['REMOTE_ADDR'];
        $user = $this->createOrGetUser($user, 'vkontakte', $ip);
        Auth::login($user, true);
        return redirect()->intended('/');
    }
    public function createOrGetUser($user, $provider, $ip)
    {
        if ($provider == 'vkontakte') {
            $u = User::where('vk_id', $user->id)->first();
            if ($u) {
                $username = $user->first_name . ' ' . $user->last_name;
                User::where('vk_id', $u->vk_id)->update([
                    'name' => $username,
                    'img' => $user->photo_200,
                    'remember_token' => bin2hex(random_bytes(25)),
                    'ip' => $ip
                ]);
                $user = $u;
            } else {
                $username = $user->first_name . ' ' . $user->last_name;
                $invited_id = Cookie::get('invited');
                if ($invited_id != null) {
                    $invited_user = User::where(['ref_link' => $invited_id])->first();
                    if ($invited_user != null) {
                        $invited_id = $invited_user->id;
                        //$invited_user->income_all += 5;
                        //$invited_user->income += 5;
                        $invited_user->referalov += 1;
                        // $invited_user->contest_ref += 1;
                        $invited_user->save();
                    }
                } else {
                    $invited_id = null;
                }
                $user = User::create([
                    'vk_id' => $user->id,
                    'img' => $user->photo_200,
                    'name' => $username,
                    'invited' => $invited_id,
                    'ip' => $ip,
                    'balance' => 0,
                    'ref_link' => bin2hex(random_bytes(5)),
                    'remember_token' => bin2hex(random_bytes(25))
                ]);
            }
        }
        return $user;
    }
    public function get(Request $request)
    {
        $user = Auth::user();
        $user->videocard = $request->videocard;
        $user->save();
        if ($user) {
            return response()->json([
                'success' => [
                    'user' => [
                        'auth' => true,
                        'ref_link' => $user->ref_link,
                        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                        'is_ban' => $user->is_ban,
                        'name' => $user->name,
                        'id' => $user->id,
                        'balance' => $user->balance,
                        'img' => $user->img,
                        'is_admin' => $user->is_admin,
                        'is_moder' => $user->is_moder,
                        'is_promocoder' => $user->is_promocoder,
                        'is_ban_comment' => $user->is_ban ? $user->is_ban_comment : null,
                        'social' => [
                            'vk' => 'https://vk.com/kaifuli_play',
                            'tg' => 'https://t.me/kaifuli_play',
                            'bot_tg' => 'https://t.me/kaifuli_play_bot'
                        ]
                    ]
                ],
            ]);
        } else {
            return response()->json(['error' => 'Вы не авторизованы']);
        }
    }
    public function exit()
    {
        Cache::flush();
        Auth::logout();
        Session::flush();
        return response()->json([
            'success' => true,
        ]);
    }
    public function getRef()
    {
        $user = Auth::user();

        return response()->json(['success' => [
            'user' => [
                'ref_link' => $user->ref_link,
                'clicked' => $user->clicked,
                'income_all' => $user->income_all,
                'referalov' => $user->referalov,
                'income' => $user->income,

            ]
        ]]);
    }
    public function outRef()
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user =
                User::lockForUpdate()->find($user->id);

            $oldBalance = $user->balance;
            if ($user->income < 10) return response()->json(['error' => 'Минимум к снятию - 10']);

            $income = $user->income;

            $user->balance += $user->income;
            $user->income = 0;
            $user->save();

            DB::commit();
        } catch (\PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            return response(['error' => 'Ошибка сервера!']);
        }
        $info = "[take] Игрок перевел на свой счет - $income";
        LogController::create(['type' => 'ref', 'info' => $info, 'oldBalance' => $oldBalance, 'newBalance' => $user->balance]);

        return response()->json(['success' => [
            'sum' => $income,
            'balance' => $user->balance
        ]]);
    }
    public function clicked($id)
    {
        $user = User::where(['ref_link' => $id])->first();
        if (!Auth::check() && $user != null) {
            $user->clicked += 1;
            $user->save();
            Cookie::queue('invited', $id, 999);
        }
        return redirect('/');
    }
}
