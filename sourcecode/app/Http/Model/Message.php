<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Message extends Model
{

    public function insertMessage($input,$department_id,$accepter){
        $code = 0;//默认失败
        if($accepter==2){
            $type = "teacher";
            $code = $this->insert($input,$department_id,$type);
            return $code;
        }elseif($accepter==1){
            $type = "student";
            $code = $this->insert($input,$department_id,$type);
            return $code;
        }elseif($accepter=3){
            $type="all";
            $code = $this->insert($input,$department_id,$type);
            return $code;
        }
    }
    public function insert($zdata){

        $time = date("Ymd",time());
        $code = DB::table('g_message')
            ->insert([
                'accepter'=>$zdata['all'],
                'department_id'=>$zdata['department'],
                'title'=>$zdata["title"],
                'content'=>$zdata["content"],
                'ifall'=>$zdata["ifall"],
                'time'=>$time,

            ]);
        if($code){
            $result['code']=1;
            $result['msg']='新增成功';

            return $result;
        }

        $result['code']=0;
        $result['msg']='新增失败';
        return $result;
    }
    public function selectContent(){
        $content = DB::table("g_message")
            ->select("content")
            ->get();
        return $content;
    }
    public function selectTeaContent(){
        $TeaContent = DB::table("g_message")
            ->select("content")
            ->where("accepter",2)
            ->get();
        return $TeaContent;
    }
    public function selectStuContent(){
        $StuContent = DB::table("g_message")
            ->select("content")
            ->where("accepter",1)
            ->get();
        return $StuContent;
    }
    public function selectDepartmentId($department_one){
        $result = DB::table('g_department')
            ->select("id")
            ->where("name",$department_one)
            ->get();
        return $result;
    }
    public function selectDepartmentName($id){
        $result = DB::table('g_department')
            ->select("name")
            ->where("pid",$id)
            ->get();

        return $result;
    }
    public function selectDepartment(){
        $result = DB::table('g_department')
            ->select("name")
            ->where("pid",0)
            ->get();

        return $result;
    }
}


