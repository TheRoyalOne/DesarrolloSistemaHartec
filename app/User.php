<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    
    protected $dates = ['deleted_at'];

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'name',
        'lastname',
        'lastname2',
        'email',
        'password',
        'profile_id',
        'cellphone',
        'job',
        'educative_institution_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function user_id(){
        return \Auth::user()->id;
    }

    public static function findUser(){
        $id = self::user_id();
        $user = User::find($id);
        return $user;
    }

    public static function findProfile(){
        $user = self::findUser();
        $profile_id = $user->profile_id;
        return $profile_id;
    }

    public function profile(){
        return $this->hasOne('App/Profile', 'id','profile_id');
    }
}
