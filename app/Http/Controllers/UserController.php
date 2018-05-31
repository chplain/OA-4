<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class UserController extends Controller
{
    /**
     * 用户登录
     * @return string
     */
    public function login()
    {
        $data=[];
        if (isset($_REQUEST)&&!empty($_REQUEST)) {
            $user = DB::table("users")->where([['email', $_REQUEST['account']], ['password', $_REQUEST['password']]])->orWhere([['phone', $_REQUEST['account']], ['password', $_REQUEST['password']]])->count();
            if ($user>0){
                $userInfo = DB::table("users")->select('id','nickname','avatar','sex')->where([['email', $_REQUEST['account']], ['password', $_REQUEST['password']]])->orWhere([['phone', $_REQUEST['account']], ['password', $_REQUEST['password']]])->get()->toArray();
                 session::put('userId',$userInfo[0]->id);
                 session::put('nickname',$userInfo[0]->nickname);
                 session::put('avatar',$userInfo[0]->avatar);
                session::put('sex',$userInfo[0]->sex);
                $data=array("status"=>200,"info"=>['id'=>$userInfo[0]->id,'nickname'=>$userInfo[0]->nickname]);
            }else{
                $data=array("status"=>900,"info"=>"账号密码错误");
            }
        }else{
            $data=array("status"=>901,"info"=>"HTTP REQUEST ERROR");
        }
        return json_encode($data);
    }

    /**
     * 登出
     */
    public function logout()
    {
        if (!empty(session::get('userId'))){
            session::pull('userId');
        }
        session::pull('userId');
        header('location:/');
    }
}
