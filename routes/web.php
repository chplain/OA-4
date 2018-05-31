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
        Route::get('noteList',function(){
            return view('user.note');
        });
        Route::get('planList',function(){
            return view('user.plan');
        });
        Route::get('pan',function(){
            return view('user.pan');
        });
        Route::get('userInfo',function(){
            return view('user.userInfo');
        });
        Route::get('infoList',function(){
            return view('user.info');
        });
        Route::get('addressBooks',function(){
            return view('user.addressBooks');
        });
        Route::get('logout','UserController@logout');
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
     * 网盘API
     */
    Route::group(['prefix'=>'pan'],function(){
        Route::post('upload',function(){});
        Route::post('download',function(){});
        Route::post('delete',function(){});
        Route::post('update',function(){});
    });
    /**
     * 用户信息API
     */
   Route::group(['prefix'=>'user'],function(){
       Route::post('login','UserController@login');
//       Route::get('login','UserController@login');
       Route::post('register',function(){});
       Route::post('update',function(){});
       Route::post('delete',function(){});

   });

   /**
    * 个人日记API
    */
   Route::group(['prefix'=>'note'],function() {
       Route::post('add',function(){});
       Route::post('update',function(){});
       Route::post('delete',function(){});
   });
    /**
     * 通讯录API
     */
//   Route::group();
//    /**
//     * 短消息公告
//     */
//   Route::group();
});