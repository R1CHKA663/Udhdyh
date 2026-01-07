<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\Action;
use Illuminate\Http\Request;
use Str;
use Auth;

class UserController extends Controller
{
    public function init()
    {
        if(!$this->user) {
            return [
                'message' => 'Вы не авторизованы'
            ];
        }

        return [
            'user' => [
                'id' => $this->user->id,
                'unique_id' => $this->user->unique_id,
                'balance' => $this->user->balance,
                'avatar' => $this->user->avatar,
                'username' => $this->user->username,
                'vk_id' => $this->user->vk_id,
                'tg_id' => $this->user->tg_id,
                'is_worker' => $this->user->is_worker
            ],
            'config' => [
                'tg_channel' => $this->config->tg_channel,
                'tg_bot' => $this->config->tg_bot,
                'vk_url' => $this->config->vk_url
            ]
        ];
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function videocardUpdate(Request $r) {
        if(Auth::guest()) return;

        $this->user->update([
            'videocard' => $r->video
        ]);
    }

    public function fingerprintUpdate(Request $r) {
        if(Auth::guest()) return;

        $this->user->update([
            'fingerprint' => $r->finger
        ]);
    }

    public function repostVK(Request $data)
	{
		switch($data->type) {
            case 'wall_repost':
                $owner_id = $data['object']['from_id'];
                $post_id = $data['object']['copy_history'][0]['id'];
                if($post_id == 34) {
				    $reward = 50;
				    $user = User::where(['vk_id' => $owner_id])->first();
                    if(!$user) return 0;
				    $waituss = User::where(['repost' => 1, 'vk_id' => $owner_id])->count();
				    if ($waituss > 0) return 0;

                    if(!(\Cache::has('user.'.$user->id.'.historyBalance'))){ \Cache::put('user.'.$user->id.'.historyBalance', '[]'); }

                    $hist_balance =	array(
                        'user_id' => $user->id,
                        'type' => 'Бонус 50р',
                        'balance_before' => round($user->balance, 2),
                        'balance_after' => round($user->balance + $reward, 2),
                        'date' => date('d.m.Y H:i:s')
                    );

                    $cashe_hist_user = \Cache::get('user.'.$user->id.'.historyBalance');

                    $cashe_hist_user = json_decode($cashe_hist_user);
                    $cashe_hist_user[] = $hist_balance;
                    $cashe_hist_user = json_encode($cashe_hist_user);
                    \Cache::put('user.'.$user->id.'.historyBalance', $cashe_hist_user);

                    $user->balance += $reward;
                    $desc_bank = ($reward / 100 * 30) / 5;
                    $this->banking->decrement('bank_dice', $desc_bank);
                    $this->banking->decrement('bank_mines', $desc_bank);
                    $this->banking->decrement('bank_bubbles', $desc_bank);
                    $this->banking->decrement('bank_allin', $desc_bank);
                    $this->banking->decrement('bank_wheel', $desc_bank);
                    $user->wager += $reward * 20;
				    $user->repost = 1;
				    $user->save();

				    return 200;
				}
            break;

            case 'confirmation':
                return '6de0cf10';
            break;

            default:
                return NULL;
        }
    }
}
