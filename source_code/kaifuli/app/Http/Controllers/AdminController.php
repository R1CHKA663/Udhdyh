<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use App\Models\Bank;
use App\Models\Payment;
use App\Models\Withdraw;
use App\Models\Log;

class AdminController extends Controller
{
    public function createPromo(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'promo.name' => 'required|min:3|max:20',
            'promo.reward' => 'required|numeric|min:0',
            'promo.limit' => 'required|integer|min:0',
            'promo.type' => [
                Rule::in([0, 1, 2]),
                'required'
            ],
            'promo.deposit' => [
                Rule::in([0, 1]),
                'required'
            ]
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->first()
            ];
        }
        $name = $request->promo['name'];
        $reward = $request->promo['reward'];
        $limit = $request->promo['limit'];
        $type = $request->promo['type'];
        $deposit = $request->promo['deposit']; // Промокод будет доступен только за депозит
        $mines = $request->promo['mines'];

        $promo = Promo::where(['name' => $name])->first();

        if ($promo != null) {
            return response()->json([
                'error' => 'Промокод с таким названием уже существует'
            ]);
        }
        $arr = [];
        if ($type == 2) {
            $arr = [
                'min' => $mines['min'],
                'max' => $mines['max'],
                'step' => $mines['step'],
                'bomb' => $mines['bomb'],
            ];
        }
        Promo::create([
            'name' => $name,
            'reward' => $reward,
            'limit' => $limit,
            'type' => $type,
            'deposit' => $deposit,
            'mines' => $arr
        ]);
        return response()->json(['success' => 'Промокод создан']);
    }
    public function getPromo()
    {
        $promo = Promo::orderByDesc('id')->paginate(10);
        return response()->json(['success' => $promo]);
    }
    public function getUpdatePromo(Request $request)
    {
        $id = $request->id;
        $promo = Promo::where('id', $id)->first();
        return response()->json(['success' => $promo]);
    }
    public function updatePromo(Request $request)
    {
        $update = $request->updatePromo;

        Promo::where(['id' => $update['id']])->update([
            'name' => $update['name'],
            'reward' => $update['reward'],
            'limit' => $update['limit'],
            'type' => $update['type'],
            'deposit' => $update['deposit'],
            'status' => $update['status'],
        ]);

        return response()->json(['success' => 'Успешно изменено']);
    }
    public function getUsers(Request $request)
    {
        $data = $request->get('data');
        if ($data == 'null') {
            $users = User::orderByDesc('id')->paginate(10);
        } else {
            $users = User::orWhere('ref_link', 'LIKE', '%' . $data . '%')->orWhere('name', 'LIKE', '%' . $data . '%')->orWhere('id', 'LIKE', '%' . $data . '%')->orWhere('ip', 'LIKE', '%' . $data . '%')->orderByDesc('id')->paginate(10);
        }
        return response()->json(['success' => $users]);
    }
    public function getUser(Request $request)
    {
        $id = $request->id;
        $user = User::where(['id' => $id])->first();
        $user->countVideoCards =
            User::where(['videocard' => $user->videocard])->count();
        $user->countIp =
            User::where(['ip' => $user->ip])->count();
        return response()->json(['success' => $user]);
    }
    public function updateUser(Request $request)
    {
        $user = $request->user;
        $userUpdate = User::where(['id' => $user['id']])->first();

        if ($userUpdate) {
            $userUpdate->name = $user['name'];
            $userUpdate->balance = $user['balance'];
            $userUpdate->is_admin = $user['is_admin'];
            $userUpdate->is_moder = $user['is_moder'];
            $userUpdate->is_youtuber = $user['is_youtuber'];
            $userUpdate->is_ban = $user['is_ban'];
            $userUpdate->is_ban_comment = $user['is_ban_comment'];
            $userUpdate->verified = $user['verified'];
            $userUpdate->comment_admin = $user['comment_admin'];
            $userUpdate->is_drain = $user['is_drain'];
            $userUpdate->is_drain_chance = $user['is_drain_chance'];
            $userUpdate->is_promocoder = $user['is_promocoder'];
            $userUpdate->promo_limit = $user['promo_limit'];
            $userUpdate->promo_reward = $user['promo_reward'];
            $userUpdate->promo_hours = $user['promo_hours'];
            $userUpdate->save();
            return response()->json(['success' => 'Изменено']);
        } else {
            return response()->json([
                'error' => 'Не найден'
            ]);
        }
    }
    public function getBank()
    {
        $bank = Bank::where(['id' => 1])->first();

        return response()->json(['success' => $bank]);
    }
    public function saveBank(Request $request)
    {
        $bank = $request->bank;
        $bank = Bank::where(['id' => 1])->update([
            'dice' => $bank['dice'],
            'mines' => $bank['mines'],
            'bubbles' => $bank['bubbles'],
            'normal_dice' => $bank['normal_dice'],
            'normal_mines' => $bank['normal_mines'],
            'normal_bubbles' => $bank['normal_bubbles'],
            'fee_dice' => $bank['fee_dice'],
            'fee_mines' => $bank['fee_mines'],
            'fee_bubbles' => $bank['fee_bubbles'],
        ]);

        return response()->json(['success' => true]);
    }
    public function getStats()
    {
        $payment_sum_today = Payment::where([['updated_at', '>=', \Carbon\Carbon::today()], ['status', 1]])->sum('sum');
        $payment_sum_7days = Payment::where([['updated_at', '>=', \Carbon\Carbon::today()->subDays(7)], ['status', 1]])->sum('sum');
        $payment_sum_Month = Payment::where([['updated_at', '>=', \Carbon\Carbon::today()->subMonth()], ['status', 1]])->sum('sum');
        $payment_sum_all = Payment::where('status', 1)->sum('sum');

        $withdraw_sum_today = Withdraw::where([['updated_at', '>=', \Carbon\Carbon::today()], ['status', 3]])->sum('sum');
        $withdraw_sum_7days = Withdraw::where([['updated_at', '>=', \Carbon\Carbon::today()->subDays(7)], ['status', 3]])->sum('sum');
        $withdraw_sum_Month = Withdraw::where([['updated_at', '>=', \Carbon\Carbon::today()->subMonth()], ['status', 3]])->sum('sum');
        $withdraw_sum_all = Withdraw::where('status', 3)->sum('sum');
        $withdraw_count_active = Withdraw::where('status', 0)->count();
        $withdraw_sum_active = Withdraw::where('status', 0)->sum('sum');

        $player_all_today = User::where([['created_at', '>=', \Carbon\Carbon::today()]])->count();
        $player_all = User::count();

        return response(['success' => [
            'payment_sum_today' => $payment_sum_today,
            'payment_sum_7days' => $payment_sum_7days,
            'payment_sum_Month' => $payment_sum_Month,
            'payment_sum_all' => $payment_sum_all,

            'withdraw_sum_today' => $withdraw_sum_today,
            'withdraw_sum_7days' => $withdraw_sum_7days,
            'withdraw_sum_Month' => $withdraw_sum_Month,
            'withdraw_sum_all' => $withdraw_sum_all,
            'withdraw_count_active' => $withdraw_count_active,
            'withdraw_sum_active' => $withdraw_sum_active,
            'player_all_today' => $player_all_today,
            'player_all' => $player_all
        ]]);
    }
    public function getLogs(Request $request)
    {
        $user_id = $request->get('user_id');
        $data = Log::where(['user_id' => $user_id])->latest('id')->paginate(10);
        return response(['success' => $data]);
    }
    public function getPayment(Request $request)
    {
        $not = $request->get('not');
        $not =
            filter_var($not, FILTER_VALIDATE_BOOLEAN);
        $data = Payment::where(['status' => !$not])->latest('id')->paginate(10);
        foreach ($data->items() as $key) {
            $user = User::where(['id' => $key->user_id])->first();
            $key->name = $user->name;
        }
        return response(['success' => $data]);
    }
    public function getWithdraws(Request $request)
    {
        $data = Withdraw::where(['status' => 0])->latest('id')->paginate(10);
        foreach ($data->items() as $key) {
            $user = User::where(['id' => $key->user_id])->first();
            $key->name = $user->name;
            $key->balance = $user->balance;
            $key->verified = $user->verified;
        }
        return response(['success' => $data]);
    }
    public function getWithdraw(Request $request)
    {
        $id = $request->id;

        $withdraw = Withdraw::where(['id' => $id])->first();
        $user = User::where(['id' => $withdraw->user_id])->first();

        $multi1 =
            User::where(['ip' => $user->ip])->count();
        $multi2 =
            User::where(['videocard' => $user->videocard])->count();
        $vivod = Withdraw::where(['user_id' => $withdraw->user_id, 'status' => 3])->count();
        $withdraw->vivod = $vivod;
        $withdraw->multi1 = $user->multi1;
        $withdraw->multi2 = $user->multi2;
        $withdraw->name = $user->name;
        $withdraw->is_ban = $user->is_ban;
        $withdraw->comment_admin = $user->comment_admin;
        $withdraw->verified = $user->verified;

        return response()->json(['success' => $withdraw]);
    }
    public function updateWithdraw(Request $request)
    {
        $data = $request->data;
        if (!$data) return;
        $check_status = Withdraw::where(['id' => $data['id']])->first();
        if ($check_status->status == 1) {
            return response(['error' => 'Пользователь отменил вывод']);
        }
        Withdraw::where(['id' => $data['id']])->update([
            'status' => $data['status'],
            'comment' => $data['comment']
        ]);
        $verified = $data['verified'];
        if ($data['status']  == 3) {
            $verified = 2;
        }
        if ($data['status']  == 2) {
            $verified = 1;
        }
        User::where(['id' => $data['user_id']])->update([
            'verified' => $verified,
            'comment_admin' => $data['comment_admin'],
            'is_drain' => 1
        ]);

        return response(['success' => 'Изменения сохранены']);
    }
}
