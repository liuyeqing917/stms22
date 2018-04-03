<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
class User extends Model
{
    protected $table='g_user';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $hidden = ['password'];

    //登录 验证账号
    public function login($number)
    {
        $builder = DB::table('g_user')
            ->where('g_user.usernumber',$number)
            ->where('g_user.ifdelete',0)
            ->get();
        if($builder){
            return $builder;
        }

            return 0;
    }

    //更新token
    public function updateToken($number,$token)
    {
        $builder = DB::table('g_user')
            ->where('g_user.usernumber',$number)
            ->update([
                'token' => $token,
            ]);;

        return $builder;
    }

    //验证token
    public function checkToken($token)
    {
        $builder = DB::table('g_user')
            ->where('g_user.token',$token)
            ->get();;

        return $builder;
    }

    //新增用户
    public function addUser($data)
    {
        $builder = DB::table('g_user')
            ->where('stu_nu',$data['stu_nu'])
            ->get();

        $result=[];
        if($builder){
            $result['code']=2;
            $result['msg']='号码已存在，请重新输入';
            return $result;
        }
        $builder = DB::table('g_user')
            ->where('g_user.phone_number',$data['phone_number'])
            ->get();

        if($builder){
            $result['code']=3;
            $result['msg']='手机号已存在，请重新输入';
            return $result;
        }

        $builder = DB::table('g_user')
            ->where('g_user.email',$data['email'])
            ->get();

        if($builder) {
            $result['code'] = 5;
            $result['msg'] = '邮箱已存在，请重新输入';
            return $result;
        }
        $builder = DB::table('g_user')
            ->where('g_user.certificate_number',$data['certificate_number'])
            ->get();

        if($builder){
            $result['code']=4;
            $result['msg']='证件号已存在，请重新输入';
            return $result;
        }
        $builder = DB::table('g_user')->insertGetId([
            'name' =>  $data['name'],
            'certificate_type' =>  $data['certificate_type'],
            'certificate_number' =>  $data['certificate_number'],
            'national' =>  $data['national'],
            'birthday' =>  $data['birthday'],
            'phone_number' =>  $data['phone_number'],
            'sex' =>  $data['sex'],
            'fixed_number' =>  $data['fixed_number'],
            'email' =>  $data['email'],
            'foreign_blity' =>  $data['foreign_blity'],
            'stu_type' =>  $data['stu_type'],
            'department_id' =>  $data['department_id'],
            'communication_adress' =>  $data['communication_adress'],
            'emergency_contact' =>  $data['emergency_contact'],
            'ec_number' =>  $data['ec_number'],
            'ec_adress' =>  $data['ec_adress'],
            'self_introduce' =>  $data['self_introduce'],
            'hospital_id' =>  $data['hospital_id'],
            'stu_nu' =>  $data['stu_nu'],
            'usernumber' =>  $data['stu_nu'],
            'password' =>  $data['password'],
            'type' =>  $data['type'],
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

    //修改用户
    public function modifyUser($data)
    {

        $builder = DB::table('g_user')
            ->where('g_user.phone_number',$data['phone_number'])
            ->where('id','<>',$data['id'])
            ->get();

        if($builder){
            $result['code']=3;
            $result['msg']='手机号已存在，请重新输入';
            return $result;
        }
        $builder = DB::table('g_user')
            ->where('g_user.certificate_number',$data['certificate_number'])
            ->where('id','<>',$data['id'])
            ->get();

        if($builder) {
            $result['code'] = 2;
            $result['msg'] = '证件号已存在，请重新输入';
        }
        $builder = DB::table('g_user')
            ->where('g_user.email',$data['email'])
            ->where('id','<>',$data['id'])
            ->get();

        if($builder) {
            $result['code'] = 4;
            $result['msg'] = '邮箱已存在，请重新输入';
        }

            $builder = DB::table('g_user')
            ->where('id',$data['id'])
            ->update([
            'name' =>  $data['name'],
            'certificate_type' =>  $data['certificate_type'],
            'certificate_number' =>  $data['certificate_number'],
            'national' =>  $data['national'],
            'birthday' =>  $data['birthday'],
            'phone_number' =>  $data['phone_number'],
            'sex' =>  $data['sex'],
            'fixed_number' =>  $data['fixed_number'],
            'email' =>  $data['email'],
            'foreign_blity' =>  $data['foreign_blity'],
            'stu_type' =>  $data['stu_type'],
            'department_id' =>  $data['department_id'],
            'communication_adress' =>  $data['communication_adress'],
            'emergency_contact' =>  $data['emergency_contact'],
            'ec_number' =>  $data['ec_number'],
            'ec_adress' =>  $data['ec_adress'],
            'self_introduce' =>  $data['self_introduce'],
            'hospital_id' =>  $data['hospital_id'],

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

    //修改用户
    public function modifyPassword($data)
    {
        $user = DB::table('g_user')
            ->where('id',$data['id'])
            ->get();

        $password = $user[0]->password;
        if($password==md5($data['oldpassword'])){
            $builder = DB::table('g_user')
                ->where('id',$data['id'])
                ->update([
                    'password' =>  $data['password'],
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

        $result['code']=2;
        $result['msg']='原密码错误';

        return $result;
    }

    //删除用户
    public function deleteUser($id)
    {
        //先判断这个老师有没有职务
        $depart = DB::table('g_department')
            ->where('g_department.teacher_id',$id)
            ->get();

        if($depart){
            $result['code']=2;
            $result['msg']='删除失败,此老师还是'.$depart[0]->name.'的负责老师';
            return $result;
        }

        $_depart = DB::table('g_department')
            ->where('g_department.teacher_ids','like','%'.','.$id.','.'%')
            ->get();

        if($_depart){
            $result['code']=3;
            $result['msg']='删除失败,此老师还是'.$_depart[0]->name.'的成员老师';
            return $result;
        }

        $builder = DB::table('g_user')
            ->where('type','<','3')
            ->where('id',$id)
            ->update([
                'ifdelete' =>1
            ]);
        if($builder){
            $result['code']=1;
            $result['msg']='删除成功';
            return $result;

        }
        $result['code']=0;
        $result['msg']='删除成功';
        return $result;

    }

    //查询用户的名字
    public function searchUserName($id)
    {
        $builder = DB::table('g_user')
            ->where('g_user.id',$id)
            ->select('name','stu_nu')
            ->get();

        return $builder;
    }

        //查询教师的信息
    public function searchTeacherName($data)
    {
        $builder = DB::table('g_user')
            ->select('name','id','phone_number','email','department_id','stu_nu','sex','certificate_number','imageurl');
        if($data['id']!=0){
            $builder=$builder->where('g_user.id',$data['id'])
            ;
        }

        $builder=$builder->where('type',2)
            ->get();

        return $builder;
    }

    //查询教师的信息
    public function searchTeacherList($id)
    {
        $builder = DB::table('g_user')
                ->where('g_user.id',$id)
                ->where('ifdelete',0)
                ->where('type',2)->select('name','id','phone_number','email','stu_nu','sex','certificate_number','imageurl')
                ->get();

        return $builder;
    }


    //查询教师的信息
    public function searchAllTeacherList()
    {
        $builder = DB::table('g_user')
            ->where('type',2)
            ->where('ifdelete',0)
            ->select('name','id','phone_number','email','stu_nu','sex','certificate_number')
            ->get();

        return $builder;
    }


}


