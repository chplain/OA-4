<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function geInfoList(){
        $planList=DB::table('Info')
            ->select('id','title','content','created_at')
            ->where('touUserId','=',session::get('userId'))
            ->get();
        return view('user.note',['planList'=>$planList]);
    }

    public function addInfo(){
        $id=DB::table('note')
            ->insertGetId(
                [
                    'title'=>$_REQUEST['title'],
                    'content'=>$_REQUEST['content'],
                    'created_at'=>date("Y-m-d H:i:s"),
                    'update_at'=>date("Y-m-d H:i:s"),
                    'executorId'=>session::get('userId')
                ]
            );
        if (isset($id)){
            return view();
        }
    }

    public function getNote($id){
        $plan=DB::table('plan')
            ->select('plan.id','plan.title','plan.content','plan.created_at','plan.deadTime','plan.status','users.nickname as executor','b.nickname as creator')
            ->leftJoin('users','plan.creatorId','=','users.id')
            ->leftJoin('users as b','plan.executorId','=','b.id')
            ->where('plan.id','=',$id)
            ->get();
        return view('user.plan',['plan'=>$plan]);
    }

}
