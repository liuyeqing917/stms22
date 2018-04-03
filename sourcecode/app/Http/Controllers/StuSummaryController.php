<?php
//学生出科小结管理控制器
//author:fandian
namespace App\Http\Controllers;
use App\Http\Model\StuSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;



class StuSummaryController extends CommonController{
    
    //get  stusummary/add   添加学生出科小结
    public function add(Request $request){
        $department_ids = $request->only('department_id');
        $department_id = $department_ids['department_id'];
        //增加对应的判断如果在这个科室已确认出科，或者还没有到达这个科室的入科时间，则不让学生填写其手册
        $user = session('user');
        $stuid = $user[0]->id;
        $ntime = time();
        $data = DB::table('g_stu_summary')->where('department_id',$department_id)->where('stuid',$stuid)->count();
       if($data>0){
                return $this->rmsg(0, '你已经添加过出科小结，不能重复添加');
        }
        $data = DB::table('g_sdcycle')->where('department_id',$department_id)->where('stuid',$stuid)->first();
        if(empty($data)){
            return $this->rmsg(0, '轮转还未生成');
        }
        if($ntime<$data->plan_starttime){
            return $this->rmsg(0, '你还没到入此科的时间不可以编辑此科的手册内容');
        }
        return $this->rmsg(1,'添加出科小结');
    }

    //get  stusummary/gettitle/{stucase}
    public function gettitle(Request $request){

        $department_ids = $request->only('department_id');
        $department_id = $department_ids['department_id'];
        $user = session('user');
        $stuid = $user[0]->id;

        //注意以stusmmary::这种方法取出来的数据（表中没有数据），并不为空
       // $data = StuSummary::where('department_id',$department_id)->where('stuid',$stuid)->get();

        $data = DB::table('g_stu_summary')->where('department_id',$department_id)->where('stuid',$stuid)->get();
        $arr = array();
        if(!empty( $data)){
            $status = $data[0]->status;
            foreach ($data as $k => $v) {
                $arr[$k]['stuid'] = $v->stuid;
                $arr[$k]['id'] = $v->id;
                $arr[$k]["department_id"] = $v->department_id;
                $arr[$k]['one'] = $v->one;
                $arr[$k]['two'] = $v->two;
                $arr[$k]['three'] = $v->three;
                $result = DB::table('g_department')->where('id', $department_id)->get();
                $arr[$k]['pid'] = $result[0]->pid;
            }
        }

        if(!empty($arr)){
            $arr1['data'] = $arr;
            $arr1['status'] = 1;
            $arr1['msg'] = '出科小结列表';
        }else{
            $arr1['status'] = 0;
            $arr1['msg'] = '没有出科小结';
        }

      return $arr1;
    }

    //post stusummary/insert  添加学生出科小结提交
    public function insert(){

        $input = Input::except('token');
        $input = $this->gyinput($input);
         $re = StuSummary::create($input);
        if($re){
            $data = $this->rmsg(1,'添加出科小结成功');
        }else{
            $data = $this->rmsg(0,'添加出科小结失败，请稍后重试！');
        }
        return $data;
    }

    //get stusummary/edit  编辑学生出科小结
    public function edit(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
        $data = StuSummary::where('id',$id)->get();
        return $data;
    }

    //post  stusummary/update   更新学生出科小结
    public function update(){

        $input = Input::except('token');
        $input = $this->gyinput($input);
        $id = $input["id"];
        $re = StuSummary::where('id',$id)->update($input);
        if($re){
            $data = $this->rmsg(1,'更新学生出科小结成功');
            return $data;
        }else{
            $data = $this->rmsg(0,'更新学生出科小结失败，请稍后重试！');
            return $data;
        }
    }

    //get stusummary  显示单个学生出科小结信息
    public function show(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
        $data = StuSummary::where('id',$id)->get();
        return $data;
    }

    //get   stusummary/delete   删除学生出科小结信息
    public function delete(Request $request){

        $ids = $request->only('id');
        $id = $ids["id"];
        $re = StuSummary::where('id',$id)->delete();
        if($re){
            $data = $this->rmsg(1,'删除学生出科小结成功');
        }else{
            $data = $this->rmsg(0,'删除学生出科小结失败，请稍后重试！');
        }
        return $data;
    }

}
