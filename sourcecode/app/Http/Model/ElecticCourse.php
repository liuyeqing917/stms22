<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ElecticCourse extends Model
{
    protected $table='g_elective_course';
    protected $primaryKey='id';
    public $timestamps=false;


    //查询所有课程的列表
    public function searchCourserList($fatherdepartid,$childdepart)
    {
        $builder = DB::table('g_elective_course')
                    ->join('g_department', 'g_department.id', '=', 'g_elective_course.department_id')
                    ->select('g_elective_course.*','g_department.name as departname')
                    ->orderBy('g_elective_course.id', 'desc');

        if($fatherdepartid!=0){

               $builder=$builder->where('g_department.pid',$fatherdepartid);

            if($childdepart!=0){

                $builder=$builder->where('g_elective_course.department_id',$childdepart);

            }

        }else{

            if($childdepart!=0){

                $builder=$builder->where('g_elective_course.department_id',$childdepart);

            }
        }


       $result= $builder->orderBy('g_elective_course.id', 'desc')->get();

        return $result;
    }





    //查询所有课程的列表
    public function searchOneCourserData($id)
    {
        $builder = DB::table('g_elective_course')
            ->select('*')
            ->where('id',$id)
            ->orderBy('g_elective_course.id', 'desc')
            ->get();

        return $builder;
    }

    //新建课程
    public function addCourser($data)
    {

        $builder = DB::table('g_elective_course')
                    ->insertGetId([
                    'ctime' =>  $data['ctime'],
                    'cname' =>  $data['cname'],
                    'department_id' =>  $data['department_id'],
                    'venue' =>  $data['venue'],
                    'tid' =>  $data['tid'],

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

    //修改课程
    public function updateCourser($data)
    {

        $builder = DB::table('g_elective_course')
            ->where("g_elective_course.id",$data['id'])
            ->update([
                'ctime' =>  $data['ctime'],
                'cname' =>  $data['cname'],
                'department_id' =>  $data['department_id'],
                'venue' =>  $data['venue'],
                'tid' =>  $data['tid'],

            ]);

        if($builder){

            $result['code']=1;
            $result['msg']='修改成功';
            return $result;

        }

        $result['code']=0;
        $result['msg']='修改失败';
        return $result;
    }

    //删除课程
    public function deleteCouser($id)
    {
        $first = DB::table('g_elective_course')
            ->where('id',$id)
            ->delete();

        if($first){

            $result['code']=1;
            $result['msg']='删除成功';
            return $result;
        }

        $result['code']=0;
        $result['msg']='删除失败';

        return $result;


    }

    //查询活动的列表
    public function searchActivityList($eid)
    {
        $builder = DB::table('g_activity')
            ->select('*')
            ->orderBy('g_activity.id', 'desc')
            ->where('g_activity.eid', $eid)->get();

        return $builder;
    }

    //查询一个课程的信息
    public function searchOneCourse($id)
    {

        $builder = DB::table('g_elective_course')
            ->where('g_elective_course.id', $id)
            ->join('g_department','g_department.id','=','g_elective_course.department_id')
            ->join('g_user','g_user.id','=','g_elective_course.tid')
            ->select('g_elective_course.*','g_user.name as teachername','g_department.pid')
            ->get();

        return $builder;

    }

    //查询一个活动的信息
    public function searchActivity($id)
    {
        $builder = DB::table('g_activity')
            ->where('g_activity.id', $id)->get();

        return $builder;
    }

    //新建活动
    public function addActivity($data)
    {
      $activity = $this->searchStudentActivity($data['stuid'],$data['eid']);

        if($activity){
            $result['code']=2;
            $result['msg']='你已经提交提交';
            return $result;
        }

        $builder = DB::table('g_activity')
            ->insertGetId([
                'eid' =>  $data['eid'],
                'stuid' =>  $data['stuid'],
                'content' =>  $data['content'],
                'status' =>  0,
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

    //修改活动
    public function updateActivity($data)
    {
        $builder = DB::table('g_activity')
            ->where("g_activity.id",$data['id'])
            ->update([
                'eid' =>  $data['eid'],
                'stuid' =>  $data['stuid'],
                'content' =>  $data['content'],
                'status' =>  $data['status'],
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

    //删除活动
    public function deleteActivity($id)
    {
       $zz= DB::table('g_activity')
            ->where('eid', $id)
            ->delete();

        if($zz){
            $result['code']=1;
            $result['msg']='删除成功';
            return $result;

        }
        $result['code']=0;
        $result['msg']='删除成功';
        return $result;

    }


    //修改课程
    public function Confirmtofill ($data)
    {

       $course = $this->seaechCousrseNews($data);

        if(count($course)>0){

            $result['code']=2;
            $result['msg']='已经有老师在你之前确认了，请刷新！';
            return $result;
        }

        $builder = DB::table('g_activity')
            ->where("g_activity.id",$data['id'])
            ->update([

                'status' =>  1,
                'comment' =>  $data['comment'],

            ]);

        if($builder){

            $result['code']=1;
            $result['msg']='确认成功';
            return $result;

        }

        $result['code']=0;
        $result['msg']='确认失败';
        return $result;
    }

    //修改课程
    public function seaechCousrseNews ($data)
    {

        $builder = DB::table('g_activity')
            ->where('g_activity.id',$data['id'])
            ->where('g_activity.status',1)
            ->get();

        return $builder;
    }


    //查询学生活动的信息
    public function searchStudentActivity($stud,$eid)
    {
        $builder = DB::table('g_activity')
            ->where('g_activity.stuid', $stud)
            ->where('g_activity.eid', $eid)->get();

        return $builder;
    }


    //查询学生活动的信息
    public function searchActivityCourse()
    {
        $time = time();

        $builder = DB::table('g_elective_course')
            ->join('g_user','g_user.id','=','g_elective_course.tid')
            ->join('g_department','g_department.id','=','g_elective_course.department_id')
            ->where('g_elective_course.ctime','<',$time)
            ->select('g_elective_course.*','g_user.name as teachername','g_department.name as depart')
            ->orderBy('g_elective_course.id', 'desc')->get();

        return $builder;

    }

    //查询学生活动的信息
    public function searchActivityStu($stuid,$eid)
    {
        $time = time();

        $builder = DB::table('g_activity')
            ->where('g_activity.stuid',$stuid)
            ->where('g_activity.eid',$eid)
            ->select('id')->get();

        return $builder;

    }


    //查询所有活动未评价的信息
    public function searchWeiPJActivity($tid)
    {

        $builder = DB::table('g_activity')
            ->join('g_user','g_user.id','=','g_activity.stuid')
            ->join('g_elective_course','g_elective_course.id','=','g_activity.eid')

            ->where('g_elective_course.tid',$tid)
            ->where('g_activity.status',0)
            ->orderBy('g_activity.id', 'desc')
            ->select('g_activity.id','g_activity.stuid','g_activity.eid','g_activity.content','g_user.name','g_user.stu_nu')
            ->get();

        return $builder;

    }

}


