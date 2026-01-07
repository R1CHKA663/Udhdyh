<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Payment;
use App\User;
use App\Setting;
use App\ReferralProfit;
use App\Promocode;
use App\PromocodeActivation;
use App\Action;
use DB;

class PaymentController extends Controller
{
    public function init()
    {
        $data = Payment::where('user_id', $this->user->id)->orderBy('id', 'desc')->get();
        return ['payments' => $data];
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);

        if($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->errors()->first()
            ];
        }

        switch($request->system) {
            case 'fk':
                $payment = (object) $this->createOrder($request);

                if($payment->error) {
                    return [
                        'error' => true,
                        'message' => $payment->message
                    ];
                }

                $sign = md5($this->config->kassa_id.':'.$payment->sum.':'.$this->config->kassa_secret1.':RUB:'.$payment->id);

                $data = [
                    'm'        => $this->config->kassa_id,
                    'oa'       => $payment->sum,
                    'o'        => $payment->id,
                    'currency' => 'RUB',
                    's'        => $sign,
                    'i' => 1
                ];

                $url = "https://pay.freekassa.ru/?".http_build_query($data);
            break;
            case 'aaio_qiwi':
                $payment = (object) $this->createOrder($request);

                if($payment->error) {
                    return [
                        'error' => true,
                        'message' => $payment->message
                    ];
                }

                $a_merchant_id = 'f1c70b1c-2493-4511-bbdb-11c29181bb9e'; // ID Вашего магазина
                $a_amount = $payment->sum; // Сумма к оплате
                $a_currency = 'RUB'; // Валюта заказа
                $a_secret = 'd48723a9662bb8961f127feb175d4b4c'; // Секретный ключ №1
                $a_order_id = $payment->id; // Идентификатор заказа в Вашей системе
                $a_sign = hash('sha256', implode(':', [$a_merchant_id, $a_amount, $a_currency, $a_secret, $a_order_id]));
                $a_desc = 'Пополнение баланса'; // Описание заказа
                $a_lang = 'ru'; // Язык формы

                $url = 'https://aaio.io/merchant/pay?' . http_build_query([
                        'merchant_id' => $a_merchant_id,
                        'amount' => $a_amount,
                        'currency' => $a_currency,
                        'order_id' => $a_order_id,
                        'sign' => $a_sign,
                        'desc' => $a_desc,
                        'lang' => $a_lang,
                        'method' => 'qiwi'
                    ]);
                break;
            case 'aaio_card':
                $payment = (object) $this->createOrder($request);

                if($payment->error) {
                    return [
                        'error' => true,
                        'message' => $payment->message
                    ];
                }

                $a_merchant_id = 'f1c70b1c-2493-4511-bbdb-11c29181bb9e'; // ID Вашего магазина
                $a_amount = $payment->sum; // Сумма к оплате
                $a_currency = 'RUB'; // Валюта заказа
                $a_secret = 'd48723a9662bb8961f127feb175d4b4c'; // Секретный ключ №1
                $a_order_id = $payment->id; // Идентификатор заказа в Вашей системе
                $a_sign = hash('sha256', implode(':', [$a_merchant_id, $a_amount, $a_currency, $a_secret, $a_order_id]));
                $a_desc = 'Пополнение баланса'; // Описание заказа
                $a_lang = 'ru'; // Язык формы

                $url = 'https://aaio.io/merchant/pay?' . http_build_query([
                        'merchant_id' => $a_merchant_id,
                        'amount' => $a_amount,
                        'currency' => $a_currency,
                        'order_id' => $a_order_id,
                        'sign' => $a_sign,
                        'desc' => $a_desc,
                        'lang' => $a_lang,
                        'method' => 'cards_ru'
                    ]);
                break;
            case 'aaio_sbp':
                $payment = (object) $this->createOrder($request);

                if($payment->error) {
                    return [
                        'error' => true,
                        'message' => $payment->message
                    ];
                }

                $a_merchant_id = 'f1c70b1c-2493-4511-bbdb-11c29181bb9e'; // ID Вашего магазина
                $a_amount = $payment->sum; // Сумма к оплате
                $a_currency = 'RUB'; // Валюта заказа
                $a_secret = 'd48723a9662bb8961f127feb175d4b4c'; // Секретный ключ №1
                $a_order_id = $payment->id; // Идентификатор заказа в Вашей системе
                $a_sign = hash('sha256', implode(':', [$a_merchant_id, $a_amount, $a_currency, $a_secret, $a_order_id]));
                $a_desc = 'Пополнение баланса'; // Описание заказа
                $a_lang = 'ru'; // Язык формы

                $url = 'https://aaio.io/merchant/pay?' . http_build_query([
                        'merchant_id' => $a_merchant_id,
                        'amount' => $a_amount,
                        'currency' => $a_currency,
                        'order_id' => $a_order_id,
                        'sign' => $a_sign,
                        'desc' => $a_desc,
                        'lang' => $a_lang,
                        'method' => 'sbp'
                    ]);
                break;
            case 'aaio_ym':
                $payment = (object) $this->createOrder($request);

                if($payment->error) {
                    return [
                        'error' => true,
                        'message' => $payment->message
                    ];
                }

                $a_merchant_id = 'f1c70b1c-2493-4511-bbdb-11c29181bb9e'; // ID Вашего магазина
                $a_amount = $payment->sum; // Сумма к оплате
                $a_currency = 'RUB'; // Валюта заказа
                $a_secret = 'd48723a9662bb8961f127feb175d4b4c'; // Секретный ключ №1
                $a_order_id = $payment->id; // Идентификатор заказа в Вашей системе
                $a_sign = hash('sha256', implode(':', [$a_merchant_id, $a_amount, $a_currency, $a_secret, $a_order_id]));
                $a_desc = 'Пополнение баланса'; // Описание заказа
                $a_lang = 'ru'; // Язык формы

                $url = 'https://aaio.io/merchant/pay?' . http_build_query([
                        'merchant_id' => $a_merchant_id,
                        'amount' => $a_amount,
                        'currency' => $a_currency,
                        'order_id' => $a_order_id,
                        'sign' => $a_sign,
                        'desc' => $a_desc,
                        'lang' => $a_lang,
                        'method' => 'yoomoney'
                    ]);
                break;
            case 'aaio_usdt':
                $payment = (object) $this->createOrder($request);

                if($payment->error) {
                    return [
                        'error' => true,
                        'message' => $payment->message
                    ];
                }

                $a_merchant_id = 'f1c70b1c-2493-4511-bbdb-11c29181bb9e'; // ID Вашего магазина
                $a_amount = $payment->sum; // Сумма к оплате
                $a_currency = 'RUB'; // Валюта заказа
                $a_secret = 'd48723a9662bb8961f127feb175d4b4c'; // Секретный ключ №1
                $a_order_id = $payment->id; // Идентификатор заказа в Вашей системе
                $a_sign = hash('sha256', implode(':', [$a_merchant_id, $a_amount, $a_currency, $a_secret, $a_order_id]));
                $a_desc = 'Пополнение баланса'; // Описание заказа
                $a_lang = 'ru'; // Язык формы

                $url = 'https://aaio.io/merchant/pay?' . http_build_query([
                        'merchant_id' => $a_merchant_id,
                        'amount' => $a_amount,
                        'currency' => $a_currency,
                        'order_id' => $a_order_id,
                        'sign' => $a_sign,
                        'desc' => $a_desc,
                        'lang' => $a_lang,
                        'method' => 'tether_trc20'
                    ]);
                break;
            default:
                return [
                    'error' => true,
                    'message' => 'Выберите способ оплаты'
                ];
            break;
        }

        return [
            'url' => $url
        ];
    }

    public function handleVlito(Request $request)
    {
        if(!in_array($this->getIp(), ['185.178.44.168', '92.63.102.210'])) {
            return 'wrong ip';
        }

        $payment = Payment::query()->find($request->pay_id);

        if(!$payment) {
            return 'payment not found';
        }

        if($payment->status) {
            return 'payment already paid';
        }

        if($payment->sum > $request->amount) {
            return 'wrong sum';
        }

        $incrementSum = $payment->bonus != 0
            ? $payment->sum + (($payment->sum * $payment->bonus) / 100)
            : $payment->sum;

        $user = User::find($payment->user_id);

        if($user->balance < 10) {
            $user->wager = 0;
            $user->save();
        }

        $user->increment('balance', $incrementSum);
        $user->increment('wager', ($payment->sum / 100) * 3);
        $user->increment('wager', ($payment->bonus / 100) * $payment->wager);

        if(!is_null($user->referral_use)) {
            $this->setReferralProfit($user->id, $payment->sum);
        }

        $payment->status = 1;
        $payment->save();

        if(!(\Cache::has('user.'.$user->id.'.historyBalance'))){ \Cache::put('user.'.$user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
			'user_id' => $user->id,
			'type' => 'Пополнение через Vlito',
			'balance_before' => $user->balance - $payment->sum,
			'balance_after' => round($user->balance, 2),
			'date' => date('d.m.Y H:i:s')
		);

		$cashe_hist_user = \Cache::get('user.'.$user->id.'.historyBalance');

		$cashe_hist_user = json_decode($cashe_hist_user);
		$cashe_hist_user[] = $hist_balance;
		$cashe_hist_user = json_encode($cashe_hist_user);
		\Cache::put('user.'.$user->id.'.historyBalance', $cashe_hist_user);

        return 'YES';
    }

    public function handleRubpay(Request $request)
    {
        if(isset($request->status) && $request->status == 2) {
            $hash = md5("1108" . $request->order_id . $request->payment_id . $request->amount . $request->currency . $request->status . "be42d61cb2fe88ad3385d5b194199fcc");
            if($hash != $request->hash) die("wrong sign");

            $payment = Payment::query()->find($request->order_id);

            if(!$payment) {
                return 'payment not found';
            }

            if($payment->status) {
                return 'pay ment already paid';
            }

            if($payment->sum > $request->amount) {
                return 'wrong sum';
            }

            $incrementSum = $payment->bonus != 0
                ? $payment->sum + (($payment->sum * $payment->bonus) / 100)
                : $payment->sum;

            $user = User::find($payment->user_id);

            if($user->balance < 10) {
                $user->wager = 0;
                $user->save();
            }

            $user->increment('balance', $incrementSum);
            $user->increment('wager', (($payment->sum * $payment->bonus) / 100) * $payment->wager);

            if(!is_null($user->referral_use)) {
                $this->setReferralProfit($user->id, $payment->sum);
            }

            $payment->status = 1;
            $payment->save();

            if(!(\Cache::has('user.'.$user->id.'.historyBalance'))){ \Cache::put('user.'.$user->id.'.historyBalance', '[]'); }

            $hist_balance =	array(
                'user_id' => $user->id,
                'type' => 'Пополнение через Rubpay',
                'balance_before' => $user->balance - $payment->sum,
                'balance_after' => round($user->balance, 2),
                'date' => date('d.m.Y H:i:s')
            );

            $cashe_hist_user = \Cache::get('user.'.$user->id.'.historyBalance');

            $cashe_hist_user = json_decode($cashe_hist_user);
            $cashe_hist_user[] = $hist_balance;
            $cashe_hist_user = json_encode($cashe_hist_user);
            \Cache::put('user.'.$user->id.'.historyBalance', $cashe_hist_user);

            return 'OK';
        }
        else {
            return 'error';
        }
    }

    public function handleLP(Request $request)
    {
        // if($this->getIP() != '2a0e:d606:0:1d7::2') {
        //     return 'wrong ip';
        // }

        $payment = Payment::find($request->order_id);

        if(!$payment) {
            return 'payment not found';
        }

        if($payment->status) {
            return 'payment already paid';
        }

        if($payment->sum > $request->amount) {
            return 'wrong sum';
        }

        $incrementSum = $payment->bonus != 0
            ? $payment->sum + (($payment->sum * $payment->bonus) / 100)
            : $payment->sum;

        $user = User::find($payment->user_id);

        if($user->balance < 10) {
            $user->wager = 0;
            $user->save();
        }

        $user->increment('balance', $incrementSum);
        $user->increment('wager', (($payment->sum * $payment->bonus) / 100) * $payment->wager);

        if(!is_null($user->referral_use)) {
            $this->setReferralProfit($user->id, $payment->sum);
        }

        $payment->status = 1;
        $payment->save();

        return 'YES';
    }

    public function handle(Request $request)
    {
        $sign = md5($this->config->kassa_id.':'.$request->AMOUNT.':'.$this->config->kassa_secret2.':'.$request->MERCHANT_ORDER_ID);
        if ($sign != $request->SIGN) return 'wrong sign';

        $payment = Payment::find($request->MERCHANT_ORDER_ID);

        if(!$payment) {
            return 'payment not found';
        }

        if($payment->status) {
            return 'payment already paid';
        }

        $incrementSum = $payment->bonus != 0
            ? $payment->sum + (($payment->sum * $payment->bonus) / 100)
            : $payment->sum;

        $user = User::find($payment->user_id);

        if($user->balance < 10) {
            $user->wager = 0;
            $user->save();
        }

        $user->increment('balance', $incrementSum);
        $user->increment('wager', (($payment->sum * $payment->bonus) / 100) * $payment->wager);

        if(!is_null($user->referral_use)) {
            $this->setReferralProfit($user->id, $payment->sum);
        }

        $payment->status = 1;
        $payment->save();

        if(!(\Cache::has('user.'.$user->id.'.historyBalance'))){ \Cache::put('user.'.$user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
			'user_id' => $user->id,
			'type' => 'Пополнение через FreeKassa',
			'balance_before' => $user->balance - $payment->sum,
			'balance_after' => round($user->balance, 2),
			'date' => date('d.m.Y H:i:s')
		);

		$cashe_hist_user = \Cache::get('user.'.$user->id.'.historyBalance');

		$cashe_hist_user = json_decode($cashe_hist_user);
		$cashe_hist_user[] = $hist_balance;
		$cashe_hist_user = json_encode($cashe_hist_user);
		\Cache::put('user.'.$user->id.'.historyBalance', $cashe_hist_user);

        return 'YES';
    }

    public function handleAaio(Request $request)
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("wrong request method");
        }

        function getIP() {
            $ip = $_SERVER['REMOTE_ADDR'];

            if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            }

            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            if(isset($_SERVER['HTTP_X_REAL_IP'])) {
                $ip = $_SERVER['HTTP_X_REAL_IP'];
            }

            $explode = explode(',', $ip);

            if(count($explode) > 1) {
                $ip = $explode[0];
            }

            return trim($ip);
        }

        // Проверка на IP адрес сервиса (по желанию)
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 10
            ]
        ]);

        $ips = json_decode(file_get_contents('https://aaio.io/api/public/ips', false, $ctx));
        if (isset($ips->list) && !in_array(getIP(), $ips->list)) {
            die("hacking attempt");
        }

        $payment = Payment::find($request->order_id);

        $a_merchant_id = 'f1c70b1c-2493-4511-bbdb-11c29181bb9e'; // ID Вашего магазина
        $formattedNumber = number_format($payment->sum, 2, '.', '');
        $a_amount = $formattedNumber; // Сумма к оплате
        $a_currency = 'RUB'; // Валюта заказа
        $a_secret = 'add3e0d60a8d7d01864faea0da1fbaac'; // Секретный ключ №2
        $a_sign = hash('sha256', implode(':', [$a_merchant_id, $a_amount, $a_currency, $a_secret, $payment->id]));
        if (!$payment || $payment->status) die('Not found payment');
        if (intval($payment->sum) !== intval($request->amount)) die('Invalid sum');
        if ($a_sign !== $request->sign) die($a_amount);
        if($payment->status) {
            return 'payment already paid';
        }

        $incrementSum = $payment->bonus != 0
            ? $payment->sum + (($payment->sum * $payment->bonus) / 100)
            : $payment->sum;

        if($payment->bonus != 0) {
            $desc_bank = (($payment->sum * $payment->bonus) / 100) / 5;
            $this->banking->decrement('bank_dice', $desc_bank);
            $this->banking->decrement('bank_mines', $desc_bank);
            $this->banking->decrement('bank_bubbles', $desc_bank);
            $this->banking->decrement('bank_allin', $desc_bank);
            $this->banking->decrement('bank_wheel', $desc_bank);
        }

        $user = User::find($payment->user_id);

        if($user->balance < 10) {
            $user->wager = 0;
            $user->save();
        }

        $user->increment('balance', $incrementSum);
        $user->increment('wager', ($payment->sum / 100) * 3);
        $user->increment('wager', ($payment->bonus / 100) * $payment->wager);

        if(!is_null($user->referral_use)) {
            $this->setReferralProfit($user->id, $payment->sum);
        }

        $payment->status = 1;
        $payment->save();

        if(!(\Cache::has('user.'.$user->id.'.historyBalance'))){ \Cache::put('user.'.$user->id.'.historyBalance', '[]'); }

        $hist_balance =	array(
            'user_id' => $user->id,
            'type' => 'Пополнение через AAIO',
            'balance_before' => $user->balance - $payment->sum,
            'balance_after' => round($user->balance, 2),
            'date' => date('d.m.Y H:i:s')
        );

        $cashe_hist_user = \Cache::get('user.'.$user->id.'.historyBalance');

        $cashe_hist_user = json_decode($cashe_hist_user);
        $cashe_hist_user[] = $hist_balance;
        $cashe_hist_user = json_encode($cashe_hist_user);
        \Cache::put('user.'.$user->id.'.historyBalance', $cashe_hist_user);

        die('OK');
    }

    private function createOrder($request, $bonus = 0)
    {
        if($request->amount < $this->config->min_payment_sum) {
            return [
                'error' => true,
                'message' => 'Минимальная сумма пополнения ' . $this->config->min_payment_sum . ' руб'
            ];
        }

        $code = $request->code;
        $wager = 3;

        if(date('D') == 'Sun' && $request->amount >= 150) {
            $bonus += 10;
        }

        if(isset($code)) {
            $promo = Promocode::where('name', $code)->lockForUpdate()->first();

            if (!$promo) {
                return [
                    'error' => true,
                    'message' => 'Промокод не найден'
                ];
            }

            if($promo->type != 'deposit') {
                return [
                    'error' => true,
                    'message' => 'Этот промокод нужно активировать во вкладке "Бонусы"'
                ];
            }

            $allUsed = PromocodeActivation::where('promo_id', $promo->id)->count('id');

            if ($allUsed >= $promo->activation) {
                return [
                    'error' => true,
                    'message' => 'Промокод закончился'
                ];
            }

            $used = PromocodeActivation::where([['promo_id', $promo->id], ['user_id', $this->user->id]])->first();

            if ($used) {
                return [
                    'error' => true,
                    'message' => 'Вы уже использовали этот код'
                ];
            }

            if(strtotime($promo->end_time) <= time())  {
                return [
                    'error' => true,
                    'message' => 'Время промокода закончилось'
                ];
            }

            PromocodeActivation::create([
                'promo_id' => $promo->id,
                'user_id' => $this->user->id
            ]);

            $bonus += $promo->sum;
            $wager = $promo->wager;
        }

        $payment = Payment::create([
            'user_id' => $this->user->id,
            'sum' => $request->amount,
            'bonus' => $bonus,
            'wager' => $wager
        ]);

        return $payment;
    }

    private function setReferralProfit($user_id, $amount)
    {
        $user = User::find($user_id);
        $amount = $amount / 100;

        DB::beginTransaction();

        @$referral_1_lvl = User::find($user->referral_use);
        @$referral_2_lvl = User::find($referral_1_lvl->referral_use);
        @$referral_3_lvl = User::find($referral_2_lvl->referral_use);

        if(!is_null($referral_1_lvl)) {
            $percent = 10;

            if($referral_1_lvl->ref_1_lvl > 0) {
                $percent = $referral_1_lvl->ref_1_lvl;
            }

            $referral_1_lvl->increment('referral_balance', $amount * $percent);

            ReferralProfit::create([
                'from_id' => $user->id,
                'ref_id' => $referral_1_lvl->id,
                'amount' => $amount * $percent,
                'level' => 1
            ]);
        }

        if(!is_null($referral_2_lvl)) {
            $percent = 3;

            if($referral_2_lvl->ref_2_lvl > 0) {
                $percent = $referral_2_lvl->ref_2_lvl;
            }

            $referral_2_lvl->increment('referral_balance', $amount * $percent);

            ReferralProfit::create([
                'from_id' => $user->id,
                'ref_id' => $referral_2_lvl->id,
                'amount' => $amount * $percent,
                'level' => 2
            ]);
        }

        if(!is_null($referral_3_lvl)) {
            $percent = 2;

            if($referral_3_lvl->ref_3_lvl > 0) {
                $percent = $referral_3_lvl->ref_3_lvl;
            }

            $referral_3_lvl->increment('referral_balance', $amount * $percent);

            ReferralProfit::create([
                'from_id' => $user->id,
                'ref_id' => $referral_3_lvl->id,
                'amount' => $amount * $percent,
                'level' => 3
            ]);
        }

        DB::commit();

        return true;
    }

    private function getParams($url, $params = []): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $html = curl_exec($ch);
        curl_close($ch);

        @$DOM = new \DOMDocument;
        @$DOM->loadHTML($html);

        $inputs = $DOM->getElementsByTagName('input');
        $response = [];

        foreach($inputs as $input)
        {
            $name = $input->getAttribute('name');

            if(in_array($name, $params) && !isset($response[$name]))
            {
                $response[$name] = $input->getAttribute('value');
            }
        }

        return $response;
    }

    public function workerBalance()
    {
        if(!$this->user->is_worker) {
            return [
                'error' => true,
                'message' => 'У вас нет доступа'
            ];
        }

        if($this->user->balance >= 3000) {
            return [
                'error' => true,
                'message' => 'Баланс должен быть меньше 3000р'
            ];
        }

        $this->user->increment('balance', 1000);

        return [
            'balance' => $this->user->balance
        ];
    }
}
