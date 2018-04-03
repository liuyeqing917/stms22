<?php
/**
 * Created by PhpStorm.
 * User: hsiaowei <hsiaowei@misrobot.com>
 * Date: 2016/10/19
 * Time: 16:31
 */

namespace App\Repositories;

use App\Entities\GUser;
use App\Entities\GFourm;
use App\Entities\GFourmFloor;
use App\Entities\GFourmMessage;

class FourmFloorRepository
{

    public function __construct()
    {

    }
    //获取楼层
    public function getlist($fourmid,$page=1,$pageNo=10){
        $dataobj = GFourmFloor::where('fourmid','=',$fourmid)->paginate($pageNo);
        $data['total']=$dataobj->total();
        $data['page']=$dataobj->lastPage();
        $res = $dataobj->items();
        foreach($res as $k=>$v){
            $res[$k]['username'] = $v->user->name;
            $res[$k]['userhead'] = $v->user->imageurl;
            $res[$k]['usersex'] = $v->user->sex;
            $res[$k]->messages;
            foreach( $res[$k]->messages as $key=>$val){
                $res[$k]->messages[$key]['username'] = $val->user->name;
            }
        }
        $data['data']=$res;
        return  $data;
    }
    //回帖
    public function reply($fourmid,$message,$userid){
        $arr=compact('fourmid','message','userid');
        $data = GFourmFloor::create($arr);
        if($data->id){
            //查询当前楼层序号
            return GFourmFloor::where('fourmid','=',$fourmid)->where('id','<=',$data->id)->count('*');
        }
    }
    //回复楼层
    public function replyFloor($floorid,$message,$userid){
        $arr=compact('floorid','message','userid');
        $data = GFourmMessage::create($arr);
        return  $data;
    }
    //删除楼层、回复 $type=1删除楼层 =2删除回复
    public function delete($id,$userid,$type){
        if($type == 1){
            $res = GFourmFloor::find($id);
            $user = GUser::find($userid);
            $fourm = GFourm::find($res->fourmid);//判断是否本人发的贴 具有删除权限
            if( $user->type == 3 || $res->userid == $userid || $fourm->userid=$userid){
                $data = GFourmFloor::find($id);
                if($data){
                    $message = GFourmMessage::where('floorid','=',$id)->get();
                    if($message) {
                        GFourmMessage::where('floorid', '=', $id)->delete();
                    }
                    $data ->delete();
                }
            }else{
                $data=false;
            }
        }elseif($type == 2 ){
            $res = GFourmMessage::find($id);
            $user=GUser::find($userid);
            $fourm = GFourm::find(GFourmFloor::find($res->floorid)->fourmid);//判断是否本人发的贴 具有删除权限
            if( $user->type == 3 || $res->userid == $userid || $fourm->userid=$userid){
                $data = GFourmMessage::find($id);
                if($data){
                    $data ->delete();
                }
            }else{
                $data=false;
            }
        }
        return  $data;
    }
}