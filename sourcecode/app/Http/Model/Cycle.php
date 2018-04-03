<?php
//轮表管理模型
//author fandian
namespace App\Http\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class Cycle extends Model
{
    protected $table='g_mcycle';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
    public $students = [];
    public $rooms = [];
    public $student = '';
    public $room = '';
    public $time = 0;

//$date = date('Y-m-d');
//$time = strtotime($date);


private function getstudent(){
    foreach($this->students as &$student){
        $student['room'] = [];
        $student['week'] = [];
    }
}
private function getroom(){

    foreach($this->rooms as &$room){
        $room['students'] = [];
    }
}

public function allDone(){
    $isDone = true;
    $room_ids = array_keys($this->rooms);
    foreach($this->students as $student){
        if( !isset($student['room']) || array_diff($room_ids, $student['room']) ){
            $isDone = false;
            break;
        }
    }
    return $isDone;
}
    public function kqcycle($data){
            $btime = $data["btime"];
            $etime = $data["etime"];
            $department_id = $data['department_id'];
            $type = $data['type'];
          if(($type ==2 )&&($btime == 0)&&($etime == 0)){
              $arr = DB::table('g_sdcycle')
                  ->rightJoin('g_user', 'g_user.id', '=', 'g_sdcycle.stuid')
                  ->select('g_sdcycle.*', 'g_user.name')
                  ->where('g_sdcycle.department_id', $department_id)
                  ->where('g_user.finish',0)
                  ->get();
          }elseif(($type ==3 )&&($btime == 0)&&($etime == 0)) {
              $arr = DB::table('g_sdcycle')
                  ->rightJoin('g_user', 'g_user.id', '=', 'g_sdcycle.stuid')
                  ->select('g_sdcycle.*', 'g_user.name')
                  ->where('g_sdcycle.department_id', $department_id)
                  ->get();
          }elseif(($type ==2 )&&(($btime <> 0)||($etime <> 0))){
              $arr = DB::table('g_sdcycle')
                  ->rightJoin('g_user', 'g_user.id', '=', 'g_sdcycle.stuid')
                  ->select('g_sdcycle.*', 'g_user.name')
                  ->where('g_sdcycle.department_id', $department_id)
                  ->where('g_sdcycle.plan_starttime', '>=', $btime)
                  ->where('g_sdcycle.plan_endtime', '<=', $etime)
                  ->where('g_user.finish',0)
                  ->get();
          } elseif(($type ==3 )&&(($btime <> 0)||($etime <> 0))){
              $arr = DB::table('g_sdcycle')
                  ->rightJoin('g_user', 'g_user.id', '=', 'g_sdcycle.stuid')
                  ->select('g_sdcycle.*', 'g_user.name')
                  ->where('g_sdcycle.department_id', $department_id)
                  ->where('g_sdcycle.plan_starttime', '>=', $btime)
                  ->where('g_sdcycle.plan_endtime', '<=', $etime)
                  ->get();
          }
        return $arr;
    }

public function parse_week2time($weeks,$time=""){
    $data['starttime'] = $this->time+($weeks[0]-1)*7*24*3600;
    $data['endtime'] = $this->time+end($weeks)*7*24*3600;
    return $data;
}
public function docycle($time,$students,$rooms){
    $this->students = $students;
    $this->rooms = $rooms;
    $this->time = $time;
    $this->getstudent();
    $this->getroom();
    $bz = $_POST["bz"];
    $sdcycle_data = array();
    $mcycle_data = [
        'name' => '方案' . date('YmdHis'),
        'starttime' => $this->time,
    ];
   //限制生成轮转
    $endtime8 = DB::table('g_mcycle')->max('endtime');
    $xztime = time();
    if(!empty($endtime8)&&($xztime<$endtime8)){
        $data8 = [
            'status' => 1,
            'msg' => "只有等此次轮转结束才可以重新生成新的轮转信息",
            'no'=>'notallow'
        ];
        echo json_encode($data8, JSON_UNESCAPED_UNICODE);
        exit;
    }
    //查看一下在下一次轮转之前，是不是还有学生没有出科，可以通过g_sdcycle中endtime是否为零
    $myqk = DB::table('g_sdcycle')->where('endtime',0)->count();
    if($myqk>0){
        $data8 = [
            'status' => 1,
            'msg' => "上次轮转中，还有学生没有出科，只有所有学生全部出科啦，才可以重新生成新的轮转信息",
            'no'=>'notallow'
        ];
        echo json_encode($data8, JSON_UNESCAPED_UNICODE);
        exit;
    }
    //科室主导
    $student_ids = array_keys($this->students);
    $i = 1;
    $loop = true;
    $stuRoom = [];
    while ($loop) {
        foreach ($this->rooms as &$room) {
            $end_week = $i + $room['weeks'] - 1;
            $period_index = $i . '-' . $end_week;

            //当前科室 本周期人数已满 或 所有学生都已加入过
            if (isset($room['period'][$period_index]) && count($room['period'][$period_index]) == $room['people'] || !array_diff($student_ids, $room['students'])) {
                continue;
            }

            $perriod_week_arr = range($i, $end_week);

            //科室加学生
            foreach ($this->students as &$student) {
                //当前科室人员满了就直接中断循环
                if (isset($room['period'][$period_index]) && count($room['period'][$period_index]) == $room['people']) break;
                //学生本周没空则跳出循环
                if (in_array($i, $student['week']) || in_array($student['id'], $room['students'])) continue;
                //如果过了本科室的最近的周期时间 则跳出循环。去掉本段代码可实现插队式加入
                if(isset($room['period'])){
                if (count($room['period']) > 0) {
                    $period_keys = array_keys($room['period']);
                    list($period_begin, $period_end) = explode('-', end($period_keys));
                    if ($period_begin < $i && $period_end >= $i) continue;
                }
                }

                $room['students'][] = $student['id'];
                $room['period'][$period_index][] = $student['name'];

                $stuRoom[$student['id'] . '-' . $room['id']] = $perriod_week_arr;
                $student['room'][] = $room['id'];
                $student['week'] = array_merge($student['week'], $perriod_week_arr);
            }
        }
        if ($this->allDone())
            $loop = false;
        else
            $i++;
    }
    if($bz==0){
        $rms = $this->rooms;
        $arr5 = array();
        foreach ($this->students as $std) {
            $f=-1;
            foreach ($rooms as $v) {
                $f++;
                $week_tmp = isset($stuRoom[$std['id'] . '-' . $v['id']]) ? $this->parse_week2time($stuRoom[$std['id'] . '-' . $v['id']],$this->time) : '';
                $arr5[$std['name']]["time"][$f]= date('Y-m-d', $week_tmp['starttime']) . " - " . date('Y-m-d', $week_tmp['endtime']) ;
            }
        }
        $status = 1;
        $arr8 = compact('rms','arr5','status');

      // print_r($arr8);
        echo  json_encode($arr8, JSON_UNESCAPED_UNICODE);
        //return Response::json($arr8);

    }else {
        $mcycle_data['endtime'] = 0;

        //$mid = $DB->insert('mcycle', $mcycle_data);
        //插入到轮转主表中
        $mid = DB::table('g_mcycle')->insertGetId($mcycle_data);

        $html = '<table border="1" align="center"><thead><tr><th>姓名</th>';

        foreach ($this->rooms as $v) {
            $html .= "<th>{$v['name']} ({$v['weeks']}周 {$v['people']}人)</th>";
        }
        $html .= '</tr></thead>';

        foreach ($this->students as $std) {
            $sdcycle_item_data['mid'] = $mid;
            $sdcycle_item_data['stuid'] = $std['id'];

            $html .= "<tr>";
            $html .= "<td>{$std['name']}</td>";
            foreach ($rooms as $v) {
                $week_tmp = isset($stuRoom[$std['id'] . '-' . $v['id']]) ? $this->parse_week2time($stuRoom[$std['id'] . '-' . $v['id']],$this->time) : '';
                $html .= "<td>" . date('Y-m-d', $week_tmp['starttime']) . " - " . date('Y-m-d', $week_tmp['endtime']) . "</td>";

                $sdcycle_item_data['department_id'] = $v['id'];
                $sdcycle_item_data['plan_starttime'] = $week_tmp['starttime'];
                $sdcycle_item_data['plan_endtime'] = $week_tmp['endtime'];
                //$sdcycle_data[] = $sdcycle_item_data;
                //插入学生与科室轮转关系表
                DB::table('g_sdcycle')->insertGetId($sdcycle_item_data);
            }
            $html .= "</tr>";
        }
        $html .= '</table>';

       // echo $html;

        //$DB->insert('sdcycle', $sdcycle_data);

       //创建科室轮转记录表，将学生科室轮转关系表中最小的计划开始时间和最大的计划结束时间拷贝到该记录表中
        DB::statement("REPLACE INTO g_kcycle(mid,department_id,plan_starttime,plan_endtime) SELECT mid,department_id,MIN(plan_starttime) plan_starttime, MAX(plan_endtime) plan_endtime FROM `g_sdcycle` WHERE mid={$mid} GROUP BY department_id");
        //找到最大的计划出科时间
        $maxtime = DB::table('g_sdcycle')->where('mid',$mid)->max('plan_endtime');
        //更新轮转主表里面的最后时间
        DB::table('g_mcycle')->where('id', $mid)->update(['endtime' => $maxtime]);
         $data = [
            'status' => 1,
            'msg' => "成功生成轮转表",
        ];
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}





}
?>



