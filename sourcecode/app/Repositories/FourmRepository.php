<?php
/**
 * Created by PhpStorm.
 * User: hsiaowei <hsiaowei@misrobot.com>
 * Date: 2016/10/14
 * Time: 16:31
 */

namespace App\Repositories;

use App\Entities\GUser;
use App\Entities\GFourm;
use App\Entities\GFourmFloor;
use App\Entities\GFourmMessage;
use DB;

class FourmRepository
{

    //获取论坛贴子列表
    public function getlist($page=1,$limit=10,$typeid=0){
        if($typeid){
            //->orderBy('id', 'desc') ;
            $dataobj = GFourm::where('typeid','=',$typeid)->orderBy('updated_at', 'desc')->paginate($limit);
        }else{
            $dataobj = GFourm::orderBy('updated_at', 'desc')->paginate($limit) ;
        }
        $data['total']=$dataobj->total();
        $data['page']=$dataobj->lastPage();
        $res = $dataobj->items();
        foreach($res as $k=>$v){
            $res[$k]['replytotal'] = count($res[$k]->floor);
            $res[$k]['username'] = $v->user->name;
            $res[$k]['typename'] = $v->type->name;
        }
        $data['data']=$res;
        return  $data;
    }
    //获取论坛贴子列表
    public function getdetail( $id ){
        $data = GFourm::find($id);
        if($data){
            $data['username'] = $data->user->name;
            $data['userhead'] =$data->user->imageurl;
            $data['usersex'] = $data->user->sex;
            $data['typename'] = $data->type->name;
        }
        return  $data;

    }
    //增加贴子浏览量
    public function addfourmTotal($id){
        /*$res= GFourm::find($id);
        $res->total++;
        $data = $res->save();*/
        $users = DB::table('g_fourm')->find($id);
        if($users){
            $data =  DB::table('g_fourm')->where('id',$id)->update(['total' => ++$users->total]);
        }

        return  $data;
    }
    //新增修改论坛分类
    public function editFourm($arr,$id = null){
        if($id){
            $data = GFourm::find($id);
            if($data){
                $data->update($arr);
            }
        }else{
            $data = GFourm::create($arr);
        }
        return  $data;
    }
    //删除论坛
    public function deleteFourm($id,$userid){
        $res = GFourm::find($id);
        $user=GUser::find($userid);
        if( $user->type == 3 || $res->userid == $userid){
            $data = GFourm::find($id);
            if($data){
                $data->delete();
                $floor = GFourmFloor::where('fourmid','=',$id)->get();
                if($floor){
                    foreach($floor as $v){
                        $message = GFourmMessage::where('floorid','=',$v['id'])->get();
                        if($message) {
                            GFourmMessage::where('floorid', '=', $v['id'])->delete();
                        }
                    }
                    GFourmFloor::where('fourmid','=',$id)->delete();
                }
            }
        }else{
            $data=false;
        }
        return  $data;
    }
}