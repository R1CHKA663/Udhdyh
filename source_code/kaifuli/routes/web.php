<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiceController;
use App\Http\Controllers\MinesController;
use App\Http\Controllers\BubblesController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VkRepostController;
use Illuminate\Http\Request;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\JackpotController;

use
    App\Http\Middleware\DelayUser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/ref/{id}', [AuthController::class, 'clicked']);
Route::get('/panel', function () {
    return view('admin');
});
Route::get('/panel/{page?}/{content?}', function () {
    return view('admin');
});
Route::group(
    [
        'prefix' => '/panel/api/',
        'middleware' => 'admin',
    ],
    function () {
        Route::post('/promo/create', [AdminController::class, 'createPromo']);
        Route::get('/promo/get', [AdminController::class, 'getPromo']);
        Route::post('/promo/getUpdate', [AdminController::class, 'getUpdatePromo']);
        Route::post('/promo/update', [AdminController::class, 'updatePromo']);

        Route::get('/users/get', [AdminController::class, 'getUsers']);
        Route::post('/user/get', [AdminController::class, 'getUser']);
        Route::post('/user/update', [AdminController::class, 'updateUser']);

        Route::get('/bank/get', [AdminController::class, 'getBank']);
        Route::post('/bank/save', [AdminController::class, 'saveBank']);

        Route::post('/stats/get', [AdminController::class, 'getStats']);

        Route::get('/user/log', [AdminController::class, 'getLogs']);

        Route::get('/payment/all', [AdminController::class, 'getPayment']);

        Route::get('/withdraws/all', [AdminController::class, 'getWithdraws']);
        Route::post('/withdraws/get', [AdminController::class, 'getWithdraw']);
        Route::post('/withdraws/update', [AdminController::class, 'updateWithdraw']);
    }
);
Route::get('/', function () {
    return view('welcome');
});
Route::get('{page}', function () {
    return view('welcome');
});
Route::get('{page}/{section}', function () {
    return view('welcome');
});
Route::get('/api/vk/auth', [AuthController::class, 'auth']);
Route::post('/api/get', [AuthController::class, 'get']);
Route::post('/api/exit', [AuthController::class, 'exit']);


// ИГРЫ

Route::middleware(['is_auth', 'ban'])->group(function () {
    Route::middleware([DelayUser::class])->group(function () {

        Route::post('/api/jackpot/play', [JackpotController::class, 'play']);

        Route::post('/api/dice/play', [DiceController::class, 'play']);

        Route::post('/api/mines/play', [MinesController::class, 'play']);
        Route::post('/api/mines/press', [MinesController::class, 'press']);
        Route::post('/api/mines/take', [MinesController::class, 'take']);

        Route::post('/api/bubbles/play', [BubblesController::class, 'play']);

        Route::post('/api/withdraw/out', [WithdrawController::class, 'out']);
        Route::post('/api/withdraw/cancel', [WithdrawController::class, 'cancel']);

        Route::post('/api/user/outRef', [AuthController::class, 'outRef']);

        Route::post('/api/promo/active', [BonusController::class, 'activePromo']);

        Route::post('/api/raceback/out', [BonusController::class, 'outRaceBack']);

        Route::post('/api/bonus/social/free', [BonusController::class, 'freeBonusSocial']);

        Route::post('/api/bonus/more/free', [BonusController::class, 'freeBonusMore']);

        Route::post('/api/bonus/repost/free', [BonusController::class, 'freeBonusRepost']);

        Route::post('/api/bonus/repost/check', [BonusController::class, 'checkBonusRepost']);

        Route::post('/api/promocoder/get', [PromoController::class, 'get']);
        Route::post('/api/promocoder/create', [PromoController::class, 'create']);
    });
    Route::post('/api/mines/get', [MinesController::class, 'get']);

    // выводы
    Route::post('/api/withdraw/getOut', [WithdrawController::class, 'getOut']);

    Route::post('/api/payment/get', [PaymentController::class, 'getPayment']);

    Route::post('/api/user/getRef', [AuthController::class, 'getRef']);

    Route::post('/api/raceback/get', [BonusController::class, 'getRaceBack']);


    Route::post('/api/bonus/social/get', [BonusController::class, 'getBonusSocial']);

    Route::post('/api/bonus/more/get', [BonusController::class, 'getBonusMore']);

    Route::post('/api/bonus/repost/get', [BonusController::class, 'getBonusRepost']);
    Route::post('/api/bonus/repost/check', [BonusController::class, 'checkBonusRepost']);
    Route::post('/api/bonus/repost/transfer', [BonusController::class, 'transferBonusRepost']);
});


Route::post('/api/payment/new', [PaymentController::class, 'new']);
Route::any('/api/payment/fk', [PaymentController::class, 'success']);
Route::any('/api/payment/linepay', [PaymentController::class, 'successLinePay']);
Route::post('/api/payment/xmpay', [PaymentController::class, 'xmpay']);

Route::post('/api/vkontakte/success', [VkRepostController::class, 'success']);


Route::post('/api/jackpot/getSlider', [JackpotController::class, 'getSlider']);
Route::post('/api/jackpot/startGame', [JackpotController::class, 'startGame']);
Route::post('/api/jackpot/addCash', [JackpotController::class, 'addCash']);
