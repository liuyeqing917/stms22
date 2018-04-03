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
use App\Entities\GFourmType;

class FourmTypeRepository
//class FourmRepository extends Repository
{

    public function __construct()
    {

    }
    //获取论坛分类
    public function getType(){
        $data = GFourmType::where('isshow','=',1)->get();
        return  $data;
    }
    //新增修改论坛分类
    public function editType($userid,$name,$id = null){
        $user=GUser::find($userid);
        if( 3 == $user->type){
            if($id){
                $data = GFourmType::find($id)->update(['name'=>$name]);
            }else{
                $isexist = GFourmType::where('name','=',$name)->first();
                if($isexist){
                    $data = GFourmType::where('id','=',$isexist->id)->update(['isshow'=>1]);
                }else{
                    $data = GFourmType::create(['name'=>$name]);
                }
            }
        }else{
            $data=null;
        }
        return  $data;
    }
    //删除论坛分类
    public function deleteType($id,$userid){
        $user=GUser::find($userid);
        if( 3 == $user->type && $id){
            $data = GFourmType::find($id);
            if($data){
                $data->update(['isshow'=>0]);
            }
        }else{
            $data=null;
        }
        return  $data;
    }
}