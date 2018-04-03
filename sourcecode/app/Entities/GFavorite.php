<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


class GFavorite extends Model {

    protected $table 		= 	'g_favorite';
    public 	  $timestamps	=	true;
    protected $primaryKey	=	'id';
    public 	  $incrementing	=	true;
    protected $guarded 		= 	[];
    protected $hidden 		= 	['user'];
    //protected $fillable 	=	['qid','schedule_id','sg_id','type','deadline'];

    /**
     * 关联用户
     */
    public function user(){

        return $this->hasOne('App\Entities\GUser','id','userid');

    }

}