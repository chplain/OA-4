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

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix'=>'/'],function(){
    Route::get('login',function(){
        return view('public.login');
    });
    Route::get('register',function(){
        return view('public.register');
    });
});
Route::group(['prefix'=>'user'],function(){
    Route::get('index',function(){
        return view('public.login');
    });
    Route::get('note',function(){
        return view('public.register');
    });
    Route::get('plan',function(){
        return view('public.register');
    });
    Route::get('pan',function(){
        return view('public.register');
    });
    Route::get('userInfo',function(){
        return view('public.register');
    });
});
Route::group(['prefix'=>'manage'],function(){
    Route::get('index',function(){
        return view('public.login');
    });
    Route::get('note',function(){
        return view('public.register');
    });
    Route::get('plan',function(){
        return view('public.register');
    });
    Route::get('pan',function(){
        return view('public.register');
    });
    Route::get('userInfo',function(){
        return view('public.register');
    });
});