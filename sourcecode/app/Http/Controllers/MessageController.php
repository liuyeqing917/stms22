<?php

namespace App\Http\Controllers;
use App\Http\Model\Message;
use App\Http\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
class MessageController extends CommonController
{
    public function test(){
        $arr = array();
        $data = new Message();
        $result = $data->selectDepartment();
        $k = 0;
        foreach($result as $value){
            $father_department = $value->name;
            $Id = $data->selectDepartmentId($father_department);
            $id =  $Id[$k]->id;
            $son_department = $data->selectDepartmentName($id);
            $arr[$father_department]=$son_department;
        }
        print_r($arr);
    }
    /**
     *消息处理方法
     */
    public function handle(Request $request){
        header("content-type:text/html;charset=utf-8");

        $data = $request->only('teacher','father_department','son_department','title','content','all');
        $Message = new Message();


        if($data['father_department']==0){

            $data['ifall']=1;
            $data['department']=0;
        }else{

            $data['department']=$data['son_department'];
            $data['ifall']=0;
        }

        $result=$Message->insert($data);

        $info = $this->rmsg($result['code'],$result['msg']);

        return $info;
    }

    /**
     * @return View 指向消息发布的页面
     */
    public function message(){
        return view("message/pMessage");
    }

    /**
     * @param $department_one  科室名
     * @return mixed  返回对应科室的id
     */
    public function sel_department_id($department_one){

        $data = new Message();
        $result = $data->selectDepartmentId($department_one);
        print_r($result);exit;
        echo $result[0]->id;
    }

    /**
     * @param $receive_type_tea  教师选中为2,否则不为2
     * @param $receive_type_stu  学生选中为1,否则不为1
     * @param $input 前台传来的信息
     * @param $id   对应科室的id
     * @param $data  数据库操作类
     * @return mixed  返回给前台消息记录
     */
    public function TeaHandle($teacher_id,$input,$department_id,$data){
            $code = $data->insertMessage($input,$department_id,$teacher_id);//插入到数据库
            $arr = array("message"=>$code);
            return $arr;exit;
    }

    public function StuHandle($student_id,$input,$department_id,$data){
            $code = $data->insertMessage($input,$department_id,$student_id);//插入到数据库
        $arr = array("message"=>$code);
        return $arr;exit;

    }
    public function AllHandle($accepter,$input,$department_id,$data)
    {
            $code=$data->insertMessage($input,$department_id,$accepter);//插入到数据库
        $arr = array("message"=>$code);
        return $arr;exit;

    }
    public function typeHandle($teacher_id,$student_id,$input,$department_id){
        $data = new Message();
        if($teacher_id==2&&$student_id!=1){//如果只发布给老师
           $code = $this->TeaHandle($teacher_id,$input,$department_id,$data);
            return $code;
        }elseif($student_id==1&&$teacher_id!=2){//如果只发布给学生
          $code =$this->StuHandle($student_id,$input,$department_id,$data);
            return $code;
        }elseif($student_id==1&&$teacher_id==2){//都发布
           $code = $this->AllHandle(3,$input,$department_id,$data);
            return $code;
        }
    }
}
