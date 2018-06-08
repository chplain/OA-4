<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ipController extends Controller
{
    public function getIpList(){
        $ipList=DB::table('user_ip')
            ->select('user_ip.id','users.nickname','user_ip.userId','user_ip.ip','user_ip.updated_at','user_ip.status')
            ->leftJoin('users','user_ip.userId','=','users.id')
            ->get();
        return view('manager.ip',['ipList'=>$ipList]);
}

public function update(){
        if ($_REQUEST['action']=='forbid'){
            DB::table('user_ip')
                ->where('id','=',$_REQUEST['id'])
                ->update(['status'=>1]);
        }else{
            DB::table('user_ip')
                ->where('id','=',$_REQUEST['id'])
                ->update(['status'=>0]);
        }
        header('location:/manager/ip');
}
}
