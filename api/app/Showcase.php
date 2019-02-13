<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Showcase extends AppModel
{
    public function solutions() {
        return $this->hasMany('App\Solution', 'id_showcase', 'id');
    }

    public function feedback() {
        return $this->hasOne('App\Feedback', 'id_showcase', 'id');
    }

    public function approaches() {
    	return $this->belongsToMany('App\Approach', 'showcase_approaches', 'id_showcase', 'id_approach');
    }
}
