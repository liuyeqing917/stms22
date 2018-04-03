<?php

namespace App\Http\Controllers;
use App\Http\Model\ElecticCourse;
use App\Http\Model\User;
use App\Http\Model\Depart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;



class ElecticCourseController extends CommonController
{
    public $logdata;


    /**  查询课程信息
     * @method get
     * @url   elective/searchall
     * @access public
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-13 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function searchall(Request $request)
    {
        $ElecticCourse = new ElecticCourse();

        $department = $request->only('fatherdepartment_id','childdepartment_id','pagecount','page');

        $stuid=$request->only('stuid');

        $page = $department['page'];

        $pagecount = $department['pagecount'];

        $result = $ElecticCourse->searchCourserList($department['fatherdepartment_id'],$department['childdepartment_id']);

        $user = new User();

        $nowtime = time();

        for($i=0;$i<count($result);$i++){

            $nameOBJ= $user->searchUserName($result[$i]->tid);

            if(count($nameOBJ)>0){

                $result[$i]->teachername = $nameOBJ[0]->name;

            }
                //根据stuid是否为0来判断是否去查询活动的信息
            if($stuid['stuid']!=0){

              $activity= $ElecticCourse->searchStudentActivity($stuid['stuid'], $result[$i]->id);
                    //activitystatus=0代表未填写，1代表已填写
                if(count($activity)==0){

                    $result[$i]->activitystatus = 0;

                }else{

                    $result[$i]->activitystatus = 1;

                }

            }

            $result[0]->nowtime  = $nowtime;

        }

        $info = $this->paginationWay($result,$page,$pagecount);

        return $info;
    }


    /**  确认课程
     * @method post
     * @url   elective/addcourse
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
    public function confirmtofill(Request $request)
    {
        $data=$request->only('id','comment');

        $ElecticCourse = new ElecticCourse;

        $result = $ElecticCourse->Confirmtofill($data);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }


    /**  查看未填写活动的课程
     * @method get
     * @url   elective/addcourse
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
    public function searchactivitycourse(Request $request)
    {
        $data=$request->only('stuid','page','pagecount');

        $page = $data['page'];

        $pagecount = $data['pagecount'];

        $ElecticCourse = new ElecticCourse;

        $courseArray = $ElecticCourse->searchActivityCourse();

        $objArray = [];

        for($i=0;$i<count($courseArray);$i++){

            $result = $ElecticCourse->searchActivityStu($data['stuid'],$courseArray[$i]->id);

                if(count($result)==0){

                    $objArray[] =  $courseArray[$i];
                }

            }

            $newArray = $this->objectToArray($objArray);

            $resultArray = [];
        //自定义分页
            for($i=0;$i<count($newArray);$i++){

                if($i>=($page-1)*$pagecount&&$i<$page*$pagecount){

                    $resultArray[] = $newArray[$i];
                }

            }

        $courseResult['page']=$page;
        $courseResult['data']=$resultArray;
        $courseResult['allcount']=count($newArray);

        return $courseResult;
    }
    /**  新增课程
     * @method post
     * @url   elective/addcourse
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
    public function addcourse(Request $request)
    {
        $BaseData=$request->only('cname','ctime','department_id','venue','tid');

        $this->gyinput($BaseData);

        $ElecticCourse = new ElecticCourse;

       $result = $ElecticCourse->addCourser($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  查询一门课程信息
     * @method post
     * @url   elective/addcourse
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
    public function searchonecourse(Request $request)
    {
        $data=$request->only('id');

        $ElecticCourse = new ElecticCourse;

        $result = $ElecticCourse->searchOneCourse($data['id']);

        return $result;
    }


    /**  修改课程
     * @method post
     * @url   elective/modifycourse
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
    public function modifycourse(Request $request)
    {
        $BaseData=$request->only('id','cname','ctime','department_id','venue','tid');

        $ElecticCourse = new ElecticCourse;

        $result = $ElecticCourse->updateCourser($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  删除课程
     * @method GET
     * @url   elective/deletecourse
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
    public function deletecourse(Request $request)
    {
        $id=$request->only('id');

        $ElecticCourse = new ElecticCourse;

        $result = $ElecticCourse->deleteCouser($id);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  查询课程活动的列表
     * @method post
     * @url   elective/searchall
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
    public function searchactivitylist(Request $request)
    {

        $user = new User;

        $eid=$request->only('eid');
        $type=$request->only('type');
        $stuid=$request->only('stuid');

        $zzdata =$request->only('page','pagecount');
        $page =$zzdata['page'];

        $pagecount = $zzdata['pagecount'];

        //1代表学生2代表老师3管理
        if($type['type']==1){

            $this->logdata=$stuid['stuid'];

            $result=  DB::table('g_elective_course')
                    ->where('g_elective_course.id',$eid['eid'])
                    ->leftJoin('g_activity', function ($leftJoin) {
                        $leftJoin->on('g_activity.eid', '=', 'g_elective_course.id')
                                ->where('g_activity.stuid','=', $this->logdata);
                    })
                    ->join('g_department','g_department.id','=','g_elective_course.department_id')
                    ->join('g_user','g_user.id','=','g_elective_course.tid')
                    ->orderBy('g_activity.id', 'desc')
                    ->select('g_elective_course.*','g_user.name as teachername','g_activity.comment','g_activity.content','g_activity.status','g_department.name as depart')
                    ->get();

            return $result;
        }else {

            $builder=  DB::table('g_elective_course')
                ->where('g_elective_course.id',$eid['eid']);

            if($type['type']==2){

                $builder=$builder->join('g_activity','g_activity.eid','=','g_elective_course.id');

            }else{
                $builder=$builder ->join('g_activity', function ($leftJoin) {
                    $leftJoin->on('g_activity.eid', '=', 'g_elective_course.id')
                             ->where('g_activity.status','=', 1);
                });
            }

             $result=  $builder ->leftJoin('g_department','g_department.id','=','g_elective_course.department_id')
                ->leftJoin('g_user','g_user.id','=','g_activity.stuid')
                ->orderBy('g_activity.id', 'desc')
                ->select('g_activity.*','g_elective_course.tid','g_user.name as studentname','g_user.stu_nu','g_activity.comment','g_activity.content','g_activity.status','g_department.name as depart')
                ->get();

            for($i=0;$i<count($result);$i++){

                $teachname= $user->searchUserName($result[0]->tid);

                $result[$i]->teachername= $teachname;

            }

            $info = $this->paginationWay($result,$page,$pagecount);

            return $info;

        }


    }


    /**  新增活动
     * @method post
     * @url   elective/addactivity
     * @access public
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-13 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function addctivity(Request $request)
    {
        $BaseData=$request->only('eid','stuid','content');

        $this->gyinput($BaseData);

        $ElecticCourse = new ElecticCourse;

        $result = $ElecticCourse->addActivity($BaseData);

       $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  修改活动
     * @url   elective/modifyactivity
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
    public function modifyactivity(Request $request)
    {
        $BaseData=$request->only('id','eid','stuid','content','status');

        $this->gyinput($BaseData);

        $ElecticCourse = new ElecticCourse;

        $result = $ElecticCourse->updateActivity($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  删除活动
     * @method GET
     * @url   elective/deleteactivity
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
    public function deleteactivity(Request $request)
    {
        $id=$request->only('id');

        $ElecticCourse = new ElecticCourse;

        $result = $ElecticCourse->deleteActivity($id);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  查询为评价的活动
     * @method GET
     * @url   elective/deleteactivity
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
    public function searchwpjActivity(Request $request)
    {
        $data=$request->only('tid','pagecount','page');

        $page = $data['page'];

        $pagecount = $data['pagecount'];

        $ElecticCourse = new ElecticCourse;

        $result = $ElecticCourse->searchWeiPJActivity($data['tid']);

        $info = $this->paginationWay($result,$page,$pagecount);

        return $info;

    }




}
