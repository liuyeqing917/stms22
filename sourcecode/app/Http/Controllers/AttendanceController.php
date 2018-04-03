<?php

namespace App\Http\Controllers;

use App\Http\Model\Attendance;
use App\Http\Model\User;
use App\Http\Model\Depart;
use App\Http\Model\Cycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;



class AttendanceController extends CommonController
{

    /** 新增考勤
     * @method GET
     * @url   fatherdepart
     * @access public
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @return view
     *
     * @version 1.0
     * @author zhochong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function addattendance(Request $request)
    {
        $dataArray=$request->only('stuid','department_id','attendance','late','early','leave','teacherid');

        $attendance = new Attendance();

        $this->gyinput($BaseData);

        $result = 0;

//        for($i=0;$i<count($dataArray['stuid']);$i++){
//
//            $addData []= [
//                'stuid'          =>  $dataArray['stuid'][$i],
//                'department_id'  =>  $dataArray['department_id'][$i],
//                'attendance'     =>  $dataArray['attendance'][$i],
//                'late'           =>  $dataArray['late'][$i],
//                'early'          =>  $dataArray['early'][$i],
//                'leave'          =>  $dataArray['leave'][$i],
//            ];

            $result = $attendance->addAttendence($dataArray);

//        }
        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }


    /**  修改考勤信息
     * @method post
     * @url   elective/addactivity
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-13 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function modifyattendance(Request $request)
    {
        $dataArray=$request->only('id','stuid','department_id','attendance','late','early','leave');

        $this->gyinput($BaseData);

        $attendance = new Attendance();

        $result = $attendance->modifyAttendence($dataArray);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  查询考勤信息
     * @method post
     * @url   elective/addactivity
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-13 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function searchattendance(Request $request)
    {
        $dataArray=$request->only('stuid','department_id');

        $attendance = new Attendance();

        $lzarray = $request->only('btime','department_id','etime','type');

        $page =  $request->only('page');

        $pagecount =  $request->only('pagecount');

        $page =$page['page'];
        $pagecount =$pagecount['pagecount'];

        $user = new User();

        $depart = new Depart();

        $cyc = new Cycle();


        //老师就进这个方法，查询科室里的学生
        if($lzarray['type']!=1){

            $nameArray = $cyc->kqcycle($lzarray);

            //所有学生的名字和id信息列表
            $newNameArray = $this->objectToArray($nameArray);

            $info =[];
            for($i=0;$i<count($newNameArray);$i++){

                $departResult = $attendance->searchDepartmentAttendence($newNameArray[$i]['stuid'], $lzarray['department_id']);

                $_depart = $depart->searchModelDepart($lzarray['department_id']);
                    if(count($departResult)>0){

                        $info [] = [
                            'id' =>     $departResult[0]->id,
                            'stuid' => $newNameArray[$i]['stuid'],
                            'department_id' =>  $lzarray['department_id'],
                            'attendance' =>  $departResult[0]->attendance,
                            'late' =>  $departResult[0]->late,
                            'early' =>  $departResult[0]->early,
                            'leave' =>  $departResult[0]->leave,
                            'teacher' => $departResult[0]->name,
                            'depart' => $_depart[0]->name,
                            'stuname' => $newNameArray[$i]['name'],
                        ];

                    }else{
                        $info [] = [
                            'id' => 0,
                            'stuid' => $newNameArray[$i]['stuid'],
                            'department_id' =>  $lzarray['department_id'],
                            'attendance' => 0,
                            'late' => 0,
                            'early' => 0,
                            'leave' => 0,
                            'teacher' => '',
                            'depart' => $_depart[0]->name,
                            'stuname' => $newNameArray[$i]['name'],
                        ];
                    }

            }

            $aainfo = $this->paginationWay($info,$page,$pagecount);

            return $aainfo;

            //学生进这个方法
        }else{


            $result=  $attendance->searchStudentAttendence($dataArray['stuid']);

            $name = $user->searchUserName($dataArray['stuid']);


            for($i=0;$i<count($result);$i++){

                $_depart = $depart->searchModelDepart($result[$i]->department_id);
                if(!empty($_depart)){
                    $result[$i]->depart = $_depart[0]->name;
                    $result[$i]->stuname =  $name[0]->name;
                }else{
                    $result = array();
                }
         }

            $info = $this->paginationWay($result,$page,$pagecount);

            return $info;
        }
    }


    //对象转成数组
    public function objectToArray($news)
    {
        $result=[];

        for($i=0;$i<count($news);$i++){

            $arr = is_object($news[$i]) ? get_object_vars($news[$i]) : $news[$i];

            if(is_array($arr)){

                $result[]=$arr;

            }
        }

        return $result;
    }
}
