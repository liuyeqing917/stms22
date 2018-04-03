<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


class GFourmMessage extends Model {

    protected $table 		= 	'g_fourm_floor_message';
    public 	  $timestamps	=	true;
    protected $primaryKey	=	'id';
    public 	  $incrementing	=	true;
    protected $guarded 		= 	[];
    protected $hidden 		= 	['user'];
    //protected $fillable 	=	[];

    /**
     * 关联用户
     */
    public function user(){

        return $this->hasOne('App\Entities\GUser','id','userid');

    }
}