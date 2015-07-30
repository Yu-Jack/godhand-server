<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $table = 'images';

    public function comment(){
        return $this->hasMany('App\Comment', 'image_id', 'id');
    }
    public function favorite(){
        return $this->hasMany('App\Favorite', 'image_id', 'id');
    }
}
