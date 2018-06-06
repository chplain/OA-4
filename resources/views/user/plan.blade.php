<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-6-5
 * Time: 上午12:01
 */?>
@extends('layout')
@section('title')
    OA-日记:{{$plan[0]->title}}
@endsection
@include('editor::decode')
@section('contents')
    <article class="am-article">
        <div class="am-article-hd">
            <h1 class="am-article-title">{{$plan[0]->title}}</h1>
            <p>
                <span class="am-article-meta">发布人： {{$plan[0]->creator}}</span>
                <span class="am-article-meta">执行人： {{$plan[0]->executor}}</span>
            </p>
            <p>
                <span class="am-article-meta">发布日期 : {{$plan[0]->created_at}}</span> -----
                <span class="am-article-meta">截止日期 : {{$plan[0]->deadTime}}</span>
            </p>
        </div>
        <div class="am-article-bd">
            <?php echo $plan[0]->content;?>
        </div>
    </article>
@endsection
