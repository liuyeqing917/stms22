<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


class GFourm extends Model {

    protected $table 		= 	'g_fourm';
    public 	  $timestamps	=	true;
    protected $primaryKey	=	'id';
    public 	  $incrementing	=	true;
    protected $guarded 		= 	[];
    protected $hidden 		= 	['user','type','floor'];
    //protected $fillable 	=	['qid','schedule_id','sg_id','type','deadline'];

    /**
     * 关联用户
     */
    public function user(){

        return $this->hasOne('App\Entities\GUser','id','userid');

    }
    /**
     * 关联主题
     */
    public function type(){

        return $this->hasOne('App\Entities\GFourmType','id','typeid');

    }
    /**
     * 关联楼层
     */
    public function floor(){

        return $this->hasMany('App\Entities\GFourmFloor','fourmid','id');

    }
}