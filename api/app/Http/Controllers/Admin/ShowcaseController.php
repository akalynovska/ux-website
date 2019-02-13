<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Showcase;
use App\Tools\Tools;

class ShowcaseController extends Controller
{
    public function index() {
    	$showcase = Showcase::where('status','<>',2)->orderBy('order')
            ->with(['Solutions'=> function($query){
                $query->where('solutions.status','<>', 2);}
            ,'Feedback','Approaches'])
            ->get();
    	return response()->json($showcase,200);
    }

    public function show($id) {
    	$showcase = Showcase::whereId($id)->where('status','<>',2)->first();
    	return response()->json($showcase,200);
    }

    public function store(Request $request) {
    	$showcase = Tools::ModelPrepare('App\Showcase',$request);
    	if ($showcase->name) 
        {
            $showcase->status = $showcase->status ? intval($showcase->status) : 0;
    		if ($showcase->save()) {
    			Tools::ModelSetOrder('App\Showcase', $showcase->id);
    			return response()->json($showcase,201);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function update($id, Request $request) {
    	$showcase = Tools::ModelPrepare('App\Showcase',$request,['id'=>$id]);
    	if ($showcase->name) 
        {
    		if ($showcase->save()) {
    			return response()->json($showcase,200);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function destroy($id) {
    	$showcase = Showcase::whereId($id)->first();    
        $showcase->status = 2;
		if ($showcase->save()) {
			return response()->json([],200);
		}    
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function move($id, Request $request) { 
    	//reorder
    	$after_id = $request->get('after_id');
    	Tools::ModelSetOrder('App\Showcase', $id, $after_id);
    	return response()->json([],200);
    }
}
