<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Approach;
use App\Tools\Tools;

class ApproachController extends Controller
{
    public function index() {
    	$appr = Approach::where('status','<>',2)->orderBy('order')->get();
    	return response()->json($appr,200);
    }

    public function show($id) {
    	$appr = Approach::whereId($id)->where('status','<>',2)->first();
    	return response()->json($appr,200);
    }

    public function store(Request $request) {
    	$appr = Tools::ModelPrepare('App\Approach',$request);
    	if ($appr->name) 
        {
            $appr->status = $appr->status ? intval($appr->status) : 0;
    		if ($appr->save()) {
    			Tools::ModelSetOrder('App\Approach', $appr->id);
    			return response()->json($appr,201);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function update($id, Request $request) {
    	$appr = Tools::ModelPrepare('App\Approach',$request,['id'=>$id]);
    	if ($appr->name) 
        {
    		if ($appr->save()) {
    			return response()->json($appr,200);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function destroy($id) {
    	$appr = Approach::whereId($id)->first();    
        $appr->status = 2;
		if ($appr->save()) {
			return response()->json([],200);
		}    
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function move($id, Request $request) { 
    	//reorder
    	$after_id = $request->get('after_id');
    	Tools::ModelSetOrder('App\Approach', $id, $after_id);
    	return response()->json([],200);
    }
}
