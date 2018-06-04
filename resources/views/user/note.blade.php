<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-6-4
 * Time: 下午11:11
 */?>
@extends('layout')
@section('title')
    OA-日记:{{$note[0]->title}}
@endsection
@include('editor::decode')
@section('contents')
        <article class="am-article">
            <div class="am-article-hd">
                <h1 class="am-article-title">{{$note[0]->title}}</h1>
                <p><span class="am-article-meta">作者 {{$note[0]->nickname}}</span>
                    <span class="am-article-meta">日期:{{$note[0]->created_at}}</span></p>
            </div>
            <div class="am-article-bd">
                 <?php echo $note[0]->content;?>
            </div>
        </article>
    @endsection
