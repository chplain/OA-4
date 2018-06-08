<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-5-28
 * Time: 下午11:50
 */
?>
@extends('layout')
@section('title')
    OA-个人日记列表
@endsection
@section('contents')
    <!-- Row start -->
    <div class="am-g">
        <!-- col start -->
        <div class="am-u-md-16">
                <h4 class="header-title m-t-0 m-b-30">个人日记</h4>
                <div class="inbox-widget nicescroll" style=" overflow: hidden; outline: none;" tabindex="5000">
                @foreach ($noteList as $note)
                    <a href="/user/note/{{$note->id}}">
                        <div class="inbox-item">
                            <div class="inbox-item-img"><img src={{$note->avatar}} class="img-circle" alt=""></div>
                            <p class="inbox-item-author">{{$note->nickname}}</p>
                            <p class="inbox-item-title">标题: {{$note->title}}</p>
                            <p class="inbox-item-date">{{$note->created_at}}</p>
                        </div>
                    </a>
                 @endforeach
                </div>
        </div>
        <!-- col end -->
    </div>
@endsection
