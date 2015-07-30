<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function image(){
        return $this->hasMany('App\Image', 'user_id', 'id');
    }

    public function view(){
        return $this->hasMany('App\View', 'user_id', 'id');
    }

    public function favorite(){
        return $this->hasMany('App\Favorite', 'user_id', 'id');
    }

    public function followed_table(){
        return $this->hasMany('App\Follow', 'user_id', 'id');
    }

    public function following_table(){
        return $this->hasMany('App\Follow', 'follow_id', 'id');
    }
}
