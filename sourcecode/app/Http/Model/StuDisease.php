<?php
//学生课程病例管理模型
//author fandian
namespace App\Http\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class StuDisease extends Model
{
    protected $table='g_stu_disease';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
    public $time = 0;
}
?>



