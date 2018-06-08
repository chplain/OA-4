<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-6-5
 * Time: 上午12:01
 */?>
@extends('layout')
@section('title')
    OA-用户ip
@endsection
@include('editor::decode')
@section('contents')
    <h4 class="header-title m-t-0 m-b-30">用户ip</h4>
    <div class="am-scrollable-horizontal am-text-ms" style="font-family: '微软雅黑';">
        <table class="am-table   am-text-nowrap">
            <thead>
            <tr>
                <th>#</th>
                <th>用户id</th>
                <th>用户昵称</th>
                <th>登陆ip</th>
                <th>登陆时间</th>
                <th>访问状态</th>
            </tr>
            </thead>
            <tbody>
            {{--user_ip.id','users.nickname','user_ip.userId','user_ip.ip','user_ip.updated_at','user_ip.status'--}}
            @foreach($ipList as $ip)
                <tr>
                    <td>{{$ip->id}}</td>
                    <th>{{$ip->userId}}</th>
                    <td>{{$ip->nickname}}</td>
                    <td>{{$ip->ip}}</td>
                    <td>{{$ip->updated_at}}</td>
                    @if($ip->status==0)
                      <td><a class="am-badge am-badge-success  am-text-lg" href="/api/ip/update?action=forbid&id={{$ip->id}}">允许</a></td>
                        @else
                        <td><a class="am-badge am-badge-warning am-text-lg" href="/api/ip/update?action=allow&id={{$ip->id}}">禁止</a></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
