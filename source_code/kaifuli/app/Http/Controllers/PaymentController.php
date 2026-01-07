<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Payment;
use App\Models\Promo;
use App\Models\User;
use App\Models\Log;
use App\Models\PromoLog;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redis;

class PaymentController extends Controller
{
    const MERCHANT_ID_FK = 22959;
    const SECRET_WORD = 'YZ_3JYLrL[R]P&H';
    const MERCHANT_ID_LINEPAY = 60;
    const SECRET_WORD_LINEPAY1 = 'treertfddgfdhfd';
    const SECRET_WORD_LINEPAY2 = 'sdggdrrere';
    public function new(Request $request)
    {
        $messages = [
            'sum.min' => 'Минимальное пополнение - :min',
            'sum.max' => 'Максимальное пополнение - :max за раз',
        ];
        $validator = \Validator::make($request->all(), [
            'sum' => 'required|integer|min:50|max:15000',
            'system' => [
                Rule::in(['linepay', 'fkwallet', 'qiwi']),
                'required'
            ]
        ], $messages);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->first()
            ];
        }

        $user = Auth::user();
        $sum = $request->sum;
        // if ($user->is_admin == 0 && $sum < 100) return response(['error' => 'Минимальное пополнение - 100']);
        $promo_name = $request->promo;
        $system = $request->system;
        $promo_id = null;
        if ($promo_name != null) {
            $promo = Promo::where(['name' => $promo_name])->first();
            if ($promo != null) $promo_id = $promo->id;
        }

        $payment = Payment::create([
            'system' => $system,
            'user_id' => $user->id,
            'promo_id' => $promo_id,
            'sum' => $sum,
        ]);
        $payment_id = \DB::getPdo()->lastInsertId();
        if ($system == 'fkwallet') {
            $array = array(
                $m = self::MERCHANT_ID_FK,
                $secret_word = self::SECRET_WORD,
                $o = $payment_id,
                $oa = $sum,
                $currency = 'RUB',
            );

            // Соединение массива в строку и хеширование функцией md5
            $sign = md5($m . ':' . $oa . ':' . $secret_word . ':' . $currency . ':' . $o);
            $link = 'https://pay.freekassa.ru/?m=' . $m . '&oa=' . $oa . '&currency=' . $currency . '&o=' . $o . '&s=' . $sign . ' ';
        } elseif ($system == 'linepay') {
            $m_id = self::MERCHANT_ID_LINEPAY; //ID вашего мерчанта
            $m_secret_1 = self::SECRET_WORD_LINEPAY1; //Секретное слово 1 вашего мерчанта
            $amount = $sum; //Сумма заказа
            $order_id = $payment_id; //Ваш идентификатор заказа
            $sign = md5($m_id . '|' . $m_secret_1 . '|' . $amount . '|' . $order_id);
            $link = "https://linepay.fun/pay?order_id=" . $order_id . "&m_id=" . $m_id . "&amount=" . $amount . "&sign=" . $sign;
        } elseif ($system == 'qiwi') {
            $data = array(
                'merchant_id' => 71, //id мерчанта
                'public_key' => 'srIlRcLj', //публичный ключ
                'amount' => $sum, //сумма платежа
                'label' => $payment_id, //id заказа в вашей системе
                'system' => 'qiwi' //необязательно. доступно: qiwi, card, yoomoney, payeer, piastrix, fkwallet, freekassa, mobile
            );

            $ch = curl_init('https://xmpay.one/api/createOrder');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $json_decode = json_decode($response, true);
            $link = $json_decode['data']['url']; //ссылка на оплату
        }
        return response()->json(['success' => ['link' => $link]]);
    }
    public function successLinePay(Request $request)
    {
        $order_id = $_POST['order_id'];

        function getIP()
        {
            if (isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
            return $_SERVER['REMOTE_ADDR'];
        }
        //if (getIP() != '45.142.122.86') {
        //     die("wrong ip");
        // }

        //if ($sign != $_sign) {
        //    die("wrong sign");
        //}
        $payment = Payment::where(['id' => $order_id, 'status' => false])->first();
        if ($this->dep($payment)) {
            return response('YES');
        }
    }
    public function success()
    {
        $order_id = $_REQUEST['MERCHANT_ORDER_ID'];
        $payment = Payment::where(['id' => $order_id, 'status' => false])->first();
        function getIP()
        {
            if (isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
            return $_SERVER['REMOTE_ADDR'];
        }
        //if (!in_array(getIP(), array('168.119.157.136', '168.119.60.227', '138.201.88.124', '178.154.197.79'))) die("hacking attempt!");
        if ($this->dep($payment)) {
            return response('YES');
        }
    }
    public function xmpay()
    {
        $order_id = $_POST['label'];
        $payment = Payment::where(['id' => $order_id, 'status' => false])->first();
        if ($this->dep($payment)) {
            return response('YES');
        }
    }
    public function dep($payment)
    {


        if ($payment != null) {
            $user = User::where(['id' => $payment->user_id])->first();
            $amount = $payment->sum;

            $bonus_rub = 0;
            $promo_id = 0;
            if (intval($payment->promo_id)) {
                $promo = Promo::where(['id' => $payment->promo_id, 'status' => false])->first();
                if ($promo && !($promo->limited >= $promo->limit)) {
                    $promolog = PromoLog::where(['user_id' => $payment->user_id, 'promo_id' => $payment->promo_id, 'type' => $promo->type])->count();
                    if (!$promolog) {
                        $promo->limited += 1;
                        $promo->save();
                        PromoLog::create([
                            'user_id' => $user->id,
                            'promo_id' => $promo->id,
                            'type' => $promo->type,
                            'reward' => $promo->reward
                        ]);
                        $bonus_rub = $amount  + ($promo->reward / 100 * $amount  - $amount);
                        $promo_id = $promo->id;
                    }
                }
            }
            $oldBalance = $user->balance;
            if ($oldBalance > 100) {
                $user->is_drain = 1;
            }
            $user->balance += $amount + $bonus_rub;
            $user->deposit += $amount;
            if ($user->wager > 0) {
                $user->wager += $amount * 3;
            } else {
                $user->wager = $amount * 3;
            }
            $user->save();

            if ($user->invited != null) {
                $invite = User::where(['id' => $user->invited])->first(); // игрок который пригласил
                if ($invite) {
                    $procent = 0;
                    if ($invite->referalov >= 0 && $invite->referalov < 50) $procent = 8;
                    if ($invite->referalov >= 50 && $invite->referalov < 150) $procent  = 10;
                    if ($invite->referalov >= 150 && $invite->referalov < 500) $procent  = 12;
                    if ($invite->referalov >= 500) $procent  = 15;
                    $income =
                        $amount  + ($procent / 100 * $amount  - $amount);
                    $invite->income_all += $income;
                    $invite->income += $income;
                    $invite->save();

                    Log::create([
                        'user_id' => $invite->id,
                        'type' => 'payment',
                        'info' => "[refPay] Пополнение рефералом #$user->id, зачислено $income",
                        'oldBalance' => $invite->balance,
                        'newBalance' =>
                        $invite->balance
                    ]);
                }
            }
            $payment->status = true;
            $payment->save();

            if ($bonus_rub == 0) {
                $info =  "[new] Игрок пополнил на сумму $amount рублей";
            } else {
                $info =  "[new] Игрок пополнил на сумму $amount рублей, используя промокод #$promo_id начисление бонусом $bonus_rub";
            }
            Redis::publish('newPayment', json_encode([
                'amount' => $amount,
            ]));
            Log::create([
                'user_id' => $user->id,
                'type' => 'payment',
                'info' => $info,
                'oldBalance' => $oldBalance,
                'newBalance' => $user->balance
            ]);
            return true;
        }
    }
    public function getPayment()
    {
        $user = Auth::user();
        $user_id = $user->id;

        $payment = Payment::where(['user_id' => $user_id])->latest('id')->paginate(5);
        return response()->json(['success' => [
            'data' => $payment,
        ]]);
    }
}
