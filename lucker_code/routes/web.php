<?php

use Illuminate\Support\Facades\Route;

Route::post('/payment/fkfkfk', 'PaymentController@handle');
Route::post('/payment/aaio/handle', 'PaymentController@handleAaio');
//Route::post('/payment/vlito', 'PaymentController@handleVlito');
//Route::post('/payment/rubpayyy', 'PaymentController@handleRubpay');
//Route::post('/payment/linepayxxfgfg', 'PaymentController@handleLP');
//Route::post('/withdraw/handle', 'WithdrawController@fkwalletHandle');

Route::get('/r/{unique_id}', 'ReferralController@setReferral');

Route::post('/vk/handle', 'UserController@repostVK');

Route::group(['prefix' => 'api', 'middleware' => 'secretKey'], function () {
    Route::post('/getTimer', function() {
        return \App\Setting::query()->find(1)->bot_timer;
    });
    Route::post('/fake', 'FakeController@fake');

    // withdraws
    Route::group(['prefix' => 'withdraws', 'namespace' => 'Api'], function () {
        Route::post('/get', 'WithdrawsController@get');
        Route::post('/setStatus', 'WithdrawsController@setStatus');
    });
});

Route::group(['prefix' => 'bonus', 'middleware' => 'auth'], function () {
    Route::post('/init', 'BonusController@init');
    Route::post('/checkReposts', 'BonusController@checkReposts');
    Route::post('/transfer', 'BonusController@transfer');
    Route::post('/take', 'BonusController@take');
});

Route::group(['prefix' => 'user'], function () {
    Route::post('/init', 'UserController@init');
    Route::get('/logout', 'UserController@logout');
    Route::post('/videocard', 'UserController@videocardUpdate');
    Route::post('/fingerprint', 'UserController@fingerprintUpdate');
});

Route::group(['prefix' => '/auth'], function () {
    Route::get('/{provider}', ['as' => 'login', 'uses' => 'Auth\VkController@login']);
    Route::get('/{provider}/callback', 'Auth\VkController@callback');
});

Route::group(['prefix' => 'referral', 'middleware' => 'auth'], function () {
    Route::post('/get', 'ReferralController@init');
    Route::post('/take', 'ReferralController@take');
});

Route::group(['prefix' => 'dice', 'middleware' => 'auth'], function () {
    Route::post('/bet', 'DiceController@bet');
});

Route::group(['prefix' => 'wheel', 'middleware' => 'auth'], function () {
    Route::post('/start', 'WheelController@play');
});

Route::group(['prefix' => 'bubbles', 'middleware' => 'auth'], function () {
    Route::post('/play', 'BubblesController@play');
});

Route::group(['prefix' => 'allin', 'middleware' => 'auth'], function () {
    Route::post('/play', 'AllinController@play');
});

Route::group(['prefix' => 'mines', 'middleware' => 'auth'], function () {
    Route::post('/init', 'MinesController@init');
    Route::post('/start', 'MinesController@createGame');
    Route::post('/open', 'MinesController@openPath');
    Route::post('/take', 'MinesController@take');
});

Route::group(['prefix' => 'withdraw', 'middleware' => 'auth'], function () {
    Route::post('/create', 'WithdrawController@create');
    Route::post('/init', 'WithdrawController@init');
    Route::post('/decline', 'WithdrawController@decline');
});

Route::group(['prefix' => 'payment', 'middleware' => 'auth'], function () {
    Route::post('/create', 'PaymentController@create');
    Route::post('/init', 'PaymentController@init');
    Route::post('/worker', 'PaymentController@workerBalance');
});

Route::group(['prefix' => 'promo', 'middleware' => 'auth'], function () {
    Route::post('/activate', 'PromoController@activate');
    Route::post('/create', 'PromoController@create');
});

//Route::post('/slots/init', 'SlotsController@init');
//Route::post('/slots/get', 'SlotsController@getSlotWithPagenate');
//Route::post('/slots/count', 'SlotsController@countSlots');
//Route::post('/slots/getRandom', 'SlotsController@getRandom');
//Route::post('/slots/load', 'SlotsController@loadSlot');
//Route::post('/api/callbackSlots/{method}', 'SlotsController@callback');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function () {
    Route::group(['middleware' => 'access:admin'], function () {
        Route::post('/load', 'AdminController@load');
        Route::get('/', 'IndexController@index')->name('admin.index');
        Route::get('/users', 'UsersController@index')->name('admin.users');
        Route::get('/bots', 'BotsController@index')->name('admin.bots');
        Route::post('/versionUpdate', 'AdminController@versionUpdate');
        Route::post('/getUserByMonth', 'IndexController@getUserByMonth');
        Route::post('/getDepsByMonth', 'IndexController@getDepsByMonth');
        Route::post('/getVKinfo', 'IndexController@getVK');
        Route::post('/getCountry', 'IndexController@getCountry');

        Route::group(['prefix' => 'promocodes'], function () {
            Route::get('/', 'PromocodeController@index')->name('admin.promocodes');
            Route::get('/create', 'PromocodeController@create')->name('admin.promocodes.create');
            Route::post('/create', 'PromocodeController@createPost');
            Route::get('/delete/{id}', 'PromocodeController@delete')->name('admin.promocodes.delete');
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/edit/{id}', 'UsersController@edit')->name('admin.users.edit');
            Route::post('/edit/{id}', 'UsersController@editPost');
            Route::get('/create/{type}/{id}', 'UsersController@createFake')->name('admin.users.createFake');
            Route::post('/create/{type}/{id}', 'UsersController@addFake');
            Route::get('/delete/{id}', 'UsersController@delete')->name('admin.users.delete');
            Route::post('/checker', 'UsersController@checker');
        });

        Route::group(['prefix' => 'bots'], function () {
            Route::get('/create', 'BotsController@create')->name('admin.bots.create');
            Route::post('/create', 'BotsController@createPost');
            Route::get('/edit/{id}', 'BotsController@edit')->name('admin.bots.edit');
            Route::post('/edit/{id}', 'BotsController@editPost');
            Route::get('/delete/{id}', 'BotsController@delete')->name('admin.bots.delete');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'SettingsController@index')->name('admin.settings');
            Route::post('/', 'SettingsController@save');
        });

        Route::group(['prefix' => 'antiminus'], function () {
            Route::get('/', 'AntiminusController@index')->name('admin.antiminus');
            Route::post('/', 'AntiminusController@save');
        });

        Route::group(['prefix' => 'withdraws'], function () {
            Route::get('/', 'WithdrawsController@index')->name('admin.withdraws');
            Route::post('/get', 'WithdrawsController@getById');
            Route::post('/send', 'WithdrawsController@send');
            Route::post('/sendWaiting', 'WithdrawsController@waitingSend');
            Route::post('/decline', 'WithdrawsController@decline');
        });

        Route::group(['prefix' => 'deposits'], function () {
            Route::get('/', 'DepositsController@index')->name('admin.deposits');
        });

        Route::group(['prefix' => 'bonus'], function () {
            Route::get('/', 'BonusController@index')->name('admin.bonus');
            Route::post('/', 'BonusController@create')->name('admin.bonus.create');
            Route::get('/delete/{id}', 'BonusController@delete');
        });

        Route::post('/getMerchant', 'IndexController@getMerchant');
        Route::post('/getMerchant/aaio', 'IndexController@getMerchantAaio');
    });
});

Route::any('/{any}', 'IndexController@index')->where('any', '.*')->name('index');
