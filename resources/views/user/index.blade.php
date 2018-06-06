<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-6-4
 * Time: 下午9:50
 */?>
@extends('layout')
@section('title')
    OA-个人主页
@endsection
@section('contents')
    <h4 class="header-title m-t-0 m-b-30">最新用户</h4>
    <hr>
    @foreach ($users as $user)
        <div class="am-u-md-3">
            <div class="{{--card-box--}} widget-user">
                <div>
                    <img src={{$user->avatar}} class="img-responsive img-circle" alt="user">
                    <div class="wid-u-info">
                        <h4 class="m-t-0 m-b-5 font-600">{{$user->nickname}} </h4>
                        <p class="text-muted m-b-5 font-13">{{$user->email}}</p>
                        <small class="text-warning"><b>{{$user->sex}}</b></small>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="am-g">
        <!-- col start -->
        <div class="am-u-md-4">
            {{--<div class="card-box">--}}
                <h4 class="header-title m-t-0 m-b-30">收件箱</h4>
                <div class="inbox-widget nicescroll" style="height: 315px; overflow: hidden; outline: none;" tabindex="5000">
                    @foreach ($infos as $info)
                        <a href="#">
                            <div class="inbox-item">
                                <div class="inbox-item-img"><img src={{$info->avatar}} class="img-circle" alt=""></div>
                                <p class="inbox-item-author">{{$info->nickname}}</p>
                                <p class="inbox-item-text">{{$info->content}}</p>
                                <p class="inbox-item-date">{{$info->created_at}}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            {{--</div>--}}
        </div>
        <!-- col end -->

        <!-- col start -->
        <div class="am-u-md-8">
            {{--<div class="card-box">--}}
                <h4 class="header-title m-t-0 m-b-30">生产计划</h4>
                <div class="am-scrollable-horizontal am-text-ms" style="font-family: '微软雅黑';">
                    <table class="am-table   am-text-nowrap">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>项目名称</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>状态</th>
                            <th>责任人</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($plans as $plan)
                            <tr>
                                <td>{{$plan->id}}</td>
                                <td><a href="/user/plan/{{$plan->id}}">{{$plan->title}}</a></td>
                                <td>{{$plan->created_at}}</td>
                                <td>{{$plan->deadTime}}</td>
                                <td><span class="label label-danger">已发布</span></td>
                                <td>{{$plan->executor}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            {{--</div>--}}
        </div>
        <!-- col end -->
    </div>
@endsection
