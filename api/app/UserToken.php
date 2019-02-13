<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    //
    protected $fillable = ['token_hash', 'status', 'created_at', 'id_user', 'date_expired'];
}
