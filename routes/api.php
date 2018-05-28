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
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//Route::get('/test', function (Request $request) {
//    return 'HELLO WORLD';
//});
//Route::get('/',function(){
//    return "123";
//});
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('login', function (){ return 'qw';});
});
//$api = app('Dingo\Api\Routing\Router');
//$api->version('v1',function($api){
//    $api->get('/test',function(){
//        return "hello ";
//    });
////});