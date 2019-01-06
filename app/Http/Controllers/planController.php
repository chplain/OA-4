<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use EndaEditor;

class planController extends Controller
{
    /**
     * 获取计划列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function getPlanList(){
        $planList=DB::table('plan')
            ->select('plan.id','plan.title','plan.created_at','plan.executorId','plan.creatorId','plan.deadTime','plan.status','users.nickname as creator','b.nickname as  executor')
            ->leftJoin('users','plan.creatorId','=','users.id')
            ->leftJoin('users as b','plan.executorId','=','b.id')
            ->where('plan.executorId','=',session::get('userId'))
            ->orWhere('plan.creatorId','=',session::get('userId'))
            ->get();
        return view('user.planList',['planList'=>$planList]);
    }

    public function updatePlan(){
        $status=1;
        $data=[];
        if (isset($_REQUEST)&&!empty($_REQUEST)){
            if ($_REQUEST['action']=='submit'){
                $status=2;
            }
        }
        $count=DB::table('plan')
            ->where('id', $_REQUEST['planId'])
            ->update(['status' => $status]);
//        var_dump($count);
        if ($count){
            if($status==1){
                $infoid=DB::table('info')
                    ->insertGetId(
                        [
                            'creatorId'=>session::get('userId'),
                            'content'=>'你发布的任务'.$_REQUEST['title'].'已经开始了',
                            'toUserId'=>$_REQUEST['creatorId'],
                            'created_at'=>date("Y-m-d H:i:s"),
                            'updated_at'=>date("Y-m-d H:i:s"),
                            'status'=>0,
                            'url'=>'/user/plan/'.$_REQUEST['planId']
                        ]
                    );
                $data=['status'=>200,'info'=>$_REQUEST['planId']];
            }else{
                $infoid=DB::table('info')
                    ->insertGetId(
                        [
                            'creatorId'=>session::get('userId'),
                            'content'=>'你发布的任务'.$_REQUEST['title'].'已经完成',
                            'toUserId'=>$_REQUEST['creatorId'],
                            'created_at'=>date("Y-m-d H:i:s"),
                            'updated_at'=>date("Y-m-d H:i:s"),
                            'status'=>0,
                            'url'=>'/user/plan/'.$_REQUEST['planId']
                        ]
                    );
            }

            $data=['status'=>200,'info'=>$_REQUEST['planId']];
        }else{
            $data=['status'=>900,'info'=>'添加失败'];
        }
        return json_encode($data);

    }

    /**
     * 增加生产计划
     */
    public function addPlan(){
            $data=[];
            $userGroupId=DB::table('user_user_group_role')
                ->select('user_user_group_role.userGroupId')
                ->where('user_user_group_role.userId','=',$_REQUEST['executorId'])
                ->get();
            $id=DB::table('plan')
                ->insertGetId(
                    [
                        'title'=>$_REQUEST['title'],
                        'content'=>$_REQUEST['content'],
                        'userGroupId'=>$userGroupId[0]->userGroupId,
                        'created_at'=>$_REQUEST['created_at'],
                        'updated_at'=>date("Y-m-d H:i:s"),
                        'deadTime'=>$_REQUEST['deadTime'],
                        'executorId'=>$_REQUEST['executorId'],
                        'creatorId'=>$_REQUEST['creatorId'],
                        'status'=>0
                    ]
                );

            if (isset($id)){
                 $infoid=DB::table('info')
                     ->insertGetId(
                         [
                             'creatorId'=>$_REQUEST['creatorId'],
                             'content'=>'你有新的任务'.$_REQUEST['title'].'需要确认',
                             'toUserId'=>$_REQUEST['executorId'],
                             'created_at'=>date("Y-m-d H:i:s"),
                             'updated_at'=>date("Y-m-d H:i:s"),
                             'status'=>0,
                             'url'=>'/user/plan/'.$id
                         ]
                     );

                 $data=['status'=>200,'info'=>$id];
            }else{
               $data=['status'=>900,'info'=>'添加失败'];
           }
            return json_encode($data);
    }

    public function getPlan($id){
          $plan;
           if (session::get('userGroupId')<2){
               $plan=DB::table('plan')
               ->select('plan.id','plan.title','plan.content','plan.creatorId','plan.executorId','plan.created_at','plan.deadTime','plan.status','users.nickname as creator','b.nickname as  executor')
               ->leftJoin('users','plan.creatorId','=','users.id')
               ->leftJoin('users as b','plan.executorId','=','b.id')
               ->where('plan.id','=',$id)
               ->get();
           }else{
               $plan=DB::table('plan')
                   ->select('plan.id','plan.title','plan.content','plan.creatorId','plan.executorId','plan.created_at','plan.deadTime','plan.status','users.nickname as creator','b.nickname as  executor')
                   ->leftJoin('users','plan.creatorId','=','users.id')
                   ->leftJoin('users as b','plan.executorId','=','b.id')
                   ->where([['plan.id','=',$id],['plan.userGroupId','=',session::get('userGroupId')]])
                   ->get();
           }
//        if ($plan[0]->executorId==session::get('userId')){
               if(isset($_REQUEST['infoId'])&&!empty($_REQUEST['infoId'])) {
                   DB::table('info')
                       ->where('id', $_REQUEST['infoId'])
                       ->update(['status' => 1]);
               }
//        }
        $plan[0]->content=EndaEditor::MarkDecode($plan[0]->content);
        return view('user.plan',['plan'=>$plan]);
    }

    public function initialAddPlan(){
        $getRoleId=DB::table('user_user_group_role')
            ->select('roleId')
            ->where('userId','=',session::get('userId'))
            ->get();
        $getGroupMember;

        if (session::get('userGroupId')==0){
            $getGroupMember=DB::select('select users.nickname,users.id,user_group.userGroup,role.role from user_user_group_role left join users on  user_user_group_role.userId=users.id left join user_group on  user_user_group_role.userGroupId=user_group.id left join role on user_user_group_role.roleId=role.id where user_user_group_role.roleId <>'.$getRoleId[0]->roleId);
        }elseif(session::get('userGroupId')==1){
            $getGroupMember=DB::select('select users.nickname,users.id,user_group.userGroup,role.role from user_user_group_role left join users on  user_user_group_role.userId=users.id left join user_group on  user_user_group_role.userGroupId=user_group.id left join role on user_user_group_role.roleId=role.id where user_user_group_role.userGroupId >
'.session::get('userGroupId').' and user_user_group_role.roleId <>'.$getRoleId[0]->roleId);
        }else{
            $getGroupMember=DB::select('select users.nickname,users.id,user_group.userGroup,role.role from user_user_group_role left join users on  user_user_group_role.userId=users.id left join user_group on  user_user_group_role.userGroupId=user_group.id left join role on user_user_group_role.roleId=role.id where user_user_group_role.userGroupId IN (select userGroupId from user_user_group_role where userId='.session::get('userId').') and user_user_group_role.roleId <>'.$getRoleId[0]->roleId);

        }
        return view('user.addPlan',['getMember'=>$getGroupMember]);
    }
}
