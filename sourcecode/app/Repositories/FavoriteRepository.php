<?php
/**
 * Created by PhpStorm.
 * User: hsiaowei <hsiaowei@misrobot.com>
 * Date: 2016/11/1
 * Time: 17:01
 */

namespace App\Repositories;

use App\Entities\GUser;
use App\Entities\GFavorite;

class FavoriteRepository
{
    public function getList($userid){
        $data = GFavorite::where('userid','=',$userid)->get();
        return $data;
    }
    public function edit($arr,$id=null){
        if($id){
            $data = GFavorite::where('id','=',$id)->update($arr);
        }else{
            $data = GFavorite::create($arr);
        }
        return $data;
    }
    public function delete($id ,$userid){
        $data=false;
        if($id && $userid ) {
            $res = GFavorite::where('id', '=', $id)->first();
            if($res->userid == $userid){
                $data = $res->delete();
            }
        }
        return $data;
    }

}