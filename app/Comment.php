<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    // public $timestamps = false;

    public function user(){
    	return $this->hasMany('App\User', 'id', 'user_id');
    }
}
