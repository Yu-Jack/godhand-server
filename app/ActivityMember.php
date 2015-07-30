<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityMember extends Model
{
    protected $table = 'activity_members';

    public function user(){
    	return $this->hasMany('App\User', 'id', 'user_id');
    }
}
