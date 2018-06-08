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
@section('import')
    <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css">
    <script src="http://cdn.amazeui.org/jquery/2.1.4/jquery.min.js"></script>
    <script src="http://cdn.amazeui.org/amazeui/2.6.2/js/amazeui.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/amazeui-datetimepicker-se@1.0.0-beta.1/dist/amazeui.datetimepicker-se.min.css"/>
@endsection
@section('contents')
    <h4 class="header-title m-t-0 m-b-30">增加生产计划</h4>
    <form class="am-form">
        <div class="am-form-group">
            <label for="doc-ipt-email-2">标题</label>
            <input type="email" class="am-radius" id="doc-ipt-email-2" placeholder="输入标题"  autofocus>
        </div>
        <div class="am-g" style="margin-bottom: 10px;">
            <div class="am-u-sm-12 am-u-md-6">
                <label for="doc-ipt-email-2">开始日期:</label>
                  <div class="am-input-group input-group" id='datetimepicker6'>
                      <input class="am-form-field" id="created_at" required>
                      <span class="am-input-group-label datepickerbutton">
                          <i class="icon-th am-icon-calendar"></i>
                      </span>
                  </div>

            </div>
            <div class="am-u-sm-12 am-u-md-6">
                <label for="doc-ipt-email-2">截止日期:</label>
                <div class="am-input-group input-group" required id='datetimepicker7'>
                    <input class="am-form-field" id="deadTime">
                    <span class="am-input-group-label datepickerbutton">
                      <i class="icon-th am-icon-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
        <div style="margin: 10px auto 10px;">
        <label for="doc-select-1">任务执行人：</label>
        <select id="doc-select-1">
            @foreach($getMember as $member)
             <option value={{$member->id}}>{{$member->nickname}}--{{$member->userGroup}}---{{$member->role}}</option>
            @endforeach
        </select>
        </div>
    </form >
    @include('editor::head')
    <div class="editor">
        <textarea id='myEditor'></textarea>
    </div>
    <button type="submit" class="am-btn am-btn-primary" onclick="javascript:submit()">Submit</button>

    <script >
        $(function() {
            var $dp6 = $('#datetimepicker6');
            var $dp7 = $('#datetimepicker7');
            $dp6.datetimepicker();
            $dp7.datetimepicker({
                useCurrent: false //Important! See issue #1075
            });
            $dp6.on('dp.change', function(e) {
                $dp7.data('DateTimePicker').minDate(e.date);
            });
            $dp7.on('dp.change', function(e) {
                $dp6.data('DateTimePicker').maxDate(e.date);
            });
        });
        function submit(){
            var data={
                title: $('#doc-ipt-email-2').val(),
                content:$('#myEditor').val(),
                created_at:$('#created_at').val(),
                deadTime:$('#deadTime').val(),
                creatorId:{{Session::get('userId')}},
                executorId:$('#doc-select-1').val(),
            };
            // alert($('#doc-select-1').val())
             postData(data);
        }
        function postData(data) {
            $.ajax({
                url:'/api/plan/addPlan',
                dataType:'JSON',
                type:'POST',
                data:data,
                success:function(data){
                    if(data.status==200){
                        window.location.href="/user/plan/"+data.info;
                    }else{
                        alert(data.info);
                    }
                }
            })
        }

    </script>
@endsection

