<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


class GUser extends Model {

    protected $table 		= 	'g_user';
    public 	  $timestamps	=	true;
    protected $primaryKey	=	'id';
    public 	  $incrementing	=	true;
    protected $guarded 		= 	[];
    protected $hidden 		= 	[];
    //protected $fillable 	=	['qid','schedule_id','sg_id','type','deadline'];
}