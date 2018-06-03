<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-6-2
 * Time: 下午4:00
 */?>
@extends('layout')
@section('title')
    OA-增加生产计划
@endsection
@section('contents')
    <h4 class="header-title m-t-0 m-b-30">增加生产计划</h4>
    <form class="am-form">
        <div class="am-form-group">
            <label for="doc-ipt-email-2">标题</label>
            <input type="email" class="am-radius" id="doc-ipt-email-2" placeholder="输入标题">
        </div>
    </form >
    @include('editor::head')
    <div class="editor">
        <textarea id='myEditor'></textarea>
    </div>
                {{--<div class="editor">--}}
                    {{--// 创建一个 textarea 而已，具体的看手册，主要在于它的 id 为 myEditor--}}
                    {{--{!! Form::textarea('content', '', ['class' => 'form-control','id'=>'myEditor']) !!}--}}

                    {{--// 上面的 Form::textarea ，在laravel 5 中被提了出去，如果你没安装的话，直接这样用--}}
                    {{--<textarea id='myEditor'></textarea>--}}

                    {{--// 主要还是在容器的 ID 为 myEditor 就行--}}

                {{--</div>--}}
    </div>
@endsection

