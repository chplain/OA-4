<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OA-Register</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="/css/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/public/css/register.css" rel="stylesheet">
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    @endif

    <div class="signIn">
        <div class="signIn-head"><img src="css/public/images/test/head_120.png" alt="" class="img-circle"></div>
        <form class="form-signIn" role="form">
            <input type="text" class="form-control" placeholder="请输入手机号" required autofocus />
            <input type="text" class="form-control" placeholder="请输入邮箱" required  />
            <input type="text" class="form-control" placeholder="请输昵称" required  />
            <input type="password" class="form-control" placeholder="密码" required />
            <input type="password" class="form-control" placeholder="请重复密码" required />
            <input type="text" class="form-control" placeholder="请输入手机号或邮箱" required  />
            <button class="btn btn-lg btn-warning btn-block" type="submit">注册</button>
            {{--<label class="checkbox">--}}
                {{--<input type="checkbox" value="remember-me"> 记住我--}}
            {{--</label>--}}
        </form>
    </div>
</div>
</body>
</html>
