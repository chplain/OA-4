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
        //return view('welcome');
        return view('public.login');
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
        Route::get('index','UserController@index');
        Route::get('noteList','noteController@getNoteList');
        Route::get('note/{id}','noteController@getNote');
        Route::get('addNote',function(){
            return view('user.addNote');
        });
        Route::get('planList','planController@getPlanlist');
        Route::get('addPlan','planController@initialAddPlan');
        Route::get('plan/{id}','planController@getPlan');
        Route::get('pan',function(){
            return view('user.pan');
        });
        Route::get('userInfo/{id}','UserController@userInfo');
        Route::get('modifyUserInfo/{id}', 'UserController@modifyUserInfo');
        Route::get('infoList','InfoController@geInfoList');
        Route::get('addressBooks','addressBooksController@getList');
        Route::get('logout','UserController@logout');
    });
    /**
     * 后端管理页面路由
     */
    Route::group(['prefix'=>'manager'],function(){
        Route::get('users','UserController@member');
        Route::get('ip','ipController@getIpList');
        Route::get('pan',function(){
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
    Route::group(['prefix'=>'ip'],function(){
        Route::post('upload',function(){});
        Route::post('download',function(){});
        Route::post('delete',function(){});
        Route::get('update','ipController@update');
    });
    /**
     * 用户信息API
     */
   Route::group(['prefix'=>'user'],function(){
       Route::post('login','UserController@login');
       Route::post('register','UserController@register');
       Route::post('update','UserController@update');
       Route::post('delete','UserController@delete');
   });

   /**
    * 个人日记API
    */
   Route::group(['prefix'=>'note'],function() {
       Route::post('add','noteController@addNote');
       Route::post('update',function(){});
       Route::post('delete',function(){});
   });
    /**
     * 通讯录API
     */
//   Route::group();
    /**
     * 短消息公告
     */
    Route::group(['prefix'=>'info'],function() {
        Route::post('addPlan','planController@addPlan');
        Route::post('updateInfo','InfoController@updateInfo');
        Route::post('delete',function(){});
    });
    Route::group(['prefix'=>'plan'],function() {
        Route::post('addPlan','planController@addPlan');
        Route::post('updatePlan','planController@updatePlan');
        Route::post('delete',function(){});
    });
});