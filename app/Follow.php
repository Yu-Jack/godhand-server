<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follows';

    public function user(){
        return $this->hasMany('App\User', 'id', 'follow_id');
    }
}
