<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';

    public function comment(){
        return $this->hasMany('App\SalesComment', 'sales_id', 'id');
    }

    public function image(){
    	return $this->hasMany('App\SalesImage', 'sales_id', 'id');	
    }
}
