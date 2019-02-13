<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Tools\Tools;
use App\Testimonial;
use App\Setting;

class TestimonialController extends Controller
{
    private static $index;

    public function index() {
    	$testimonials = Testimonial::where('status','<>',2)->orderBy('order')->get();
    	return response()->json($testimonials,200);
    }

    public function show($id) {
    	$testimonial = Testimonial::whereId($id)->where('status','<>',2)->first();
    	return response()->json($testimonial,200);
    }

    public function store(Request $request) {
    	$testimonial = Tools::ModelPrepare('App\Testimonial',$request);
    	if ($testimonial->title) 
        {
            $testimonial->status = $testimonial->status ? intval($testimonial->status) : 0;
    		if ($testimonial->save()) {
    			Tools::ModelSetOrder('App\Testimonial', $testimonial->id);
                if ($appends = $request->get('appendedto')) {
                    $ret = $this->appendto($testimonial->id, $appends);
                    $testimonial->fresh();
                    if (is_array($ret)) {
                        return response()->json($ret, 201);
                    }
                }
    			return response()->json($testimonial,201);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function update($id, Request $request) {
    	$testimonial = Tools::ModelPrepare('App\Testimonial',$request,['id'=>$id]);
    	if ($testimonial->title) 
        {
    		if ($testimonial->save()) {
                if ($request->has('appendedto')) {
                    $appends = $request->get('appendedto');
                    $appends = $appends ? : [];
                    $ret = $this->appendto($testimonial->id, $appends, true);
                    $testimonial->fresh();
                    if (is_array($ret)) {
                        return response()->json($ret, 200);
                    }
                }
    			return response()->json($testimonial,200);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function destroy($id) {
    	$testimonial = Testimonial::whereId($id)->first();    
        $testimonial->status = 2;
		if ($testimonial->save()) {
			return response()->json([],200);
		}    
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function move($id, Request $request) { 
    	//first check if appendto
    	if ($request->has('appendto')) {
            $appends = [$request->get('appendto')=>$request->get('order')];
    		$ret = $this->appendto($id, $appends);
            if (is_array($ret)) {
                return response()->json($ret, 400);
            }
            $testimonial = Testimonial::whereId($id)->where('status','<>',2)->first();
    		return response()->json($testimonial->appendedto,200);
    	}
    	//reorder
    	$after_id = $request->get('after_id');
    	Tools::ModelSetOrder('App\Testimonial', $id, $after_id);
    	return response()->json([],200);
    }

    private function appendto($id, $appends, $remove_others = false) {
        if ($append_points = \Config::get('setting.testimonial.append.points')) {
            $config = [];
            //validate points and load config array
            foreach ($appends as $point=>$order) {
                if (!in_array($point, $append_points)) {
                    return ['message'=>'Only '.implode(',', $append_points).' allowed'];
                }
            }
            foreach ($append_points as $point) {
                $conf = \Config::get('setting.testimonial.'.$point);
                $config[$point] = $conf ? : [];
            }

            if ($remove_others) {
                foreach (array_keys($config) as $p) {
                    if (!in_array($p, array_keys($appends))) {
                        if (isset($config[$p][$id])) {
                            unset($config[$p][$id]);
                            self::$index = -1;
                            asort($config[$p]);
                            $config[$p] = array_map(function($n) {
                                self::$index++;
                                return self::$index;
                            },$config[$p]);
                            $set = Setting::where('Key','=','testimonial.'.$p)->first();
                            $set->value = json_encode($config[$p]);
                            $set->save();
                            \Config::set('setting.testimonial.'.$p, $config[$p]);
                        }
                    }
                }
            }

            foreach ($appends as $point=>$order) {
                $set = Setting::where('Key','=','testimonial.'.$point)->first();
                $p_config = $config[$point];            
                asort($p_config);
            
                if ($order < 0) //unset
                {
                    unset($p_config[$id]);
                    self::$index = -1;
                    $p_config = array_map(function($n) {
                        self::$index++;
                        return self::$index;
                    },$p_config);
                    $set->value = json_encode($p_config);
                    $set->update();
                    \Config::set('setting.testimonial.'.$point, $p_config);
                    return true;
                }
                elseif (isset($p_config[$id])) {                    
                    if ($p_config[$id] != $order) { //reorder
                        $prev_order = $p_config[$id];
                        unset($p_config[$id]);
                        $p_config = array_map(function($n) use($order, $prev_order){
                            if ($n > $prev_order)
                                $n--;
                            if ($n >= $order)
                                $n++;
                            return $n;
                        },$p_config);
                        $p_config[$id] = $order;
                        asort($p_config);
                        self::$index = -1;
                        $p_config = array_map(function($n) {
                            self::$index++;
                            return self::$index;
                        },$p_config);
                        $set->value = json_encode($p_config);
                        $set->update();
                        \Config::set('setting.testimonial.'.$point, $p_config);
                        return true;
                    }
                }
                //new
                if ($i = array_search($order, $p_config)) {
                    $p_config = array_map(function($n) use($order){
                            if ($n >= $order)
                                $n++;
                            return $n;
                        },$p_config);
                }
                $p_config[$id] = $order;
                asort($p_config);
                self::$index = -1;
                $p_config = array_map(function($n) {
                    self::$index++;
                    return self::$index;
                },$p_config);
                $set = $set ? : new Setting;
                $set->key = 'testimonial.'.$point;
                $set->value = json_encode($p_config);
                $set->save();
                \Config::set('setting.testimonial.'.$point, $p_config);
            }
            return true;
        }
        return true;
    }

}
