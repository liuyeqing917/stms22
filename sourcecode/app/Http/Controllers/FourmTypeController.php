<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\FourmTypeRepository;

class FourmTypeController extends CommonController
{

    /*
     *
     */
    //查看
    public function getType(Request $request,FourmTypeRepository $type)
    {
        $result=$type->getType();
        return response()->json($this->success($result, 1, "获取主题成功！"));
    }
    //新增和修改
    public function editType(Request $request,FourmTypeRepository $type)
    {
        $this->validate($request, [
            'name'    => 'required|max:10'
        ],[
            'name.required'   => '类型名称必传',
            'name.max'   => '类型长度小于10',
        ]);
        $name = $request->get('name');
        $id = $request->get('id');
        try {
            $userid = $this->check_token($request->get('token'));
            $str = $id ? '修改' : '新增';
            $result = $type->editType($userid, $name, $id);
            if ($result) {
                return response()->json($this->success([], 1, $str . "数据成功！"));
            }else{
                return response()->json($this->success([], 0, $str."数据失败或权限不足！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }
    //删除主题
    public function deleteType(Request $request,FourmTypeRepository $type)
    {
        $this->validate($request, [
            'id'    => 'required|integer'
        ],[
            'id.required'   => '类型ID必传',
            'id.integer'   => 'ID必须为数字',
        ]);
        $id = $request->get('id');
        try{
            $userid=$this->check_token($request->get('token'));
            $result = $type->deleteType($id,$userid);
            if( $result) {
                return response()->json($this->success([], 1, "数据删除成功！"));
            }else{
                return response()->json($this->success([], 0, "数据删除失败或权限不足！"));
            }
        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }


}
