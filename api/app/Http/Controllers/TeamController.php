<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TeamMember;

class TeamController extends Controller
{
    public function index(Request $request) {
    	
    	$team_query = TeamMember::where('status','=',1);
    	$team_query->orderBy('order');
    	$team = $team_query->get();

    	return response()->json($team,200);
    }

    public function show($id) {
    	$team = TeamMember::whereId($id)->where('status','=',1)->first();
    	return response()->json($team,200);
    }
}
