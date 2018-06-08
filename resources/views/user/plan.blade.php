<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-6-5
 * Time: 上午12:01
 */?>
@extends('layout')
@section('title')
    OA-生产计划:{{$plan[0]->title}}
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
    <script type="text/javascript">
        function submits(){
            var data={
                action: 'submit',
                planId:{{$plan[0]->id}},
                executorId:{{$plan[0]->executorId}},
                creatorId:{{$plan[0]-> creatorId}},
                title:'{{$plan[0]->title}}'
            };

            postData(data);
        }
        function accepts(){
            var data={
                action: 'accept',
                planId:{{$plan[0]->id}},
                executorId:{{$plan[0]->executorId}},
                creatorId:{{$plan[0]-> creatorId}},
                title: '{{$plan[0]->title}}'
            };

            postData(data);
        }
        function postData(data) {
            $.ajax({
                url:'/api/plan/updatePlan',
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
    <div>
        @if(($plan[0]->status==0)&&(Session::get('userId')==$plan[0]->executorId))
            <input type="button" class="am-btn am-btn-primary" onclick="javascript:accepts()"value="接受任务">
            @elseif(($plan[0]->status==1)&&(Session::get('userId')==$plan[0]->executorId))
            <input type="button" class="am-btn am-btn-primary" onclick="javascript:submits()" value="提交任务">
       @endif
    </div>

@endsection
