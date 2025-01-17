<?php

/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
|
| Disini adalah routing untuk client Rabbit Media dengan middleware "web" .
|
*/

Route::group(['namespace' => 'Pages', 'prefix' => '/'], function () {

    Route::get('/', [
        'middleware' => 'visitor',
        'uses' => 'UserController@index',
        'as' => 'home'
    ]);

    Route::get('how-it-works', [
        'middleware' => 'visitor',
        'uses' => 'UserController@showHowItWorks',
        'as' => 'show.how-it-works'
    ]);

    Route::get('about', [
        'middleware' => 'visitor',
        'uses' => 'UserController@about',
        'as' => 'about'
    ]);

    Route::post('contact', [
        'uses' => 'UserController@postContact',
        'as' => 'submit.contact'
    ]);

    Route::get('info', [
        'middleware' => 'visitor',
        'uses' => 'UserController@info',
        'as' => 'info'
    ]);

    Route::get('faq', [
        'middleware' => 'visitor',
        'uses' => 'UserController@faq',
        'as' => 'faq'
    ]);

    Route::group(['prefix' => 'portfolios'], function () {

        Route::get('/', [
            'middleware' => 'visitor',
            'uses' => 'UserController@showPortfolio',
            'as' => 'show.portfolio'
        ]);

        Route::get('data', [
            'uses' => 'UserController@getPortfolios',
            'as' => 'get.portfolios'
        ]);

        Route::get('title/{title}', [
            'uses' => 'UserController@getTitlePortfolios',
            'as' => 'get.title.portfolios'
        ]);

        Route::get('{jenis}/{id}/galleries', [
            'middleware' => 'visitor',
            'uses' => 'UserController@showPortfolioGalleries',
            'as' => 'show.portfolio.gallery'
        ]);

    });

    Route::group(['prefix' => 'services'], function () {

        Route::get('/', [
            'middleware' => 'visitor',
            'uses' => 'UserController@showService',
            'as' => 'show.service'
        ]);

        Route::get('{jenis}/{id}/pricing', [
            'middleware' => 'visitor',
            'uses' => 'UserController@showServicePricing',
            'as' => 'show.service.pricing'
        ]);

        Route::group(['prefix' => 'order', 'namespace' => 'Client'], function () {

            Route::get('{id}', [
                'middleware' => 'visitor',
                'uses' => 'OrderController@showOrder',
                'as' => 'show.order'
            ]);

            Route::get('studio/{id}', [
                'uses' => 'OrderController@getDetailStudio',
                'as' => 'get.detail.studio'
            ]);

            Route::post('submit', [
                'uses' => 'OrderController@submitOrder',
                'as' => 'submit.order'
            ]);

            Route::get('paymentMethod/{id}', [
                'uses' => 'OrderController@getPaymentMethod',
                'as' => 'get.paymentMethod'
            ]);

            Route::put('pay', [
                'uses' => 'OrderController@payOrder',
                'as' => 'pay.order'
            ]);

            Route::put('payment-proof/submit', [
                'uses' => 'OrderController@uploadPaymentProof',
                'as' => 'upload.paymentProof'
            ]);

            Route::get('delete/{id}', [
                'uses' => 'OrderController@deleteOrder',
                'as' => 'delete.order'
            ]);

            Route::get('invoice/{id}', [
                'middleware' => 'visitor',
                'uses' => 'OrderController@invoiceOrder',
                'as' => 'invoice.order'
            ]);

            Route::post('log/review', [
                'uses' => 'OrderController@orderLogReview',
                'as' => 'review.orderLog'
            ]);

        });

    });

    Route::group(['prefix' => 'feedback'], function () {

        Route::get('/', [
            'middleware' => 'visitor',
            'uses' => 'UserController@feedback',
            'as' => 'feedback'
        ]);

        Route::post('submit', [
            'middleware' => 'auth',
            'uses' => 'UserController@postFeedback',
            'as' => 'feedback.submit'
        ]);

        Route::get('{id}/delete', [
            'middleware' => 'auth',
            'uses' => 'UserController@deleteFeedback',
            'as' => 'feedback.delete'
        ]);

    });

    Route::group(['prefix' => 'account', 'namespace' => 'Client'], function () {

        Route::get('profile', [
            'middleware' => 'visitor',
            'uses' => 'AccountController@editProfile',
            'as' => 'client.edit.profile'
        ]);

        Route::put('profile/update', [
            'uses' => 'AccountController@updateProfile',
            'as' => 'client.update.profile'
        ]);

        Route::get('settings', [
            'middleware' => 'visitor',
            'uses' => 'AccountController@accountSettings',
            'as' => 'client.settings'
        ]);

        Route::put('settings/update', [
            'uses' => 'AccountController@updateAccount',
            'as' => 'client.update.settings'
        ]);

        Route::group(['prefix' => 'dashboard'], function () {

            Route::get('order-status', [
                'middleware' => 'visitor',
                'uses' => 'OrderController@showDashboard',
                'as' => 'client.dashboard'
            ]);

            Route::get('data', [
                'uses' => 'OrderController@getOrderStatus',
                'as' => 'get.client.orderStatus'
            ]);

        });

    });

});