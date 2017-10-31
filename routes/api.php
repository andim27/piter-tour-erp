<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    //--my---
    Route::post('/users','Users\UsersController@getList');
    Route::post('/users/create','Users\UsersController@create');
    Route::post('/users/update','Users\UsersController@update');
    Route::post('/users/deactivate','Users\UsersController@deactivate');

    Route::post('/departments','Users\Departments@getList');
    Route::post('/departments/create','Users\Departments@create');
    Route::post('/departments/getusers','Users\Departments@getUsers');

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');


    Route::post('/tours','Tours\TourController@index');
    Route::post('/tours/create-q','Tours\TourController@createByQuotation');
});
//Route::resource('api/tours', 'Tours\TourController');


Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
//    Route::post('bitrix/contactcreate','Bitrix\EventController@createContact');
//    Route::post('bitrix/contactupdate','Bitrix\EventController@updateContact');
//    Route::get('bitrix/contactupdate','Bitrix\EventController@updateContact');
//    Route::get('bitrix/contactupdate','Bitrix\EventController@updateContact');
});




