<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-5-29
 * Time: 下午10:56
 */?>
@extends('layout')
@section('title')
    OA-消息列表
@endsection
@section('contents')
    <h4 class="header-title m-t-0 m-b-30">收件箱</h4>
    <div class="inbox-widget nicescroll" style=" overflow: hidden; outline: none;" tabindex="5000">
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
@endsection