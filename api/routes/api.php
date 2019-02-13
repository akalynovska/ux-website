<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'api'], function () {
	Route::get('init', function () {
		$google_api_id = \Config::get('setting.google_api_id');
		$api_key = \Config::get('setting.api_key');
		$client_secret = \Config::get('setting.client_secret');
		return response()->json(compact('google_api_id', 'api_key', 'client_secret'),200);
	});
	Route::get('testimonials','TestimonialController@index'); 
	Route::get('testimonials/{id}','TestimonialController@show'); 

	Route::get('team','TeamController@index'); 
	Route::get('team/{id}','TeamController@show');
});

Route::group(['prefix'=>'admin', 'middleware' => 'auth:api'], function () {
	Route::patch('testimonials/{id}','Admin\TestimonialController@move'); 
	Route::resource('testimonials','Admin\TestimonialController'); 

	Route::patch('team/{id}','Admin\TeamController@move'); 
	Route::resource('team','Admin\TeamController'); 

	Route::patch('approaches/{id}','Admin\ApproachController@move'); 
	Route::resource('approaches','Admin\ApproachController'); 

	Route::resource('users','Admin\UserController'); 

	Route::patch('showcases/{id}','Admin\ShowcaseController@move'); 
	Route::resource('showcases','Admin\ShowcaseController'); 
});
