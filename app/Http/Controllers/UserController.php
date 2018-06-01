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

    /**
     * 注册
     */
    public function register()
    {
        $data=[];
        if (isset($_REQUEST)&&!empty($_REQUEST)) {
            $id=DB::table('users')->insertGetId(['nickname'=>$_REQUEST['nickname'],'phone'=>$_REQUEST['phone'],'sex'=>$_REQUEST['sex'],'password'=>$_REQUEST['password'],'email'=>$_REQUEST['email'],'avatar'=>$_REQUEST['avatar'],'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]);
            if($id){
                $data=array("status"=>200,'info'=>'创建成功');
            }else{
                $data=array("status"=>900,"info"=>"手机号后邮箱已经被占用");
            }
        }else{
            $data=array("status"=>901,"info"=>"HTTP REQUEST ERROR");
        }
        return json_encode($data);
    }
    /**
     * 更新用户的个人信息
     */
    public function updateUserInfo()
    {
        $data=[];
        if (isset($_REQUEST)&&!empty($_REQUEST)) {
            $id=DB::table('users')->where('id',
                1)->update(['nickname'=>$_REQUEST['nickname'],'phone'=>$_REQUEST['phone'],'sex'=>$_REQUEST['sex'],'password'=>$_REQUEST['password'],'email'=>$_REQUEST['email'],'avatar'=>$_REQUEST['avatar'],'update_at'=>date("Y-m-d H:i:s")]);
            if($id){
                $data=array("status"=>200,'info'=>'更新成功');
            }else{
                $data=array("status"=>900,"info"=>"更新的信息有误，手机号后邮箱已经被占用");
            }
        }else{
            $data=array("status"=>901,"info"=>"HTTP REQUEST ERROR");
        }
        return json_encode($data);
    }
    
    /**
     * 删除用户
     */
    public function deleteUser()
    {
        
    }
}
