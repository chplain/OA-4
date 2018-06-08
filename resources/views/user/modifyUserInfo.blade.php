<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-5-28
 * Time: 下午11:51
 */?>
@extends('layout')
@section('contents')
    <h4 class="header-title m-t-0 m-b-30">修改个人信息</h4>
    <form class="am-form">
        <div class="am-form-group">
            <label for="doc-ipt-email-2">昵称</label>
            <input type="email" class="am-radius" id="doc-ipt-nickname-2" value={{$users[0]->nickname}} placeholder="输入电子邮件">
        </div>
        <div class="am-form-group">
            <label for="doc-ipt-email-2">邮件</label>
            <input type="email" class="am-radius" id="doc-ipt-email-2" value={{$users[0]->email}} placeholder="输入电子邮件">
        </div>
        <div class="am-form-group">
            <label for="doc-ipt-email-2">手机号</label>
            <input type="email" class="am-radius" id="doc-ipt-phone-2" value={{$users[0]->phone}} placeholder="输入手机号">
        </div>
        <div class="am-form-group">
            <label for="doc-ipt-pwd-2">密码</label>
            <input type="password" class="am-radius" id="doc-ipt-pwd-2" value={{$users[0]->password}} placeholder="输入密码">
        </div>
        <div class="am-form-group">
            <label for="doc-ipt-pwd-2">再密码</label>
            <input type="password" class="am-radius" id="doc-ipt-pwd-2" value={{$users[0]->password }} placeholderplaceholder="再次输入个密码">
        </div>
        {{--<div class="am-checkbox">--}}
            {{--<label>--}}
                {{--<input type="checkbox"> 记住我--}}
            {{--</label>--}}
        {{--</div>--}}

    </form>
    <button type="submit" class="am-btn am-btn-primary" onclick="javascript:submit()">Submit</button>
    <script type="text/javascript">
        function submit(){
            var data={
                nickname:$('#doc-ipt-nickname-2').val(),
                password:$('#doc-ipt-pwd-2').val(),
                phone:$('#doc-ipt-phone-2').val(),
                email:$('#doc-ipt-email-2').val()
            };
            postData(data);
        }

        function postData(data) {
            $.ajax({
                url:'/api/user/update',
                dataType:'JSON',
                type:'POST',
                data:data,
                success:function(data){
                    if(data.status==200){
                        window.location.href="/user/index";//登陆成功刷新页面
                    }else{
                        alert(data.info);//登陆失败，返回错误
                    }
                }
            })
        }
    </script>
@endsection