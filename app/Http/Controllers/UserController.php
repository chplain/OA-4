<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class UserController extends Controller
{
    public function index(){
        $users=DB::table('users')
            ->select('nickname','id','avatar','phone','email','sex')
            ->where('id','<>',session::get('userId'))
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();
        $plans=DB::table('plan')
            ->select('plan.id','plan.title','plan.created_at','plan.deadTime','plan.status','users.nickname as executor')
            ->leftJoin('users','plan.creatorId','=','users.id')
            ->where('plan.executorId','=',session::get('userId'))
            ->orWhere('plan.creatorId','=',session::get('userId'))
            ->orderBy('plan.id', 'desc')
            ->limit(4)
            ->get();
        return view('user.index',['users'=>$users,'plans'=>$plans]);
    }

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
                $userInfo = DB::table("users")
                    ->select('users.id','users.nickname','users.avatar','users.sex','user_user_group.userGroupId')
                    ->leftJoin('user_user_group','user_user_group.userId','=','users.id')
                    ->where([['email', $_REQUEST['account']], ['password', $_REQUEST['password']]])->orWhere([['phone', $_REQUEST['account']], ['password', $_REQUEST['password']]])
                    ->get()->toArray();
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
                $_REQUEST['id'])->update(['nickname'=>$_REQUEST['nickname'],'phone'=>$_REQUEST['phone'],'sex'=>$_REQUEST['sex'],'password'=>$_REQUEST['password'],'email'=>$_REQUEST['email'],'avatar'=>$_REQUEST['avatar'],'update_at'=>date("Y-m-d H:i:s")]);
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
    /**
     * get userInfo
     */
    public function userInfo($id){
        $user='';
        if (isset($id)&&$id!=='') {
            $user=DB::table('users')
                ->leftJoin('user_role','users.id','=','user_role.userId')
                ->leftJoin('role','user_role.roleId','=','role.id')
                ->leftJoin('user_user_group','users.id','=','user_user_group.userId')
                ->leftJoin('user_group','user_user_group.userGroupId','=','user_group.id')
                ->select('users.nickname','users.sex','users.birth','users.phone','users.avatar','users.email','users.created_at','role.role','user_group.userGroup')
                ->where('users.id', $id)
                ->get();
            if(!empty($user)){
                $data=array("status"=>200,'info'=>'更新成功');
                return  view('user.userInfo',['users'=>$user]);
            }else{
                $data=array("status"=>900,"info"=>"更新的信息有误，手机号后邮箱已经被占用");
            }
        }else{
            $data=array("status"=>901,"info"=>"HTTP REQUEST ERROR");
        }
//        return  view('user.userInfo',['user'=>$user]);
    }
}
