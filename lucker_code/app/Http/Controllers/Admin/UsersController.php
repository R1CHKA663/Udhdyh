<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Action;
use App\ReferralProfit;
use Illuminate\Http\Request;
use Request as Req;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersController extends Controller
{
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function index()
    {
        return view('admin.users.index');
    }

    public function edit($id)
    {
        $user = User::query()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }
        //$logs = Action::where('user_id', $user->id)->get();

        $cashe_hist_user = \Cache::get('user.'.$id.'.historyBalance') ?? '[]';
        $cashe_hist_user = json_decode($cashe_hist_user);
        if(count($cashe_hist_user) > 0){
            $cashe_hist_user = array_reverse($cashe_hist_user);
        }
            

        $logs = $cashe_hist_user;
        return view('admin.users.edit', compact('user', 'refs', 'mults', 'logs'));
    }

    public function editPost($id, Request $r)
    {
        $user = User::query()->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        if ($user->password !== $r->get('password')) {
            $user->update([
                'password' => hash('sha256', $r->get('password'))
            ]);
        }

        User::query()->find($id)->update($r->all());

        return redirect('/admin/users/edit/' . $id)->with('success', 'Данные пользователя обновлены!');
    }

    public function delete($id)
    {
        User::query()->find($id)->delete();

        return redirect()->back()->with('success', 'Пользователь удален');
    }

    public function checker(Request $request)
    {
        $user = User::find($request->user_id);

        $multi = User::query()
            ->orWhere('used_ip', $user->used_ip)
            ->orWhere('created_ip', $user->created_ip)
            ->orWhere('fingerprint', $user->fingerprint)
            ->get();

        return [
            'user' => $user,
            'list' => collect($multi)->where('id', '!=', $user->id)->values()
        ];
    }

    public function createFake($type, $id) {
        $user = User::query()->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        return view('admin.users.create' . $type, compact('user'));
    }

    public function addFake($type, $id, Request $r) {
        $user = User::query()->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Пользователь не найден!');
        }

        if($type == 'Payout') {
            $system = $r->system;
            $wallet = $r->wallet;
            $amount = $r->amount;
            $status = $r->status;
            
            if(!$system) {
                return redirect()->back()->with('error', 'Выберите платежную систему');
            }
    
            if(!$wallet) {
                return redirect()->back()->with('error', 'Введите кошелек корректно');
            }
    
            if(!$amount || !is_numeric($amount) || $amount < 1) {
                return redirect()->back()->with('error', 'Введите сумму корректно');
            }
    
            if(!$status) {
                return redirect()->back()->with('error', 'Выберите статус выплаты');
            }
    
            \App\Withdraw::query()->create([
                'user_id' => $user->id,
                'sum' => $amount,
                'sumWithCom' => $amount,
                'wallet' => $wallet,
                'system' => $system,
                'status' => $status,
                'fake' => 1
            ]);
        }

        if($type == 'Pay') {
            $amount = $r->amount;
            $add = $r->add;
            
            if(!$amount || !is_numeric($amount) || $amount < 1) {
                return redirect()->back()->with('error', 'Введите сумму корректно');
            }
    
            if(!$add) {
                return redirect()->back()->with('error', 'Заполните все поля');
            }
            
            if($add == 'y') {
                $user->balance += $amount;
                $user->save();
            }

            \App\Payment::query()->create([
                'user_id' => $user->id,
                'sum' => $amount,
                'status' => 1,
                'fake' => 1
            ]);
        }
        return redirect()->back()->with('success', $type == 'Payout' ? 'Выплата успешно добавлена' : 'Пополнение успешно добавлено');
    }
}
