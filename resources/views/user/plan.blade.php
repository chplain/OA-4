<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-5-28
 * Time: 下午11:51
 */?>
@extends('layout')
@section('contents')
    <h4 class="header-title m-t-0 m-b-30">生产计划</h4>
    <div class="am-scrollable-horizontal am-text-ms" style="font-family: '微软雅黑';">
        <table class="am-table   am-text-nowrap">
            <thead>
            <tr>
                <th>#</th>
                <th>项目名称</th>
                <th>开始时间</th>
                <th>结束时间</th>
                <th>状态</th>
                <th>发布人</th>
                <th>责任人</th>
            </tr>
            </thead>
            <tbody>
            @foreach($planList as $plan)
            <tr>
                <td>{{$plan->id}}</td>
                <td>{{$plan->title}}</td>
                <td>{{$plan->created_at}}</td>
                <td>{{$plan->deadTime}}</td>
                <td><span class="label label-danger">已发布</span></td>
                <td>{{$plan->creator}}</td>
                <td>{{$plan->executor}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection


