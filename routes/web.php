<?php

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
include('admin.php');

Route::group(['namespace' => 'site', 'as' => 'site.'], function () {    
    Route::get('/', 'HomeController@index')->name('home');

    /* User */
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::any('/login', 'UsersController@login')->name('login');
        Route::any('/register', 'UsersController@register')->name('register');
        Route::any('/forgot-password', 'UsersController@forgotPassword')->name('forgot-password');
        Route::any('/change-password', 'UsersController@changePassword')->name('change-password');
        Route::any('/reset-password/{token}', 'UsersController@resetPassword')->name('reset-password');

        /* Authenticated sections */
        Route::group(['middleware' => 'guest:web'], function () {
            Route::any('/personal-details', 'UsersController@personalDetails')->name('personal-details');
            
            Route::any('/change-user-password', 'UsersController@changeUserPassword')->name('change-user-password');
            Route::any('/logout', 'UsersController@logout')->name('logout');
        });
    });
    
});
