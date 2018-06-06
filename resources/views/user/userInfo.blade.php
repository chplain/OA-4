<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-6-2
 * Time: 下午3:59
 */?>
@extends('layout')
@section('title')
    OA-用户信息
@endsection

@section('contents')

<!--    --><?php //var_dump($user);?>
@foreach($users as $user)
    <h4 class="header-title m-t-0 m-b-30">个人信息</h4>
    <div style="width: 60%;margin: auto;">
     <div class="">
        <img src={{$user->avatar}} class="img-circle img-thumbnail img-responsive">
     </div>
     <div>
        用户昵称：{{$user->nickname}}
     </div>
     <div>
        性别：{{$user->sex}}
     </div>
     <div>
        邮箱：{{$user->email}}
     </div>
     <div>
        手机：{{$user->phone}}
     </div>
     <div>
        用户角色：{{$user->role}}
     </div>
     <div>
        用户组：{{$user->userGroup}}
     </div>
     <div>
        加入时间:{{$user->created_at}}
     </div>
    <div>
        用户生日：{{$user->birth}}
    </div>
    </div>
    @endforeach
@endsection
