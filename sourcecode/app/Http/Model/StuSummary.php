<?php
//学生出科小结管理模型
//author fandian
namespace App\Http\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class StuSummary extends Model
{
    protected $table='g_stu_summary';
    protected $primaryKey='id';
    public $timestamps=false;
    protected $guarded=[];
    public $time = 0;
}
?>



