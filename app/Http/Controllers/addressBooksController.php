<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class addressBooksController extends Controller
{
    //
    public function getList()
    {
        $users=DB::table('users')->select('users.nickname','users.id','users.avatar','users.phone','users.email','users.sex','user_group.userGroup','role.role')
            ->leftJoin('user_user_group_role','user_user_group_role.userId','=','users.id')
            ->leftJoin('role','role.id','=','user_user_group_role.roleId')
            ->leftJoin('user_group','user_group.id','=','user_user_group_role.userGroupId')
            ->where('users.id','<>',session::get('userId'))
            ->get();
//        var_dump($users);
        return  view('user.addressBooks',['users'=>$users]);
    }
}
