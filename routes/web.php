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
        Route::any('/sign-up', 'UsersController@signUp')->name('sign-up');
        Route::any('/add-payment-method', 'UsersController@addPaymentMethod')->name('add-payment-method');
        Route::any('/affiliated-sign-up', 'UsersController@affiliatedSignUp')->name('affiliated-sign-up');
        Route::any('/affiliated-payment', 'UsersController@affiliatedPayment')->name('affiliated-payment');

        Route::any('/forgot-password', 'UsersController@forgotPassword')->name('forgot-password');
        Route::any('/change-password', 'UsersController@changePassword')->name('change-password');
        Route::any('/reset-password/{token}', 'UsersController@resetPassword')->name('reset-password');

        /* Authenticated sections */
        Route::group(['middleware' => 'guest:web'], function () {
            Route::any('/edit-profile', 'UsersController@editProfile')->name('edit-profile');
            Route::any('/my-purchases', 'UsersController@editProfile')->name('my-purchases');
            Route::any('/my-favourites', 'UsersController@editProfile')->name('my-favourites');
            Route::any('/membership', 'UsersController@editProfile')->name('membership');
            
            Route::any('/change-user-password', 'UsersController@changeUserPassword')->name('change-user-password');
            Route::any('/logout', 'UsersController@logout')->name('logout');
        });
    });
    
});
