@extends('layout')
@section('title')
    OA-个人主页
@endsection
@section('contents')
    {{--<div class="am-g">--}}
    <!-- Row start -->
        {{--<div class="am-u-md-16 card-box">--}}
            <h4 class="header-title m-t-0 m-b-30">最新用户</h4>
            <hr>
            @foreach ($users as $user)
                <div class="am-u-md-3">
                    <div class="{{--card-box--}} widget-user">
                        <div>
                            <img src={{$user->avatar}} class="img-responsive img-circle" alt="user">
                            <div class="wid-u-info">
                                <h4 class="m-t-0 m-b-5 font-600">{{$user->nickname}} </h4>
                                <p class="text-muted m-b-5 font-13">{{$user->email}}</p>
                                <small class="text-warning"><b>{{$user->sex}}</b></small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        {{--</div>--}}
    <!-- Row end -->
    {{--</div>--}}
    <!-- Row start -->
    <div class="am-g">
    <!-- col start -->
    <div class="am-u-md-4">
    {{--<div class="card-box">--}}
    <h4 class="header-title m-t-0 m-b-30">收件箱</h4>
    <div class="inbox-widget nicescroll" style="height: 315px; overflow: hidden; outline: none;" tabindex="5000">
    <a href="#">
    <div class="inbox-item">
    <div class="inbox-item-img"><img src="/css/user/img/avatar-1.jpg" class="img-circle" alt=""></div>
    <p class="inbox-item-author">Chadengle</p>
    <p class="inbox-item-text">Hey! there I'm available...</p>
    <p class="inbox-item-date">13:40 PM</p>
    </div>
    </a>
    <a href="#">
    <div class="inbox-item">
    <div class="inbox-item-img"><img src="/css/user/img/avatar-2.jpg" class="img-circle" alt=""></div>
    <p class="inbox-item-author">Shahedk</p>
    <p class="inbox-item-text">Hey! there I'm available...</p>
    <p class="inbox-item-date">10:15 AM</p>
    </div>
    </a>
    <a href="#">
    <div class="inbox-item">
    <div class="inbox-item-img"><img src="/css/user/img/avatar-10.jpg" class="img-circle" alt=""></div>
    <p class="inbox-item-author">Tomaslau</p>
    <p class="inbox-item-text">I've finished it! See you so...</p>
    <p class="inbox-item-date">13:34 PM</p>
    </div>
    </a>
    <a href="#">
    <div class="inbox-item">
    <div class="inbox-item-img"><img src="/css/user/img/avatar-4.jpg" class="img-circle" alt=""></div>
    <p class="inbox-item-author">Stillnotdavid</p>
    <p class="inbox-item-text">This theme is awesome!</p>
    <p class="inbox-item-date">13:17 PM</p>
    </div>
    </a>
    <a href="#">
    <div class="inbox-item">
    <div class="inbox-item-img"><img src="/css/user/img/avatar-5.jpg" class="img-circle" alt=""></div>
    <p class="inbox-item-author">Kurafire</p>
    <p class="inbox-item-text">Nice to meet you ff</p>
    <p class="inbox-item-date">12:20 PM</p>
    </div>
    </a>
    <a href="#">
    <div class="inbox-item">
    <div class="inbox-item-img"><img src="/css/user/img/avatar-5.jpg" class="img-circle" alt=""></div>
    <p class="inbox-item-author">Kurafire</p>
    <p class="inbox-item-text">Nice to meet youddd</p>
    <p class="inbox-item-date">12:20 PM</p>
    </div>
    </a>
    </div>
    </div>
    {{--</div>--}}
    <!-- col end -->

    <!-- col start -->
    <div class="am-u-md-8">
       <div class="card-box">
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
                      <th>责任人</th>
                  </tr>
               </thead>
               <tbody>
               @foreach($plans as $plan)
                   <tr>
                       <td>{{$plan->id}}</td>
                       <td>{{$plan->title}}</td>
                       <td>{{$plan->created_at}}</td>
                       <td>{{$plan->deadTime}}</td>
                       <td><span class="label label-danger">已发布</span></td>
                       <td>{{$plan->executor}}</td>
                   </tr>
               @endforeach
               </tbody>
           </table>
          </div>
        </div>
    </div>
    <!-- col end -->
    </div>
    @endsection