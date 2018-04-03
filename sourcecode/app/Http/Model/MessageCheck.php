<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class MessageCheck extends Model
{

    public function selectMessage($type,$departid){
        $message = DB::table("g_message")
            ->select("g_message.id","g_message.title","g_message.time")
            ->orderBy('g_message.id', 'desc');

        if($type==1){

            $message=$message
                ->where("g_message.department_id",$departid)
                ->where('g_message.accepter', '<>', 2)
                ->orWhere(function($query)
                {
                    $query->where('g_message.department_id',0)
                        ->where('g_message.accepter','<>',2);
                });


          }else  if($type==2) {

            $message = $message->where('g_message.accepter', '<>',1);

           }

            $message = $message->get();

        return $message;
    }

    //查询教师的信息
    public function searchTeachernews($id)
    {
        $builder = DB::table('g_user')
            ->where('g_user.id',$id)
            ->select('department_id')
            ->get();

        return $builder;
    }
    


    public function selectContent($id){
        $content = DB::table("g_message")
            ->select("content","title")
            ->where("id",$id)
            ->get();
        return $content;
    }


    public function searchDepartCk($id){
        $content = DB::table("g_message")
            ->select("content","title")
            ->where("id",$id)
            ->get();
        return $content;
    }


        //查询学生入科的信息
    public function searchruketime($id){

        $tstime = time() ;

        $content = DB::table("g_sdcycle")
            ->join('g_department','g_department.id','=','g_sdcycle.department_id')
            ->join('g_user','g_user.id','=','g_sdcycle.stuid')
            ->where('g_sdcycle.stuid',$id)
            ->where("g_sdcycle.plan_starttime",'<',$tstime)
            ->select("g_user.name as stuname","g_department.name as departname")
            ->get();
        return $content;
    }


    //查询学生入科的信息
    public function searchchuketime($id){

        $content = DB::table("g_stu_summary")
            ->join('g_department','g_department.id','=','g_stu_summary.department_id')
            ->join('g_user','g_user.id','=','g_stu_summary.stuid')
            ->where('g_stu_summary.stuid',$id)
            ->where("g_stu_summary.status",1)
            ->select("g_user.name as stuname","g_department.name as departname")
            ->get();
        return $content;
    }

    //查询学生考试的信息
    public function searchexaminfo($id){

        $content = DB::table("g_sdcycle")
            ->join('g_department','g_department.id','=','g_sdcycle.department_id')
            ->join('g_user','g_user.id','=','g_sdcycle.stuid')
            ->join('g_test_log','g_test_log.department_id','=','g_sdcycle.department_id')
            ->join('g_classroom','g_classroom.id','=','g_test_log.classroom')
            ->where('g_sdcycle.stuid',$id)
            ->where("g_sdcycle.plan_starttime",'<',time())
            ->where("g_sdcycle.endtime",0)
            ->where("g_test_log.end",'<',time()+172800)
            ->groupBy('g_sdcycle.plan_starttime')
            ->select("g_user.name as stuname","g_department.name as departname",'g_test_log.start','g_test_log.end','g_test_log.type as examtype','g_test_log.id','g_classroom.name as classroom')
            ->get();


        return $content;
    }


    //查询学生考试的信息
    public function searchteacherexaminfo($id){


        $departs= DB::select("SELECT id FROM g_department WHERE teacher_ids LIKE '%$id,%' OR teacher_ids LIKE '%$id' OR teacher_id = '$id'");

        $result= [];
        for($i=0;$i<count($departs);$i++){
            $content = DB::table("g_kcycle")
                ->join('g_department','g_department.id','=','g_kcycle.department_id')
                ->where('g_kcycle.department_id',$departs[$i]->id)
                ->where("g_kcycle.plan_starttime",'<',time()+172800)
                ->where("g_kcycle.plan_starttime",'>',time())
                ->select("g_department.name as departname","g_kcycle.plan_starttime as time")
                ->get();
//1467252600

            if($content){
                $content[0]->newstype=4;

                $result[]=$content;
            }



            $content = DB::table("g_kcycle")
                ->join('g_department','g_department.id','=','g_kcycle.department_id')
                ->where('g_kcycle.department_id',$departs[$i]->id)
                ->where("g_kcycle.plan_endtime",'<',time()-172800)
                ->where("g_kcycle.plan_endtime",'<',time())
                ->select("g_department.name as departname","g_kcycle.plan_endtime as time")
                ->get();
            if($content){
                $content[0]->newstype=5;

                $result[]=$content;
            }



        }


        return $result;
    }


}


