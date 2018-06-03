<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class planController extends Controller
{
    public function getPlanList(){
        $planList=DB::table('plan')
            ->select('plan.id','plan.title','plan.created_at','plan.deadTime','plan.status','users.nickname as executor','b.nickname as creator')
            ->leftJoin('users','plan.creatorId','=','users.id')
            ->leftJoin('users as b','plan.executorId','=','b.id')
            ->where('plan.executorId','=',session::get('userId'))
            ->orWhere('plan.creatorId','=',session::get('userId'))
            ->get();
        return view('user.plan',['planList'=>$planList]);
    }
}
