<?php
//大钢课程管理模型
//author fandian
namespace App\Http\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class CourseMain extends Model
{
    protected $table='g_course_main';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
    public $time = 0;
}
?>



