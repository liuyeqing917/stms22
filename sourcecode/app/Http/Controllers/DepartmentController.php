<?php

namespace App\Http\Controllers;
use App\Http\Model\User;

use App\Http\Model\Depart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;



class DepartmentController extends CommonController
{

    /**  获取所有科室
     * @method GET
     * @url   fatherdepart
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @return view
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function fatherdepart(Request $request)
    {
        $depart = new Depart;

         $info=$depart->searchFatherDepart(0);

        return $info;
    }

    /**  获取子科室
     * @method GET
     * @url   childdepart
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     *
     * @return view
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function cilddepart(Request $request)
    {
        $id=$request->only('pid');

        $depart = new Depart;

        $info=$depart->searchChildDepart($id['pid']);

        return $info;
    }


    /**  新增科室
     * @method post
     * @url   adddepart
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     *
     * @return view
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function addfatherdepart(Request $request)
    {
        $BaseData=$request->only('name','hospital_id','fathername','teacher_ids','teacher_id');

        $this->gyinput($BaseData);

        $depart = new Depart;

        $fatherDepart = [
            'name' =>  $BaseData['fathername'],
            'hospital_id' =>  $BaseData['hospital_id'],
            'teacher_id' =>  0,
            'pid'  =>0

        ];
        
       $id  = DB::table('g_department')->insertGetId($fatherDepart);

        $sondata = [
            'name' =>  $BaseData['name'],
            'pid' => $id,
            'hospital_id' =>  $BaseData['hospital_id'],
            'teacher_ids' => ','. implode(",", $BaseData['teacher_ids']).',' ,
            'teacher_id' =>  $BaseData['teacher_id'],

        ];

        $result=$depart->adddepart($sondata);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }


    /**  新增科室
     * @method post
     * @url   adddepart
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     *
     * @return view
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function adddepart(Request $request)
    {
        $BaseData=$request->only('name','hospital_id','pid','teacher_ids','teacher_id');

        $this->gyinput($BaseData);

        $depart = new Depart;

        if($BaseData['pid']!=0){
            $BaseData['teacher_ids'] =  ','. implode(",", $BaseData['teacher_ids']).',';
        }
        $result=$depart->adddepart($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }
    /**  修改科室
     * @method GET
     * @url   modifydepart
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return view
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function modifydepart(Request $request)
    {
        $BaseData=$request->only('id','name','pid','hospital_id','teacher_ids','teacher_id');

        $this->gyinput($BaseData);

        $depart = new Depart;

        if($BaseData['pid']!=0){
            $BaseData['teacher_ids'] =','.  implode(",", $BaseData['teacher_ids']).',';
        }

        $result=$depart->updatedepart($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  删除科室
     * @method GET
     * @url   deletedepart
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @return view
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function deletedepart(Request $request)
    {
        $id=$request->only('id');

        $depart = new Depart;

        $result=$depart->deletedepart($id);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;

    }

    /**  科室管理界面信息
     * @method GET
     * @url   deletedepart
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @return view
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function managedepartinfo(Request $request)
    {
        $data=$request->only('pid','department_id','page','pagecount');


        $page = $data['page'];

        $pagecount = $data['pagecount'];

        $data['type']=1;

        $depart = new Depart;

        $user = new User();

        $result=$depart->searchdepartlist($data);

        for($i=0;$i<count($result);$i++){

            $nameOBJ= $user->searchUserName($result[$i]->teacher_id);

            if(count($nameOBJ)>0){

                $result[$i]->guanliname = $nameOBJ[0]->name;

            }else{
                $result[$i]->guanliname = '无';
            }

            $_depart=$depart->searchModelDepart($result[$i]->pid);

            if(count($_depart)>0){

                $result[$i]->fatherdepart = $_depart[0]->name;
            }

            $result[$i]->teacher_ids=substr($result[$i]->teacher_ids,1);
            $result[$i]->teacher_ids=substr($result[$i]->teacher_ids,0,strlen($result[$i]->teacher_ids)-1);

            $teacherArray = explode(",",$result[$i]->teacher_ids);

            $result[$i]->teacherids = $teacherArray;


            if($result[$i]->teacher_ids==""){

                $result[$i]->tearchernames[0] = "无";

              }else{

                for($j=0;$j<count($teacherArray);$j++){

                    $OBJ= $user->searchUserName($teacherArray[$j]);

                    if(count($OBJ)>0){

                        $result[$i]->tearchernames[] = $OBJ[0]->name;

                    }
                }
            }

        }

        $resultArray = [];
        for($i=0;$i<count($result);$i++){

            if($i>=($page-1)*$pagecount&&$i<$page*$pagecount){

                $resultArray[] = $result[$i];
            }

        }

        $courseResult['page']=$page;
        $courseResult['data']=$resultArray;
        $courseResult['allcount']=count($result);

        return $courseResult;

    }




    /**  科室管理界面信息
     * @method GET
     * @url   deletedepart
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>
     * @return view
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */

    public function searchdepartinfo(Request $request)
    {
        $data=$request->only('department_id','pid','type');

        $depart = new Depart;

        $user = new User();

        $result=$depart->searchdepart($data);

        for($i=0;$i<count($result);$i++){

            $nameOBJ= $user->searchUserName($result[$i]->teacher_id);

            if(count($nameOBJ)>0){

                $result[$i]->guanliname = $nameOBJ[0]->name;

            }else{
                $result[$i]->guanliname = '无';
            }

            $_depart=$depart->searchModelDepart($result[$i]->pid);

            if(count($_depart)>0){

                $result[$i]->fatherdepart = $_depart[0]->name;
            }

            $result[$i]->teacher_ids=substr($result[$i]->teacher_ids,1);
            $result[$i]->teacher_ids=substr($result[$i]->teacher_ids,0,strlen($result[$i]->teacher_ids)-1);
            $teacherArray = explode(",",$result[$i]->teacher_ids);

            $result[$i]->teacherids = $teacherArray;


            if($result[$i]->teacher_ids==""){

                $result[$i]->tearchernames[0] = "无";

            }else{

                for($j=0;$j<count($teacherArray);$j++){

                    $OBJ= $user->searchUserName($teacherArray[$j]);

                    if(count($OBJ)>0){

                        $result[$i]->tearchernames[] = $OBJ[0]->name;

                    }
                }
            }

        }

        return $result;


    }
}
