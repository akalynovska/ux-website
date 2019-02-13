<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function tokens() {
        return $this->hasMany('App\UserToken', 'id_user', 'id');
    }

    public function addToken($token) {
        if ($this->tokens()->whereToken_hash(md5($token))->whereStatus(1)->first()) {
            //TODO date_expired up
            return true;
        }
        return $this->tokens()->save(new \App\UserToken(['token_hash'=>md5($token), 'status'=>1, 'created_at'=>date('Y-m-d H:i:s'), 'date_expired'=>date('Y-m-d H:i:s', strtotime("+1 day"))]));

    }

    public static function getByToken($token, $id = null){

        $query =  self::Join('user_tokens', function ($join) use($token) {
                $join->on('users.id', '=', 'user_tokens.id_user')
                ->where('token_hash', '=', md5($token))
                ->where('user_tokens.status','=',1);
            })
            ->where('users.status','=',1);
        if ($id) {
            $query->where('users.id','=',$id);
        }
        return $query->first();
    }

    public static function getByEmail($email, $id = null){

        $query = self::whereEmail($email)->where('users.status','=',1);
        if ($id) {
            $query->where('id','=',$id);
        }
        return $query->first();
    }

    public function getCreatedAtAttribute($value)
    {
        return strtotime($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }
}
