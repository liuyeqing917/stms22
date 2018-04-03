<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Depart extends Model
{
    protected $table='g_department';
    protected $primaryKey='id';
    public $timestamps=false;

    //查询父科室信息
    public function searchFatherDepart($id)
    {
        $builder = DB::table('g_department')
            ->where('g_department.pid',$id)
            ->get();

        return $builder;
    }


    //查询子科室信息
    public function searchChildDepart($id)
    {
        $builder = DB::table('g_department');
        if($id==0){

            $builder=$builder->where('g_department.pid','>',0)
                ->get();
        }else{
            $builder=$builder->where('g_department.pid',$id)
                ->get();
        }

        return $builder;
    }

    //查询当前科室信息
    public function searchModelDepart($id)
    {
        $builder = DB::table('g_department')
            ->where('g_department.id',$id)
            ->get();


        return $builder;
    }

        //新增科室
    public function adddepart($data)
    {

        $result= DB::table('g_department')
            ->where('g_department.name',$data['name'])
            ->get();

        if($result){
            $result['code']=2;
            $result['msg']='新增失败，科室名字已存在';
            return $result;
        }

        $builder = DB::table('g_department')->insertGetId([
            'name' =>  $data['name'],
            'pid' =>  $data['pid'],
            'hospital_id' =>  $data['hospital_id'],
            'teacher_ids' =>  $data['teacher_ids'],
            'teacher_id' =>  $data['teacher_id'],

        ]);

        if($builder){
            $result['code']=1;
            $result['msg']='新增成功';
            return $result;

        }
        $result['code']=0;
        $result['msg']='新增成功';
        return $result;

    }
    //更新科室
    public function updatedepart($data)
    {

        $result= DB::table('g_department')
            ->where('g_department.name',$data['name'])
            ->where('g_department.id','<>',$data['id'])
            ->get();

        if($result){
            $result['code']=2;
            $result['msg']='修改失败，科室名字已存在';
            return $result;
        }

        $builder = DB::table('g_department')->
            where('g_department.id',$data['id'])->
              update([
                    'name' =>  $data['name'],
                    'pid' =>  $data['pid'],
                    'hospital_id' =>  $data['hospital_id'],
                    'teacher_ids' =>  $data['teacher_ids'],
                    'teacher_id' =>  $data['teacher_id'],

                ]);

        if($builder){
            $result['code']=1;
            $result['msg']='修改成功';
            return $result;

        }
        $result['code']=0;
        $result['msg']='修改失败';
        return $result;

    }

    //删除科室
    public function deletedepart($data)
    {
        $depart = DB::table('g_department')
            ->where('g_department.id',$data['id'])->first();

        if($depart->pid==0){

            $departs=DB::table('g_department')
                ->where('g_department.pid',$depart->id)->get();

            if(!empty($departs)){
                $result['code']=2;
                $result['msg']='删除失败,请先删除其子科室';
                return $result;
            }
        }

        $_departs=DB::table('g_department')
            ->where('g_department.pid',$depart->id)->get();

        if(count($_departs)==1){
         DB::table('g_department')
                ->where('g_department.id',$depart->id)->delete();
        }

        $builder = DB::table('g_department')
            ->where('g_department.id',$data['id'])->delete();

        if($builder){
            $result['code']=1;
            $result['msg']='删除成功';
            return $result;

        }
        $result['code']=0;
        $result['msg']='删除失败';
        return $result;

    }



    //查询科室信息科室
    public function searchdepart($data)
    {
        $builder = DB::table('g_department');
        if($data['type']==1){
            $builder=$builder->where('g_department.pid','>',0);
        }

        if($data['pid']==0){
            if($data['department_id']==0){

                $builder=$builder->get();

                return $builder;
            }else{
                $builder=$builder->where('g_department.id',$data['department_id'])
                    ->get();

                return $builder;
            }
        }else{

            if($data['department_id']==0){
                $builder=$builder->where('g_department.id',$data['pid'])
                    ->get();

                return $builder;
            }else{

                $builder=$builder->where('g_department.id',$data['department_id'])
                    ->get();

                return $builder;
            }

        }

    }

    public function searchdepartlist($data)
    {

        $builder = DB::table('g_department');
        if($data['type']==1){
            $builder=$builder->where('g_department.pid','>',0);
        }

        if($data['pid']==0){

                $builder=$builder->get();

                return $builder;

        }else{
                $builder=$builder->where('g_department.pid',$data['pid'])
                    ->get();

                return $builder;

        }


    }





}


