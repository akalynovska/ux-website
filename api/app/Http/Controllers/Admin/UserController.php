<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Tools\Tools;
use App\User;

class UserController extends Controller
{
    public function index() {
    	$users = User::where('status','<>',2)->orderBy('lastname')->get();
    	return response()->json($users,200);
    }

    public function show($id) {
    	$user = User::whereId($id)->where('status','<>',2)->first();
    	return response()->json($user,200);
    }

    public function store(Request $request) {
    	$user = Tools::ModelPrepare('App\User',$request);
    	if ($user->name) 
        {
            $user->status = $user->status ? intval($user->status) : 0;
    		if ($user->save()) {
    			return response()->json($user,201);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function update($id, Request $request) {
    	$user = Tools::ModelPrepare('App\User',$request,['id'=>$id]);
    	if ($user->name) 
        {
    		if ($user->save()) {
    			return response()->json($user,200);
    		}
        }
        return response()->json(['message'=>'Something wrong'],400);
    }

    public function destroy($id) {
    	$user = User::whereId($id)->first();    
        $user->status = 2;
		if ($user->save()) {
			return response()->json([],200);
		}    
        return response()->json(['message'=>'Something wrong'],400);
    }
}
