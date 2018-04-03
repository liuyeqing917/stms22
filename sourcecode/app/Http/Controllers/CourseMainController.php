<?php
//大钢内管理控制器
//author:fandian
namespace App\Http\Controllers;
use App\Http\Model\CourseMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;



class CourseMainController extends CommonController{

   //get coursemain/index   课程列表
    public function index(Request $request){
        $deps = $request->only('department_id');
        $department_id = $deps['department_id'];
        $mids = $request->only('mid');
        $mid = $mids['mid'];
       //1病种,2临床操作,3大病历书写,4活动记录
        $types = [1=>"病种",2=>"临床操作",3=>"大病历书写",4=>"活动记录"];
        $data= CourseMain::orderBy('type','asc','id','desc')->where('department_id',$department_id)->where('mid',$mid)->get();
        $arr = array();
        foreach($data as $k=>$v){
            $arr[$k]['id'] = $v->id;
            $arr[$k]['department_id'] = $v->department_id;
            $arr[$k]['type'] = $v->type;
            $arr[$k]['title'] = $v->title;
            $arr[$k]['ctype'] = $v->ctype;
            $arr[$k]['num'] = $v->num;
            $arr[$k]['mid'] = $mid;
            $result =  DB::table('g_department')->where('id',$v->department_id)->get();
            $arr[$k]['pid'] = $result[0]->pid;
        }
        $data = compact('types','arr');
        return $data;

   }
    //get  coursemain/add   添加课程
    public function add(){

        //1病种,2临床操作,3大病历书写,4活动记录
        $types = [1=>"病种",2=>"临床操作",3=>"大病历书写",4=>"活动记录"];
        return $types;
    }

    //post coursemain/insert  添加课程提交
    public function insert(){

        $input = Input::except('token');
        $input = $this->gyinput($input);
         $re = CourseMain::create($input);
            if($re){
                $data = $this->rmsg(1,'添加课程成功');
            }else{
                $data = $this->rmsg(0,'添加课程失败，请稍后重试！');
            }
        return $data;
    }

    //get coursemain/edit  编辑课程
    public function edit(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
        $data = CourseMain::where('id',$id)->get();
        return $data;
    }

    //post coursemain/update   更新课程
    public function update(){

        $input = Input::except('token');
        $input = $this->gyinput($input);
        $id = $input["id"];
        $re = CourseMain::where('id',$id)->update($input);
        if($re){
            $data = $this->rmsg(1,'更新课程成功');
        }else{
            $data = $this->rmsg(0,'更新课程失败，请稍后重试！');
        }
        return $data;
    }

    //get coursemain/show  显示单个课程信息
    public function show(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
       $data = CourseMain::where('id',$id)->get();
        return $data;
    }

    //get coursemain/delete   删除单个课程信息
    public function delete(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
        $re = CourseMain::where('id',$id)->delete();
        if($re){
            $data = $this->rmsg(1,'删除课程病例成功');
        }else{
            $data = $this->rmsg(0,'删除课程失败，请稍后重试！');
        }
        return $data;
    }

    //post  coursemain/copycourse   复制大钢内课程
    public function copycourse(Request $request){

        $mids = $request->only('mid');
        $mid = $mids["mid"];
        $cmids = $request->only('cmid');
        $cmid = $cmids["cmid"];

        //自己不能复制自己
        if($mid==$cmid){
            $data = $this->rmsg(0,'不可以重复复制！');
            return $data;
        }
        //要复制的大钢内的课程对应的轮转主键
        $cpdata = CourseMain::where('mid',$cmid)->get();
        foreach($cpdata as $cp){
            $cparr = array();
            $cparr['department_id'] = $cp['department_id'];
            $cparr['type'] = $cp['type'];
            $cparr['title'] = $cp['title'];
            $cparr['ctype'] = $cp['ctype'];
            $cparr['num'] = $cp['num'];
            $cparr['mid'] = $mid;
            $re = CourseMain::create($cparr);
        }
        if($re){
          $data = $this->rmsg(1,'复制大钢内课程成功');
        }else{
          $data = $this->rmsg(0,'复制大钢内课程失败，请稍后重试！');
        }
        return $data;
    }

}
