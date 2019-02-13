<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Testimonial;

class TestimonialController extends Controller
{
    //
    public function index(Request $request) {
    	$site = $request->get('appendedto');
    	$config = [];
		if ($conf = \Config::get('setting.testimonial.'.$site)) {
			$config = json_decode($conf,1);
		}
    	$testimonials_query = Testimonial::where('status','=',1);
    	if (sizeof($config)) {
    		asort($config);
    		$testimonials_query->whereIn('id',array_keys($config))
    		->orderByRaw( "FIELD(id, ".implode(',',array_keys($config)).")" );
    	}
    	elseif ($site) { //not appended
    		return response()->json([],200);
    	}
    	else {
    		$testimonials_query->orderBy('order');
    	}
    	$testimonials = $testimonials_query->get();
    	return response()->json($testimonials,200);
    }

    public function show($id) {
    	$testimonial = Testimonial::whereId($id)->where('status','=',1)->first();
    	return response()->json($testimonial,200);
    }
    
}
