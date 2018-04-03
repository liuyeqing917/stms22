<?php
//学生出科小结管理控制器
//author:fandian
namespace App\Http\Controllers;
use App\Http\Model\Evaluate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;



class EvaluateController extends CommonController
{

    //get  获取学生信息
    /*
     * 1.需要判断角色(老师角色)
     * 2.需要根据老师的科室
     */
    public function getRoles(Request $request)
    {

        $data = $request->all();

        $user = session('user');

        $data['type'] = $user[0]->type;
        $data['id'] = $user[0]->id;

        $eva = new Evaluate();

        $data['time'] = time();
        $info = $eva->getRoles($data);

        $ones = $eva->getOnes($data);

        if($data['dtype']!=3){
            foreach($ones as $one){
                foreach($info as $item){
                    if($one->evaluated_id==$item->id&&$one->evaluating_id==$data['id']){
                        $item->id=0;
                    }
                }
            }
        }

        $arr = [];
        foreach($info as $v){
            if($v->id!=0){
                $arr[] = $v;
            }
        }

        return $arr;
    }

    //get  获取新增评价表
    /*
     * 1.需要判断角色
     * 2.需要根据科室选择评价表
     */
    public function getInfo(Request $request)
    {
        $data = $request->all();

        $user = session('user');

        $data['type'] = $user[0]->type;

        $eva = new Evaluate();
        $base = $eva->getBase($data);

        $info = $eva->getInfo($data);

        $arr = array(
            'base' => $base,
            'info' => $info
        );
        return $arr;
    }

    //post  新增评价记录
    /*
     * 1.评价主表id
     * 2.评价内容id
     * 3.评价得分
     * 4.评价者id
     * 5.被评价者id
     */
    public function addEva(Request $request)
    {
        $data = $request->all();

        $str = '';
        for($i=0;$i<count($data['ids']);$i++){
            if($i==0){
                $str = $str.$data['ids'][$i].':'.$data['scores'][$i];
            }else{
                $str = $str.','.$data['ids'][$i].':'.$data['scores'][$i];
            }
        }
        $data['result'] = $str;

        $user = session('user');

        $data['evaluating_id'] = $user[0]->id;

        $data['eva_time'] = date('Y-m-d H:i:s', time());
        $eva = new Evaluate();
        $info = $eva->add($data);

        if($info){
            $data = $this->rmsg(1,'新增评价成功');
            return $data;
        }else{
            $data = $this->rmsg(0,'新增评价失败，请稍后重试！');
            return $data;
        }
    }

    //get  评价列表
    /*
     * 1.科室id
     * 2.被评价者id
     */
    public function getList(Request $request)
    {
        $data = $request->all();
        $data['role'] = $data['evaluated_id'];

        $user = session('user');
        $type = $user[0]->type;

        if($type!=3){
            $data['own'] = 1;
            $data['evaluated_id'] = $user[0]->id;
        }

        $eva = new Evaluate();
        $info = $eva->getList($data);

        $count = $data['pagecount'];
        $page  = $data['page'];

        $arr = $this->paginationWay($info,$page,$count);
        return $arr;
    }

    //get  评价明细
    /*
     * 1.记录表id
     */
    public function getDetail(Request $request)
    {
        $data = $request->all();

        $eva = new Evaluate();
        $base = $eva->getDetailBase($data);

        $info = $eva->getDetail($data);

        $arr = array(
            'base' => $base,
            'info' => $info
        );
        return $arr;
    }

    //post  新增评价内容
    /*
     * 1.评价内容
     * 2.评价类型
     */
    public function addContent(Request $request)
    {
        $data = $request->all();

        $eva = new Evaluate();
        $info = $eva->addContent($data);

        if($info){
            $data = $this->rmsg(1,'新增评价成功');
            return $data;
        }else{
            $data = $this->rmsg(0,'新增评价失败，请稍后重试！');
            return $data;
        }
    }


    //get  统计
    /*
     * 1.科室id
     * 2.用户id
     */
    public function statistics(Request $request)
    {
        $data = $request->all();

        $user = session('user');

        $data['type'] = $user[0]->type;
        $data['id'] = $user[0]->id;

        $eva = new Evaluate();
        $info = $eva->statistics($data);

        $base = $eva->getBase($data);

        $logs = $eva->getLogs($data);

        $count = count($logs);
        foreach($base as &$v){
            $score = 0;
            foreach($info as $item){

                if($item->id==$v->id){

                    $score = $score + $item->score;
                }

            }
            $v->score = ceil($score/$count);
        }

        return $base;
    }

}
