<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OA-login</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="/css/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/public/css/signIn.css" rel="stylesheet">
    <!-- Styles -->
    <link href="/css/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/public/css/signIn.css" rel="stylesheet">
</head>
<body>
@if(Session::has('userId'))
   <?php header('location:/user/index');?>
@endif
<div class="flex-center position-ref full-height">
    {{--@if (Route::has('login'))--}}
        {{--<div class="top-right links">--}}
            {{--@auth--}}
                {{--<a href="{{ url('/home') }}">Home</a>--}}
            {{--@else--}}
                {{--<a href="{{ route('login') }}">Login</a>--}}
                {{--<a href="{{ route('register') }}">Register</a>--}}
            {{--@endauth--}}
        {{--</div>--}}
    {{--@endif--}}
    <div class="signIn">
        <div class="signIn-head">
            <img src="/user/test/head_120.png" alt="" class="img-circle">
        </div>
        <div class="form-signIn" role="form">
            <input type="text" name="account" class="form-control" placeholder="请输入手机号或邮箱" required autofocus />
            <input type="password" name="password" class="form-control" placeholder="密码" required />
            <button class="btn btn-lg btn-warning btn-block" id="submit" onclick="javascript:submit()"  >登录</button>
            <label class="checkbox">
                    <input type="checkbox" value="remember-me"> 记住我
            </label>
        </div>
        <div class="signIn-bottom">
            <div class="register">
                <a href="/register">注册>></a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/css/user/js/jquery-2.1.0.js" ></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    function submit(){
       var data={account:$('input[name="account"]').val(), password:$('input[name="password"]').val()};
        postData(data);
    }

    function postData(data) {
        $.ajax({
            url:'/api/user/login',
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
</body>
</html>
