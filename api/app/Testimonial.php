<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends AppModel
{
	protected $appends = ['appendedto'];

	public function getAppendedtoAttribute() {
		$appendto = [];
		if ($append_points = \Config::get('setting.testimonial.append.points')) {
			foreach ($append_points as $point) {
				$conf = \Config::get('setting.testimonial.'.$point);
				if (isset($conf[$this->id])) {
					$appendto[$point] = $conf[$this->id];
				}
			}
		}
		return $appendto;
	}
}
