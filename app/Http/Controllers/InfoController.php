<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class InfoController extends Controller
{
    public function geInfoList(){
        $infos=DB::table('info')
            ->select('info.id','info.content','info.created_at','users.nickname','users.avatar')
            ->leftJoin('users','info.creatorId','=','users.id')
            ->where('info.toUserId','=',session::get('userId'))
            ->orderBy('info.id', 'desc')
            ->limit(4)
            ->get();
        return view('user.info',['infos'=>$infos]);
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
