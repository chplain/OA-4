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
/*Route::get('/', function () {
    return view('welcome');
});*/

/**
 * 公共的页面路由
 */
Route::group(['prefix'=>'/'],function(){
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('login',function(){
        return view('public.login');
    });
    Route::get('register',function(){
        return view('public.register');
    });
    /**
     * 用户相关路由页面
     */
    Route::group(['prefix'=>'user'],function(){
        Route::get('index',function(){
            return view('user.index');
        });
        Route::get('note',function(){
            return view('user.note');
        });
        Route::get('plan',function(){
            return view('user.plan');
        });
        Route::get('pan',function(){
            return view('user.pan');
        });
        Route::get('userInfo',function(){
            return view('user.userInfo');
        });
    });
    /**
     * 后端管理页面路由
     */
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

});

/**
 * 处理数据的后端api
 */
Route::group(['prefix'=>'api'],function(){
    /**
     * 网盘
     */
    Route::group(['prefix'=>'pan'],function(){
        Route::post('upload',function(){});
        Route::post('download',function(){});
        Route::post('delete',function(){});
        Route::post('',function(){});
    });
    /**
     * 用户信息
     */
   Route::group(['prefix'=>'user'],function(){
       Route::post('login',function(){});
       Route::post('register',function(){});
   });

});