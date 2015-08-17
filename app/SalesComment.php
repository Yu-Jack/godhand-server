<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesComment extends Model
{
    protected $table = 'sales_comments';

    public function user(){
    	return $this->hasMany('App\User', 'id', 'user_id');
    }
}
