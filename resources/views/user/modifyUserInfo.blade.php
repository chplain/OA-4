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
            <label for="doc-ipt-email-2">邮件</label>
            <input type="email" class="am-radius" id="doc-ipt-email-2" placeholder="输入电子邮件">
        </div>
        <div class="am-form-group">
            <label for="doc-ipt-email-2">手机号</label>
            <input type="email" class="am-radius" id="doc-ipt-email-2" placeholder="输入手机号">
        </div>
        <div class="am-form-group">
            <label for="doc-ipt-pwd-2">密码</label>
            <input type="password" class="am-radius" id="doc-ipt-pwd-2" placeholder="输入密码">
        </div>
        <div class="am-form-group">
            <label for="doc-ipt-pwd-2">密码</label>
            <input type="password" class="am-radius" id="doc-ipt-pwd-2" placeholder="再次输入个密码">
        </div>
        <div class="am-checkbox">
            <label>
                <input type="checkbox"> 记住我
            </label>
        </div>
        <button type="submit" class="am-btn am-btn-primary">Submit</button>
    </form>
@endsection