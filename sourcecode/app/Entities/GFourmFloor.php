<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


class GFourmFloor extends Model {

    protected $table 		= 	'g_fourm_floor';
    public 	  $timestamps	=	true;
    protected $primaryKey	=	'id';
    public 	  $incrementing	=	true;
    protected $guarded 		= 	[];
    protected $hidden 		= 	['user'];
    //protected $touches = ['GFourm'];
    //protected $fillable 	=	['qid','schedule_id','sg_id','type','deadline'];

    /**
     * 关联用户
     */
    public function user(){

        return $this->hasOne('App\Entities\GUser','id','userid');

    }
    /**
     * 关联回复
     */
    public function messages(){

        return $this->hasMany('App\Entities\GFourmMessage','floorid','id')->orderBy('id', 'asc');

    }
}