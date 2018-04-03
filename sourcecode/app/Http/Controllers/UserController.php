<?php

namespace App\Http\Controllers;
use App\Http\Model\Depart;
use App\Http\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

require_once '../resources/org/code/Code.class.php';

class UserController extends CommonController
{


    /**  学生修改个人信息
 * @method GET
 * @url   student/modify
 * @access public
 *
 * @param Request $request post请求<br><br>
 * <b>post请求字段：</b>

 *
 * @return
 * 返回信息
 *  0 注册成功
 *  1 账号已存在
 *  2 手机号已存在
 *  3 邮箱已存在
 *
 * @version 1.0
 * @author zhouchong <zouyuchao@misrobot.com>
 * @date 2016-5-12 14:05
 * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
 * @
 */
    public function studentmodify(Request $request)
    {
        $BaseData=$request->only('id','name','certificate_type','certificate_number','national','birthday','phone_number','sex','fixed_number',
            'email','foreign_blity','stu_type','department_id','communication_adress','emergency_contact','ec_number',
            'ec_adress','self_introduce','hospital_id');


        $this->gyinput($BaseData);

        $BaseData['type'] =2;

        $user = new User();

        $result= $user->modifyUser($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  修改密码
     * @method GET
     * @url   student/modify
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function modifypassword(Request $request)
    {
        $BaseData=$request->only('id','oldpassword');

        $passwords = $request->only('newpassword');

        $pwd = $passwords['newpassword'];

        $BaseData['password']= md5($pwd);

        $user = new User();

        $result= $user->modifyPassword($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;

    }

    /**  新增老师
     * @method GET
     * @url   teacher/registe
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function teacherregiste(Request $request)
    {
        $BaseData=$request->only('name','certificate_type','certificate_number','national','birthday','phone_number','sex','fixed_number',
            'email','foreign_blity','stu_type','department_id','communication_adress','emergency_contact','ec_number',
            'ec_adress','self_introduce','hospital_id','stu_nu');

        $passwords = $request->only('password');

        $this->gyinput($BaseData);

        $pwd = $passwords['password'];
        $BaseData['password']= md5($pwd);

        $BaseData['type']=2;
        $user = new User();

        $result= $user->addUser($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**  老师修改个人信息
     * @method GET
     * @url   teacher/modify
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function teachermodify(Request $request)
    {
        $BaseData=$request->only('id','name','certificate_type','certificate_number','national','birthday','phone_number','sex','fixed_number',
            'email','foreign_blity','stu_type','department_id','communication_adress','emergency_contact','ec_number',
            'ec_adress','self_introduce','hospital_id','stu_nu');


        $passwords = $request->only('password');

        $pwd = $passwords['password'];

        $BaseData['password']= md5($pwd);

        $user = new User();

        $result= $user->modifyUser($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /** 老师删除个人信息
     * @method GET
     * @url   teacher/modify
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function teacherdelete(Request $request)
    {
        $id=$request->only('id');

        $user = new User();

        $result= $user->deleteUser($id['id']);

        DB::table('g_fourm')->where('g_fourm.userid')->delete();
        DB::table('g_fourm_floor')->where('g_fourm_floor.userid')->delete();
        DB::table('g_fourm_floor_message')->where('g_fourm_floor_message.userid')->delete();

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

        /** 查询老师的个人信息
     * @method GET
     * @url   teacher/search
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function searchteacher(Request $request)
    {
        $data =$request->only('id','department_id');

        $user = new User();

        $info= $user->searchTeacherName($data);

        return $info;
    }

    /** 查询学生师的个人信息
     * @method GET
     * @url   student/search
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function searchstudent(Request $request)
    {
        $id=$request->only('usernumber');

        $user = new User();

        $depart = new Depart();

        $info= $user->login($id);

        if($info==0){
            $result['msg']='用户名错误！';
            return $result;

        }
        
         $_depart = $depart->searchModelDepart($info[0]->department_id);

         $info[0]->fatherdepartid = $_depart[0]->pid;

        return $info;
    }


    /** 查看老师个人信息的列表页面
     * @method GET
     * @url   teacher/search
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function teacherlist(Request $request)
    {
        $data =$request->only('userid','department_id','pid','page','pagecount');

        $page = $data['page'];

        $pagecount = $data['pagecount'];

        $data['type'] = 1 ;

        $user = new User();

        $depart = new Depart;

        $info = [];

        if($data['department_id']==0&&$data['pid']==0){
            $info=  $user-> searchAllTeacherList();

        }else{
            $departresult=$depart->searchdepart($data);


            for($i=0;$i<count($departresult);$i++){

                $teachersArray =  explode(",",$departresult[$i]->teacher_ids);


                for($j=0;$j<count( $teachersArray);$j++){

                    $teacherNes= $user->searchTeacherList($teachersArray[$j]);

                    $info[]=$teacherNes[0];
                }

            }

        }


        $resultArray = [];
        for($i=0;$i<count($info);$i++){

            if($i>=($page-1)*$pagecount&&$i<$page*$pagecount){

                $resultArray[] = $info[$i];
            }

        }
        $courseResult['page']=$page;
        $courseResult['data']=$resultArray;
        $courseResult['allcount']=count($info);


        return $courseResult;
    }
    /** 上传图片
     * @method GET
     * @url   student/search
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function uploadPhoto()
    {
        $imageurl =$this->upload();

        if($imageurl){


            $result['code']=1;

            $result['msg']='上传成功';

            $result['imageurl']=$imageurl;



        }else{
            $result['code']=0;

            $result['msg']='上传失败';

        }

        return $result;
    }


    /** 修改头像
     * @method GET
     * @url   student/search
     * @access public
     *
     * @param Request $request post请求<br><br>
     * <b>post请求字段：</b>

     *
     * @return
     * 返回信息
     *  0 注册成功
     *  1 账号已存在
     *  2 手机号已存在
     *  3 邮箱已存在
     *
     * @version 1.0
     * @author zhouchong <zouyuchao@misrobot.com>
     * @date 2016-5-12 14:05
     * @copyright 2013-2015 MIS misrobot.com Inc. All Rights Reserved
     * @
     */
    public function uploadimageurl()
    {
        $user = session('user');

        $imageurl =$this->upload();

        if($imageurl){

            DB::table('g_user')->where('g_user.id',$user['0']->id)->update(['imageurl'=>$imageurl]);

            $result['code']=1;

            $result['msg']='上传成功';

            $result['imageurl']=$imageurl;

        }else{
            $result['code']=0;

            $result['msg']='上传失败';

            $result['imageurl']='';
        }

        return $result;
    }


}
