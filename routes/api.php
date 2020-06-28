<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('api')
    ->namespace('api')
    ->prefix("v1")
    ->group(function () {
        Route::get('/', 'HomeController@index')->name('api_index');
        Route::get('/generate-token', 'HomeController@generateToken')->name('api_generate_token');
        Route::get('/verification/{token}', 'HomeController@verification')->name('api_signup_verification');
        Route::post('/logout', 'HomeController@logOut')->name('api_logout');
        
        Route::middleware('api.token')
            ->group(function () {
                Route::get('/home-page', 'HomeController@homePage')->name('api_get_home_page');
            });
    });
