<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-6-1
 * Time: 下午11:02
 */
?>
@extends('layout')
@section('title')
    OA-写日记
@endsection
@section('contents')
    <h4 class="header-title m-t-0 m-b-30">写日记</h4>
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
@endsection
