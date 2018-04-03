<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\FavoriteRepository;

class FavoriteController extends CommonController
{
    /*
     *
     */
    //查看列表
    public function getList(Request $request,FavoriteRepository $favorite){

        $userid=$this->check_token($request->get('token'));
        try {
            $result = $favorite->getList($userid);
            if ($result) {
                return response()->json($this->success($result , 1, "查询数据成功！"));
            }else{
                return response()->json($this->success([], 0, "查询数据失败！"));
            }
        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }
    //新增和修改
    public function edit(Request $request,FavoriteRepository $favorite){

        $this->validate($request, [
            'name'    => 'required|max:20',
            'url'     =>'required'
        ],[
            'name.required'   => '名称必传',
            'name.max'   => '名字长度小于20',
            'url.required'   =>'链接必传'
        ]);
        $name = $request->get('name');
        $url = $request->get('url');
        $id = $request->get('id');
        try {
            $userid = $this->check_token($request->get('token'));
            $str = $id ? '修改' : '新增';
            $Arr=compact('userid','name','url');
            $result = $favorite->edit($Arr, $id);
            if ($result) {
                return response()->json($this->success($id ? []: $result, 1, $str . "数据成功！"));
            }else{
                return response()->json($this->success([], 0, $str."数据失败！"));
            }
        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }
    }
    //新增和修改
    public function delete(Request $request,FavoriteRepository $favorite){
        $this->validate($request, [
            'id'    => 'required|integer'
        ],[
            'id.required'   => 'ID必传',
            'id.integer'   => 'ID必须为数字'
        ]);
        $id = $request->get('id');
        try {
            $userid = $this->check_token($request->get('token'));
            $result = $favorite->delete($id, $userid);
            if ($result) {
                return response()->json($this->success([], 1,  "删除数据成功！"));
            }else{
                return response()->json($this->success([], 0, "删除数据失败！"));
            }
        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }
    }

}
