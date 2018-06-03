<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-5-29
 * Time: 下午10:16
 */?>
@extends('layout')
@section('style')
    .outer-container,.content-content {
    width: 100%; height: 580px;
    }
    .outer-container {
    position: relative;
    overflow: hidden;
    }
    .inner-container {
    position: absolute;
    left: 0;
    overflow-x: hidden;
    overflow-y: scroll;
    width:100%;
    }

    /* for Chrome */
    .inner-container::-webkit-scrollbar {
    display: none;
    }
    .chat{
     border:1px solid #dddddd;
    }
    @endsection
@section('title')
    OA-通讯录
@endsection
@section('contents')
    <div class="am-g" style="">
        <div class="am-u-sm-3">
            <h4 class="header-title m-t-0 m-b-30">联系人</h4>
            <div class="outer-container">
                <div class="inner-container">
                    <div class="content-content">
                        @foreach ($users as $user)
                            <div class="card-box widget-user">
                                <div>
                                    <img src={{$user->avatar}} class="img-responsive img-circle" alt="user">
                                    <div class="wid-u-info">
                                        <h4 class="m-t-0 m-b-5 font-600">{{$user->nickname}}</h4>
                                        <p class="text-muted m-b-5 font-13">{{$user->email}}</p>
                                        <small class="text-warning"><b>{{$user->sex}}</b></small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="am-u-sm-9">
            <h4 class="header-title m-t-0 m-b-30">聊天</h4>
            <div class="chat">
            <div class="outer-container ">
                <div class="inner-container ">
                    <div class="content-content ">
                        @foreach ($users as $user)
                            <div class="{{--card-box widget-user--}}">
                                <div>
                                    <img src={{$user->avatar}} class="img-responsive img-circle" alt="user">
                                    <div class="wid-u-info">
                                        <h4 class="m-t-0 m-b-5 font-600">{{$user->nickname}}</h4>
                                        <p class="text-muted m-b-5 font-13">{{$user->email}}</p>
                                        <small class="text-warning"><b>{{$user->sex}}</b></small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <textarea>fdfd</textarea><input  type="button" value="12">
                </div>
            </div>
            </div>
        </div>
    </div>

@endsection