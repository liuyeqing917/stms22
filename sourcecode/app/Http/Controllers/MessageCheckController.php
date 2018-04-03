<?php

namespace App\Http\Controllers;

use App\Http\Model\MessageCheck;
use App\Http\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
class MessageCheckController extends CommonController
{
    public function messageCheck(Request $request){
        $data = $request->only('userId','type','page','pagecount');
        $message= new MessageCheck();
        $type = $data['type'];

        $page = $data['page'];

        $pagecount = $data['pagecount'];

        $userdata = $message-> searchTeachernews($data['userId']);

        $returninfo = $message->selectMessage($type,$userdata[0]->department_id);

        $info = $this->paginationWay($returninfo,$page,$pagecount);

        return $info;
    }
    public function messageContent(){
        $id = Input::get("id");
        $data = new MessageCheck();
        $content = $data->selectContent($id);
        return $content;
    }

    //消息通知

    public function messageinform(Request $request){

        $data = $request->only('userid','type','page','pagecount');

        $page = $data['page'];

        $pagecount = $data['pagecount'];

        $message = new MessageCheck();

        $returninfo= [];
        //查学生的消息
        if($data['type']==1){

            //出科信息
            $chukeinfo = $message->searchchuketime($data['userid']);

            for($i=0;$i<count($chukeinfo);$i++){
                $returninfo[] = [
                    'stuname'  => $chukeinfo[$i]->stuname,
                    'asdepartname'  => $chukeinfo[$i]->departname,
                    'newstype'  => 1
                ];

            }

            $rukeinfo = $message->searchruketime($data['userid']);

            for($k=0;$k<count($rukeinfo);$k++){
                $returninfo[] = [
                    'stuname'  => $rukeinfo[$k]->stuname,
                    'asdepartname'  => $rukeinfo[$k]->departname,
                    'newstype'  => 2
                ];

            }

            $examinfo = $message->searchexaminfo($data['userid']);

            for($j=0;$j<count($examinfo);$j++){
                $returninfo[] = [
                    'stuname'  => $examinfo[$j]->stuname,
                    'asdepartname'  => $examinfo[$j]->departname,
                    'start'  => $examinfo[$j]->start,
                    'end'  => $examinfo[$j]->end,
                    'classrom'  => $examinfo[$j]->classroom,
                    'examtype'  => $examinfo[$j]->examtype,
                    'newstype'  => 3
                ];

            }

            ksort($returninfo) ;


        }elseif($data['type']==2){

            $returninfo = $message->searchteacherexaminfo($data['userid']);

        }

        $info = $this->paginationWay($returninfo,$page,$pagecount);

        return $info;

    }

}
