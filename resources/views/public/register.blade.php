<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OA-Register</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="/css/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/public/css/register.css" rel="stylesheet">
</head>
<body>
<div class="flex-center position-ref full-height">
    @if(Session::has('userId'))
        <?php header('location:/user/index');?>
    @endif
    <div class="signIn">
        <div class="signIn-head"><img src="css/public/images/test/head_120.png" alt="" class="img-circle"></div>
        <div class="form-signIn" role="form">
            <input type="text" class="form-control" name='phone' placeholder="请输入手机号" required autofocus />
            <input type="text" class="form-control" name='email' placeholder="请输入邮箱" required  />
            <input type="text" class="form-control" name='nickname' placeholder="请输昵称" required  />
            <input type="text" class="form-control" name='sex' placeholder="性别男或女" required  />
            <input type="password" class="form-control" name="password" placeholder="密码" required />
            <input type="password" class="form-control" name="password1" placeholder="请重复密码" required />
            <button class="btn btn-lg btn-warning btn-block" id="submit" onclick="javascript:submit()">登录</button>
        </div>
    </div>
</div>
</body>
<script type="text/javascript" src="/css/user/js/jquery-2.1.0.js" ></script>
<script type="text/javascript">
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
    function submit(){
        var data={
            nickname:$('input[name="nickname"]').val(),
            password:$('input[name="password"]').val(),
            email:$('input[name="email"]').val(),
            sex:$('input[name="sex"]').val(),
            phone:$('input[name="phone"]').val(),
            avatar:"/user/test/head_120.png"
        };

        postData(data);
    }

    function postData(data) {
        $.ajax({
            url:'/api/user/register',
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
</html>
