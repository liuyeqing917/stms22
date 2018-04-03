<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


class GFourmType extends Model {

    protected $table 		= 	'g_fourm_type';
    public 	  $timestamps	=	true;
    protected $primaryKey	=	'id';
    public 	  $incrementing	=	true;
    protected $guarded 		= 	[];
    protected $hidden 		= 	[];
    protected $fillable 	=	['id','name','isshow','created_at'];

    /**
     *
     */
    /*public function user(){

        return $this->hasOne('App\Entities\User','id','userid');

    }*/
}