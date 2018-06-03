<?php
/**
 * Created by PhpStorm.
 * User: vaniot
 * Date: 18-5-28
 * Time: 下午11:49
 */

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @section('title')
            @yield('title')
    </title>
    <link rel="stylesheet" href="/css/user/amazeui.css" />
    <link rel="stylesheet" href="/css/user/core.css" />
    <link rel="stylesheet" href="/css/user/menu.css" />
    <link rel="stylesheet" href="/css/user/index.css" />
    <link rel="stylesheet" href="/css/user/admin.css" />
    <link rel="stylesheet" href="/css/user/page/typography.css" />
    <link rel="stylesheet" href="/css/user/page/form.css" />
    <link rel="stylesheet" href="/css/user/component.css" />
    <link rel="stylesheet" href="/css/user/app.css" />
    <script type="text/javascript" src="/css/user/js/jquery-2.1.0.js" ></script>
    <style>
        @section('style')
            @yield('style')
    </style>
</head>
<body>
@if(!Session::has('userId'))
    <?php header('location:/login');?>
@endif

<div class="admin" style="background-color:#EAEEF2">
    <!-- ========== Left Sidebar Start ========== -->
    <!-- sidebar start -->
    <div class="admin-sidebar am-offcanvas  am-padding-0" style="margin-top: 0px;background: #ffffff;" id="admin-offcanvas">
        <div class="am-offcanvas-bar admin-offcanvas-bar">
            <!-- User -->
            <div class="user-box am-hide-sm-only">
                <div class="user-img">
                    <img src={{Session::get('avatar')}} alt="user-img" title={{Session::get('nickname')}} class="img-circle img-thumbnail img-responsive">
                    <div class="user-status offline"><i class="am-icon-dot-circle-o" aria-hidden="true"></i></div>
                </div>
                <h5><a href="#">{{Session::get('nickname')}}</a> </h5>
                <ul class="list-inline">
                    <li>
                        <a href="#">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-custom">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- End User -->

            <ul class="am-list admin-sidebar-list" >
                <li><a href="/user/index"><span class="am-icon-home"></span>首页</a></li>
                <li><a href="/user/pan"><span class="am-icon-home"></span>网盘</a></li>
                <li><a href="/user/addressBooks"><span class="am-icon-home"></span>通讯录</a></li>
                <li><a href="/user/infoList"><span class="am-icon-home"></span>收件箱</a></li>
                <li class="admin-parent">
                    <a class="am-cf am-collapsed " data-am-collapse="{target: '#collapse-nav0'}"><span class="am-icon-table"></span>生产计划<span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub " id="collapse-nav0">
                        <li><a href="/user/planList" class="am-cf"> 生产计划列表</a></li>
                        <li><a href="/user/addPlan">增加生产计划</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf am-collapsed " data-am-collapse="{target: '#collapse-nav1'}"><span class="am-icon-table"></span>个人日记 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub " id="collapse-nav1">
                        <li><a href="/user/noteList" class="am-cf"> 个人日记列表</a></li>
                        <li><a href="/user/addNote">写日记</a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-nav2'}"><span class="am-icon-table"></span>个人信息 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub " id="collapse-nav2">
                        <li><a href="/user/userInfo/{{Session::get('userId')}}" class="am-cf"> 个人信息</a></li>
                        <li><a href="/user/modifyUserInfo">修改个人信息</a></li>
                    </ul>
                </li>
                <li><a href="/user/logout"><span class="am-icon-home"></span>登出</a></li>
                {{--<li class="admin-parent">--}}
                {{--<a class="am-cf" data-am-collapse="{target: '#collapse-nav2'}"><i class="am-icon-line-chart" aria-hidden="true"></i> 统计图表 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>--}}
                {{--<ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav2">--}}
                {{--<li><a href="html/chart_line.html" class="am-cf"> 折线图</span></a></li>--}}
                {{--<li><a href="html/chart_columnar.html" class="am-cf"> 柱状图</span></a></li>--}}
                {{--<li><a href="html/chart_pie.html" class="am-cf"> 饼状图</span></a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="admin-parent">--}}
                {{--<a class="am-cf" data-am-collapse="{target: '#collapse-nav5'}"><span class="am-icon-file"></span> 表单 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>--}}
                {{--<ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav5">--}}
                {{--<li><a href="html/form_basic.html" class="am-cf"> 基本表单</a></li>--}}
                {{--<li><a href="html/form_validate.html">表单验证</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
            </ul>
        </div>
    </div>
    <!-- sidebar end -->
    <!-- ========== Left Sidebar end ========== -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <div class="content-page" style="background-color: #EAEEF2">
        <!-- Start content -->
        <div class="content">
            <!-- Row start -->
            <div class="am-g">
                <!-- col start -->
                <div class="am-u-md-12">
                    <div class="card-box">
                     @section('content')
                        @yield('contents')
                    </div>
                </div>
        </div>
    </div>
    <!-- end right Content here -->

</div>


<!-- navbar -->
<a href="admin-offcanvas" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu" data-am-offcanvas="{target: '#admin-offcanvas'}"><!--<i class="fa fa-bars" aria-hidden="true"></i>--></a>


<script type="text/javascript" src="/css/user/js/amazeui.min.js"></script>
<script type="text/javascript" src="/css/user/js/app.js" ></script>
<script type="text/javascript" src="/css/user/js/blockUI.js" ></script>
<script type="text/javascript" src="/css/user/js/charts/echarts.min.js" ></script>
<script type="text/javascript" src="/css/user/js/charts/indexChart.js" ></script>
<script type="text/javascript" src="/css/user/js/app1.js" ></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
</script>
</body>

</html>