<?php
//公共控制器
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{
    public function __construct(Request $request){
        //获取控制器名

       $c =strtolower($this->getCurrentControllerName());
        //不需要验证的方法
        $nologins = array('logincontroller','departmentcontroller');

        $data =$request->only('token');

        $user = new User();

        if(!in_array($c,$nologins)){

            if(!empty($data["token"]) && $token = $data["token"]){

              $result = $this->check_token($token);

                if(!$result){

                    echo'{status:99,msg:"您的账号在其他地方登录！"}';exit;

                }

            }else{

                echo'{status:99,msg:"token已经不存在，请重新登录！"}';exit;

            }
        }
    }


    /**
     * 获取当前控制器名
     *
     * @return string
     */
    public function getCurrentControllerName()
    {
        $arrs = explode("\\",$this->getCurrentAction()['controller']);
        $cou = count($arrs)-1;
        return $arrs[$cou];
    }

    /**
     * 获取当前方法名
     *
     * @return string
     */
    public function getCurrentMethodName()
    {
        return $this->getCurrentAction()['method'];
    }

    /**
     * 获取当前控制器与方法
     *
     * @return array
     */
    public function getCurrentAction()
    {
        $action =\Route::current()->getActionName();
        list($class, $method) = explode('@', $action);

        return ['controller' => $class, 'method' => $method];
    }


    //图片上传
    public function upload()
    {
        $file = Input::file('Filedata');
        if($file -> isValid()){
            $entension = $file -> getClientOriginalExtension(); //上传文件的后缀.
            $newName = date('YmdHis').mt_rand(100,999).'.'.$entension;
            $path = $file -> move(base_path().'/public/uploads',$newName);
            $filepath = 'uploads/'.$newName;
            return $filepath;
        }
    }

    //生成随机数字
    public function rand_string($len = 6) {
        $chars = 'abcdefghijkmnpqrstuvwxyz23456789';
        if ($len > 10) { //位数过长重复字符串一定次数
            $chars = str_repeat ( $chars, 5 );
        }
        $chars = str_shuffle ( $chars );
        $str = substr ( $chars, 0, $len );
        return $str;
    }

    //生成token值
    public function make_token(){
       $str = md5($this->rand_string(12).time());
        return $str;
    }

    //获取token值
    public function get_token($token){
        Cache::put('key', $token,3);
        $anrr = Cache::get('key');
    }

    //生成token缓存文件
    public function cache_member_token(){
        //先清除所有缓存
        Cache::flush();

        $token_arr =DB::table('g_user')->get();


        foreach($token_arr as $v){
            $v->token && $member_token[$v->token] = $v->id;
        }


        Cache::put('member_token', $member_token,3000);

    }

    //验证token是否存在文件
    public function check_token($token){
        $member_id = 0;
        $member_token = Cache::get('member_token');


        if($member_token && isset($member_token[$token])){
            $member_id = $member_token[$token];
        }
        return $member_id;
    }

    //组合多维数组
    public function unlimitForLayer($cate, $pid = 0, $name= 'child'){
        $arr = array();
        foreach ($cate as $v){
            if($v->pid== $pid){
                $v->$name = $this->unlimitForLayer($cate, $v->id, $name);
                $arr[] = $v;
            }
        }

        return $arr;
    }

    //对象转数组
    public function std_class_object_to_array($stdclassobject)
    {
        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
        foreach ($_array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? $this->std_class_object_to_array($value) : $value;
            $array[$key] = $value;
        }
        return $array;
    }

    /*返回信息接口*/
    public function rmsg($code=1, $msg=''){
        $data = [
            'status' => $code,
            'msg' => $msg,
        ];
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $data;
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


   //自定义分页
    public function  paginationWay($array,$page,$pagecount)
    {

        $resultArray=[];

        for($i=0;$i<count($array);$i++){

            if($i>=($page-1)*$pagecount&&$i<$page*$pagecount){
                
                $resultArray[] = $array[$i];
            }

        }

        $courseResult['page']=$page;
        $courseResult['data']=$resultArray;
        $courseResult['allcount']=count($array);

        return $courseResult;

    }
//过滤表单提交过来的数据
public function gyinput(&$array){
    if(empty($array)){
        return $array;
    }
    foreach($array as $key=>$var){
        if(is_array($var)){
            $array[$key] = $this->gyinput($var);
        }else {
            $array[$key] = htmlentities($var);
        }
    }
    return  $array;
}
    /**
     * 返回成功的json数据
     *
     * @return string
     *
     * [
     *    'code'            =>    1,
     *    'message'        =>    'success',
     *    'data'            =>    ''
     * ];
     *
     */
    public function success($data = [], $code = 1, $message = 'success')
    {
        return [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * 返回失败的json数据
     *
     * @param \Exception $ex
     * @return string 'code'            =>    -999,
     *
     * 'code'            =>     -999,
     * 'message'        =>      'fail'
     * ];
     */
    public function fail(\Exception $ex)
    {
        if ($ex->getCode() == 0) {
            $code = 0;
        } else {
            $code = $ex->getCode();
        }

        if ('Trying to get property of non-object' == $ex->getMessage()) {
            return [
                'code' => -50000,
                'message' => '当前系统错误，请重试！',
            ];
        } else {

            return [
                'code' => $code,
                'message' => $ex->getMessage(),
            ];
        }
    }
}


