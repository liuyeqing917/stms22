<?php

namespace App\Http\Controllers;
use App\Http\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

require_once '../resources/org/code/Code.class.php';

class LoginController extends CommonController
{


    /**  登录
     * @method GET
     * @url   login
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
    public function login(Request $request)
    {
        $input =$request->only('password','username','code');

        if(!$input['code']){

            $result['msg']='验证码为空！';

            return $result;

        }else {

            $code = new \Code;
            $_code = $code->get();

            if(strtoupper($input['code'])!=$_code){

                $result['msg']='验证码错误！';

                return $result;
            }

            $user = new User();

            $_user = $user->login($input['username']);

            if($_user==0){

                $result['msg']='没有这个用户';

                return $result;

            }

            $password=0;

            $password = $_user[0]->password;


            if($password!=md5($input['password'])){

                $result['msg']='用户名或者密码错误！';

                return $result;

            }


            $token = $this->make_token();

            $user->updateToken($input['username'],$token);

            $this->cache_member_token();

            $newuser = $user->login($input['username']);

            //$_SESSION['user'] = $newuser;
            session(['user'=>$newuser]);

            return $newuser;
         }
    }

    /**  学生注册
     * @method GET
     * @url   student/registe
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
    public function studentregiste(Request $request)
    {
        $BaseData=$request->only('name','certificate_type','certificate_number','national','birthday','phone_number','sex','fixed_number',
            'email','foreign_blity','stu_type','department_id','communication_adress','emergency_contact','ec_number',
            'ec_adress','self_introduce','hospital_id','stu_nu');

        $passwords = $request->only('password');

        $pwd = $passwords['password'];

        $BaseData['password']= md5($pwd);

        $BaseData['type'] =1;

        $user = new User();

        $result= $user->addUser($BaseData);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    public function quit()
    {
        session(['user'=>null]);

        return redirect('login');
    }

    public function code()
    {
        $code = new \Code;

        $code->make();
    }


    public function test()
    {
        return view('login');
    }

}
