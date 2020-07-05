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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['namespace' => 'admin', 'prefix' => 'securepanel', 'as' => 'admin.'], function () {
    Route::any('/', 'AuthController@login')->name('login');
    Route::any('/forget-password', 'AuthController@forgetPassword')->name('forget-password');

    Route::group(['middleware' => 'admin'], function () {
        Route::any('/dashboard', 'AccountController@dashboard')->name('dashboard');
        Route::any('/edit-profile', 'AccountController@editProfile')->name('edit-profile');
        Route::any('/change-password', 'AccountController@changePassword')->name('change-password');
        Route::any('/site-settings', 'AccountController@siteSettings')->name('site-settings');
        
        Route::any('/logout', 'AuthController@logout')->name('logout');

        Route::group(['prefix' => 'users', 'as' => 'user.'], function () {
            Route::get('/list', 'UsersController@list')->name('list');
            Route::get('/add', 'UsersController@add')->name('add');
            Route::post('/add-submit', 'UsersController@add')->name('addsubmit');
            Route::get('/edit/{id}', 'UsersController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'UsersController@edit')->name('editsubmit');
            Route::get('/status/{id}', 'UsersController@status')->name('change-status')->where('id','[0-9]+');
            Route::any('/change-password/{id}', 'UsersController@changePassword')->name('change-password')->where('id','[0-9]+');
            Route::get('/delete/{id}', 'UsersController@delete')->name('delete')->where('id','[0-9]+');
        });
        
        Route::group(['prefix' => 'cms', 'as' => 'CMS.'], function () {
			Route::get('/', 'CmsController@list')->name('list');
            Route::get('/edit/{id}', 'CmsController@edit')->name('edit')->where('id','[0-9]+');
            Route::post('/edit-submit/{id}', 'CmsController@edit')->name('editsubmit')->where('id','[0-9]+');
        });       
        
        Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
            Route::any('/', 'RoleController@list')->name('list');
            Route::post('/add-edit-action', 'RoleController@addEdit')->name('add-edit');
            
            Route::any('/permission/{roleType}', 'RoleController@permission')->name('permission');
            Route::post('/submit/{roleType}', 'RoleController@submitRolePermission')->name('submitpermission');
            Route::any('/delete/{id}', 'RoleController@delete')->name('delete')->where('id','[0-9]+');
        });

        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('/', 'CategoriesController@list')->name('list');
            Route::get('/add', 'CategoriesController@add')->name('add');
            Route::post('/add-submit', 'CategoriesController@add')->name('addsubmit');            
            Route::get('/edit/{id}', 'CategoriesController@edit')->name('edit')->where('id','[0-9]+');
            Route::any('/edit-submit/{id}', 'CategoriesController@edit')->name('editsubmit')->where('id','[0-9]+');
            Route::get('/status/{id}', 'CategoriesController@status')->name('change-status')->where('id','[0-9]+');
            Route::get('/delete/{id}', 'CategoriesController@delete')->name('delete')->where('id','[0-9]+');
        });

        Route::group(['prefix' => 'tag', 'as' => 'tag.'], function () {
            Route::get('/', 'TagsController@list')->name('list');
            Route::get('/add', 'TagsController@add')->name('add');
            Route::post('/add-submit', 'TagsController@add')->name('addsubmit');            
            Route::get('/edit/{id}', 'TagsController@edit')->name('edit')->where('id','[0-9]+');
            Route::any('/edit-submit/{id}', 'TagsController@edit')->name('editsubmit')->where('id','[0-9]+');
            Route::get('/status/{id}', 'TagsController@status')->name('change-status')->where('id','[0-9]+');
            Route::get('/delete/{id}', 'TagsController@delete')->name('delete')->where('id','[0-9]+');
        });

        Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
            Route::get('/', 'ProductsController@list')->name('list');
            Route::get('/add', 'ProductsController@add')->name('add');
            Route::post('/add-submit', 'ProductsController@add')->name('addsubmit');            
            Route::get('/edit/{id}', 'ProductsController@edit')->name('edit')->where('id','[0-9]+');
            Route::any('/edit-submit/{id}', 'ProductsController@edit')->name('editsubmit')->where('id','[0-9]+');
            Route::get('/status/{id}', 'ProductsController@status')->name('change-status')->where('id','[0-9]+');
            Route::get('/delete/{id}', 'ProductsController@delete')->name('delete')->where('id','[0-9]+');
            Route::any('/delete-product-feature', 'ProductsController@deleteProductFeature')->name('delete-product-feature')->where('id','[0-9]+');

            Route::get('/multiple-image/{id}', 'ProductsController@multifileupload')->name('multiple-image');
            Route::any('/store/{id}', 'ProductsController@store')->name('store');
            Route::any('/make_default_image', 'ProductsController@makeDefaultImage')->name('make_default_image');            
            Route::any('/image-delete','ProductsController@imageDelete')->name('image-delete');
            Route::any('/delete-product-image/{id}/{product_id}','ProductsController@deleteProductImage')->name('delete-product-image');
        });

        Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
            Route::get('/', 'BannersController@list')->name('list');
            Route::get('/add', 'BannersController@add')->name('add');
            Route::post('/add-submit', 'BannersController@add')->name('addsubmit');            
            Route::get('/edit/{id}', 'BannersController@edit')->name('edit')->where('id','[0-9]+');
            Route::any('/edit-submit/{id}', 'BannersController@edit')->name('editsubmit')->where('id','[0-9]+');
            Route::get('/status/{id}', 'BannersController@status')->name('change-status')->where('id','[0-9]+');
            Route::get('/delete/{id}', 'BannersController@delete')->name('delete')->where('id','[0-9]+');
        });


    
		
    });
});