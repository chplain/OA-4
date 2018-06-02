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
//        var_dump(session::get('userId'));
        $users=DB::table('users')->select('nickname','id','avatar','phone','email','sex')->where('id','<>',session::get('userId'))->get();
//        var_dump($users);
       return  view('user.addressBooks',['users'=>$users]);
    }
}
