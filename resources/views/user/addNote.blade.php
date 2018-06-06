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
            <input type="text" class="am-radius" id="title" placeholder="输入标题" required autofocus>
        </div>
    </form >
    @include('editor::head')
    <div class="editor">
        <textarea id='myEditor'></textarea>
    </div>
    <button type="submit" class="am-btn am-btn-primary" onclick="javascript:submit()">Submit</button>

    <script type="text/javascript">
         function submit(){
             var data={
                 title: $('#title').val(),
                 content:$('#myEditor').val(),
                 userId: {{Session::get('userId')}}
             };
             postData(data);
         }
         function postData(data) {
             $.ajax({
                 url:'/api/note/add',
                 dataType:'JSON',
                 type:'POST',
                 data:data,
                 success:function(data){
                     if(data.status==200){
                         window.location.href="/user/note/"+data.info;
                     }else{
                         alert(data.info);
                     }
                 }
             })
         }
    </script>
@endsection
