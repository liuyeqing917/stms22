<?php
//轮转管理控制器
//author:fandian
namespace App\Http\Controllers;
use App\Http\Model\Cycle;
use App\Http\Model\Evaluate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;



class CycleController extends CommonController{

    /**  科室列表
     * @method GET
     * @url   cycle/depshow
     */
    public function depshow(){

        $data = DB::table('g_department')->get();
        $arr = $this->unlimitForLayer($data);
        return $arr;
    }

   //得到学生数组
    public function getstuarr(){

        $data = DB::table('g_user')->select('name', 'id')->where('type',1)->where('finish',0)->get();
        $arr = array();
        foreach($data as $k=>$v){
            $arr[$k]["id"] = $v->id;
            $arr[$k]["name"] = $v->name;
        }
        return $arr;
    }

    /** 删除轮转主表
     * @method GET
     * @url   cycle/delcycle
     */
   public function delcycle(Request $request){

       $data =$request->only('id');
       $id = $data["id"];
       $re = (DB::table('g_mcycle')->where('id',$id)->delete())&&(DB::table('g_sdcycle')->where('mid',$id)->delete())&&(DB::table('g_kcycle')->where('mid',$id)->delete())&&(DB::table('g_course_main')->where('mid',$id)->delete());
       if($re){
           $ydels = DB::table('g_sdcycle')->where('mid',$id)->groupBy('stuid')->get();
           $del = array();
           foreach($ydels as $ydel){
               $del[]=$ydel->stuid;
           }
           DB::table('g_stu_case')->whereIn('stuid', $del)->delete();
           DB::table('g_stu_clinical')->whereIn('stuid',$del)->delete();
           DB::table('g_stu_disease')->whereIn('stuid',$del)->delete();
           DB::table('g_stu_summary')->whereIn('stuid',$del)->delete();
       }
       //删除对应评价信息
       $evaluate = new Evaluate;
       $data1 = array();
       $data1["mid"] = $id;
       $evaluate->delEval($data1);
       if($re){
           echo $this-> rmsg(1,'成功删除轮转主表');
       }else{
           echo $this-> rmsg(0,'删除轮转主表失败');
       }

   }

    /** 轮转主表列表
     * @method GET
     * @url   cycle/listmcycle
     */
    public function listmcycle(){

        $data = DB::table('g_mcycle')->get();
        foreach($data as $k=>&$v){
            $result = DB::table('g_evaluate_main')->where('mid',$v->id)->get();
            if(empty($result))
                $data[$k]->pj = 0;
            else
                $data[$k]->pj = 1;
        }
        return $data;
    }


    /**  轮转表生成
     * @method GET
     * @url   cycle/makecycle
     */
    public function makecycle(Request $request){

        $data = $request->only('rooms');
        $sjs = $request->only('date');
        $sj = $sjs["date"];
/*        $arr8 = array();
        foreach($data["rooms"]; as $k=>$v){
            if(!empty($v)){
                $arr8[$k] = $v;
            }
        }*/
        $rooms = array_filter($data["rooms"]);
        $year = ((int)substr($sj,0,4));//取得年份
        $month = ((int)substr($sj,5,2));//取得月份
        $day = ((int)substr($sj,8,2));//取得几号
        $time = mktime(0,0,0,$month,$day,$year);
        $cycle = new cycle;
        $students = $this->getstuarr();
        $cycle->docycle($time,$students,$rooms);

    }

    /** 按时间查找
     * @method POST
     * @url    cycle/ftsdcycle
     */
    public function ftsdcycle(Request $request){

        //$value = session('user');
        //$value[0]->id;
        $btime =$request->only('btime');
        $btime = $btime["btime"];
        $etime =$request->only('etime');
        $etime = $etime["etime"];
        $data1 = DB::table('g_sdcycle')
            ->leftJoin('g_department', 'g_sdcycle.department_id', '=', 'g_department.id')
            ->leftJoin('g_user', 'g_user.id', '=', 'g_sdcycle.stuid')
            ->leftJoin('g_stu_summary','g_stu_summary.stuid','=', 'g_sdcycle.stuid')
            ->select('g_sdcycle.*', 'g_department.name as kname','g_department.teacher_id', 'g_user.name')
            ->where('g_sdcycle.plan_starttime', '>=', $btime)
            ->where('g_sdcycle.plan_endtime', '<=', $etime)
            ->get();
        $arr = array();
        $data2 = $this->ksteacher();
        foreach($data1 as $k=>$v){
            $tid = $v->teacher_id;
            $arr[$k]["teacher_name"] = $data2[$tid];
            $arr[$k]["name"] = $v->name;
            $arr[$k]["plan_starttime"] =  $v->plan_starttime;
            $arr[$k]["plan_endtime"] =  $v->plan_endtime;
            $arr[$k]["kname"] =  $v->kname;

          $res= DB::table('g_stu_summary')->where('stuid',$v->stuid)->where('department_id',$v->department_id)->first();
            if(!empty($res)){
                if($res->status == 1){
                    $zt = "出科";
                }else{
                    $zt = "等待出科";
                }
            }else{
                    $nowtime = time();
                    if($nowtime< $v->plan_starttime){
                        $zt = "即将入科";
                    }elseif(($nowtime>= $v->plan_starttime)&&($nowtime<= $v->plan_endtime)){
                        $zt = "入科中";
                    }else{
                        $zt = "出科小结未填写";
                    }
            }
            $arr[$k]["zt"] = $zt;
            $arr[$k]["stuid"] =  $v->stuid;
            $arr[$k]["department_id"] = $v->department_id;
        }
        //增加分页
        $page =  $request->only('page');
        $pagecount =  $request->only('pagecount');
        $page =$page['page'];
        $pagecount =$pagecount['pagecount'];
        $arr = $this->paginationWay($arr,$page,$pagecount);
        $status = 1;
        $data = compact('arr','status');

        echo  json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    /** 获取对应科室负责老师的数组
     * @method GET
     * @url   cycle/ksteacher
     */
    public function ksteacher(){

        $data = DB::table('g_department')
            ->leftJoin('g_user', 'g_user.id', '=', 'g_department.teacher_id')
            ->select('g_user.name','g_user.id')
            ->where('g_department.teacher_id','<>','null')
            ->get();
        $arr = array();
        foreach($data as $v){
            $arr[$v->id] = $v->name;
        }
        return $arr;
    }

    /** 按科室查找
     * @method POST
     * @url   cycle/fksdcycle
     */
    public function fksdcycle(Request $request){

        $ks =$request->only('department_id');
        $ks = $ks["department_id"];
        $data1 = DB::table('g_sdcycle')
            ->leftJoin('g_department', 'g_sdcycle.department_id', '=', 'g_department.id')
            ->leftJoin('g_user', 'g_user.id', '=', 'g_sdcycle.stuid')
            ->select('g_sdcycle.*', 'g_department.name as kname','g_department.teacher_id', 'g_user.name')
            ->where('g_sdcycle.department_id',$ks)
            ->orderBy('g_sdcycle.plan_starttime','desc')
            ->get();
        $arr = array();
        $data2 =$this->ksteacher();
        foreach($data1 as $k=>$v){
            $tid = $v->teacher_id;
            $arr[$k]["teacher_name"] = $data2[$tid];
            $arr[$k]["name"] = $v->name;
            $arr[$k]["plan_starttime"] =  $v->plan_starttime;
            $arr[$k]["plan_endtime"] =  $v->plan_endtime;
            $arr[$k]["kname"] =  $v->kname;
            $res= DB::table('g_stu_summary')->where('stuid',$v->stuid)->where('department_id',$v->department_id)->first();
            if(!empty($res)){
                if($res->status == 1){
                    $zt = "出科";
                }else{
                    $zt = "等待出科";
                }
            }else{
                $nowtime = time();
                if($nowtime< $v->plan_starttime){
                    $zt = " 即将入科";
                }elseif(($nowtime>= $v->plan_starttime)&&($nowtime<= $v->plan_endtime)){
                    $zt = "入科中";
                }else{
                    $zt = "出科小结未填写";
                }
            }
            $arr[$k]["zt"] = $zt;
            $arr[$k]["stuid"] =  $v->stuid;
            $arr[$k]["department_id"] = $v->department_id;
        }
        //增加分页
        $page =  $request->only('page');
        $pagecount =  $request->only('pagecount');
        $page =$page['page'];
        $pagecount =$pagecount['pagecount'];
        $arr = $this->paginationWay($arr,$page,$pagecount);
        $status = 1;
        $data = compact('arr','status');

        echo  json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /** 轮转表详细信息
     * @method GET
     * @url   cycle/xcycle
     */
    public function xcycle(Request $request){

        $stu =$request->only('stuid');
        $ks = $request->only('department_id');
        $stuid = $stu["stuid"];
        $midata = DB::table('g_sdcycle')->where('stuid',$stuid)->first();
        $mid = $midata->mid;


        $department_id = $ks["department_id"];
        $teachers = $request->only('teacher_name');
        $teacher_name = $teachers["teacher_name"];
        //病例
        $arr1 =  DB::table('g_course_main')->where('type',1)->where('department_id',$department_id)->where('mid',$mid)->get();
        $brr1 = array();
        foreach($arr1 as $k=>$v){
            $brr1[$k]["title"] = $v->title;
            $brr1[$k]["teacher_name"] = $teacher_name;
            if($v->ctype==1){
                $cd = "掌握";
            }else{
                $cd = "熟悉";
            }
            $brr1[$k]["cd"] = $cd;
            $brr1[$k]["num"] = $v->num;
            $brr1[$k]["eid"] = $v->id;
            $brr1[$k]['stuid'] = $stuid;
            $rnum = DB::table('g_stu_case')->where('stuid',$stuid)->where('department_id',$department_id)->where('eid',$v->id)->count();
            $brr1[$k]["rnum"] = $rnum;
        }

        //临床操作表
        $arr2 =  DB::table('g_course_main')->where('type',2)->where('department_id',$department_id)->where('mid',$mid)->get();
        $brr2 = array();
        foreach($arr2 as $k=>$v){
            $brr2[$k]["title"] = $v->title;
            $brr2[$k]["teacher_name"] = $teacher_name;
            if($v->ctype==1){
                $cd = "掌握";
            }else{
                $cd = "熟悉";
            }
            $brr2[$k]["cd"] = $cd;
            $brr2[$k]["num"] = $v->num;
            $brr2[$k]["eid"] = $v->id;
            $brr2[$k]['stuid'] = $stuid;
            $rnum = DB::table('g_stu_clinical')->where('stuid',$stuid)->where('department_id',$department_id)->where('eid',$v->id)->count();
            $brr2[$k]["rnum"] = $rnum;
        }

        //大病历
        $arr3 =  DB::table('g_course_main')->where('type',3)->where('department_id',$department_id)->where('mid',$mid)->get();
        $brr3 = array();
        foreach($arr3 as $k=>$v){
            $brr3[$k]["title"] = $v->title;
            $brr3[$k]["teacher_name"] = $teacher_name;
            if($v->ctype==1){
                $cd = "掌握";
            }else{
                $cd = "熟悉";
            }
            $brr3[$k]["cd"] = $cd;
            $brr3[$k]["num"] = $v->num;
            $brr3[$k]['stuid'] = $stuid;
            $brr3[$k]["eid"] = $v->id;
            $rnum = DB::table('g_stu_disease')->where('stuid',$stuid)->where('department_id',$department_id)->where('eid',$v->id)->count();
            $brr3[$k]["rnum"] = $rnum;
        }

        //活动记录
        $arr4=  DB::table('g_course_main')->where('type',4)->where('department_id',$department_id)->where('mid',$mid)->get();
        $brr4 = array();
        foreach($arr4 as $k=>$v){
            $brr4[$k]["title"] = $v->title;
            $brr4[$k]["teacher_name"] = $teacher_name;
            if($v->ctype==1){
                $cd = "掌握";
            }else{
                $cd = "熟悉";
            }
            $brr4[$k]["cd"] = $cd;
            $brr4[$k]["num"] = $v->num;
            $brr4[$k]["eid"] = $v->id;
            $brr4[$k]['stuid'] = $stuid;
            $rnum = DB::table('g_stu_activity')->where('stuid',$stuid)->where('department_id',$department_id)->where('eid',$v->id)->count();
            $brr4[$k]["rnum"] = $rnum;
        }

        //出科小结
        $brr5 =  DB::table('g_stu_summary')->where('stuid',$stuid)->where('department_id',$department_id)->get();

        $status = 1;
        $arr = compact('status','brr1','brr2','brr3','brr4','brr5');
        return $arr;

    }

    //老师确认出科操作
    function confirmcycle(){

        $input = Input::except('token');
        $input = $this->gyinput($input);
        $id = $input["id"];
        $user = session('user');
        $tid = $user[0]->id;
        //必顺要此课室的老师才能确认其出科
        $data = DB::table('g_stu_summary')->where('id',$id)->first();
        $department_id = $data->department_id;
        $data = DB::table('g_department')->where('id',$department_id)->first();
        $ssid1 = $data->teacher_ids;
        $ssid2 = $data->teacher_id;
        $ssid1 = trim($ssid1,',');
        $ssid = $ssid1.",".$ssid2;
        $syarr = explode(",",$ssid);

        if(!in_array($tid,$syarr)){
            $data = $this->rmsg(0,'您不是本科室的老师无法确认出科操作');
            return $data;
        }
        $re = DB::table('g_stu_summary')->where('id',$id)->update($input);
        if($re){
            $data = $this->rmsg(1,'确认学生出科操作成功');
            //找出对应的学生
            $arr1 = DB::table('g_stu_summary')->where('id',$id)->first();
            $stuid = $arr1->stuid;
            //找到对应科室
            $department_id = $arr1->department_id;
            //修改一下g_sdcycle表中的endtime
            $data0['endtime'] = time();
            DB::table('g_sdcycle')->where('stuid',$stuid)->where('department_id',$department_id)->update($data0);
            //先查出科记录填写的情况
            $num1 = DB::table('g_stu_summary')->where('stuid',$stuid)->count();
            //轮转表中记录的学生情况
            $num2 = DB::table('g_sdcycle')->where('stuid', $stuid)->count();
            //先查询一下学生的状态，如果已经finish则不要操作
            $data4 = DB::table('g_user')->where('id', $stuid)->first();
            if($data4->finish == 0){
                if($num1 == $num2){
                    $bz = 0;
                    $data3 = DB::table('g_sdcycle')->where('stuid', $stuid)->get();
                    foreach($data3 as $k=>$v) {
                        $data2 = DB::table('g_stu_summary')->where('stuid', $stuid)->where('department_id',$v->department_id)->first();
                        if($data2->status ==1){
                            $bz = 1;
                        }else{
                            $bz = 0;
                            break;
                        }
                    }
                    //学生出科小结里面的所有科都已经出科，则更新g_user表中的finish记录
                    if($bz ==1){
                        $data3["finish"] = 1;
                        DB::table('g_user')->where('id',$stuid)->update($data3);
                    }
                }
            }
            return $data;
        }else{
            $data = $this->rmsg(0,'确认学生出科操作失败，请稍后重试！');
            return $data;
        }
    }

    //列表展示页
     function showlist(Request $request){

         $tbs = $request->only('tb');
         $eids = $request->only('eid');
         $stuids = $request->only('stuid');
         $tb = $tbs['tb'];
         $eid = $eids['eid'];
         $stuid = $stuids['stuid'];
         $data = DB::table($tb)->where('eid',$eid)->where('stuid',$stuid)->get();
         return $data;
     }

    //学生手册查看
    function stulookcycle(){

        $user = session('user');
        $stuid = $user[0]->id;
        $data1 = DB::table('g_department')->get();
        $arr1 = $this->unlimitForLayer($data1);
        $arr2 = array();
        $arr3 = $this->ksteacher();
        foreach ($arr1 as $k => $v) {
            $arr2[$k]["name"] = $v->name;
            foreach ($v->child as $k1 => $v1) {
                $data2 = DB::table('g_sdcycle')->where('stuid', $stuid)->where('department_id',$v1->id)->first();
                if(!empty($data2)){
                    $data2->name = $v1->name;
                    $data2->teacher_name = $arr3[$v1->teacher_id];
                    $arr2[$k]['child'][] = $data2;
                }
            }
        }
        return $arr2;
    }

}
