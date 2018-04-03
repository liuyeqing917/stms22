<?php
//学生出科小结管理模型
//author fandian
namespace App\Http\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Evaluate extends Model
{

    //获取信息
    public function getRoles($data)
    {
        if($data['type']==2){
            $builder = DB::table('g_sdcycle')
                ->join('g_user','g_user.id','=','g_sdcycle.stuid')
                ->join('g_department', 'g_department.id', '=', 'g_sdcycle.department_id')
                ->select('g_user.id','g_user.name');

            $list = $builder->where('g_sdcycle.department_id',$data['depid'])
                ->where('g_department.teacher_id',$data['id'])
                ->where('g_sdcycle.endtime','<',$data['time'])
                ->where('g_sdcycle.endtime','>',0)
                ->groupby('g_user.id')
                ->get();
        }else if($data['type']==1){
            if($data['dtype']==3){
                if($data['depid']!=0){
                    $builder = DB::table('g_sdcycle')
                        ->join('g_evaluate_log','g_sdcycle.department_id','=','g_evaluate_log.depid')
                        ->join('g_user','g_user.id','=','g_evaluate_log.evaluating_id')
                        ->select('g_user.id','g_user.name');

                    $list = $builder->where('g_sdcycle.department_id',$data['depid'])
                        ->where('g_sdcycle.stuid',$data['id'])
                        ->where('g_evaluate_log.evaluated_id',$data['id'])
                        ->where('g_sdcycle.endtime','<',$data['time'])
                        ->where('g_sdcycle.endtime','>',0)
                        ->groupby('g_user.id')
                        ->get();
                }else{
                    $info = DB::table('g_department')
                        ->select('g_department.id')
                        ->where('g_department.pid',$data['pid'])
                        ->get();

                    $list = [];
                    foreach($info as $v){
                        $builder = DB::table('g_sdcycle')
                            ->join('g_evaluate_log','g_sdcycle.department_id','=','g_evaluate_log.depid')
                            ->join('g_user','g_user.id','=','g_evaluate_log.evaluating_id')
                            ->select('g_user.id','g_user.name');

                        $alist = $builder->where('g_sdcycle.department_id',$v->id)
                            ->where('g_sdcycle.stuid',$data['id'])
                            ->where('g_evaluate_log.evaluated_id',$data['id'])
                            ->where('g_sdcycle.endtime','<',$data['time'])
                            ->where('g_sdcycle.endtime','>',0)
                            ->groupby('g_user.id')
                            ->get();

                        foreach($alist as $a){
                            $list[] = $a;
                        }
                    }

                }

            }else{
                $builder = DB::table('g_sdcycle')
                    ->join('g_department','g_sdcycle.department_id','=','g_department.id')
                    ->join('g_user','g_user.id','=','g_department.teacher_id')
                    ->select('g_user.id','g_user.name');

                $list = $builder->where('g_sdcycle.department_id',$data['depid'])
                    ->where('g_sdcycle.stuid',$data['id'])
                    ->where('g_sdcycle.endtime','<',$data['time'])
                    ->where('g_sdcycle.endtime','>',0)
                    ->groupby('g_user.id')
                    ->get();
            }

        }else{
            if($data['dtype']==2){
                if($data['depid']!=0){
                    $builder = DB::table('g_sdcycle')
                        ->join('g_user', 'g_user.id', '=', 'g_sdcycle.stuid')
                        ->select('g_user.id','g_user.name');

                    $list = $builder->where('g_sdcycle.department_id',$data['depid'])
                        ->where('g_sdcycle.endtime','<',$data['time'])
                        ->where('g_sdcycle.endtime','>',0)
                        ->groupby('g_user.id')
                        ->get();
                }else{
                    $info = DB::table('g_department')
                        ->select('g_department.id')
                        ->where('g_department.pid',$data['pid'])
                        ->get();

                    $arr = array();
                    foreach($info as $item){
                        $builder = DB::table('g_sdcycle')
                            ->join('g_user', 'g_user.id', '=', 'g_sdcycle.stuid')
                            ->select('g_user.id','g_user.name');

                        $alist = $builder->where('g_sdcycle.department_id',$item->id)
                            ->where('g_sdcycle.endtime','<',$data['time'])
                            ->where('g_sdcycle.endtime','>',0)
                            ->get();
                        foreach($alist as $i){
                            $arr[] = $i;
                        }

                    }
                    foreach($arr as $k=>$m){
                        foreach($arr as $kk=>$n){
                            if($m->id==$n->id&&$k<$kk){
                                $m->id = 0;
                            }
                        }
                    }

                    $list = [];
                    foreach($arr as $value){
                        if($value->id!=0){
                            $list[] = $value;
                        }
                    }

                }

            }else{
                if($data['depid']!=0){
                    $builder = DB::table('g_sdcycle')
                        ->join('g_department', 'g_sdcycle.department_id', '=', 'g_department.id')
                        ->join('g_user', 'g_user.id', '=', 'g_department.teacher_id')
                        ->select('g_user.id','g_user.name');

                    $list = $builder->where('g_sdcycle.department_id',$data['depid'])
                        ->where('g_sdcycle.endtime','<',$data['time'])
                        ->where('g_sdcycle.endtime','>',0)
                        ->groupby('g_user.id')
                        ->get();
                }else{
                    $info = DB::table('g_department')
                        ->select('g_department.id')
                        ->where('g_department.pid',$data['pid'])
                        ->get();

                    $arr = array();
                    foreach($info as $item){
                        $builder = DB::table('g_sdcycle')
                            ->join('g_department', 'g_sdcycle.department_id', '=', 'g_department.id')
                            ->join('g_user', 'g_user.id', '=', 'g_department.teacher_id')
                            ->select('g_user.id','g_user.name');

                        $alist = $builder->where('g_sdcycle.department_id',$item->id)
                            ->where('g_sdcycle.endtime','<',$data['time'])
                            ->where('g_sdcycle.endtime','>',0)
                            ->get();
                        foreach($alist as $i){
                            $arr[] = $i;
                        }

                    }
                    foreach($arr as $k=>$m){
                        foreach($arr as $kk=>$n){
                            if($m->id==$n->id&&$k<$kk){
                                $m->id = 0;
                            }
                        }
                    }

                    $list = [];
                    foreach($arr as $value){
                        if($value->id!=0){
                            $list[] = $value;
                        }
                    }
                }

            }
        }

        return $list;
    }


    //获取信息
    public function getOnes($data)
    {
        $list = DB::table('g_evaluate_log')
                ->select('g_evaluate_log.evaluated_id','g_evaluate_log.evaluating_id')
                ->where('g_evaluate_log.depid',$data['depid'])
                ->get();

        return $list;
    }

    //获取base id
    public function getBase($data)
    {
        $builder = DB::table('g_evaluate_main')
            ->join('g_evaluate_type', 'g_evaluate_main.id', '=', 'g_evaluate_type.main_id')
            ->select('g_evaluate_type.id','g_evaluate_type.base');

        $list = $builder->where('g_evaluate_main.department_id',$data['depid'])
            ->where('g_evaluate_main.status',0)
            ->get();

        if($list==null||$list==''){
            $builder = DB::table('g_evaluate_main')
                ->join('g_evaluate_type', 'g_evaluate_main.id', '=', 'g_evaluate_type.main_id')
                ->select('g_evaluate_type.id','g_evaluate_type.base');

            $list = $builder->where('g_evaluate_main.department_id',0)
                ->where('g_evaluate_main.status',0)
                ->get();
        }

        return $list;
    }

    //获取新增评价表
    public function getInfo($data)
    {
        $builder = DB::table('g_evaluate_main')
            ->join('g_evaluate_type', 'g_evaluate_main.id', '=', 'g_evaluate_type.main_id')
            ->join('g_evaluate_content', 'g_evaluate_type.id', '=', 'g_evaluate_content.type_id')
            ->select('g_evaluate_main.id','g_evaluate_type.base','g_evaluate_type.id as tid', 'g_evaluate_content.content',
                'g_evaluate_content.id as cid','g_evaluate_content.score');

        $builder = $builder->where('g_evaluate_main.department_id',$data['depid'])
            ->where('g_evaluate_main.status',0);

        if($data['type']!=3){
            $builder = $builder->where('g_evaluate_main.type','<>',$data['type']);
        }

        $list =  $builder->get();

        if($list==null||$list==''){
            $builder = DB::table('g_evaluate_main')
                ->join('g_evaluate_type', 'g_evaluate_main.id', '=', 'g_evaluate_type.main_id')
                ->join('g_evaluate_content', 'g_evaluate_type.id', '=', 'g_evaluate_content.type_id')
                ->select('g_evaluate_main.id','g_evaluate_type.base','g_evaluate_type.id as tid', 'g_evaluate_content.content',
                    'g_evaluate_content.id as cid','g_evaluate_content.score');

            $builder = $builder->where('g_evaluate_main.department_id',0)
                ->where('g_evaluate_main.status',0);

            if($data['type']!=3){
                $builder = $builder->where('g_evaluate_main.type','<>',$data['type']);
            }

            $list =  $builder->get();
        }

        return $list;
    }

    //新增评价记录
    public function add($data)
    {
        $id = DB::table('g_evaluate_log')->insertGetId([
            'main_id' =>  $data['main_id'],
            'evaluating_id' =>  $data['evaluating_id'],
            'evaluated_id' =>  $data['evaluated_id'],
            'depid' =>  $data['depid'],
            'eva_time' =>  $data['eva_time']
        ]);

        $scores = 0;
        $arr = explode(",",$data['result']);
        foreach($arr as $item){
            $info = explode(":",$item);
            DB::table('g_evaluate_result')->insertGetId([
                'log_id' =>  $id,
                'content_id' =>  $info[0],
                'score' =>  $info[1]
            ]);
            $scores = $scores + $info[1];
        }

        DB::table('g_evaluate_log')
            ->where('id',$id)
            ->update([
                'scores' => $scores
            ]);

        return $id;
    }

    //获取评价列表
    public function getList($data)
    {
        if(isset($data['own'])){
            if($data['depid']!=0){

                $builder = DB::table('g_evaluate_log')
                    ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                    ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                    ->join('g_sdcycle', 'g_evaluate_log.evaluated_id', '=', 'g_sdcycle.stuid')
                    ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime');

                $list = $builder->where('g_sdcycle.department_id',$data['depid'])
                    ->where('g_department.id',$data['depid'])
                    ->where('g_evaluate_log.evaluated_id',$data['evaluated_id'])
                    ->get();

                $arr = $this->addname($list);

                return $arr;

            }else{

                if($data['role']!=0){

                    $builder = DB::table('g_evaluate_log')
                        ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                        ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                        ->join('g_sdcycle', 'g_evaluate_log.evaluated_id', '=', 'g_sdcycle.stuid')
                        ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime','g_sdcycle.department_id','g_evaluate_log.depid');

                    $alist = $builder->where('g_evaluate_log.evaluating_id',$data['role'])
                        ->where('g_evaluate_log.evaluated_id',$data['evaluated_id'])
                        ->get();

                    $list = [];
                    foreach($alist as $item){
                        if($item->department_id==$item->depid){
                            $list[] = $item;
                        }
                    }

                    $arr = $this->addname($list);

                    return $arr;

                }else{

                    $info = DB::table('g_evaluate_log')
                        ->join('g_department', 'g_evaluate_log.depid', '=', 'g_department.id')
                        ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                        ->select('g_user.id','g_evaluate_log.depid')
                        ->where('g_evaluate_log.evaluated_id',$data['evaluated_id'])
                        ->where('g_department.pid',$data['pid'])
                        ->get();

                    $list = [];
                    foreach($info as $i){
                        $builder = DB::table('g_evaluate_log')
                            ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                            ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                            ->join('g_sdcycle', 'g_evaluate_log.evaluated_id', '=', 'g_sdcycle.stuid')
                            ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime');

                        $alist = $builder->where('g_evaluate_log.evaluating_id',$i->id)
                            ->where('g_evaluate_log.evaluated_id',$data['evaluated_id'])
                            ->where('g_sdcycle.department_id',$i->depid)
                            ->get();

                        foreach($alist as $aa){
                            $list[] = $aa;
                        }
                    }

                    $arr = $this->addname($list);

                    return $arr;
                }

            }

        }else{
            if($data['dtype']==2){
                if($data['depid']!=0){
                    $builder = DB::table('g_evaluate_log')
                        ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                        ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                        ->join('g_sdcycle', 'g_evaluate_log.evaluated_id', '=', 'g_sdcycle.stuid')
                        ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime');

                    if($data['evaluated_id']!=0){
                        $builder = $builder->where('g_evaluate_log.evaluated_id',$data['evaluated_id']);
                    }

                    $list = $builder->where('g_sdcycle.department_id',$data['depid'])
                        ->where('g_evaluate_log.depid',$data['depid'])
                        ->where('g_sdcycle.department_id',$data['depid'])
                        ->where('g_user.type',2)
                        ->get();

                    $arr = $this->addname($list);

                    return $arr;

                }else{

                    if($data['role']!=0){
                        $builder = DB::table('g_evaluate_log')
                            ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                            ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                            ->join('g_sdcycle', 'g_evaluate_log.evaluated_id', '=', 'g_sdcycle.stuid')
                            ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime','g_sdcycle.department_id','g_evaluate_log.depid');

                        $alist = $builder->where('g_evaluate_log.evaluated_id',$data['role'])
                            ->get();

                        $list = [];
                        foreach($alist as $item){
                            if($item->department_id==$item->depid){
                                $list[] = $item;
                            }
                        }

                        $arr = $this->addname($list);

                        return $arr;

                    }else{

                        $info = DB::table('g_evaluate_log')
                            ->join('g_department', 'g_evaluate_log.depid', '=', 'g_department.id')
                            ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                            ->select('g_user.id','g_evaluate_log.depid')
                            ->where('g_department.pid',$data['pid'])
                            ->groupby('g_user.id')
                            ->get();

                        $list = [];
                        foreach($info as $i){
                            $builder = DB::table('g_evaluate_log')
                                ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                                ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                                ->join('g_sdcycle', 'g_evaluate_log.evaluated_id', '=', 'g_sdcycle.stuid')
                                ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime');

                            $alist = $builder->where('g_evaluate_log.evaluating_id',$i->id)
                                ->where('g_sdcycle.department_id',$i->depid)
                                ->get();

                            foreach($alist as $aa){
                                $list[] = $aa;
                            }
                        }

                        $arr = $this->addname($list);

                        return $arr;
                    }
                }

            }else{
                if($data['depid']!=0){
                    $builder = DB::table('g_evaluate_log')
                        ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                        ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                        ->join('g_sdcycle', 'g_evaluate_log.evaluating_id', '=', 'g_sdcycle.stuid')
                        ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime');

                    if($data['evaluated_id']!=0){
                        $builder = $builder->where('g_evaluate_log.evaluated_id',$data['evaluated_id']);
                    }

                    $list = $builder->where('g_sdcycle.department_id',$data['depid'])
                        ->where('g_evaluate_log.depid',$data['depid'])
                        ->where('g_user.type',1)
                        ->get();

                    $arr = $this->addname($list);

                    return $arr;
                }else{
                    if($data['role']!=0){
                        $builder = DB::table('g_evaluate_log')
                            ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                            ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                            ->join('g_sdcycle', 'g_evaluate_log.evaluating_id', '=', 'g_sdcycle.stuid')
                            ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime','g_sdcycle.department_id','g_evaluate_log.depid');

                        $alist = $builder->where('g_evaluate_log.evaluated_id',$data['role'])
                            ->get();

                        $list = [];
                        foreach($alist as $item){
                            if($item->department_id==$item->depid){
                                $list[] = $item;
                            }
                        }

                        $arr = $this->addname($list);

                        return $arr;

                    }else{

                        $info = DB::table('g_evaluate_log')
                            ->join('g_department', 'g_evaluate_log.depid', '=', 'g_department.id')
                            ->select('g_evaluate_log.id','g_evaluate_log.depid')
                            ->where('g_department.pid',$data['pid'])
                            ->get();

                        $list = [];
                        foreach($info as $i){
                            $builder = DB::table('g_evaluate_log')
                                ->join('g_user', 'g_evaluate_log.evaluating_id', '=', 'g_user.id')
                                ->join('g_department', 'g_department.id', '=', 'g_evaluate_log.depid')
                                ->join('g_sdcycle', 'g_evaluate_log.evaluating_id', '=', 'g_sdcycle.stuid')
                                ->select('g_user.name','g_evaluate_log.scores','g_evaluate_log.id','g_department.name as dname','g_sdcycle.plan_starttime as strattime','g_sdcycle.endtime');

                            $alist = $builder->where('g_evaluate_log.id',$i->id)
                                ->where('g_sdcycle.department_id',$i->depid)
                                ->get();

                            foreach($alist as $aa){
                                $list[] = $aa;
                            }
                        }

                        $arr = $this->addname($list);

                        return $arr;
                    }
                }
            }
        }

    }

    //获取评价明细
    public function addname($data)
    {

        foreach($data as &$item){
            $base = DB::table('g_user')
                ->join('g_evaluate_log', 'g_evaluate_log.evaluated_id', '=', 'g_user.id')
                ->select('g_user.name')
                ->where('g_evaluate_log.id',$item->id)
                ->get();
            $item->uname = $base[0]->name;
        }

        return $data;
    }

    //获取评价明细
    public function getDetailBase($data)
    {
        $builder = DB::table('g_evaluate_log')
            ->join('g_evaluate_main', 'g_evaluate_log.main_id', '=', 'g_evaluate_main.id')
            ->join('g_evaluate_type', 'g_evaluate_main.id', '=', 'g_evaluate_type.main_id')
            ->select('g_evaluate_type.id','g_evaluate_type.base');

        $list = $builder->where('g_evaluate_log.id',$data['id'])
            ->where('g_evaluate_main.status',0)->get();

        return $list;
    }

    //获取评价明细
    public function getDetail($data)
    {
        $builder = DB::table('g_evaluate_result')
            ->join('g_evaluate_content', 'g_evaluate_result.content_id', '=', 'g_evaluate_content.id')
            ->join('g_evaluate_type', 'g_evaluate_content.type_id', '=', 'g_evaluate_type.id')
            ->join('g_evaluate_log', 'g_evaluate_result.log_id', '=', 'g_evaluate_log.id')
            ->join('g_user', 'g_evaluate_log.evaluated_id', '=', 'g_user.id')
            ->join('g_evaluate_main', 'g_evaluate_main.id', '=', 'g_evaluate_log.main_id')
            ->select('g_evaluate_content.content','g_evaluate_type.base','g_evaluate_type.id as tid','g_evaluate_result.score','g_user.name','g_evaluate_content.score as scores');

        $list = $builder->where('g_evaluate_result.log_id',$data['id'])
            ->where('g_evaluate_main.status',0)
            ->get();

        return $list;
    }


    //新增评价表
    public function addContent($data)
    {

        DB::table('g_evaluate_main')
            ->where('status', 0)
            ->update([
                'status' => 1
            ]);

        $id = DB::table('g_evaluate_main')->insertGetId([
            'mid' => $data['mid'],
            'department_id' =>  0,
            'type' =>  3
        ]);

        foreach($data as $k=>$item){
            if($k!='mid'&&$k!='token'){
                $base = array();
                $base['type'] = $k;

                $type_id = DB::table('g_evaluate_type')->insertGetId([
                    'main_id' =>  $id,
                    'base' =>  $base['type']
                ]);

                $content = $item['content'];
                $score = $item['score'];
                for($i=0;$i<count($content);$i++){
                    for($j=0;$j<count($score);$j++){
                        if($i==$j){
                            $base['content'] = $content[$i];
                            $base['score'] = $score[$j];

                            DB::table('g_evaluate_content')->insertGetId([
                                'type_id' =>  $type_id,
                                'content' =>  $base['content'],
                                'score'   =>   $base['score']
                            ]);

                        }
                    }
                }
            }
        }

        return $id;
    }


    //删除评价
    public function delEval($data)
    {

        $info = DB::table('g_evaluate_main')
            ->join('g_evaluate_type', 'g_evaluate_main.id', '=', 'g_evaluate_type.main_id')
            ->select('g_evaluate_main.id','g_evaluate_type.id as tid')
            ->where('g_evaluate_main.mid',$data['mid'])
            ->get();

        if($info!=''&&$info!=null){
            foreach($info as $item){
                DB::table('g_evaluate_content')
                    ->where('g_evaluate_content.type_id',$item->tid)
                    ->delete();
            }

            DB::table('g_evaluate_main')
                ->where('g_evaluate_main.id',$info[0]->id)
                ->delete();

            $base = DB::table('g_evaluate_type')
                ->where('g_evaluate_type.main_id',$info[0]->id)
                ->delete();
        }else{
            $base = '';
        }

        return $base;
    }

    //统计分析
    public function statistics($data)
    {
        $builder = DB::table('g_evaluate_result')
            ->join('g_evaluate_log', 'g_evaluate_log.id', '=', 'g_evaluate_result.log_id')
            ->join('g_evaluate_content', 'g_evaluate_content.id', '=', 'g_evaluate_result.content_id')
            ->join('g_evaluate_type', 'g_evaluate_type.id', '=', 'g_evaluate_content.type_id')
            ->select('g_evaluate_type.id', 'g_evaluate_result.score');

        if($data['type']!=3){
            $builder = $builder->where('g_evaluate_log.evaluated_id',$data['id']);

            $list = $builder->where('g_evaluate_log.evaluating_id',$data['role'])
                ->where('g_evaluate_log.depid',$data['depid'])->get();
        }else{
            $list = $builder->where('g_evaluate_log.evaluated_id',$data['role'])
                ->where('g_evaluate_log.depid',$data['depid'])->get();
        }

        return $list;
    }

    //获取评价数量
    public function getLogs($data)
    {
        $builder = DB::table('g_evaluate_log')
            ->select('g_evaluate_log.id');

        if($data['type']!=3){
            $builder = $builder->where('g_evaluate_log.evaluated_id',$data['id']);

            $list = $builder->where('g_evaluate_log.evaluating_id',$data['role'])
                ->where('g_evaluate_log.depid',$data['depid'])->get();
        }else{
            $list = $builder->where('g_evaluate_log.evaluated_id',$data['role'])
                ->where('g_evaluate_log.depid',$data['depid'])->get();
        }

        return $list;
    }


}
?>



