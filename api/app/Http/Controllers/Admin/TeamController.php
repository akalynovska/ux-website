<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Tools\Tools;
use App\TeamMember;

class TeamController extends Controller
{
    public function index() {
    	$team = TeamMember::where('status','<>',2)->orderBy('order')->get();
    	return response()->json($team,200);
    }

    public function show($id) {
    	$team = TeamMember::whereId($id)->where('status','<>',2)->first();
    	return response()->json($team,200);
    }

    public function store(Request $request) {
    	$team = Tools::ModelPrepare('App\TeamMember',$request);
    	if ($team->firstname) 
        {
            $team->status = $team->status ? intval($team->status) : 0;
    		if ($team->save()) {
    			Tools::ModelSetOrder('App\TeamMember', $team->id);
    			return response()->json($team,201);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function update($id, Request $request) {
    	$team = Tools::ModelPrepare('App\TeamMember',$request,['id'=>$id]);
    	if ($team->title) 
        {
    		if ($team->save()) {
    			return response()->json($team,200);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function destroy($id) {
    	$team = TeamMember::whereId($id)->first();    
        $team->status = 2;
		if ($team->save()) {
			return response()->json([],200);
		}    
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function move($id, Request $request) { 
    	//reorder
    	$after_id = $request->get('after_id');
    	Tools::ModelSetOrder('App\TeamMember', $id, $after_id);
    	return response()->json([],200);
    }
}
