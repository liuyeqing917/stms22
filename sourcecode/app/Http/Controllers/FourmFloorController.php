<?php

namespace App\Http\Controllers;


use App\Entities\GFourm;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\FourmFloorRepository;


class FourmFloorController extends CommonController
{

    /*
     *回复贴子
     */
    public function getFloorList(Request $request,FourmFloorRepository $floor)
    {
        $this->validate($request, [
            'fourmid'    => 'required|integer',
            'page'    => 'integer'
        ],[
            'fourmid.required'   => '贴子ID必传',
            'fourmid.integer'   => '贴子ID必须为数字',
            'page.integer'    => '页码必须为数字'
        ]);
        $fourmid = $request->get('fourmid');
        $page = $request->get('page',1);
        $pageNo = $request->get('pageNo',10);
        try{
            $result = $floor->getlist($fourmid,$page,$pageNo);
            if( $result){
                return response()->json($this->success($result, 1, "查询数据成功！"));
            }else{
                return response()->json($this->success([], 0, "回复失败！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }
    /*
     *回复贴子
     */
    public function replyFourm(Request $request,FourmFloorRepository $floor)
    {
        $this->validate($request, [
            'id'    => 'required|integer',
            'message' =>'required'
        ],[
            'id.required'   => 'ID必传',
            'id.integer'   => '贴子ID必须为数字',
            'message.required'   => '回复消息必传'
        ]);
        $fourmid = $request->get('id');
        $message = $request->get('message');
        try{
            $userid=$this->check_token($request->get('token'));
            $result = $floor->reply($fourmid,$message,$userid);
            if( $result){
                return response()->json($this->success($result, 1, "回复成功！"));
            }else{
                return response()->json($this->success([], 0, "回复失败！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }
    /*
     *回复楼层
     */
    public function replyFloor(Request $request,FourmFloorRepository $floor)
    {
        $this->validate($request, [
            'id'    => 'required|integer',
            'message' =>'required'
        ],[
            'id.required'   => 'ID必传',
            'id.integer'   => 'ID必须为数字',
            'message.required'   => '回复消息必传'
        ]);
        $id = $request->get('id');
        $message = $request->get('message');
        try{
            $userid=$this->check_token($request->get('token'));
            $result = $floor->replyFloor($id,$message,$userid);
            if( $result){
                return response()->json($this->success($result, 1, "回复成功！"));
            }else{
                return response()->json($this->success([], 0, "回复失败！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }
    /*
     *删除楼层
     */
    public function deleteFloor(Request $request,FourmFloorRepository $floor)
    {
        $this->validate($request, [
            'id'    => 'required|integer',
        ],[
            'id.required'   => 'ID必传',
            'id.integer'   => 'ID必须为数字',
        ]);
        $id = $request->get('id');
        try{
            $userid=$this->check_token($request->get('token'));
            $result = $floor->delete($id,$userid,1);
            if( $result){
                return response()->json($this->success([], 1, "删除回复成功！"));
            }else{
                return response()->json($this->success([], 0, "删除回复失败！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }
    /*
     *楼层消息回复
     */
    public function deleteMessage(Request $request,FourmFloorRepository $floor)
    {
        $this->validate($request, [
            'id'    => 'required|integer',
        ],[
            'id.required'   => 'ID必传',
            'id.integer'   => 'ID必须为数字',
        ]);
        $id = $request->get('id');
        try{
            $userid=$this->check_token($request->get('token'));
            $result = $floor->delete($id,$userid,2);
            if( $result){
                return response()->json($this->success([], 1, "删除回复成功！"));
            }else{
                return response()->json($this->success([], 0, "删除回复失败！"));
            }

        }
        catch (\Exception $ex){
            return response()->json($this->fail($ex));
        }

    }



}
