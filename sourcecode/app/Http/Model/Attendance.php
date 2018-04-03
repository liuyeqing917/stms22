<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Attendance extends Model
{
    protected $table='g_check';
    protected $primaryKey='id';
    public $timestamps=false;

    //查询所有学生的名字
    public function searchAllStudentName()
    {
        $builder = DB::table('g_user')
            ->where('g_user.type',1)
            ->where('g_user.finish',0)
            ->select('id','name')
            ->get();


        return $builder;
    }

    //新增考勤信息
    public function addAttendence($data)
    {
        $builder = DB::table('g_check')->insertGetId([
            'stuid' =>  $data['stuid'],
            'department_id' =>  $data['department_id'],
            'attendance' =>  $data['attendance'],
            'late' =>  $data['late'],
            'early' =>  $data['early'],
            'leave' =>  $data['leave'],
            'teacher' =>  $data['teacherid'],
        ]);

        if($builder){
            $result['code']=1;
            $result['msg']='新增成功';
            return $result;

        }
        $result['code']=0;
        $result['msg']='新增失败';
        return $result;

    }

    //修改考勤信息
    public function modifyAttendence($data)
    {
        $builder = DB::table('g_check')
            ->where('id',$data['id'])
            ->insertGetId([
            'attendance' =>  $data['attendance'],
            'late' =>  $data['late'],
            'early' =>  $data['early'],
            'leave' =>  $data['leave'],
            'teacher' =>  $data['teacherid'],
        ]);

        if($builder){
            $result['code']=1;
            $result['msg']='修改成功';
            return $result;

        }
        $result['code']=0;
        $result['msg']='修改成功';
        return $result;

    }


    //查询一个学生的考勤信息
    public function searchStudentAttendence($stuid)
    {
        $builder = DB::table('g_check')
            ->join('g_user','g_user.id','=','g_check.teacher')
            ->where('g_check.stuid',$stuid)
            ->select('g_check.*','g_user.name as teacher')
            ->get();


        return $builder;
    }

    //查询一个学生的考勤信息
    public function searchDepartmentAttendence($stuid,$depid)
    {
        $builder = DB::table('g_check')
            ->join('g_user','g_user.id','=','g_check.teacher')
            ->where('g_check.department_id',$depid)
            ->where('g_check.stuid',$stuid)
            ->select('g_check.*','g_user.name')
            ->get();


        return $builder;
    }

}


