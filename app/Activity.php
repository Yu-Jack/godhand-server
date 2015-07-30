<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activitys';

    // public $timestamps = false;

    public function member_table(){
    	return $this->hasMany('App\ActivityMember', 'activity_id', 'id');
    }
}
