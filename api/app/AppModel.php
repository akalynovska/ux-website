<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppModel extends Model
{
    public function getCreatedAtAttribute($value)
    {
    	return strtotime($value);
    }

    public function getUpdatedAtAttribute($value)
    {
    	return strtotime($value);
    }
}
