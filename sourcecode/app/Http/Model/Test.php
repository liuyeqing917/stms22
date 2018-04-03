<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Test extends Model
{
    protected $table='g_check';
    protected $primaryKey='id';
    public $timestamps=false;

    //新增答卷
    public function addTest($data)
    {
        $id = DB::table('g_test')->insertGetId([
            'depid'          =>  $data['depid'],
            'name'           =>  $data['name'],
            'ctime'           =>  time()
        ]);

        return $id;
    }

    //新增题目
    public function addContent($data)
    {

        $id = DB::table('g_test_content')->insertGetId([
            'department_id' =>  $data['depid'],
            'type'           =>  $data['type'],
            'answer'          =>   $data['answer'],
            'poins'           =>  $data['poins'],
            'question'          =>   $data['question'],
            'content'          =>   $data['content'],
            'pbase'           =>  $data['pbase'],
            'base'          =>   $data['base'],
            'cognition'           =>  $data['cognition'],
            'source'          =>   $data['source'],
            'lv'          =>   $data['lv'],
            'require'           =>  $data['require'],
            'times'          =>   $data['times'],
            'degree'           =>  $data['degree'],
            'separate'          =>   $data['separate']
        ]);

        DB::table('test_content')->insertGetId([
            'tid'            =>  $data['tid'],
            'cid'            =>  $id
        ]);

        return $id;
    }

    //选择试题
    public function getChoose($data)
    {

        $builder = DB::table('g_test')
            ->join('g_department','g_department.id','=','g_test.depid')
            ->select('g_department.name as dname','g_test.id','g_test.name','g_test.depid');

        if($data['father_id']!=0){
            if($data['son_id']!=0){

                $builder = $builder->where('g_test.depid',$data['son_id']);

            }else{
                $builder = $builder->where('g_department.pid',$data['father_id']);
            }

        }else{
            if($data['son_id']!=0){
                $builder = $builder->where('g_test.depid',$data['son_id']);
            }

        }

        $info = $builder->get();

        return $info;

    }

    //选择教室
    public function getClassroom()
    {

        $info = DB::table('g_classroom')->get();

        return $info;

    }


    //选择教室
    public function del($data)
    {

        $base = DB::table('g_test_log')
            ->where('g_test_log.tid',$data['id'])
            ->get();

        $arr = array();
        if($base!=''&&$base!=null){
            $arr['log'] = 1;
            return $arr;
        }
        DB::table('g_test')
            ->where('g_test.id',$data['id'])
            ->delete();

        $info = DB::table('test_content')
            ->where('test_content.tid',$data['id'])
            ->get();

        DB::table('test_content')
            ->where('test_content.tid',$data['id'])
            ->delete();
        foreach($info as $item){
            $count =  DB::table('g_test_content')
                ->where('g_test_content.id',$item->cid)
                ->delete();
        }

        $arr['count'] = $count;
        return $count;

    }



}


