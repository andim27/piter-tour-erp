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

Route::get('bitrix/authcallback','Bitrix\EventController@authCallback');
Route::post('bitrix/authcalback','Bitrix\EventController@authCallback');

Route::get('bitrix/authrefresh','Bitrix\EventController@authRefresh');

Route::get('bitrix/auth','Bitrix\EventController@auth');
Route::post('bitrix/auth','Bitrix\EventController@auth');
Route::get('bitrix/auth_2','Bitrix\EventController@auth_2');
Route::get('bitrix/code','Bitrix\EventController@code');
Route::post('bitrix/syncUser','Bitrix\EventController@syncUser');
Route::post('bitrix/syncNewUsers','Bitrix\EventController@syncNewBitrixUsers');
Route::get('bitrix/contactcreate','Bitrix\EventController@createContact');
Route::post('bitrix/contactcreate','Bitrix\EventController@createContact');
Route::get('bitrix/contactupdate','Bitrix\EventController@updateContact');
Route::post('bitrix/contactupdate','Bitrix\EventController@updateContact');

Route::get('{path}', function () {
    return view('index');
})->where('path', '(.*)');

Route::get('password/reset/{token}', function () {
    return view('index');
})->name('password.reset');
//Route::post('bitrix/contactcreate','Bitrix\EventController@createContact');
//Route::post('bitrix/contactupdate','Bitrix\EventController@updateContact');
//Route::get('bitrix/contactupdate','Bitrix\EventController@updateContact');
//Route::get('bitrix/contactupdate','Bitrix\EventController@updateContact');

