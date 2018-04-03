<?php

namespace App\Http\Controllers;


use App\Entities\GFourm;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\FourmRepository;
use App\Repositories\FourmFloorRepository;


class FourmController extends CommonController
{

    /*
     *获取贴子列表
     */
    public function getFourmList(Request $request,FourmRepository $fourm)
    {
        $this->validate($request, [
            'page'    => 'required|integer'
        ],[
            'page.required'   => '页码必传',
            'page.integer'    => '页码必须为数字'
        ]);
        $page = $request->get('page');
        $pageNo = $request->get('pageNo',10);
        $typeid = $request->get('typeid');
        try{
            $result = $fourm->getlist($page,$pageNo,$typeid);

            if( $result){
                return response()->json($this->success($result, 1, "查询数据成功！"));
            }else{
                return response()->json($this->success([], 0, "查询失败！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }
    /*
     *获取贴子详情
     */
    public function getFourmDetail(Request $request,FourmRepository $fourm,FourmFloorRepository $floor)
    {
        $this->validate($request, [
            'id'    => 'required|integer'
        ],[
            'id.required'   => 'ID必传',
        ]);
        $id = $request->get('id');
        try{
            $result = $fourm->getdetail($id);
            $result['floor'] = $floor->getlist($id);
            if( $result ){
                $fourm->addfourmTotal($id);
                return response()->json($this->success($result, 1, "查询数据成功！"));
            }else{
                return response()->json($this->success([], 0, "查询失败！"));
            }
        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }
    }
    public function editFourm(Request $request,FourmRepository $fourm)
    {
        $this->validate($request, [
            'title'    => 'required',
            'content'    => 'required',
            'typeid'  => 'required|integer',
            'id'    => 'integer'
        ],[
            'title.required'   => '贴子标题必传',
            'content.required'   => '贴子内容必传',
            'typeid.required'   => '贴子类型必传',
            'id.integer'   => 'ID必须为数字',
            'typeid.integer'   => '类型ID必须为数字'
        ]);

        $id = $request->get('id');
        $title = $request->get('title');
        $desc = $request->get('desc');
        $content = $request->get('content');
        $typeid = $request->get('typeid');
        try{
            $userid=$this->check_token($request->get('token'));
            $arr=compact('title','desc','content','typeid','userid');
            //dd($arr);
            $result = $fourm->editFourm($arr,$id);
            $str=$id?'修改':'新增';
            if( $result){
                return response()->json($this->success([], 1, $str."数据成功！"));
            }else{
                return response()->json($this->success([], 0, $str."数据失败！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }
    }
    public function deleteFourm(Request $request,FourmRepository $fourm)
    {
        $this->validate($request, [
            'id'    => 'required|integer'
        ],[
            'id.required'   => 'ID必传',
             'id.integer'   => 'ID必须为数字'
        ]);
        $id = $request->get('id');
        try{
            $userid=$this->check_token($request->get('token'));
            $result = $fourm->deleteFourm($id,$userid);
            if( $result){
                return response()->json($this->success([], 1, "删除数据成功！"));
            }else{
                return response()->json($this->success([], 0, "删除数据失败或权限不足！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }
    }
    /*
    *增加贴子浏览量
    */
    public function addFourmTolal(Request $request,FourmRepository $fourm)
    {
        $this->validate($request, [
            'id'    => 'required|integer'
        ],[
            'id.required'   => 'ID必传',
            'id.integer'   => 'ID必须为数字',
        ]);
        $id = $request->get('id');
        try{
            $result = $fourm->addfourmTotal($id);
            if( $result){
                return response()->json($this->success([], 1, "数据更新成功！"));
            }else{
                return response()->json($this->success([], 0, "数据更新失败！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }



    }
