<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class UserController extends Controller
{
    public function index(){
        //获取用户列表
        $users=DB::table('users')
            ->select('nickname','id','avatar','phone','email','sex')
            ->where('id','<>',session::get('userId'))
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();
        //获取生产计划列表
        $plans=DB::table('plan')
            ->select('plan.id','plan.title','plan.created_at','plan.deadTime','plan.status')
            ->leftJoin('users','plan.creatorId','=','users.id')
            ->where('plan.executorId','=',session::get('userId'))
            ->orWhere('plan.creatorId','=',session::get('userId'))
            ->orderBy('plan.id', 'desc')
            ->limit(4)
            ->get();
            //获取消息  列表
        $infos=DB::table('info')
            ->select('info.id','info.content','info.created_at','users.nickname','users.avatar','info.url')
            ->leftJoin('users','info.creatorId','=','users.id')
            ->where('info.toUserId','=',session::get('userId'))
            ->orderBy('info.id', 'desc')
            ->limit(4)
            ->get();
        return view('user.index',['users'=>$users,'plans'=>$plans,'infos'=>$infos]);
//        return "123";
    }

    public function get_client_ip($type = 0,$client=true)
    {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($client){
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos    =   array_search('unknown',$arr);
                if(false !== $pos) unset($arr[$pos]);
                $ip     =   trim($arr[0]);
            }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip     =   $_SERVER['HTTP_CLIENT_IP'];
            }elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip     =   $_SERVER['REMOTE_ADDR'];
            }
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // 防止IP伪造
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
    /**
     * 用户登录
     * @return string
     */
    public function login()
    {
       $data=[];
       $ip=DB::table('user_ip')
           ->where([['ip','=',$this->get_client_ip()],['status','=','1']])
           ->count();
       if ($ip>0){
           $data=array("status"=>900,"info"=>"你已经被禁止访问");
       }else {
           if (isset($_REQUEST) && !empty($_REQUEST)) {
               $user = DB::table("users")
                   ->where([['email', $_REQUEST['account']], ['password', $_REQUEST['password']]])
                   ->orWhere([['phone', $_REQUEST['account']], ['password', $_REQUEST['password']]])
                   ->count();
               if ($user > 0) {
                   $userInfo = DB::table("users")
                       ->select('users.id', 'users.nickname', 'users.avatar', 'users.sex', 'user_user_group_role.userGroupId')
                       ->leftJoin('user_user_group_role', 'user_user_group_role.userId', '=', 'users.id')
                       ->where([['email', $_REQUEST['account']], ['password', $_REQUEST['password']]])->orWhere([['phone', $_REQUEST['account']], ['password', $_REQUEST['password']]])
                       ->get()->toArray();
                   //将用户的信息写入session中
                   session::put('userId', $userInfo[0]->id);
                   session::put('nickname', $userInfo[0]->nickname);
                   session::put('avatar', $userInfo[0]->avatar);
                   session::put('sex', $userInfo[0]->sex);
                   session::put('userGroupId', $userInfo[0]->userGroupId);
                   $userIp = DB::table("user_ip")
                       ->where('userId','=', $userInfo[0]->id)
                       ->count();
                   if ($userIp>0){
                       DB::table('user_ip')
                           ->where('userId','=',$userInfo[0]->id)
                           ->update(['ip'=>$this->get_client_ip(), 'updated_at'=>date("Y-m-d H:i:s")])
                           ;
                   }else{
                    DB::table('user_ip')
                       ->insertGetId(
                           [
                               'ip'=>$this->get_client_ip(),
                               'status'=>0,
                               'created_at'=>date("Y-m-d H:i:s"),
                               'updated_at'=>date("Y-m-d H:i:s"),
                               'userId'=>$userInfo[0]->id
                           ]
                       );
                   }
                   $data = array("status" => 200, "info" => ['id' => $userInfo[0]->id, 'nickname' => $userInfo[0]->nickname]);
               } else {
                   $data = array("status" => 900, "info" => "账号密码错误");
               }
           } else {
               $data = array("status" => 901, "info" => "HTTP REQUEST ERROR");
           }
       }
        return json_encode($data);
    }

    /**
     * 登出
     */
    public function logout()
    {
        if (!empty(session::get('userId'))){
            //删除用户session
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
            $id=DB::table('users')
                ->insertGetId(
                    [
                     'nickname'=>$_REQUEST['nickname'],
                     'phone'=>$_REQUEST['phone'],
                     'sex'=>$_REQUEST['sex'],
                     'password'=>$_REQUEST['password'],
                     'email'=>$_REQUEST['email'],
                     'avatar'=>$_REQUEST['avatar'],
                     'created_at'=>date("Y-m-d H:i:s"),
                     'updated_at'=>date("Y-m-d H:i:s"),
                     'birth'=>date("Y-m-d H:i:s")
                    ]
                );
            if($id){
                $ids=DB::table('user_user_group_role')
                    ->insertGetId(
                        [
                            'userId'=>$id,
                            'userGroupId'=>7,
                            'role'=>4
                        ]
                    );
                if ($ids){
                $data=array("status"=>200,'info'=>'创建成功');
                }
            }else{
                $data=array("status"=>900,"info"=>"手机号或邮箱已经被占用");
            }
        }else{
            $data=array("status"=>901,"info"=>"HTTP REQUEST ERROR");
        }
        return json_encode($data);
    }
    /**
     * 更新用户的个人信息
     */
    public function update()
    {
        $data=[];
        if (isset($_REQUEST)&&!empty($_REQUEST)) {
            $id=DB::table('users')
                ->where('id',session::get('userId'))
                ->update(
                    [
                     'nickname'=>$_REQUEST['nickname'],
                     'phone'=>$_REQUEST['phone'],
//                     'sex'=>$_REQUEST['sex'],
                     'password'=>$_REQUEST['password'],
                     'email'=>$_REQUEST['email'],
//                     'avatar'=>$_REQUEST['avatar'],
                     'updated_at'=>date("Y-m-d H:i:s")
                    ]);
            if($id){
                $data=array("status"=>200,'info'=>'更新成功');
                session::put('nickname',$_REQUEST['nickname']);
            }else{
                $data=array("status"=>900,"info"=>"更新的信息有误，手机号后邮箱已经被占用或未有信息的变化");
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
            $user=DB::table('user_user_group_role')
//                ->leftJoin('user_role','users.id','=','user_role.userId')
//                ->leftJoin('rolex','user_role.roleId','=','role.id')
                ->Join('users','user_user_group_role.userId','=','users.id')
                ->Join('user_group','user_user_group_role.userGroupId','=','user_group.id')
                ->Join('role','user_user_group_role.roleId','=','role.id')
                ->select('users.nickname','users.id','users.sex','users.birth','users.phone','users.avatar','users.email','users.created_at','role.role','user_group.userGroup')
                ->where('user_user_group_role.userId', $id)
                ->get();
            if(!empty($user)){
                $data=array("status"=>200,'info'=>'获取成功');
                return  view('user.userInfo',['users'=>$user]);
            }else{
                $data=array("status"=>900,"info"=>"获取信息失败");
            }
        }else{
            $data=array("status"=>901,"info"=>"HTTP REQUEST ERROR");
        }
       return  view('user.userInfo',['user'=>$user]);
    }

    public function  modifyUserInfo($id){
        if(session::get('userId')==$id){
        $user=DB::table('users')
            ->select('users.nickname','users.sex','users.birth','users.phone','users.email','users.password')
            ->where('users.id', $id)
            ->get();
        return view('user.modifyUserInfo',['users'=>$user]);
        }
    }

    /*
     *后台的人员管理信息
     * */
    public function member(){
        $users=DB::table('users')
            ->select('users.nickname','users.id','users.avatar','user_group.userGroup','role.role')
            ->leftJoin('user_user_group_role','user_user_group_role.userId','=','users.id')
            ->leftJoin('role','role.id','=','user_user_group_role.roleId')
            ->leftJoin('user_group','user_group.id','=','user_user_group_role.userGroupId')
            ->where('users.id','<>',session::get('userId'))
            ->get();
        return view('manager.member',['users'=>$users]);
    }
}
