<?php
//学生活动记录管理控制器
//author:fandian
namespace App\Http\Controllers;
use App\Http\Model\StuActivity;
use App\Http\Model\StuCase;
use App\Http\Model\CourseMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;



class StuActivityController extends CommonController{

    public $mid;

    public function __construct(){
        $user = session('user');
        $stuid = $user[0]->id;
        $midata = DB::table('g_sdcycle')->where('stuid',$stuid)->first();
        if(!empty($midata)){
            $this->mid = $midata->mid;
        }
    }

    //get  stuactivity/add   添加学生活动记录
    public function add(Request $request){

        $department_ids = $request->only('department_id');
        $department_id = $department_ids['department_id'];
        //增加对应的判断如果在这个科室已确认出科，或者还没有到达这个科室的入科时间，则不让学生填写其手册
        $user = session('user');
        $stuid = $user[0]->id;
        $ntime = time();
        $data = DB::table('g_stu_summary')->where('department_id',$department_id)->where('stuid',$stuid)->first();
        if(!empty($data)){
            if($data->status==1){
                return $this->rmsg(0, '你已出科不可以再编辑此科的手册内容');
            }
        }
        $data = DB::table('g_sdcycle')->where('department_id',$department_id)->where('stuid',$stuid)->first();
        if(empty($data)){
            return $this->rmsg(0, '轮转还未生成');
        }
        if($ntime<$data->plan_starttime){
            return $this->rmsg(0, '你还没到入此科的时间不可以编辑此科的手册内容');
        }
        $data = DB::table('g_course_main')->where('department_id',$department_id)->where('type',4)->where('mid',$this->mid)->count();
        if($data == 0){
            return $this->rmsg(0, '此类别下还没有设置对应的题目');
        }
        $data = DB::table('g_course_main')->where('department_id',$department_id)->where('type',4)->where('mid',$this->mid)->get();
        return $data;
    }

    //get  stuactivity/showtype  学生活动记录对应题目
    public function showtype(Request $request){
        $department_ids = $request->only('department_id');
        $department_id = $department_ids['department_id'];
        $data = DB::table('g_course_main')->where('department_id',$department_id)->where('type',4)->where('mid',$this->mid)->get();
        return $data;
    }

    //get  stuactivity/showlist  根据type=4 和科室id 取出对应的病例标题(g_course_main(大纲课程设置表)中的title)
    public function showlist(Request $request){

        $department_ids = $request->only('department_id');
        $department_id = $department_ids['department_id'];
        $data = DB::table('g_course_main')->where('type',4)->where('department_id',$department_id)->where('mid',$this->mid)->get();
        $arr = array();
        if(!empty($data)){
            foreach($data as $k=>$v){
                $arr[$k]['eid'] = $v->id;
                $arr[$k]['type'] = $v->title;
                $user = session('user');
                $stuid = $user[0]->id;
                $result =  DB::table('g_department')->where('id',$department_id)->get();
                $result1 = DB::table('g_stu_summary')->where('department_id',$department_id)->where('stuid',$stuid)->first();
                if(!empty($result1)){
                    $arr[$k]['status'] = $result1->status;
                }else{
                    $arr[$k]['status'] = 0;
                }
                $arr[$k]['pid'] = $result[0]->pid;
                $arr[$k]['department_id'] = $department_id;
                $field = StuActivity::where('stuid',$stuid)->where('eid',$v->id)->get();
                $arr[$k]["children"] = $field;
            }
        }
        return $arr;
    }

    //post stuactivity/insert   添加学生活动记录提交
    public function insert(){

        $input = Input::except('token');
        $input = $this->gyinput($input);
         $re = StuActivity::create($input);
        if($re){
            $data = $this->rmsg(1,'添加学生活动记录成功');
        }else{
            $data = $this->rmsg(0,'添加学生活动记录失败，请稍后重试！');
        }
        return $data;
    }

    //get stuactivity/edit  编辑学生活动记录
    public function edit(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
        $data = StuActivity::where('id',$id)->get();
        return $data;
    }

    //post stuactivity/update   更新学生活动记录
    public function update(){

        $input = Input::except('token');
        $input = $this->gyinput($input);
        $id = $input["id"];
        $re = StuActivity::where('id',$id)->update($input);
        if($re){
            $data = $this->rmsg(1,'更新学生活动记录成功');
            return $data;
        }else{
            $data = $this->rmsg(0,'更新学生活动记录失败，请稍后重试！');
            return $data;
        }
    }

    //get stuactivity/show   显示单个学生活动记录
    public function show(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
        $data = StuActivity::where('id',$id)->get();
        return $data;
    }

    //get stuactivity/delete   删除单个学生活动记录
    public function delete(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
        $re = StuActivity::where('id',$id)->delete();
        if($re){
            $data = $this->rmsg(1,'删除学生活动记录成功');
        }else{
            $data = $this->rmsg(0,'删除学生活动记录失败，请稍后重试！');
        }
        return $data;
    }

}
