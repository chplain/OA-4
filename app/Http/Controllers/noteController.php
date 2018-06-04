<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use EndaEditor;

class noteController extends Controller
{
    public function getNoteList(){
        $noteList=DB::table('user_note')
            ->select('user_note.id','user_note.title','user_note.created_at','users.avatar','users.nickname')
            ->leftJoin('users','user_note.userId','=','users.id')
            ->where('userId','=',session::get('userId'))
            ->get();
        return view('user.noteList',['noteList'=>$noteList]);
    }

    public function addNote(){
        $id=DB::table('user_note')
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
        $count=DB::table('user_note')
            ->where([['id','=',$id],['userId','=',session::get('userId')]])
            ->count();
//        var_dump($count);
        if ($count>0) {
            $note = DB::table('user_note')
                ->select('user_note.title', 'user_note.content', 'user_note.created_at', 'users.nickname')
                ->leftJoin('users', 'user_note.userId', '=', 'users.id')
                ->where('user_note.id', '=', $id)
                ->get();
            $note[0]->content=EndaEditor::MarkDecode($note[0]->content);
            return view('user.note', ['note'=>$note]);
        }else{
            return "没有权限";
        }
    }
}
